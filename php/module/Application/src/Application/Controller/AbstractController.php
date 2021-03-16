<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;


class AbstractController extends AbstractActionController
{

	protected function setDefaultOptions($default_options = [])
	{
		$q_options['q'] = $this->params()->fromQuery('q', (isset($default_options['q'])? $default_options['q'] : null ));
		$q_options['_order'] = $this->params()->fromQuery('_order', (isset($default_options['_order'])? $default_options['_order'] : null ));
		$q_options['up'] = $this->params()->fromQuery('up', (isset($default_options['up'])? $default_options['up'] : null ));
		$q_options['_limit'] = $this->params()->fromQuery('_limit', (isset($default_options['_limit'])? $default_options['_limit'] : 10 ));
		$q_options['_page'] = $this->params()->fromQuery('_page', (isset($default_options['_page'])? $default_options['_page'] : 1 ));
		$q_options['_offset'] = $this->params()->fromQuery('_offset', (isset($default_options['_offset'])? $default_options['_offset'] : null ));
		$q_options['layout'] = $this->params()->fromQuery('layout', (isset($default_options['layout'])? $default_options['layout'] : null ));
		$q_options['show_fields'] = $this->params()->fromQuery('show_fields', (isset($default_options['show_fields'])? $default_options['show_fields'] : null ));
		$q_options['form_fields'] = $this->params()->fromQuery('form_fields', (isset($default_options['form_fields'])? $default_options['form_fields'] : null ));
		$filters = $this->params()->fromQuery('filters');
		$q_options['filters'] = ($filters)? array_filter($filters) : (isset($default_options['filters'])? $default_options['filters'] : [] );
		return array_merge($default_options, $q_options);
	}


	public function viewResponse($success = 1, $message = null, $options)
	{
		$viewData = (isset($options['view_data']))? $options['view_data'] : null;
		$template = (isset($options['template']))? $options['template'] : null;
		$redirect = (isset($options['redirect']))? $options['redirect'] : 0;
		$force_redirect = (isset($options['force_redirect']))? $options['force_redirect'] : 0;
		$terminal = (isset($options['terminal']))? $options['terminal'] : 0;
		$no_json = (isset($options['no_json']))? $options['no_json'] : 0;
		$exception = (isset($options['exception']))? $options['exception'] : null;
		$extra_data = (isset($options['extra_data']))? $options['extra_data'] : null;
		$refresh = (isset($options['refresh']))? $options['refresh'] : 0;
		if(isset($options['code'])) $code =$options['code'];
		else if($success) $code = 200;
		else if($exception) $code = $exception->getCode();
		else $code = 400;

		if($this->request->isXmlHttpRequest() && !$no_json) {
			if($viewData instanceof \Zend\View\Model\ViewModel) {
				$viewData->setTemplate($template);
				$viewData->setOption('has_parent', true);
				$viewData = $this->get('ViewManager')->getView()->render($viewData);
			}			
		    $response['success'] = $success;
		    $response['code'] = $code;
		    $response['message'] = $message; 
		    $response['data'] = $viewData;
		    $response['extra_data'] = $extra_data;
		    $response['redirect'] = $redirect;
		    $view = new ViewModel(array(
		        'response' => json_encode($response),
		        ));
		    $view->setTemplate('/user-info/json');
		    $view->setTerminal(true);
		    return $view;
		} 
		// if($success && $message) $this->fm()->addSuccessMessage($message);
		// else if(!$success) $this->fm()->addErrorMessage($message);
		if($redirect) {
			if($force_redirect) return $this->redirect()->toUrl($redirect);

			$url = $this->request->getServer()->HTTP_REFERER;
			if($url) return $this->redirect()->toUrl($url);
			return $this->redirect()->toUrl($redirect);
		}

		if($refresh) return $this->redirect()->refresh();
		// if there is no viewmodel built and no redirect, means error or exception
		if(!$viewData) throw new \Application\Exception\Exception((isset($exception) ? $exception : $message), 1);

		if($viewData instanceof \Zend\View\Model\ViewModel){
			if($terminal) $viewData->setTerminal(1);
			if($template) $viewData->setTemplate($template);
		}
		return $viewData;	
	}

