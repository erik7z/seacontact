<?php
namespace Admin\Controller;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class VacanciesController extends AbstractController
{

	
	/**
	 * Index action of some controller
	 * @return [type]
	 */

	public function indexAction()
	{
		$success = 1;
		$message ='';
		$show_form = 0;

		$q_options = $this->setDefaultOptions();

		try {
			$vacanciesTable = $this->get('VacanciesTable');
			$q_options['buffer'] = 1;
			$q_options['show_hidden'] = 1;
			$q_options['count_candidates'] = 1;
			$vacancies = $vacanciesTable->getAllVacancies(null, $q_options['filters'], $q_options);
			$q_options['buffer'] = 0;
			$q_options['count'] = 1;
			$total_results = $vacanciesTable->getAllVacancies(null, $q_options['filters'], $q_options)->current()->count;
			$form = $this->get('VacancyForm');
			$form->setup($this->get('VacancyFields'), $this->identity()->id, ['admin_form' => 1, 'post_vk' => $this->isPermitted('_info_\social\post_vk')]);
			
			$show_form = 0;
			
			if($this->request->isPost()){
				$result =  $this->forward()->dispatch('Application\Controller\Vacancies', array(
				                'controller' => 'Application\Controller\Vacancies', 
				                'action' => 'add',
				                'dispatch' => 1,
				                'admin_form' => 1,
				                'query' => ['redirect' => $this->url()->fromRoute('admin/actions', ['controller' => 'vacancies', 'action' => 'index'])]
				                ));
				// it is necessary to reset layout after dispatch
				$this->layout('layout/admin');
				if($result->success){
					$this->get('AdminActivityTable')
						->addActivity($this->identity()->id, ['module' => 'Admin','controller' => 'vacancies','action' => 'index'],'Added new vacancy'.' : '.$result->data['vacancies']['title']);
					$redirect = $this->url()->fromRoute('admin/actions', ['controller' => 'vacancies']);
				} 
				else {
					// if any errors display form
					$form = $result->form;
					$show_form = 1;
				}
			} else $this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin','controller' => 'vacancies','action' => 'index'],'Opened vacancies list');
		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}


	$viewModel = 0;
	if(isset($form)) $viewModel = new ViewModel(array(
		'vacancies' => $vacancies,
		'show_form' => $show_form,
		'form' => $form,
		'q_options' => $q_options,
		'total_results' => $total_results
		));

	return $this->viewResponse($success, $message, [
	    'view_data' => $viewModel, 
	    'template' => 'admin/vacancies/index',
	    'exception' => (isset($e)) ? $e : null,
	    'redirect' => (isset($redirect))? $redirect : null
	    ]);	
	}



	public function vacancyAction()
	{

		$vacancy_id = $this->params()->fromRoute('id');
		$vacanciesTable = $this->get('VacanciesTable');
		$vacancy = $vacanciesTable->getAllVacancies($this->identity()->id, ['id' => $vacancy_id])->current();
		if(!$vacancy) throw new \Application\Exception\Exception($this->translate('Vacancy with such id not found', 404));

		$this->get('AdminNotifReadedTable')->readNotifications($this->identity()->id, null, 'vacancy', $vacancy_id);
		
		$form = $this->get('VacancyForm');
		$form->setup($this->get('VacancyFields'), $this->identity()->id, ['admin_form' => 1, 'vacancy_data' => $vacancy, 'submit_text' => $this->translate('Save Vacancy'), 'post_vk' => $this->isPermitted('_info_\social\post_vk')]);
		
		$company_id = $vacancy['user'];
		$original_time = $vacancy['time'];

		if($this->request->isPost()){
			$result =  $this->forward()->dispatch('Application\Controller\Vacancies', array(
			                'controller' => 'Application\Controller\Vacancies', 
			                'action' => 'edit',
			                'id' => $vacancy_id,
			                'dispatch' => 1,
			                'admin_form' => 1,
			                'query' => ['redirect' => $this->url()->fromRoute('admin/actions', ['controller' => 'vacancies', 'action' => 'vacancy'])]
			                ));
			// it is necessary to reset layout after dispatch
			$this->layout('layout/admin');
			if($result->success)  $this->get('AdminActivityTable')
									->addActivity($this->identity()->id, ['module' => 'Admin','controller' => 'vacancies','action' => 'vacancy', 'id' => $vacancy_id],'Updated Vacancy'.' : '.$result->data['vacancies']['title']);
			else {
				// if any errors display form
				if($result->form) $form = $result->form;
				$show_form = 1;
			}
		} else $this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin','controller' => 'vacancies','action' => 'vacancy', 'id' => $vacancy_id], 'Opened vacancy page');


		$subscribers = $this->get('VacancySubsTable')->getVacancySubscribers($this->identity()->id, ['id' => $vacancy_id], ['include_notes' => 1]);
		$candidates = $this->get('VacancyCandTable')->getVacancyCandidates($this->identity()->id, ['vacancy_id' => $vacancy_id]);

		$view = new ViewModel(array(
			'vacancy' => $vacancy,
			'subscribers' => $subscribers,
			'candidates' => $candidates,
			'form' => $form
			));
		return $view;	
	}

