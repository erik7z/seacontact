<?php
namespace My\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;


class ContactsController extends AbstractController
{

	public function searchAction()
	{
		return $this->getContactList([], ['set_filters' => 0]);
	}

	public function indexAction()
	{
		return $this->getContactList(['relations' => \Application\Model\UserContactsTable::RELATION_FRIENDS]);
	}


	public function colleguesAction()
	{
		return $this->getContactList(['relations' => \Application\Model\UserContactsTable::RELATION_COLLEGUE], ['empty_message' => '']);
	}


	public function rcvdreqAction()
	{
		return $this->getContactList(['relations' => \Application\Model\UserContactsTable::RELATION_RCVD]);
	}	

	public function sentreqAction()
	{
		return $this->getContactList(['relations' => \Application\Model\UserContactsTable::RELATION_SENT]);
	}


	public function subscribersAction()
	{
		return $this->getContactList(['relations' => \Application\Model\UserContactsTable::RELATION_FOLLOWER]);
	}



	private function getContactList($options, $view_options = [])
	{
		$q_options = $this->setDefaultOptions(['_limit' => 10]);
		$q_options = array_merge($options, $q_options);
		
		$success = 1;
		$message = '';
		$data_list = [];
		$total_results = 0;

		$contactsTable = $this->get('ContactsTable');
		try {
			$form = $this->get('EmptyForm');
			$set_filters = (isset($view_options['set_filters'])) ? $view_options['set_filters'] : 1;
			if($set_filters) {
				$filterFields = $this->get('UserFilterFields')->remainFields([
					'name',
					]);
				
					$options['no_limits'] = 1;
					$users_list = $contactsTable->getContacts($this->identity()->id, [], $q_options)->buffer();
					$ranks_list = [];
					$ships_list = [];
					foreach ($users_list as $user) {
						$ranks_list[$user->desired_rank] = $user->desired_rank; 
					 	$ships_list[$user->worked_on] = $user->worked_on; 
					}
					
					$filterFields->add(array(
						'name' => 'ship_name',
						'type' => 'Zend\Form\Element\Select',
						'options' => array(
							'label' => $this->translate('Ship Name'),
							'empty_option' => '--Select--',
							'value_options' => $ships_list,
							'required' => false,
							),
						));

					$filterFields->add(array(
						'name' => 'desired_rank',
						'type' => 'Zend\Form\Element\Select',
						'options' => array(
							'label' => $this->translate('Rank'),
							'empty_option' => '--Select--',
							'value_options' => $ranks_list,
							'required' => false,
							),
						));

			} else {
				$filterFields = $this->get('UserFilterFields')->remainFields([
					'name',
					'nationality',
					'home_city',
					'age_from',
					'age_to',
					'worked_in_psn',
					'ship_name',
					'ship_type',
					'online',
					]);
				$filterFields->get('home_city')->setAttribute('multiple', false);
				$filterFields->get('worked_in_psn')->setAttribute('multiple', false);
				$filterFields->get('ship_type')->setAttribute('multiple', false);
				$filterFields->setup([], ['min_home_city' => 50]);

			}

			$form->add($filterFields);
			$form->addSubmit($this->translate('Search'));
			$form->setAttribute('method', 'get');

			if($this->params()->fromQuery('filters')){
			    $data = $this->request->getQuery();
			    $form->setData($data);
		        if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
		        $data = $form->getData();
		        $filters = $data['filters'];
		        $filters = array_filter($filters);
			} else $filters = [];

			$q_options['no_limits'] = 0;
			$data_list = $contactsTable->getContacts($this->identity()->id, $q_options['filters'], $q_options);
			$q_options['count'] = 1;
			$total_results = $contactsTable->getContacts($this->identity()->id, $q_options['filters'], $q_options)->current()->count;
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}


		
		$viewModel = 0;
		if(isset($form)) 
			$viewModel = new ViewModel([
				'data_list' => $data_list,
				'form' => $form,
				'q_options' => $q_options,
				'total_results' => $total_results,
				]);

		return $this->viewResponse($success, $message, [
			'view_data' => $viewModel, 
			'template' => 'my/contacts/index',
			'exception' => (isset($e)) ? $e : null
			]);
	}


	public function addAction()
	{
		return $this->editContacts('add');
	}

	public function removeAction()
	{
		return $this->editContacts('remove');
	}

	public function deleteAction()
	{
		return $this->editContacts('remove');
	}

	public function tosubAction()
	{
		return $this->editContacts('tosub');
	}


	private function editContacts($action)
	{
		$user_id = $this->identity()->id;
		$friend_id = $this->params()->fromRoute('id');
		$message = '';
		try {
			if(!$friend_id) throw new \Application\Exception\Exception("Friend id not provided", 1);
			$contactsTable = $this->get('ContactsTable');
			if($action == 'add') {
				$contactsTable->addFriendship($user_id, $friend_id);
				$userNotTable = $this->get('UserNotificationsTable');
				$message = $this->translate('Request sent');
				$this->addUserNotification($userNotTable::TYPE_NEW_CONTACT, \Application\Model\NewsTable::SECTION_USER, $user_id, $user_id, $friend_id);
			}
			else if($action == 'remove') {
				$contactsTable->denyFriendship($user_id, $friend_id);
				$message = $this->translate('Friendship canceled');
			}
			else if($action == 'tosub') {
				$contactsTable->denyFriendship($user_id, $friend_id);
				$message = $this->translate('User moved to subscribers');
			}
			else if($action == 'notified_about_contact') throw new \Application\Exception\Exception("To be made", 1);
			$success = 1;
			
		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}

		return $this->viewResponse($success, $message, [
			'redirect' => $this->url()->fromRoute('sc/contacts'), 
			'exception' => (isset($e)) ? $e : null
			]);

	}

}