	protected function addUserNotification($notif_type, $section, $section_id, $from_id, $to_id, $options = [])
	{
		$text = isset($options['text'])? $options['text'] : null;
		$time = isset($options['time'])? $options['time'] : null;

		if($from_id == $to_id) return false;
		$not_often_than = time() - 86400; // 24hrs mins
		$userNotTable = $this->get('UserNotificationsTable');
		$userTable = $this->get('UserTable');
		$contactsTable = $this->get('ContactsTable');
		$partnership_info = $contactsTable->getPartnershipInfo($to_id, $from_id);
		

		// not recording private message notifications to db
		if($notif_type != $userNotTable::TYPE_PRIVATE_MESSAGE)
			$userNotTable->addNotification($notif_type, $section, $section_id , $from_id, $to_id, $text, null, $time);
		
		
		if($userNotTable->getLastMailNotificationTime($to_id, $notif_type, $from_id) < $not_often_than) {
			$mail = $this->get('Mail');
			$from_user_info = $userTable->getUserById($from_id, false, ['name', 'surname', 'full_name','login', 'email', 'avatar', 'cv_avatar','home_country','home_city','home_address','contact_email','contact_mobile','contact_phone','info_website','company_name','company_description']);
			$to_user_info = $userTable->getUserById($to_id, false, ['name', 'surname', 'full_name','login', 'email', 'avatar', 'cv_avatar']);
			if ($notif_type == $userNotTable::TYPE_COMMENT 
				// || $notif_type == $userNotTable::TYPE_LIKE // no send emails for the likes
				) {
				$mail->sendPostActivitytNotificationMail($to_user_info->email,zgetUserName($to_user_info),
					['type' => $notif_type, 'post_url' => $this->getPostUrl($section, $section_id), 'text' => htmlspecialchars_decode($text)], 
					$from_user_info, 
					$partnership_info
				);
			} else if($notif_type == $userNotTable::TYPE_UNLOCK_CV) {
				$mail->sendCvUnlockedNotificationMail($to_user_info->email,$to_user_info,$from_user_info);
			} else if($notif_type == $userNotTable::TYPE_ANSWER){
				$mail->sendPostActivitytNotificationMail(
				    $to_user_info->email,
				    zgetUserName($to_user_info),
				    [
				        'type' => $notif_type, 
				        'post_url' => $options['post_url'], 
				        'q_text' => $options['q_text'], 
				        'a_text' => $options['a_text'], 
				    ], 
				    $options['answer_author'], 
				    $partnership_info
				);
				
			} else if ($notif_type == $userNotTable::TYPE_PRIVATE_MESSAGE) {
				$mail->sendPrivateMessageNotification(
					$to_user_info->email,
					zgetUserName($to_user_info),
					['chat_id' => $options['chat_id'], 'text' => htmlspecialchars_decode($options['text'])], 
					$from_user_info, 
					$partnership_info
				);
			}
			
			$userNotTable->updateMailNotificationTime(time(), $notif_type, $section, $section_id, $from_id, $to_id);
		}


	}

	protected function getPostAuthorBySectionId($section, $section_id)
	{
		$owner_id = 0;
		if($section == \Application\Model\NewsTable::SECTION_VACANCY) {
			$owner_id = $this->get('VacanciesTable')->get($section_id)->user;
		} else if($section == \Application\Model\NewsTable::SECTION_LOGBOOK) {
			$owner_id = $this->get('LogBookTable')->get($section_id)->user;
		} else if($section == \Application\Model\NewsTable::SECTION_QUESTIONS) {
			$owner_id = $this->get('QuestionsTable')->get($section_id)->user;
		} else if($section == \Application\Model\NewsTable::SECTION_ANSWERS) {
			$owner_id = $this->get('QuestionAnswersTable')->get($section_id)->user;
		}
		return $owner_id;
	}


	protected function getPostUrl($section, $section_id)
	{
		if($section == \Application\Model\NewsTable::SECTION_VACANCY) $url = '/vacancies/view/'.$section_id;
		else if($section == \Application\Model\NewsTable::SECTION_LOGBOOK) $url = '/logbook/view/'.$section_id; 
		else if($section == \Application\Model\NewsTable::SECTION_QUESTIONS) $url = '/questions/view/'.$section_id;
		else if($section == \Application\Model\NewsTable::SECTION_ANSWERS) {
			$question_id = $this->get('QuestionAnswersTable')->get($section_id)->question_id;
			$url = '/questions/view/'.$question_id;
		}
		else $url = '/news';
		return _ADDRESS_NO_SLASH_.$url;
	}
	

	protected function getPublicationTime($new_time, $original_time = null)
	{
		$new_time = strtotime($new_time);
		$original_time = strtotime($original_time);
		
		if (!$new_time) {
			if($original_time) $new_time = $original_time;
			else $new_time = time();
		} else {
			if($original_time) {
				if($new_time < time() && $new_time != $original_time) $new_time = $original_time;
			} 
		}

		return $new_time;
	}


}