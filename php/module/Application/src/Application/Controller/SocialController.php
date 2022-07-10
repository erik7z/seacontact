<?php
namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;
use \Application\Model\SocialPublicsTable as PublicsTable;

class SocialController extends AbstractController
{

	public function updateVkEntries()
	{
		$logTable = $this->get('LogbookTable');
		$res = $logTable->query(
			"SELECT   
				lb.*, u.id user_id, u.social_vk
				FROM `user_logbook` lb
				INNER JOIN `user` u ON (u.id = lb.user AND u.social_vk IS NOT NULL AND u.social_vk != '')
				WHERE lb.post_vk_id IS NOT NULL AND lb.post_vk_id != ''

			");
		// foreach ($res as $log) {
		// 	preg_match("/(?'id'[0-9]+)$/" , $log->social_vk, $matches);
		// 	if($matches['id']) {
		// 		$logTable->save(['id' => $log->id, 'post_vk_wall' => $matches['id']]);
		// 	}
		// }
		// d('stop');
		d($res->toArray());
	}

	public function updateVkAccounts()
	{
		$userTable = $this->get('UserTable');
		$vk_users_in_db = $userTable->getUsersList(null, ['have_vk_id' => 1], ['_user_fields' => ['id', 'social_vk']]);
		foreach ($vk_users_in_db as $vk_user) {
			if (preg_match("/(?'id'[0-9]{5,12})$/" , $vk_user->social_vk, $matches)) {
				$social_id = (isset($matches['id']))? $matches['id'] : null;
				$userTable->save(['id' => $vk_user->id, 'social_vk' => $social_id]);
			}


		}
		d($vk_users_in_db->toArray());
	}

	public function updateComments()
	{
		// $table = $this->get('SocialCommentsTable');
		// $comms = $table->getAll();
		// foreach ($comms as $comm) {
		// 	$text = htmlentities($comm->comment);
		// 	// $table->save(['id' => $comm->id, 'comment' => $text]);
		// 	d($text, 1);
		// }

		// d('stop');
	}


	public function parseVkAction()
	{
		$success = 0;
		$message = '';
		$public_id = $this->params()->fromQuery('public_id');

		try {
			if(!$this->identity()) throw new \Application\Exception\Exception($this->translate('Authorisation Required'), 401);
			$active_user = $this->get('UserTable')->getUserById($this->identity()->id, 1);

			if(!$active_user->social_vk) {
				$redirect = $this->url()->fromRoute('sc/vk_oauth');
				throw new \Application\Exception\Exception($this->translate("Please confirm authentication to VKontakte"), 705);
			}

			$captcha_key = $this->params()->fromQuery('captcha_key');
			$captcha_sid = $this->params()->fromQuery('captcha_sid');
			if($captcha_sid && $captcha_key) $this->get('api_vk')->setCaptcha($captcha_sid, $captcha_key);
			
			try {				
				if ($active_user->social_vk_token && $active_user->social_vk_secret) {
					$this->get('api_vk')->setUserToken($active_user->social_vk_token);
					$this->get('api_vk')->setUserSecret($active_user->social_vk_secret);
				} else $this->get('api_vk')->setHttps(true);

				if($public_id) {
					$options = $this->get('SocialPublicsTable')->get($public_id);
					if(!$options) throw new \Application\Exception\Exception("Public not found", 1);
					$wall_id = $options->social_id;
				} else {
					$wall_id = $active_user->social_vk;
					if(!$active_user->social_vk_domain) {
						$vk_user_info = $this->get('api_vk')->getUserInfo($active_user->social_vk);
						if(isset($vk_user_info->domain)) $active_user->social_vk_domain = $vk_user_info->domain;
					}
					$options = [
						'user_id' => $active_user->id, 
						'user_login' => $active_user->login, 
						'soc_domain' => $active_user->social_vk_domain, 
					];
				}
				$vk_wall_data = $this->get('api_vk')->getWall($wall_id);
				// $dump = $this->get('Dump');
				// $dump->createDump($vk_wall_data, 'vk_wall_data');
				// $vk_wall_data = $dump->getDump('vk_wall_data');
				// d($vk_wall_data);
				// setting on automatic parsing after successfull 1st download
				$active_user->social_vk_parsing = 1;

			} catch (\Exception $e) {
				if ($e->getCode() == 14) {
					$extra_data = unserialize($e->getMessage());
					throw new \Application\Exception\Exception($this->translate('Please enter captcha'), $e->getCode());
				}
				if ($e->getCode() == 15) throw new \Application\Exception\Exception('<span class="text-danger">'.$e->getMessage().'.</span>  <br />'.$this->translate('Recomendation: in Vkontakte on your <b>Settings</b> page please set option <b>"Who can see my page in internet"</b> to <b>"Everybody"</b>'), $e->getCode());
				if ($e->getCode() == 5) throw new \Application\Exception\Exception('<span class="text-danger">'.$e->getMessage().'.</span>  <br />'.$this->translate('Recomendation: You need to confirm authorisation in Vkontakte'), 705);
				else throw new \Application\Exception\Exception($this->translate('Error during request to VK').' : <br />'.$e->getMessage(), $e->getCode());

			}
			$result = $this->get('vk_wall_parsing')->parseVkWall($vk_wall_data, $wall_id, $options);
			$extra_data = $result;
			$active_user->social_vk_parsed = time();
			$active_user->save();
			$this->get('RefreshUserId');
		
			// вохвращаемся на страницу
			$success = 1;
			$message = sprintf($this->translate('Wall from VK synchronised: %s new entries added, %s entries refreshed'), $result['new_posts'], $result['refreshed']);
			$this->get('ErrorLog')->count = 0;
		} catch (\Exception $e) {
			if($e->getCode() == 705) $redirect = $this->url()->fromRoute('sc/vk_oauth', [], ['query' => ['success_action' => 'vk_wall']]);
			$success = 0;
			$message = $e->getMessage();
		}

		return $this->viewResponse($success, $message, [
			'redirect' => (isset($redirect))? $redirect : $this->url()->fromRoute('sc/home'), 
			'force_redirect' => (isset($redirect))? 1 : 0,
			'exception' => (isset($e)) ? $e : null,
			'extra_data' => (isset($extra_data)) ? $extra_data : null,
			]);
	}

	public function parseFbAction()
	{
		$success = 0;
		$message = '';
		try {
			if(!$this->identity()) throw new \Application\Exception\Exception($this->translate('Authorisation Required'), 401);
			$active_user = $this->get('UserTable')->getUserById($this->identity()->id, 1);
			if(!$active_user->social_fb || !$active_user->social_fb_token) {
				$redirect = $this->url()->fromRoute('sc/fb_oauth');
				throw new \Application\Exception\Exception($this->translate("Please confirm authentication to Facebook"), 705);
			}
			$fb = $this->get('api_fb');
			$fb->setDefaultAccessToken($active_user->social_fb_token);
			$response = $fb->get('/me/posts?fields=message,message_tags,link,name,caption,description,picture,source,created_time,attachments');
			$social_feed = $response->getDecodedBody();
			$active_user->social_fb_parsing = 1;
			$already_parsed = $this->get('LogBookTable')->getAllLogbooks(null, ['owner_id' => $this->identity()->id, 'posted_in_fb' => 1], ['no_counters' => 1, 'no_user_info' => 1, '_limit' => 0]);
			$already_parsed_ids = [];
			foreach ($already_parsed as $log_entry) {
				$already_parsed_ids[$log_entry->post_fb_id] = $log_entry->post_fb_time;
			}
			$parsing_tags = _SOCIAL_PARSE_KEYWORD_.strtolower($active_user->login);
			$new_parsed = $this->get('LogBookTable')->parseFbWall($social_feed, $active_user->id,[
				'user_login' => $active_user->login, 
				'user_domain' => zgetUserName($active_user), 
				'user_social_id' => $active_user->social_fb, 
				'parsing_tags' => $parsing_tags, 
				'last_parsed_time' => $active_user->social_fb_parsed,  
				'already_parsed_ids' => $already_parsed_ids, 
				]);
			

			// записываем в бд время последнего обновления вк
			if($new_parsed > 0) $active_user->social_vk_parsed = time();
			$active_user->save();
			$this->get('RefreshUserId');

			// вохвращаемся на страницу
			$success = 1;
			$message = sprintf($this->translate('Wall from Facebook synchronised %s new logbook entries added.'), $new_parsed);
			$this->get('ErrorLog')->count = 0;
		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}
		
		return $this->viewResponse($success, $message, [
			'redirect' => (isset($redirect))? $redirect : $this->url()->fromRoute('sc/home'), 
			'force_redirect' => (isset($redirect))? 1 : 0,
			'exception' => (isset($e)) ? $e : null,
			'extra_data' => (isset($extra_data)) ? $extra_data : null,
			]);
	}


	public function addVkPublicAction()
	{
		$success = 1;
		$message = '';
		try {
			$form = $this->get('EmptyForm');
			$form->add($this->get('ParsingOptions'));
			$form->setAttribute('action', $this->url()->fromRoute('sc/social/actions', ['action' => 'add-vk-public']));
			$form->addSubmit($this->translate('Add VK Public'));
			$form->get('submit')->setAttribute('data-ajax', true);

			if($this->request->isPost()){
				$data = $this->request->getPost();
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
				$data = $form->getData();
				$data = $data['parsing_options'];
				$data['soc_domain'] = array_pop(explode('/', $data['page']));
				if(!$data['soc_domain']) throw new \Application\Exception\Exception("Social public page incorrect", 1);
				$data['user_id'] = $this->identity()->id;
				$data['social'] = 'vk';
				$data['parsing_tags'] = str_replace(' ', '', $data['parsing_tags']);
				$data['author_tags'] = '#sc_blog';
				$data['without_tags'] = 1;
				$data['add_link'] = 0;
				
				$info = $this->get('api_vk')->getGroups($data['soc_domain']);
				// $dump = $this->get('Dump');
				// $dump->createDump($info, 'info');
				// $info = $dump->getDump('info');
				if(!$info) throw new \Application\Exception\Exception("Social public info not found", 1);
				$info = $info[0];				
				$data['social_id'] = -1 * $info->gid;
				$data['name'] = $info->name;
				$data['description'] = $info->description;

				
				try {
					$image_data = $this->get('PicsTable')->getImageFromVkAttachment($info, null, 1);
					$data['avatar'] = $image_data['img'];
				} catch (\Exception $e) {}

				$this->get('SocialPublicsTable')->save($data);

				$redirect = $this->url()->fromRoute('sc/settings');
				$success = 1;
				$message = $this->translate('Public added for parsing');
			} 

		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
			if($e->getCode() == 100) $message = $this->translate("Link to public is invalid, please recheck");
			
			$redirect = $this->url()->fromRoute('sc/settings');
		}
		
		
		$viewModel = 0;
		if(isset($form)) 
		    $viewModel = new ViewModel(array(
		        'form' => $form,
		        )); 

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'application/social/add-vk-public',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect)) ? $redirect : null,
		    'force_redirect' => (isset($redirect)) ? 1 : 0,
		    ]);
	}


	public function attachWallPostAction()
	{
		$success = 1;
		$message = '';

		try {
			$section = $this->params()->fromQuery('section', 'logbook');
			$id = $this->params()->fromQuery('id');
			if(!$section || !$id) throw new Exception("section and article to be provided", 1);
			
			$form = $this->get('EmptyForm');

			$form->add($this->get('ParsingOptions')->remainFields(['page']));
			$form->get('parsing_options')->get('page')->setLabel($this->translate('Related Link'));
			$form->setAttribute('action', $this->url()->fromRoute('sc/social/actions', ['action' => 'attach-wall-post'], ['query' => ['section' => $section, 'id' => $id]]));
			$form->addSubmit($this->translate('Attach'));
			$form->get('submit')->setAttribute('data-ajax', true);

			if($this->request->isPost()){
				$data = $this->request->getPost();
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
				$data = $form->getData();
				$data = $data['parsing_options'];
				if(!preg_match("%wall(?'wall'-?[0-9]+)_(?'post_id'[0-9]+)$%", $data['page'], $matches))
					throw new \Application\Exception\Exception("Link incorrect", 1);
				$data['post_vk_wall'] = $matches['wall'];
				$data['post_vk_id'] = $matches['post_id'];
				$data['id'] = $id;
				if($section == \Application\Model\NewsTable::SECTION_QUESTIONS)
					$table = $this->get('QuestionsTable');
				else {
					$table = $this->get('LogbookTable');
					$section = \Application\Model\NewsTable::SECTION_LOGBOOK;
				}
				$table->save($data);				
				$redirect = $this->url()->fromRoute('sc/'.$section.'/actions', ['action' => 'view', 'id' => $id]);
				$success = 1;
				$message = $this->translate('Vk Post Attached');
			} 

		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
			if($e->getCode() == 100) $message = $this->translate("Link to public is invalid, please recheck");
		}
		
		
		$viewModel = 0;
		if(isset($form)) 
		    $viewModel = new ViewModel(array(
		        'form' => $form,
		        )); 

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'application/social/attach-wall-post',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect)) ? $redirect : null,
		    'force_redirect' => (isset($redirect)) ? 1 : 0,
		    ]);
	}

	public function deletePublicAction()
	{
		try {
			$success = 1;
			$message = '';
			$id = (int)$this->params()->fromRoute('id');
			if($id == 0) throw new \Application\Exception\Exception("Public id Not provided", 1);
			if(!$this->isPermitted('application\controller\social.delete-public')) 
				throw new \Application\Exception\Exception("You cannot access this action", 1);
			
			$this->get('SocialPublicsTable')->delete($id);
		
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}
		
		return $this->viewResponse($success, $message, [
			'redirect' => $this->url()->fromRoute('sc/settings'), 
			'force_redirect' => 1,
			'exception' => (isset($e)) ? $e : null
			]);
	}



}