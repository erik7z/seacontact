<?php
namespace UserInfo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class ContactsController extends AbstractController
{

	
	public function indexAction()
	{
		return $this->getContactList(['relations' => \Application\Model\UserContactsTable::RELATION_COLLEGUE]);
	}


	public function colleguesAction()
	{
		return $this->getContactList(['relations' => \Application\Model\UserContactsTable::RELATION_FRIENDS], ['empty_message' => '']);
	}


	public function subscribersAction()
	{
		return $this->getContactList(['relations' => \Application\Model\UserContactsTable::RELATION_FOLLOWER]);
	}



	private function getContactList($options, $view_options = [])
	{
		$q_options = $this->setDefaultOptions(['_limit' => 10]);
		$q_options = array_merge($options, $q_options);

		$login = $this->params()->fromRoute('user');
		if(!$login) throw new \Application\Exception\Exception("User not found", 1);
		$user_id = $this->get('UserTable')->getUserByLogin($login)->id;

		$contactsTable = $this->get('ContactsTable');
		try {
			$success = 1;
			$message = '';
			$form = $this->get('EmptyForm');
			
			$set_filters = (isset($view_options['set_filters'])) ? $view_options['set_filters'] : 1;
			if($set_filters) {
				$filterFields = $this->get('UserFilterFields')->remainFields([
					'name',
					]);
				
					$q_options['no_limits'] = 1;
					$users_list = $contactsTable->getContacts($user_id, [], $q_options)->buffer();
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
					]);
				$filterFields->get('home_city')->setAttribute('multiple', false);
				$filterFields->get('worked_in_psn')->setAttribute('multiple', false);
				$filterFields->get('ship_type')->setAttribute('multiple', false);

			}

			$form->add($filterFields);
			$form->addSubmit($this->translate('Search'));
			$form->setAttribute('method', 'get');

			if($this->params()->fromQuery('filters')){
			    $data = $this->request->getQuery();
			    $form->setData($data);
		        if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
		        $data = $form->getData();
		        $q_options['filters'] = array_filter($data['filters']);
			} else $filters = [];

		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}

		

		$q_options['no_limits'] = 0;
		$data_list = $contactsTable->getContacts($user_id, $q_options['filters'], $q_options);
		$q_options['count'] = 1;
		$total_results = $contactsTable->getContacts($user_id, $q_options['filters'], $q_options)->current()->count;
		
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
	
}