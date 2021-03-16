<?php
namespace My\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class MessagesController extends AbstractController
{
	public function indexAction()
	{
		$success = 1;
		$message = '';
		$from_id = $this->identity()->id;
		if($this->params()->fromRoute('dispatch')) {
			$from_id = $this->params()->fromRoute('from_id', $from_id);
		}
		$q_options = $this->setDefaultOptions(['_limit' => 30]);
		try {
			$messageTable = $this->get('MessageTable');
			$data_list = $messageTable->getListOfChats($from_id, $q_options['filters'], $q_options);
			$q_options['count'] = 1;
			$total_results = $messageTable->getListOfChats($from_id, $q_options['filters'], $q_options)->current()->count;
			if(!$q_options['_offset']) $q_options['_offset'] = ($q_options['_limit'] * $q_options['_page']) - $q_options['_limit'];
		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}

		$viewModel = new ViewModel(array(
		'data_list' => $data_list,
		'total_results' => $total_results,
		'q_options' => $q_options,
		'from_id' => $from_id
		));

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'my/messages/index',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect))? $redirect : null
		    ]);	
	}

	public function pmAction()
	{
		$success = 1;
		$message = '';
		$dispatch = 0;
		$send_to_id = $this->params()->fromQuery('talk');
		$chat_id = $this->params()->fromRoute('id');
		$from_id = $this->identity()->id;
		if($this->params()->fromRoute('dispatch')) {
			$from_id = $this->params()->fromRoute('from_id', $from_id);
			$dispatch = 1;
		}

		try {
			$messageTable = $this->get('MessageTable');
			$userTable = $this->get('UserTable');
			if(!$chat_id) {
				if(!$send_to_id) throw new \Application\Exception\Exception("No chat id given", 1);
				if(!$userTable->getUserById($send_to_id)) throw new \Application\Exception\Exception("User not found", 1);
				$chat_id = $messageTable->getChatIDBy2Users($from_id, $send_to_id);
				$chat_members = array(array('user_id' => $send_to_id, 'name' => $userTable->getUserName($send_to_id)));
			}
			
			if(!$dispatch && !$messageTable->getChatAccess($from_id, $chat_id)) throw new \Application\Exception\Exception("You cannot Access this page", 1);

			$form = $this->get('ContactsMessagesForm');
			if($this->request->isPost())
			{
				$data = $this->request->getPost();
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
				$reply = $form->getData();
				$to_user = $messageTable->getChatMembers($chat_id, $from_id)->current();
				$to_id = ($to_user['user_id'])? $to_user['user_id'] : $send_to_id;
				if(!$to_id) throw new \Application\Exception\Exception("Message could not be sent to nobody", 1);
				if($this->identity()->type == 'company'){
					$old_chat = $messageTable->checkChatIdBy2Users($from_id, $to_id);
					if(!$old_chat) {
						$stats = $this->get('CompanyUsersTable')->getCompanyStats($from_id);
						$role_limits = $this->get('access')->getAccessList()->getRolesLimits($this->identity()->role);
						if($stats->messages_day >= $role_limits['MESSAGES_DAY']) throw new \Application\Exception\Exception(sprintf($this->translate('You cannot write to more than %s new users per day'), $role_limits['MESSAGES_DAY']), 1);
					}
				}
				if(!$messageTable->validateMessage($from_id, $reply['text'] , $chat_id, $to_id, time())) throw new \Application\Exception\Exception("Your message didnt pass validation", 1);

				$messageTable->addMessage($from_id, $reply['text'], $chat_id,  $to_id);

				$userNotTable = $this->get('UserNotificationsTable');
				$this->addUserNotification(
						$userNotTable::TYPE_PRIVATE_MESSAGE, 
						\Application\Model\NewsTable::SECTION_USER, 
						$from_id, $from_id, $to_id, 
						['text' => $reply['text'], 'chat_id' => $chat_id]
					);

				$form->get('text')->setValue('');
				$success = 1;
				$message = $this->translate('Message Sent!');
			}
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777) ? unserialize($e->getMessage()) : $e->getMessage();
			$redirect = $this->url()->fromRoute('sc/messages');
		}

		$messageTable->setReadedMessages($from_id, $chat_id);
		// $fresh_msgs = $messageTable->getUnreadedMessages($from_id)->count();
		// $this->layout()->fresh_msgs = $fresh_msgs;
		// $response['fresh_msgs'] = $fresh_msgs;

		if(!isset($chat_members)) $chat_members = $messageTable->getChatMembers($chat_id, $from_id);

		$viewModel = 0;
		if(isset($form)) 
			$viewModel = new ViewModel(array(
				'form' => $form,
				'chat_id' => $chat_id,
				'send_to_id' => $send_to_id,
				'chat_members' => $chat_members,
				'from_id' => $from_id
				));

		return $this->viewResponse($success, $message, [
			'view_data' => $viewModel, 
			'template' => 'my/messages/pm',
			'exception' => (isset($e)) ? $e : null,
			'redirect' => (isset($redirect))? $redirect : null
			]);
	}



	public function pmflowAction()
	{
		$success = 1;
		$message = '';
		$from_id = $this->identity()->id;
		$chat_id = $this->params()->fromRoute('id');
		$messages = [];
		try {
			if(!$chat_id) throw new \Application\Exception\Exception("No chat id given", 1);
			$messageTable = $this->get('MessageTable');
			if($this->params()->fromRoute('dispatch')) {
				$from_id = $this->params()->fromRoute('from_id', $from_id);
			} else {
				if(!$messageTable->getChatAccess($this->identity()->id, $chat_id)) throw new \Application\Exception\Exception("You cannot Access this page", 1);
			}
			$messages = array_reverse($messageTable->getChat($chat_id)->toArray());
		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}

		$viewModel = new ViewModel(array(
			'messages' => $messages,
			'from_id' => $from_id
		));

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'my/messages/pm-flow',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect))? $redirect : null,
		    'terminal' => true
		    ]);	
	}







	public function deleteAction()
	{
		$user_id = $this->identity()->id;
		$message_id = $this->params()->fromRoute('id');
		if(!$user_id || !$message_id) return $this->error();
		
		$messageTable = $this->get('MessageTable');
		if(!$messageTable->existsID($message_id)) return $this->error('Message not exists');
		$chat_id = $messageTable->getChatIDByMsgID($message_id);
		$url = $this->url()->fromRoute('sc/messages/actions', array('action' => 'pm', 'id' => $chat_id));

		if(!$messageTable->canDelete($user_id, $message_id)) return $this->error('You dont have rights to perform this operation', $url);
		$result = $messageTable->save(['id' => $message_id, 'active' => 0]);

		if(!$result) return $this->error('Error during delete', $url);
		return $this->success('Message Deleted', $url);
	}




	public function error($message = 'Unknown Error', $url = null)
	{
		if(!$url) $url = $this->url()->fromRoute('sc/messages');
		$this->fm()->addErrorMessage($message);
		return $this->redirect()->toUrl($url);
	}

	public function success($message = 'Success!', $url = null)
	{
		if(!$url) $url = $this->url()->fromRoute('sc/messages');
		$this->fm()->addSuccessMessage($message);
		return $this->redirect()->toUrl($url);
	}



}