	public function vacanciesCandidatesAction()
	{
		$success = 1;
		$message ='';
		$q_options = $this->setDefaultOptions();
		$q_options['show_fields'] = ['item_number', 'vacancy_title', 'vacancy_description', 'edit', 'login', 'office_note', 'name', 'desired_rank', 'assigned_time', 'assigned_by', 'status'];
		$q_options['table_sorting'] = 1;
		$q_options['filters'] = (empty($q_options['filters']))? ['status' => \Application\Model\VacancyCandidatesTable::STATUS_PENDING] : $q_options['filters'];
		
		try {
			$table = $this->get('VacancyCandTable');
			$data_list = $table->getVacancyCandidates($this->identity()->id, $q_options['filters'], $q_options);
			$q_options['count'] = 1;
			$total_results = $table->getVacancyCandidates($this->identity()->id, $q_options['filters'], $q_options)->current()->count;
			
		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}

		$viewModel = 0;
		if($data_list) $viewModel = new ViewModel(array(
		'total_results' => $total_results,
		'data_list' => $data_list,
		'q_options' => $q_options,
		));

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'admin/vacancies/vacancies-candidates',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect))? $redirect : null
		    ]);	

	}

	public function toggleActiveAction() {

		try {
			$vacancy_id = (int)$this->params()->fromRoute('id', null);
			if(!$vacancy_id) throw new Exception("Vacancy id not set", 1);
			$vacancy_status = $this->params()->fromQuery('status', 0);

			$this->get('VacanciesTable')->setFieldOnID($vacancy_id, 'active', $vacancy_status);
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
															array(
																'module' => 'Admin',
																'controller' => 'vacancies',
																'action' => $this->routeMatch()->getParam('action'),
																'id' => $vacancy_id
																) ,
															$this->translate('Changed vacancy status') 
														);
			$response['success'] = true;
			$response['data'] = 'Vacancy status changed';			
		} catch (\Exception $e) {
			$response['success'] = false;
			$response['data'] = $e->getMessage();
		}

		$view = new ViewModel(array(
			'response' => json_encode($response),
			));
		$view->setTemplate('/user-info/json');
		$view->setTerminal(true);
		return $view;
	}


	public function assignCandidateAction()
	{
		$user_id = $this->params()->fromRoute('id');
		try {
			if(!$user_id) throw new \Application\Exception\Exception("User id not provided", 1);
			
			$user = $this->get('UserTable')->getUserById($user_id, 0, ['id','name', 'surname', 'full_name', 'login', 'avatar', 'cv_avatar', 'desired_rank']);
			$vacanciesTable = $this->get('VacanciesTable');
			$active_vacancies = $vacanciesTable->getAllVacancies(null, ['company_id' => _CREWING_ID_], array('show_hidden' => false, 'buffer' => 1));
			$candidate_vacancies = $vacanciesTable->getAllVacancies(null, ['candidate_id' => $user_id], array('show_hidden' => true, 'buffer' => 1));

			$value_options = [];
			foreach ($active_vacancies as $vacancy) {
				$value_options[] = ['value' => $vacancy->id, 'label' => $vacancy->title, 'selected' => false];
			}

			$candidateFields = $this->get('VacancyCandFields');
			$candidateFields->get('vacancy')->setValueOptions($value_options);
			$form = $this->get('EmptyForm');
			$form->add($candidateFields);
			$form->addSubmit($this->translate('Assign Candidate'));
			$form->get('submit')->setAttribute('data-ajax', true);
			$form->setAttribute('action', $this->url()->fromRoute('admin/actions', ['controller'=>'vacancies', 'action' => 'assign-candidate','id' => $user_id]));
			$success = 1;
			$message = '';
			if($this->request->isPost()){
				$data = $this->request->getPost()->toArray();
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
				$data = $form->getData();
				$data = $data['vacancy_candidates'];
				$this->get('VacancyCandTable')->assignCandidate($data['vacancy'], $user_id, $this->identity()->id, $data['status']);
				$message = $this->translate('Assigned Candidate for vacancy');
				$notificationsTable = $this->get('AdminNotifTable');
				$notificationsTable->addNotification('assign_candidate', $notificationsTable::NOT_SECTION_CREWING, $data['vacancy'], $user_id, $message);
				if($data['comments']) $message .= ' [ Comments: '.$data['comments'].' ]';
				$cvNotesTable = $this->get('UserCvNotesTable');
				$cvNotesTable->saveUserCvNote($user_id, $this->identity()->id, $message, $cvNotesTable::VIS_ADMIN_ALL);
				$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
																array(
																	'module' => 'Admin',
																	'controller' => 'vacancies',
																	'action' => 'assign-candidate',
																	'id' => $data['vacancy']
																	) ,
																$message
															);
			}			
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}

		$viewModel = 0;
		if(isset($form)) 
			$viewModel = new ViewModel(array(
				'form' => $form,
				'active_vacancies' => $active_vacancies,
				'candidate_vacancies' => $candidate_vacancies,
				'user' => $user,
				));

		return $this->viewResponse($success, $message, [
			'view_data' => $viewModel, 
			'template' => 'admin/vacancies/assign-candidate',
			'exception' => (isset($e)) ? $e : null
			]);

	}


	public function setCandidateStatusAction()
	{
		$user_id = $this->params()->fromRoute('id');
		$vacancy_id = $this->params()->fromQuery('vacancy');
		$status = $this->params()->fromQuery('status');
		try {
			if(!$user_id) throw new \Application\Exception\Exception("User Id Not provided", 1);
			if(!$vacancy_id) throw new \Application\Exception\Exception("Vacancy Id Not provided", 1);
			
			$this->get('VacancyCandTable')->setCandidateStatus($vacancy_id, $user_id, $status);
			$success = 1;
			$message = $this->translate('Candidate Status Changed To').' : '.\Application\Model\VacancyCandidatesTable::getCandidateStatusList()[$status];
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
															array(
																'module' => 'Admin',
																'controller' => $this->routeMatch()->getParam('__CONTROLLER__'),
																'action' => $this->routeMatch()->getParam('action'),
																'id' => $user_id
																) ,
															$message
														);
			$notificationsTable = $this->get('AdminNotifTable');
			$notificationsTable->addNotification('assign_status', $notificationsTable::NOT_SECTION_CREWING, $vacancy_id, $user_id, $message);

		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}
		return $this->viewResponse($success, $message, [
			'redirect' => $this->url()->fromRoute('admin/actions', array('controller' => 'userdb', 'action' => 'user', 'id' => $user_id)), 
			'exception' => (isset($e)) ? $e : null
			]);
	}




}