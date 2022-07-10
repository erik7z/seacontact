<?php
namespace Admin\Controller;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class MailboxController extends AbstractController
{

	protected $mailParser = null;

	protected function getMailParser()
	{
		if(!$this->mailParser) $this->mailParser = $this->get('MailParser');
		// if(!$this->mailParser) $this->mailParser = new \Application\zLibrary\Afakemailer;
		return $this->mailParser;
	}


	public function indexAction()
	{

		$success = 1;
		$message = '';
		try {
		    $q_options = $this->setDefaultOptions(['_limit' => 20]);
		    if(!count($q_options['filters'])) $q_options['filters'] = ['unreaded' => 1];
		    $viewer_id = ($this->identity())? $this->identity()->id : 0;
		    $data_list = $this->get('MailBoxTable')->getMails($viewer_id, $q_options['filters'], $q_options);
		    $q_options['count'] = 1;
		    $total_results = $this->get('MailBoxTable')->getMails($viewer_id, $q_options['filters'], $q_options)->current()->count;
		    $mail_accounts = $this->get('MailAccountsTable')->getAccounts()->toArray();
		} catch (\Exception $e) {
		    $success = 0;
		    $message = $e->getMessage();
		}

			
		$viewModel = 0;
		if(isset($data_list)) 
		    $viewModel = new ViewModel(array(
		        'data_list' => $data_list,
		        'q_options' => $q_options,
		        'total_results' => $total_results,
		        'mail_accounts' => $mail_accounts
		        )); 

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'admin/mailbox/index',
		    'exception' => (isset($e)) ? $e : null
		    ]);
	}




	public function viewAction()
	{
		$success = 1;
		$message = '';
		try {
			$q_options = $this->setDefaultOptions(['_limit' => 10]);
			$mail_id = $this->params()->fromRoute('id');
			if($mail_id == 0) throw new \Application\Exception\Exception("Mail id not provided", 1);
			$mail = $this->get('MailBoxTable')->getMail($mail_id);
			if(!$mail) throw new \Application\Exception\Exception("Mail with such id not found", 1);
			$mail_to = json_decode($mail->mail_to);
			$readed = $this->markReaded([$mail_id]);
			$mail_accounts = $this->get('MailAccountsTable')->getAccounts()->toArray();
			$q_options['current_mail_id'] = $mail->unique_id;
			$data_list = $this->get('MailBoxTable')->getMailsFlow($this->identity()->id,$mail->from_mail,$mail_to, $q_options['filters'], $q_options);
			$q_options['count'] = 1;
			$total_results = $this->get('MailBoxTable')->getMailsFlow($this->identity()->id,$mail->from_mail,$mail_to, $q_options['filters'], $q_options)->current()->count;
		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}
		$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
														array(
															'module' => 'Admin',
															'controller' => $this->routeMatch()->getParam('__CONTROLLER__'),
															'action' => $this->routeMatch()->getParam('action'),
															'id' => $mail_id
															) ,
														$this->translate('Mail Readed') 
													);
		$viewModel = 0;
		if(isset($data_list)) 
		    $viewModel = new ViewModel(array(
		        'mail_accounts' => $mail_accounts,
		        'mail' => $mail,
		        'data_list' => $data_list,
		        'q_options' => $q_options,
		        'total_results' => $total_results,
		        )); 

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'admin/mailbox/view',
		    'exception' => (isset($e)) ? $e : null
		    ]);
	}


	public function newMailAction()
	{
		$success = 1;
		$message = '';

		$mail_to = $this->params()->fromQuery('mail_to', null);
		$mail_box = $this->params()->fromQuery('mail_box');
		$forward = $this->params()->fromQuery('forward');
		$reply_on_id = $this->params()->fromQuery('reply_on_id');
		$reply_all = $this->params()->fromQuery('reply_all');
		$user_id  = (int)$this->params()->fromRoute('id');
		$template_id = $this->params()->fromQuery('template');

		try {
			$form = $this->get('MailForm')->removeFields(['template_name']);
			$form->setAttribute('action', $this->url()->fromRoute('admin/actions', array('controller' => 'mailbox', 'action' => 'new-mail')));

			$form->get('mail_from')->setValue($mail_box);
			$mail_text = '<br /><br /><br />';

			$templates = $this->get('AdminMailTemplatesTable')->getMailTemplates(null)->toArray();
			foreach ($templates as $template) {
				$templates_list[$template['id']] = $template['template_name'];
			}
			$form->get('mail_template')->setValueOptions($templates_list);
			if($template_id) {
				$form->get('mail_template')->setValue($template_id);
				for ($i=0; $i < count($templates); $i++) { 
					if($templates[$i]['id'] == $template_id) $template = $templates[$i];
				}
				$mail_text = str_replace(array('%site_name%', '%author_name%'), 
					array( _SITENAME_, zgetUserName($this->identity())), $template['mail_text']);
				$form->get('title')->setValue($template['mail_title']);
			}

			$mails_flow = [];
			if($mail_to) {
				try {
					$user = $this->get('UserTable')->getUserByEmail($mail_to);
				} catch (\Exception $e) {}
				$mail_to_obj = new \stdClass();
				$mail_to_obj->email = $mail_to;
				$mails_flow = $this->get('MailBoxTable')->getMailsFlow($this->identity()->id, $mail_box, array($mail_to_obj));
				if($user_id) $user = $this->get('UserTable')->getUserById($user_id);
			}

			$user_name = '';
			if(isset($user)) {
				$user_name = zgetUserName($user);
				$user_email = $user->email;
				$user_cv_page = $this->url()->fromRoute('sc/userinfo', array('controller' => 'cv', 'action' => null, 'user' =>  'id'.$user->id)); //zgetuserLogin ???
				
				$mail_text = str_replace(array('%user_name%', '%cv_page%', '%email%'), 
										array($user_name, $user_cv_page, $user_email), $mail_text);
			}
			$settingsTable = $this->get('AdminUserSettingsTable');
			$settings = $settingsTable->getUserSettings($this->identity()->id);

			$mail_text = htmlspecialchars_decode($mail_text);
			$mail_text .= '<br />'.htmlspecialchars_decode($settings->mails_signature);

			if($reply_on_id && $reply_on_id != '') {
				$reply_mail = $this->get('MailBoxTable')->getMail($reply_on_id);
				if ($reply_mail) {
					$reply_view = new ViewModel($reply_mail);
					$reply_view->setTemplate('/admin/partial/reply-mail.phtml');
					$reply_view->setOption('has_parent', true);
					$reply_text = $this->get('ViewManager')->getView()->render($reply_view);
					$reply_text = nl2br($reply_text);
					$form->setAttribute('action', $this->url()->fromRoute('admin/actions', 
												array('controller' => 'mailbox', 'action' => 'new-mail'), 
												array('query' => array('reply_on_id' => $reply_on_id))
												));
					$mail_text .= '<br />'.$reply_text;
					if($reply_mail->attachments) {
						$form->add([
							'name' => 'old_attachments',
							'type' => 'Zend\Form\Element\Select',
							'options' => array(
								'label' => $this->translate('Include Original Attachemts'),
								'required' => false,
								'options' => [
									0 => $this->translate('No'),
									1 => $this->translate('Yes')
								],
								),
							'attributes' => array(
								'required' => false,
								),
							]);
					}
					$reply_title = ($forward)? 'FWD: ' : 'RE: ';
					$reply_title .= $reply_mail->subject;
				}
			}

			$form->get('text')->setValue($mail_text);

			if(isset($reply_title)) $form->get('title')->setValue($reply_title);
			if($mail_to) $mail_to_string = $mail_to;
			if($reply_all && $reply_mail) {
				$mail_to_string = $mail_box;
				if(strpos($mail_to_string, $reply_mail->from_mail) === false ) $mail_to_string .= ', '.$reply_mail->from_mail;
				$reply_mail_to = json_decode($reply_mail->mail_to);
				if($reply_mail_to) {
					foreach ($reply_mail_to as $to) {
						if(strpos($mail_to_string, $to->email) === false ) $mail_to_string .= ', '.$to->email;
					}
				}
				$mail_cc = json_decode($reply_mail->mail_cc);
				if($mail_cc) {
					foreach ($mail_cc as $cc) {
						if(strpos($mail_to_string, $cc->email) === false ) $mail_to_string .= ', '.$cc->email;
					}
				}
			}
			if(isset($mail_to_string) && $mail_to_string) $form->get('mail_to')->setValue($mail_to_string);

			$form->addSubmit($this->translate('Send Mail'));
			$form->get('submit')->setAttribute('data-ajax', true);

			if($this->request->isPost()){
				$data = array_merge_recursive(
					$this->request->getPost()->toArray(),
					$this->request->getFiles()->toArray()
					);
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
					$data = $form->getData();
					do {
						$unique_id = $this->get('salt')->mail_id();
					} while (file_exists(_MAILSROOT_.$unique_id) != false);
					if(isset($data['attachments'])) {
						try {
							$k = 0;
							foreach ($data['attachments'] as $attachment) {
								if('' != $attachment['tmp_name']) {
									if($k == 0) $this->get('UploadMailAttachment')->init($unique_id);
									$k++;
									$up_attachment = $this->get('UploadMailAttachment')->upload($attachment, $this->identity()->id);
									$upl_attachments[] = $up_attachment['name'];
								}
							}
						} catch (Exception $e) {
							$message = $e->getMessage();
						}
					}
					$data['attachments'] = (isset($upl_attachments))? $upl_attachments : [];
					if(isset($data['old_attachments']) && $data['old_attachments']) {
						$old_att = json_decode($reply_mail->attachments);
						$new_folder = _MAILSROOT_.$unique_id;
						if(!file_exists($new_folder)) z_createDir($new_folder);
						for ($i=0; $i < count($old_att); $i++) { 
							$old_path = _MAILSROOT_.$reply_mail->unique_id.'/'.$old_att[$i];
							$new_path = $new_folder.'/'.$old_att[$i];
							if(file_exists($old_path)) {
								copy($old_path, $new_path);
								array_push($data['attachments'], $old_att[$i]);
							}
						}
					}
					$mail = $this->get('Mail');
					try {
						$mail->sendAdminMail($data['mail_to'], $data['mail_from'],  $data['title'], htmlspecialchars_decode($data['text']),
												['unique_id' => $unique_id, 'attachments' => $data['attachments'], 'from_name' => zgetUserName($this->identity()), 'to_name' => $user_name, 'mail_cc' => $data['mail_cc']]
												);
					} catch (\Exception $e) {
						if($e->getCode() == 707 || $e->getCode() == 704) {
							$extra_data['status'] = $e->getCode() == 707;
							$extra_data['message'] = $message = $e->getMessage();
							$extra_data['unique_id'] = $unique_id;
						} else throw new \Application\Exception\Exception($e->getMessage(), 1);
					}

					$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
																	array(
																		'module' => 'Admin',
																		'controller' => $this->routeMatch()->getParam('__CONTROLLER__'),
																		'action' => $this->routeMatch()->getParam('action'),
																		'id' => $unique_id
																		) ,
																	$this->translate('New Mail Sent') 
																);
					$result = $this->get('MailBoxTable')
									->saveSentMail($unique_id,
													$this->identity()->id, 
													$data['mail_from'], 
													zgetUserName($this->identity()),
													$data['mail_to'],
													$data['mail_cc'],
													$data['title'],
													$data['text'],
													$data['attachments']
												);

					$success = 1;
					$message = ($message == '')? $this->translate('Mail Sended!') : $message;

			} // end of POST

		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}

		$viewModel = 0;
		if(isset($form)) 
			$viewModel = new ViewModel(array(
				'form' => $form,
				'mails_flow' => $mails_flow,
				'template_id' => $template_id,
				'mail_to' => $mail_to,
				'reply_on_id' => $reply_on_id,
				'controller' => 'mailbox',
				));

		return $this->viewResponse($success, $message, [
			'view_data' => $viewModel, 
			'template' => 'admin/mailbox/new-mail',
			'exception' => (isset($e)) ? $e : null,
			'extra_data' => (isset($extra_data)) ? $extra_data : null
			]);
	}

	public function withSelectedAction()
	{
		$success = 1;
		$message = '';

		$redirect_folder = $this->params()->fromQuery('redirect_folder');
		if($this->request->isPost()){
			 $post = $this->request->getPost();
			 try {
			 	foreach ($post as $key => $value) {
			 		if(strpos($key, 'mail_') !== false){
			 			$mail_ids[] = $value;
			 		}
			 	}
			 	$response['success'] = 0;
		     	if ($post['submit'] == 'Move') {
		     		$folder_data = explode(':', $post['folder_name']);
		     		if(is_array($mail_ids) && is_array($folder_data)) $response = $this->moveToFolder($mail_ids, $folder_data[0], $folder_data[1]);
		     		else throw new \Application\Exception\Exception("Ooops...something went wrong...", 1);
		     		if(!$response['success']) throw new \Application\Exception\Exception(serialize($response['data']), 777);
		     		$message = $response['data'];
		     		$success = 1;
		     	}
		     	else if ($post['submit'] == 'mark_readed') {
		     		if(is_array($mail_ids)) $response = $this->markReaded($mail_ids);
		     		else throw new \Application\Exception\Exception("Ooops...something went wrong...", 1);
		     		if(!$response['success']) throw new \Application\Exception\Exception(serialize($response['data']), 777);
		     		$message = $response['data'];
		     		$success = 1;
		     	}
		     }   
		     catch(\Exception $e) {
		     	$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		     	$success = 0;
		     }
		 }
		 if($redirect_folder) 
		 	$redirect = $this->url()->fromRoute('admin/actions', array('controller' => 'mailbox', 'action' => 'index'), array('query' => array('filters' => array('folder' => $redirect_folder))));

		 return $this->viewResponse($success, $message, [
		 	'redirect' => ($redirect)? $redirect : $this->url()->fromRoute('admin/actions', array('controller' => 'mailbox', 'action' => 'index')), 
		 	'force_redirect' => ($redirect)? 1 : 0,
		 	'exception' => (isset($e)) ? $e : null
		 	]);
	}

	protected function moveToFolder(array $mail_ids, $mail_box, $folder_global_name)
	{
		$response['success'] = false;
		$response['message'] = '';
		$refresh_mailbox = false;
		foreach ($mail_ids as $mail_id) {
			try {
				$mail = $this->get('MailBoxTable')->getMail($mail_id);
				if($mail->mail_box != $mail_box) throw new \Application\Exception\Exception($this->translate('You cannot move mails between different mailboxes'), 1);
				
				if(!$mail) throw new \Application\Exception\Exception("Mail with such id not found [MailboxController]", 404);
				if($mail->location == 'server') {
					$account = $this->get('MailAccountsTable')->getOnField($mail_box, 'mail_box');
					$mailParser = $this->getMailParser();
					$mailParser->init($account, $this->identity()->id);
					$mailParser->getStorage()->selectFolder($mail->folder_full);
					$unique_id = str_replace($mail->time, '', $mail->unique_id);
					$current_id = $mailParser->getStorage()->getNumberByUniqueId($unique_id);
					$mailParser->getStorage()->moveMessage($current_id, $folder_global_name);
					$this->get('MailBoxTable')->deleteOnFields(array('unique_id' => $mail->unique_id));
					$refresh_mailbox = true;
				} else {
					$folder = $this->get('MailBoxFoldersTable')->getFieldsByFields(['folder', 'folder_full'], ['mail_box' => $mail_box, 'folder_full' => $folder_global_name])->current();
					if(!$folder) throw new \Application\Exception\Exception("Folder {$folder_global_name} not found", 1);
					$this->get('MailBoxTable')->toFolder($mail, $folder->folder, $folder->folder_full);
				}
				$response['success'] = true;
				$response['data'][] = 'Mail '.$mail_id.' moved to the '.$mail_box.' / '.$folder_global_name.' folder';
			} catch (\Exception $e) {
				$response['success'] = false;
				$response['data'][] = $e->getMessage();
			}
		}
		return $response;
	}

	protected function markReaded($mail_ids){
		$response['success'] = false;
		$response['message'] = '';
		foreach ($mail_ids as $mail_id) {
			try {
				if(!$mail_id) throw new \Application\Exception\Exception("Mail id should be provided [MailBoxController]", 1);
				$mail = $this->get('MailBoxTable')->getMail($mail_id);
				if(!$mail) throw new \Application\Exception\Exception("Mail with such id not found [MailBoxController]", 404);
				if($mail->location == 'server') {
					// deleting folder from the mail host
					$account = $this->get('MailAccountsTable')->getOnField($mail_box, 'mail_box');
					$mailParser = $this->getMailParser();
					$mailParser->init($account, $this->identity()->id);
					$mailParser->getStorage()->selectFolder($mail->folder_full);
					$unique_id = str_replace($mail->time, '', $mail->unique_id);
					$current_id = $mailParser->getStorage()->getNumberByUniqueId($unique_id);
					$mailParser->getStorage()->setFlags($current_id, array(\Zend\Mail\Storage::FLAG_SEEN));
				} 
				$this->get('MailBoxTable')->setReaded($mail);
				$this->get('AdminNotifReadedTable')->readNotifications($this->identity()->id, 'new_mail', 'mail', $mail_id);
				$response['success'] = true;
				$response['data'][] = 'message :'.$mail_id.' marked as Seen';

			} catch (\Exception $e) {
				$response['success'] = false;
				$response['data'][] = $e->getMessage();
			}		
		}

		return $response;
	}

	public function toTrashAction(){
		$mail_id = $this->params()->fromRoute('id');
		$mail_box = $this->params()->fromQuery('mail_box');
		$redirect_folder = $this->params()->fromQuery('redirect_folder');
		$success = 1;
		$message = '';
		try {
			$response = $this->moveToFolder(array($mail_id), $mail_box, 'INBOX.Trash');
			if(!$response['success']) throw new \Application\Exception\Exception(serialize($response['data'], 777));
			$success = 1;
			$message = $response['data'];
			if($redirect_folder) $redirect = $this->url()->fromRoute('admin/actions', ['controller' => 'mailbox'], ['query' => ['filters' => ['mail_box' => $mail_box, 'folder' => $redirect_folder]]]);
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}
		
		$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
														array(
															'module' => 'Admin',
															'controller' => 'mailbox',
															'action' => 'to-trash',
															'id' => $mail_id
															) ,
														$message
													);
		return $this->viewResponse($success, $message, [
			'redirect' => ($redirect) ? $redirect : $this->url()->fromRoute('admin/actions', ['controller' => 'mailbox']), 
			'force_redirect' => ($redirect)? 1 : 0,
			]);
	}



	public function createNewFolderAction()
	{
		$success = 1;
		$message = '';
		$mail_box = $this->params()->fromQuery('mail_box');
		try {
			$form = $this->get('EmptyForm');
			$form->setAttribute('action', $this->url()->fromRoute('admin/actions', array('controller' => 'mailbox', 'action' => 'create-new-folder')));
			$form->add(array(
				'name' => 'folder_name',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
					'label' => $this->translate('Folder Name'),
					),
				'attributes' => array(
					'required' => 'required',
					'placeholder' => $this->translate('New folder name'),
					),
				));
			$form->add(array(
			'name' => 'mail_box',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Mail Box'),
				'options' => $this->get('MailAccountsTable')->getFieldCountForSelect('mail_box', [], ['use_cache' => 0, 'show_count' => 0]),
				'required' => true,
				),
			));

			$form->add(array(
				'name' => 'priority',
				'type' => 'Zend\Form\Element\Number',
				'options' => array(
					'required' => false,
					'label' => $this->translate('Priority'),
					),
				'attributes' => array(
					'min' => 0,
					'max' => 10,
					),
				));

			if($mail_box) $form->get('mail_box')->setValue($mail_box);
			$form->addSubmit($this->translate('Create New Folder'));

			if($this->request->isPost()){
			     $post = $this->request->getPost();
			     $form->setData($post);
			         if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
			         $data = $form->getData();
			         $mail_box = $data['mail_box'];
			         $folder_full = 'INBOX.'.$data['folder_name'];
			         
			         // saving to the mail host
			         $account = $this->get('MailAccountsTable')->getOnField($mail_box, 'mail_box');
			         $mailParser = $this->getMailParser();
			         $mailParser->init($account, $this->identity()->id);
			         $mailParser->getStorage()->createFolder($folder_full);

			         $this->get('MailBoxFoldersTable')->save(['mail_box' => $mail_box, 'folder' => $data['folder_name'], 'folder_full' => $folder_full, 'priority' => $data['priority']]);
			         $message = $this->translate('New Folder Created');
			         $this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin','controller' => 'mailbox','action' => 'create-new-folder'] , $message,['result' => $data['folder_name']]);
			         $redirect = $this->url()->fromRoute('admin/actions', ['controller' => 'mailbox'], ['query' => ['filters' => ['mail_box' => $mail_box, 'folder' => $data['folder_name']]]]);
			 }
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}


		$viewModel = 0;
		if(isset($form)) 
			$viewModel = new ViewModel(array(
				'form' => $form,
				));

		return $this->viewResponse($success, $message, [
			'view_data' => $viewModel, 
			'template' => 'admin/mailbox/create-new-folder',
			'exception' => (isset($e)) ? $e : null,
			'redirect' => (isset($redirect)) ? $redirect : null,
			'force_redirect' => (isset($redirect)) ? 1 : 0,
			]);
	}


	public function editFolderAction()
	{
		$success = 1;
		$message = '';
		$id = $this->params()->fromRoute('id');
		$mail_box = $this->params()->fromQuery('mail_box');
		$folder_name = $this->params()->fromQuery('folder');
		try {
			if(!$mail_box || !$folder_name) throw new \Application\Exception\Exception("Folder name and mailbox should be provided", 1);
			$folder = $this->get('MailBoxFoldersTable')->getFieldsByFields(['id', 'folder','folder_full', 'priority'], ['mail_box' => $mail_box, 'folder' => $folder_name])->current();
			if(!$folder) throw new \Application\Exception\Exception($this->translate('Folder not found'), 1);

			$form = $this->get('EmptyForm');
			$form->setAttribute('action', $this->url()->fromRoute('admin/actions', ['controller' => 'mailbox', 'action' => 'edit-folder'], ['query' => ['mail_box' => $mail_box, 'folder' => $folder_name]]));

			$form->add(array(
				'name' => 'id',
				'type' => 'hidden',
				));

			$form->add(array(
				'name' => 'folder',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
					'label' => $this->translate('Folder Name'),
					),
				'attributes' => array(
					'required' => 'required',
					'placeholder' => $this->translate('Folder Name'),
					),
				));

			$form->add(array(
				'name' => 'priority',
				'type' => 'Zend\Form\Element\Number',
				'options' => array(
					'required' => false,
					'label' => $this->translate('Priority'),
					),
				'attributes' => array(
					'min' => 0,
					'max' => 10,
					),
				));


			$form->addSubmit($this->translate('Save'));
			$form->setData($folder);

			if($this->request->isPost()){
			     $post = $this->request->getPost();
			     $form->setData($post);
			        if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
			        $data = $form->getData();
			        if($data['folder'] != $folder->folder) {
			        	$f = explode('.', $folder->folder_full);
			        	$predicat = $f[0];
			        	$data['folder_full'] = $predicat.'.'.$data['folder'];
						$account = $this->get('MailAccountsTable')->getOnField($mail_box, 'mail_box');
						$mailParser = $this->getMailParser();
						$mailParser->init($account, $this->identity()->id);
			        	try {
			        		$mailParser->getStorage()->selectFolder($folder->folder_full);
			        		$folder_exist = 1;
			        	} catch (\Exception $e) {
			        		$message = $e->getMessage();
			        	}
			        	if(isset($folder_exist)) $mailParser->getStorage()->renameFolder($folder->folder_full, $data['folder_full']);
			
			        	$this->get('MailBoxTable')->update(['mail_box' => $mail_box, 'folder_full' => $folder->folder_full], ['folder' => $data['folder'], 'folder_full' => $data['folder_full']]);
			        }

			        $this->get('MailBoxFoldersTable')->save($data);
			    $redirect = $this->url()->fromRoute('admin/actions', ['controller' => 'mailbox'], ['query' => ['filters' => ['mail_box' => $mail_box, 'folder' => $data['folder']]]]);
			 }
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
			$redirect = $this->url()->fromRoute('admin/actions', ['controller' => 'mailbox'], ['query' => ['filters' => ['mail_box' => $mail_box, 'folder' => $folder->folder]]]);
		}


		$viewModel = 0;
		if(isset($form)) 
			$viewModel = new ViewModel(array(
				'form' => $form,
				));

		return $this->viewResponse($success, $message, [
			'view_data' => $viewModel, 
			'template' => 'admin/mailbox/edit-folder',
			'exception' => (isset($e)) ? $e : null,
			'redirect' => (isset($redirect)) ? $redirect : null,
			'force_redirect' => (isset($redirect)) ? 1 : 0,
			]);
	}


	public function deleteFolderAction()
	{
		$success = 1;
		$message = '';
		$mail_box = $this->params()->fromQuery('mail_box');
		$folder = $this->params()->fromQuery('folder');
		try {
		
			$mails_count = $this->get('MailBoxTable')->getMails(null, ['mail_box' => $mail_box, 'folder' => $folder], ['count' => 1])->current()->count;
			if($mails_count) throw new \Application\Exception\Exception($this->translate('You cannot delete folder with mails'), 1);
			$folder = $this->get('MailBoxFoldersTable')->getFieldsByFields(['id', 'folder', 'folder_full'], ['mail_box' => $mail_box, 'folder' => $folder])->current();
			if(!$folder) throw new \Application\Exception\Exception($this->translate('Folder not found'), 1);

			// deleting folder from the mail host
			$account = $this->get('MailAccountsTable')->getOnField($mail_box, 'mail_box');
			$mailParser = $this->getMailParser();
			$mailParser->init($account, $this->identity()->id);
			// some metod deleting folders from storage :
			$mailParser->getStorage()->removeFolder($folder->folder_full);

			$this->get('MailBoxFoldersTable')->delete($folder->id);
			$message = $this->translate('Folder Deleted');
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin','controller' => 'mailbox','action' => 'delete-folder'] , $message);
			$redirect = $this->url()->fromRoute('admin/actions', ['controller' => 'mailbox'], ['query' => ['filters' => ['mail_box' => $mail_box]]]);
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}


		return $this->viewResponse($success, $message, [
			'exception' => (isset($e)) ? $e : null,
			'redirect' => (isset($redirect)) ? $redirect : $this->url()->fromRoute('admin/actions', ['controller' => 'mailbox']),
			'force_redirect' => (isset($redirect)) ? 1 : 0,
			]);
	}


	public function templatesAction(){
		$success = 1;
		$message = '';

		$template_id = $this->params()->fromQuery('template');

		try {
			$form = $this->get('MailForm')->removeFields(['attachments', 'mail_to', 'mail_cc']);
			$form->setAttribute('action', $this->url()->fromRoute('admin/actions', array('controller' => 'mailbox', 'action' => 'templates')));

			$templates = $this->get('AdminMailTemplatesTable')->getMailTemplates(null)->toArray();
			foreach ($templates as $template) {
				$templates_list[$template['id']] = $template['template_name'];
			}
			$form->get('mail_template')->setValueOptions($templates_list);
			$mail_text = '';
			if($template_id) {
				$form->get('mail_template')->setValue($template_id);
				for ($i=0; $i < count($templates); $i++) { 
					if($templates[$i]['id'] == $template_id) $template = $templates[$i];
				}
				$form->get('title')->setValue($template['mail_title']);
				$mail_text .= htmlspecialchars_decode($template['mail_text']);
			}
			$form->get('text')->setValue($mail_text);
			$form->addSubmit($this->translate('Save Template'));
			$form->get('submit')->setAttribute('data-ajax', true);

			if($this->request->isPost()){
				$data = array_merge_recursive(
					$this->request->getPost()->toArray(),
					$this->request->getFiles()->toArray()
					);
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
					$data = $form->getData();
					$new_template = array('owner_id' => $this->identity()->id, 
											'template_name' => $data['template_name'],
											'mail_title' => $data['title'],
											'mail_text' => $data['text'],
											'section' => $data['mail_from']
											);
					$this->get('AdminMailTemplatesTable')->save($new_template);	

					$success = 1;
					$message = $this->translate('Template Saved!');
					$redirect = $this->url()->fromRoute('admin/actions', ['controller' => 'mailbox', 'action' => 'templates']);
			} 

		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}

		$viewModel = 0;
		if(isset($form)) 
			$viewModel = new ViewModel(array(
				'form' => $form,
				'template_id' => $template_id,
				));

		return $this->viewResponse($success, $message, [
			'view_data' => $viewModel, 
			'template' => 'admin/mailbox/templates',
			'redirect' => (isset($redirect)) ? $redirect : null,
			'force_redirect' => (isset($redirect)) ? 1 : 0,
			'exception' => (isset($e)) ? $e : null,
			'extra_data' => (isset($extra_data)) ? $extra_data : null
			]);
	}


	public function signatureAction(){
		$success = 1;
		$message = '';

		try {
			$form = $this->get('EmptyForm');
			$form->setAttribute('action', $this->url()->fromRoute('admin/actions', array('controller' => 'mailbox', 'action' => 'signature')));
			$form->add(array(
			'name' => 'signature',
			'type' => 'Zend\Form\Element\Textarea',
			'options' => array(
				'label' => 'Signature',
				'required' => true,
				'filters'  => array(
					array('name' => 'HtmlEntities'),
					),
				),
			'attributes' => array(
				'rows' => 18,
				'id' => 'signature',
				),
			));
			$settingsTable = $this->get('AdminUserSettingsTable');
			$settings = $settingsTable->getUserSettings($this->identity()->id);
			$form->get('signature')->setValue(htmlspecialchars_decode($settings->mails_signature));

			$form->addSubmit($this->translate('Save Signature'));
			$form->get('submit')->setAttribute('data-ajax', true);

			if($this->request->isPost()){
				$data = array_merge_recursive(
					$this->request->getPost()->toArray(),
					$this->request->getFiles()->toArray()
					);
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
					$data = $form->getData();
					$settingsTable->save(array('user_id' => $this->identity()->id, 'mails_signature' => $data['signature']));
					$success = 1;
					$message = $this->translate('Signature Saved!');
					$redirect = $this->url()->fromRoute('admin/actions', ['controller' => 'mailbox', 'action' => 'signature']);
			} 

		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}

		$viewModel = 0;
		if(isset($form)) 
			$viewModel = new ViewModel(array(
				'form' => $form,
				));

		return $this->viewResponse($success, $message, [
			'view_data' => $viewModel, 
			'template' => 'admin/mailbox/signature',
			'redirect' => (isset($redirect)) ? $redirect : null,
			'force_redirect' => (isset($redirect)) ? 1 : 0,
			'exception' => (isset($e)) ? $e : null,
			]);
	}



	public function accountsAction()
	{

		$success = 1;
		$message = '';
		try {
		    $data_list = $this->get('MailAccountsTable')->getAccounts();
		} catch (\Exception $e) {
		    $success = 0;
		    $message = $e->getMessage();
		}

		
		$viewModel = 0;
		if(isset($data_list)) 
		    $viewModel = new ViewModel(array(
		        'data_list' => $data_list,
		        )); 

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'admin/mailbox/accounts',
		    'exception' => (isset($e)) ? $e : null
		    ]);
	}

	public function addAccountAction()
	{

		$success = 1;
		$message = '';
		try {
			$form = $this->get('MailAccountsForm');
			$form->setAttribute('action', $this->url()->fromRoute('admin/actions', array('controller' => 'mailbox', 'action' => 'add-account')));
			$form->addSubmit($this->translate('Add Mail Box'));
			$form->get('submit')->setAttribute('data-ajax', true);

			if($this->request->isPost()){
				$data = $this->request->getPost();
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
				$data = $form->getData();
				$this->get('MailAccountsTable')->save($data);
				$success = 1;
				$message = $this->translate('Mailbox Account Added');
				$redirect = $this->url()->fromRoute('admin/actions', ['controller' => 'mailbox', 'action' => 'accounts']);
			} 

		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}

		
		$viewModel = 0;
		if(isset($form)) 
		    $viewModel = new ViewModel(array(
		        'form' => $form,
		        )); 

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'admin/mailbox/add-account',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect)) ? $redirect : null,
		    'force_redirect' => (isset($redirect)) ? 1 : 0,
		    ]);
	}

	public function editAccountAction()
	{

		$success = 1;
		$message = '';
		try {
			$id = $this->params()->fromRoute('id');
			if(!$id) throw new \Application\Exception\Exception("Account id not supplied", 1);
			$accountTable = $this->get('MailAccountsTable');
			$account = $accountTable->get($id);
			if(!$account) throw new \Application\Exception\Exception("Account with current id not found", 1);
			$form = $this->get('MailAccountsForm');
			$old_password = $account->password;
			$form->setData($account);
			$form->setAttribute('action', $this->url()->fromRoute('admin/actions', array('controller' => 'mailbox', 'action' => 'edit-account', 'id' => $id)));
			$form->addSubmit($this->translate('Save Mail Box'));
			$form->get('submit')->setAttribute('data-ajax', true);

			if($this->request->isPost()){
				$data = $this->request->getPost();
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
				$data = $form->getData();
				$accountTable->save($data);
				$success = 1;
				$message = $this->translate('Mailbox Account Saved');
				$redirect = $this->url()->fromRoute('admin/actions', ['controller' => 'mailbox', 'action' => 'accounts']);
			} 

		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}
		
		$viewModel = 0;
		if(isset($form)) 
		    $viewModel = new ViewModel(array(
		        'form' => $form,
		        )); 

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'admin/mailbox/edit-account',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect)) ? $redirect : null,
		    'force_redirect' => (isset($redirect)) ? 1 : 0,
		    ]);
	}



	public function queryAction(){

		$success = 1;
		$message ='';
		$q_options = $this->setDefaultOptions(['_limit' => 50]);

		try {
			$table = $this->get('MailQueryTable');
			$data_list = $table->getQuery(null, $q_options['filters'], $q_options);
			$q_options['count'] = 1;
			$total_results = $table->getQuery(null, $q_options['filters'], $q_options)->current()->count;
		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}

		$viewModel = new ViewModel(array(
		'total_results' => $total_results,
		'data_list' => $data_list,
		'q_options' => $q_options,
		));

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'admin/mailbox/query',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect))? $redirect : null
		    ]);		
	}

	public function sendSavedMailAction()
	{
		$success = 1;
		$message = '';
		try {
			$unique_id = $this->params()->fromRoute('id');
			if(!$unique_id) throw new \Application\Exception\Exception("Mail id should be provided", 1);
			$success = $this->get('Mail')->sendSavedMail($unique_id);
			$message = $this->translate('Message Sent');
		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}

		return $this->viewResponse($success, $message, [
		    'exception' => (isset($e)) ? $e : null,
		    'force_redirect' => (isset($redirect)) ? 1 : 0,
		    'redirect' => (isset($redirect))? $redirect : $this->url()->fromRoute('admin/actions', ['controller' => 'mail_box', 'action' => 'query']),
		    ]);	
		
	}





	public function refreshMailboxAction()
	{
		$success = 1;
		$message = '';
		$force_update = $this->params()->fromQuery('force');
		$mail_box = $this->params()->fromQuery('mail_box');
		try {
			if($mail_box) $mail_accounts[] = $this->get('MailAccountsTable')->getOnField($mail_box, 'mail_box');
			else $mail_accounts = $this->get('MailAccountsTable')->getAccounts()->toArray();
			$extra_data['parsed'] = 0;
			$extra_data['total'] = 0;
			$extra_data['errors'] = 0;
			foreach ($mail_accounts as $account) {
				try {
					$extra_data[$account['mail_box']] = $this->updateAccount($account, null, $force_update);
					$extra_data['parsed'] = $extra_data['parsed'] + $extra_data[$account['mail_box']]['parsed'];
					$extra_data['errors'] = $extra_data['errors'] + $extra_data[$account['mail_box']]['errors'];
					$extra_data['total'] = $extra_data['total'] + $extra_data[$account['mail_box']]['total'];
				} catch (\Exception $e) {
					$extra_data[$account['mail_box']] = $e->getMessage();
					$extra_data['errors']++;
					$success = 0;
					if($e->getCode() == 743) $success = 1;
				}
				$message = 'Mail updated, '.$extra_data['parsed'].'/'.$extra_data['total'].' new messages saved ';
				if($extra_data['errors']) $message .= '<br />'.$extra_data['errors'].' errors occured during mailbox parsing';
			}
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}

		return $this->viewResponse($success, $message, [
			'exception' => (isset($e)) ? $e : null,
			'extra_data' => (isset($extra_data)) ? $extra_data : null,
			'redirect' => (isset($redirect)) ? $redirect : $this->url()->fromRoute('admin/actions', ['controller' => 'mailbox']),
			'force_redirect' => (isset($redirect)) ? 1 : 0,
			]);

	}

	protected function updateAccount($account, $folder_name = null, $force_update = 0)
	{
		$interval = 300;
		$last_update = $this->get('RefreshTable')->getLastRefresh($account['mail_box']);
		if(!$folder_name && ((time() - $last_update) < $interval)) 
			throw new \Application\Exception\Exception('not updated, '.$account['mail_box'].' last update :'.zformatDateTime($last_update).', next update: '.zformatDateTime($last_update+$interval), 743);


		$mailParser = $this->getMailParser();
		$mailParser->init($account, $this->identity()->id);

		$foldersTable = $this->get('MailBoxFoldersTable');
		$mb_folders = $foldersTable->getFieldsByFields(['id','folder', 'folder_full'], ['mail_box' => $account['mail_box']])->toArray();

		$response['parsed'] = 0;
		$response['errors'] = 0;
		$response['total'] = 0;
		$folders = $mailParser->getFolders();
		foreach ($folders as $folder) {
			$server_folder_full = $folder->getGlobalName();
			$server_folder = $folder->getLocalName();
			// adding new folders to database
			$found = 0;
			for ($i=0; $i < count($mb_folders); $i++) { 
				if($server_folder_full == $mb_folders[$i]['folder_full']) $found++;
			}
			if(!$found) $foldersTable->save(['mail_box' => $account['mail_box'], 'folder' => $server_folder, 'folder_full' => $server_folder_full]);
			
			//check to parse mailbox folder or not
			$to_parse = 0;
			if($folder_name) {
				if($server_folder_full == $folder_name) $to_parse++;
			} else $to_parse++;

			if($to_parse) {
				$mailParser->setCurrentFolder($folder);
				$result = $mailParser->parseMailbox();
				$response['folders'][$server_folder] = $result;
				$response['total'] = $response['total'] + $result['total_messages'];
				$response['parsed'] = $response['parsed'] + $result['parsed'];
				$response['errors'] = $response['errors'] + count($result['errors']);
			}
		}

		$this->get('RefreshTable')->refresh($account['mail_box']);
		return $response;
	}


	// public function deleteMailAction()
	// {
	// 	$mail_id = $this->params()->fromRoute('id');
	// 	try {
	// 		if(!$mail_id) throw new \Application\Exception\Exception("Mail id should be provided [MailerController]", 1);

	// 		$mail = $this->get('MailBoxTable')->getMail($mail_id);
	// 		if(!$mail) throw new \Application\Exception\Exception("Mail with such id not found [MailerController]", 404);
	// 		if($mail->location == 'server') {
	// 			$this->getMailParser()->getStorage()->selectFolder($mail->folder_full);
	// 			$unique_id = str_replace($mail->time, '', $mail->unique_id);
	// 			$current_id = $this->getMailParser()->getStorage()->getNumberByUniqueId($unique_id);
	// 			$this->getMailParser()->getStorage()->removeMessage($current_id);
	// 		}
	// 		$this->get('MailBoxTable')->deleteMail($mail);
	// 		$success = 1;
	// 		$message = $this->translate('message').' :'.$mail_id.' '.$this->translate('deleted');

	// 	} catch (\Exception $e) {
	// 		$success = 0;
	// 		$message = $e->getMessage();

	// 	}

	// 	return $this->viewResponse($success, $message, [
	// 		'redirect' => $this->url()->fromRoute('admin/actions', array('controller' => 'mailbox', 'action' => 'index')), 
	// 		'exception' => (isset($e)) ? $e : null
	// 		]);
	// }


}