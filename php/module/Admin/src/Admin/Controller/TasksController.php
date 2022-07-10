<?php
namespace Admin\Controller;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class TasksController extends AbstractController
{

	
	/**
	 * Index action of some controller
	 * @return [type]
	 */

	public function indexAction()
	{
		$success = 1;
		$message ='';
		$q_options = $this->setDefaultOptions(['_limit' => 50]);

		try {
			$table = $this->get('TasksTable');
			$data_list = $table->getTasks($this->identity()->id, $q_options['filters'], $q_options);
			$q_options['count'] = 1;
			$total_results = $table->getTasks($this->identity()->id, $q_options['filters'], $q_options)->current()->count;
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin', 'controller' => 'tasks', 'action' => 'index', 'query' => http_build_query(['filters' => $q_options['filters']])] ,'Opened tasks list');
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
		    'template' => 'admin/tasks/index',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect))? $redirect : null
		    ]);		
	}

	public function viewAction()
	{

		try {
			$success = 1;
			$message = '';
			$id = (int)$this->params()->fromRoute('id');
			if($id == 0) throw new \Application\Exception\Exception("Task id Not provided", 1);
			$task = $this->get('TasksTable')->getTask($id, $this->identity()->id);
			if(!$task) throw new \Application\Exception\Exception($this->translate('Task not found or not visible for you', 1));
			$task_activity = $this->get('TasksTable')->getTaskActivity($id);
			$this->get('AdminNotifReadedTable')->readNotifications($this->identity()->id, null, \Application\Model\AdminNotifTable::NOT_SECTION_ADMIN_TASK, $id);
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
															array(
																'module' => 'Admin',
																'controller' => 'tasks',
																'action' => 'view',
																'id' => $id
																) ,
															'Viewed task'.' : '.$task->title 
														);
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}

		$viewModel = 0;
		if(isset($task)) 
			$viewModel = new ViewModel(array(
				'task_activity' => $task_activity,
				'task' => $task
				));

		return $this->viewResponse($success, $message, [
			'view_data' => $viewModel, 
			'template' => 'admin/tasks/view',
			'exception' => (isset($e)) ? $e : null,
			]);			
	}


	public function addAction()
	{
		$success = 1;
		$message = '';
		try {
			$form = $this->getTaskForm();
			$flag_redirect = 0;
			if($this->request->isPost()) {
				$data = $this->request->getPost();
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
				if(!array_search(1, $data['responsible'])) {
					$err_message = $this->translate("Responsible person not defined");
					$form->get('responsible')->get('resp_errors')->setMessages([$err_message]);
					throw new \Application\Exception\Exception($err_message, 1);
				}
				
				$data = $form->getData();
				$task_id = $this->get('TasksTable')->addTask($this->identity()->id, $data['admin_tasks'],$data['responsible'], $data['visible']);
				$success = 1;
				$message = 'New Task Added';
				$notTable = $this->get('AdminNotifTable');
				$notTable->addNotification($notTable::NOT_TYPE_NEW_TASK, $notTable::NOT_SECTION_ADMIN_TASK, $task_id, $this->identity()->id, $data['admin_tasks']['title']);
				$flag_redirect = 1;
				$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
																array(
																	'module' => 'Admin',
																	'controller' => 'tasks',
																	'action' => 'add',
																	'id' => $task_id
																	) ,
																'Added new task'.' : '.$data['admin_tasks']['title'] 
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
				));

		return $this->viewResponse($success, $message, [
			'view_data' => $viewModel, 
			'template' => 'admin/tasks/add',
			'exception' => (isset($e)) ? $e : null,
			'redirect' => ($flag_redirect) ? $this->url()->fromRoute('admin/actions', ['controller' => 'tasks', 'action' => 'index']) : null,
			'force_redirect' => 1,
			]);
	}

	public function deleteAction()
	{
		try {
			$success = 1;
			$message = '';
			$id = (int)$this->params()->fromRoute('id');
			if($id == 0) throw new \Application\Exception\Exception("Task id Not provided", 1);
			if(!$this->isPermitted('admin\controller\tasks.delete')) throw new \Application\Exception\Exception("You cannot access this action", 1);
			
			$this->get('TasksTable')->deleteTask($id);
	
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}
		
		return $this->viewResponse($success, $message, [
			'redirect' => $this->url()->fromRoute('admin/actions', array('controller' => 'tasks')), 
			'force_redirect' => 1,
			'exception' => (isset($e)) ? $e : null
			]);
	}

	public function addActivityAction()
	{

		try {
			$success = 1;
			$message = '';
			$id = (int)$this->params()->fromRoute('id');
			if($id == 0) throw new \Application\Exception\Exception("Task id Not provided", 1);
			$task = $this->get('TasksTable')->getTask($id, $this->identity()->id);
			if(!$task) throw new \Application\Exception\Exception($this->translate('Task not found or not visible for you', 1));
			$form = $this->get('EmptyForm');
			$taskFields = $this->get('TaskFields')->remainFields(['text', 'status']);
			$taskFields->get('status')->setValue($task->status);
			$form->add($taskFields);
			$form->setAttribute('action', $this->url()->fromRoute('admin/actions', ['controller' => 'tasks', 'action' => 'add-activity', 'id' => $id]));
			$form->addSubmit($this->translate('Update Task'));
			$form->get('submit')->setAttribute('data-ajax', 1);
			$flag_redirect = 0;
			if($this->request->isPost()) {
				$data = $this->request->getPost();
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
				$data = $form->getData();
				$this->get('TasksTable')->addTaskActivity($id, $this->identity()->id, $data['admin_tasks']);
				$success = 1;
				$message = 'New Activity Added';
				$not_text = ($data['admin_tasks']['text']) ? zlessChars($data['admin_tasks']['text'], 100) : $message;
				$notTable = $this->get('AdminNotifTable');
				$notTable->addNotification($notTable::NOT_TYPE_TASK_ACTIVITY, $notTable::NOT_SECTION_ADMIN_TASK, $id, $this->identity()->id, $not_text);
				$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
																array(
																	'module' => 'Admin',
																	'controller' => 'tasks',
																	'action' => 'add-activity',
																	'id' => $id
																	) ,
																'Updated task'.' : '.$task->title 
															);
				$flag_redirect = 1;
			}
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}

		$viewModel = 0;
		if(isset($form)) 
			$viewModel = new ViewModel(array(
				'form' => $form,
				'task' => $task
				));

		return $this->viewResponse($success, $message, [
			'view_data' => $viewModel, 
			'template' => 'admin/tasks/add-activity',
			'exception' => (isset($e)) ? $e : null,
			'redirect' => ($flag_redirect) ? $this->url()->fromRoute('admin/actions', ['controller' => 'tasks', 'action' => 'index']) : null,
			'force_redirect' => 1,
			]);	
	}


	public function changePriorityAction()
	{
		try {
			$success = 1;
			$message = '';
			$id = (int)$this->params()->fromRoute('id');
			$priority = $this->params()->fromQuery('priority');
			if($id == 0) throw new \Application\Exception\Exception("Task id Not provided", 1);
			if(!$this->isPermitted('admin\controller\tasks.delete')) throw new \Application\Exception\Exception("You cannot access this action", 1);
			if(!$priority) throw new \Application\Exception\Exception("priority Not provided", 1);
			$task = $this->get('TasksTable')->getTask($id, $this->identity()->id, 1);
			if(!$task) throw new \Application\Exception\Exception($this->translate('Task not found or not visible for you', 1));
			if($priority == 'up') {
				$max_priority = \Application\Model\AdminTasksTable::PRIORITY_MAX;
				if ($task->priority < $max_priority) $task->priority++;
				else $task->priority = $max_priority;
				$message = 'Priority Increased';
			} else {
				$min_priority = \Application\Model\AdminTasksTable::PRIORITY_MIN;
				if ($task->priority > $min_priority) $task->priority--;
				else $task->priority = $min_priority;
				$message = 'Priority Reduced';
			}		
			$task->save();		
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
															array(
																'module' => 'Admin',
																'controller' => 'tasks',
																'action' => 'change-priority',
																'id' => $id
																) ,
															'Changed task priority'
														);	
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}
		
		return $this->viewResponse($success, $message, [
			'redirect' => $this->url()->fromRoute('admin/actions', array('controller' => 'tasks')), 
			'exception' => (isset($e)) ? $e : null
			]);
	}


	public function closeAction()
	{
		try {
			$success = 1;
			$message = '';
			$id = (int)$this->params()->fromRoute('id');
			if($id == 0) throw new \Application\Exception\Exception("Task id Not provided", 1);
			
			$this->get('TasksTable')->addTaskActivity($id, $this->identity()->id, ['text' => 'Closed Task', 'status' => \Application\Model\AdminTasksTable::STATUS_CLOSED]);
			$message = 'Task Closed';
			$notTable = $this->get('AdminNotifTable');
			$notTable->addNotification($notTable::NOT_TYPE_TASK_CLOSED, $notTable::NOT_SECTION_ADMIN_TASK, $id, $this->identity()->id, $message);
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
															array(
																'module' => 'Admin',
																'controller' => 'tasks',
																'action' => 'close',
																'id' => $id
																) ,
															'Closed Task'
														);
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}
		return $this->viewResponse($success, $message, [
			'redirect' => $this->url()->fromRoute('admin/actions', array('controller' => 'tasks')), 
			'exception' => (isset($e)) ? $e : null
			]);
	}


	protected function getTaskForm()
	{
		$form = $this->get('EmptyForm');
		$form->add($this->get('TaskFields'));

		$visible = $this->get('EmptyFieldset');
		$responsible = clone $visible;

		$visible->setName('visible');
		$responsible->setName('responsible');

		$admin_roles = $this->get('Access')->getAccessList()->getAdminRoles();
		$admins = $this->get('UserTable')->getAdmins($admin_roles);


		$visible->add([
		'name' => 'everybody',
		'type' => 'Zend\Form\Element\Radio',
		'options' => array(
			'label' => $this->translate('Visible For'),
			'value_options' => [
				[
					'value' => 1,
					'label' => $this->translate(' Everybody'),
					'selected' => 1,
				],
				[
					'value' => 0,
					'label' => $this->translate(' Only For :'),
					'selected' => 0,
				]
			]
			),
		'attributes' => [
			'checked' => 'checked',
		]
		]);

		foreach ($admins as $admin) {
			if($admin->id != $this->identity()->id) {
				$visible->add([
				'name' => $admin->id,
				'type' => 'Zend\Form\Element\Checkbox',
				'options' => array(
					'label' => zgetUserName($admin),
					),
				]);
			}
			$responsible->add([
			'name' => $admin->id,
			'type' => 'Zend\Form\Element\Checkbox',
			'options' => array(
				'label' => zgetUserName($admin).' ('.$admin->role.')',
				),
			]);
		}
		$responsible->add([
			'name' => 'resp_errors',
			'type' => 'hidden',
		]);
		$form->add($visible);
		$form->add($responsible);
		$form->setAttribute('action', $this->url()->fromRoute('admin/actions', ['controller' => 'tasks', 'action' => 'add']));
		$form->addSubmit($this->translate('Add Task'));
		$form->get('submit')->setAttribute('data-ajax', 1);
		return $form;
	}





}