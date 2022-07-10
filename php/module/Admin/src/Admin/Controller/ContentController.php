<?php
namespace Admin\Controller;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class ContentController extends AbstractController
{

	
	/**
	 * Index action of some controller
	 * @return [type]
	 */

	public function indexAction()
	{


	$view = new ViewModel(array(
		));
	return $view;		
	}


	public function fusersActivityAction()
	{
		$success = 1;
		$message ='';
		$q_options = $this->setDefaultOptions(['_limit' => 50, 'up' => 0, 'table_sorting' => 1,
			'show_fields' => ['item_number','name', 'last_activity', 'role', 'period_activity', 'total_activity','online', 'period_online', 'total_online', 'messages']]);

		try {
			$table = $this->get('FUsersActivityTable');
			$data_list = $table->getFUsers($this->identity()->id, $q_options['filters'], $q_options);
			$q_options['count'] = 1;
			$total_results = $table->getFUsers($this->identity()->id, $q_options['filters'], $q_options)->current()->count;
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin', 'controller' => 'content', 'action' => 'fusers-activity'] ,'Opened fusers list');
		
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
		    'template' => 'admin/content/fusers-activity',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect))? $redirect : null
		    ]);			
	}

	public function commentsAction()
	{
		$success = 1;
		$message ='';
		$q_options = $this->setDefaultOptions(['_limit' => 50, 'up' => 0]);

		try {
			$table = $this->get('CommentsTable');
			$q_options['show_article_fields'] = 1;
			$data_list = $table->getComments(null, null, $q_options['filters'], $q_options);
			$q_options['count'] = 1;
			$total_results = $table->getComments(null, null, $q_options['filters'], $q_options)->current()->count;
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin', 'controller' => 'content', 'action' => 'comments'] ,'Opened comments list');
		
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
		    'template' => 'admin/content/comments',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect))? $redirect : null
		    ]);			
	}


	public function logbooksAction()
	{
		$success = 1;
		$message ='';
		$q_options = $this->setDefaultOptions(['_limit' => 50, '_order' => 'id', 'up' => 0]);

		try {
			$table = $this->get('LogBookTable');
			$data_list = $table->getAllLogbooks(null, $q_options['filters'], $q_options);
			$q_options['count'] = 1;
			$total_results = $table->getAllLogbooks(null, $q_options['filters'], $q_options)->current()->count;
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin', 'controller' => 'content', 'action' => 'logbooks'] ,'Opened logbooks list');
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
		    'template' => 'admin/content/logbooks',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect))? $redirect : null
		    ]);		
	}


	public function questionsAction()
	{

	$success = 1;
	$message ='';
	$q_options = $this->setDefaultOptions(['_limit' => 50]);

	try {
		$table = $this->get('QuestionsTable');
		$data_list = $table->getQuestions(null, $q_options['filters'], $q_options);
		$q_options['count'] = 1;
		$total_results = $table->getQuestions(null, $q_options['filters'], $q_options)->current()->count;
		$this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin', 'controller' => 'content', 'action' => 'questions'] ,'Opened questions list');
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
	    'template' => 'admin/content/questions',
	    'exception' => (isset($e)) ? $e : null,
	    'redirect' => (isset($redirect))? $redirect : null
	    ]);			
	}


	public function answersAction()
	{
		$success = 1;
		$message ='';
		$q_options = $this->setDefaultOptions(['_limit' => 50]);

		try {
			$table = $this->get('QuestionAnswersTable');
			$data_list = $table->getAnswers(null, $q_options['filters'], $q_options);
			$q_options['count'] = 1;
			$total_results = $table->getAnswers(null, $q_options['filters'], $q_options)->current()->count;
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin', 'controller' => 'content', 'action' => 'answers'] ,'Opened answers list');
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}

		$viewModel = new ViewModel(array(
		'total_results' => $total_results,
		'data_list' => $data_list,
		'q_options' => $q_options,
		));

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'admin/content/answers',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect))? $redirect : null
		    ]);			
		
	}


	public function tagsAction()
	{

		$success = 1;
		$message ='';
		$q_options = $this->setDefaultOptions(['_limit' => 50]);

		try {
			$table = $this->get('TagMapTable');
			$data_list = $table->getTagsMap(null, $q_options['filters'], $q_options);
			$q_options['count'] = 1;
			$total_results = $table->getTagsMap(null, $q_options['filters'], $q_options)->current()->count;
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin', 'controller' => 'content', 'action' => 'tags'] ,'Opened tags list');
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
		    'template' => 'admin/content/tags',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect))? $redirect : null
		    ]);			
	
	}

	public function editTagAction()
	{
		$success = 1;
		$message = '';

		$id = $this->params()->fromRoute('id');
		try {
		    if(!$id) throw new \Application\Exception\Exception("Question id not provided", 1);
		    $table = $this->get('TagsTable');
		    $tag = $table->get($id);

		    $form = $this->get('EmptyForm');
		    $form->add(array(
			'name' => 'id',
			'type' => 'hidden',
			));

		    $form->add(array(
			'name' => 'name',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Tag'),
				'required' => false,
				'filters'  => [],
				'validators' => [],
				),
			));

		    $form->add(array(
			'name' => 'description',
			'type' => 'Zend\Form\Element\Textarea',
			'options' => array(
				'label' => $this->translate('Tag Description'),
				'required' => false,
				'filters'  => [],
				'validators' => [],
				),
			'attributes' => array(
				'placeholder' => $this->translate('Any additional tag info'),
				'rows' => 3,
				),
			));
		    $controller = $this->routeMatch()->getParam('__CONTROLLER__');
		    $action = $this->routeMatch()->getParam('action');
		    $form->setAttribute('action', $this->url()->fromRoute('admin/actions', ['controller' => $controller, 'action' => $action, 'id' => $id]));

			$form->addSubmit($this->translate('Save'));
			$form->setData($tag);

			if($this->request->isPost()) {
				$data = $this->request->getPost();
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
				$data = $form->getData();
				$table->save($data);
				$success = 1;
				$message = 'Tag edited';
				$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
																array(
																	'module' => 'Admin',
																	'controller' => $controller,
																	'action' => $action,
																	'id' => $id
																	) ,
																$message
															);
			}

		} catch (\Exception $e) {
		    $success = 0;
		    $message = ($e->getCode() == 777) ? unserialize($e->getMessage()) : $e->getMessage();
		}

		$viewModel = 0;
		if(isset($form)) 
			$viewModel = new ViewModel(array(
				'form' => $form,
				));

		return $this->viewResponse($success, $message, [
			'view_data' => $viewModel,
			'redirect' => $this->url()->fromRoute('admin/actions', ['controller' => 'content', 'action' => 'tags']), 
			'template' => 'admin/content/edit-tag',
			'exception' => (isset($e)) ? $e : null
			]);
	}

	public function deleteLogbookAction()
	{
	    $id = $this->params()->fromRoute('id');
	    try {
	        if(!$id) throw new \Application\Exception\Exception("Logbook id not provided", 1);
	        $this->get('LogBookTable')->save(['id' => $id, 'active' => 0]);
	        
	        $success = 1;
	        $message = $this->translate('Logbook Deleted');

	        $this->get('AdminActivityTable')->addActivity($this->identity()->id, 
	                                                        array(
	                                                            'module' => 'Admin',
	                                                            'controller' => 'content',
	                                                            'action' => 'delete',
	                                                            ) ,
	                                                       'Deleted logbook'
	                                                    );
	    } catch (\Exception $e) {
	        $success = 0;
	        $message = $e->getMessage();
	    }
	    return $this->viewResponse($success, $message, [
	        'redirect' => $this->url()->fromRoute('admin/actions', ['controller' => 'content', 'action' => 'logbooks']), 
	        'exception' => (isset($e)) ? $e : null
	        ]);     
	}

	public function deleteQuestionAction()
	{
	    $id = $this->params()->fromRoute('id');
	    try {
	        if(!$id) throw new \Application\Exception\Exception("Question id not provided", 1);
	        $this->get('QuestionsTable')->save(['id' => $id, 'active' => 0]);
	        
	        $success = 1;
	        $message = $this->translate('Answer Deleted');

	        $this->get('AdminActivityTable')->addActivity($this->identity()->id, 
	                                                        array(
	                                                            'module' => 'Admin',
	                                                            'controller' => 'content',
	                                                            'action' => 'delete',
	                                                            ) ,
	                                                       'Deleted Question'
	                                                    );
	    } catch (\Exception $e) {
	        $success = 0;
	        $message = $e->getMessage();
	    }
	    return $this->viewResponse($success, $message, [
	        'redirect' => $this->url()->fromRoute('admin/actions', ['controller' => 'content', 'action' => 'answers']), 
	        'exception' => (isset($e)) ? $e : null
	        ]);     
	}


	public function deleteAnswerAction()
	{
	    $id = $this->params()->fromRoute('id');
	    try {
	        if(!$id) throw new \Application\Exception\Exception("Answer id not provided", 1);
	        $this->get('QuestionAnswersTable')->save(['id' => $id, 'active' => 0]);
	        
	        $success = 1;
	        $message = $this->translate('Answer Deleted');

	        $this->get('AdminActivityTable')->addActivity($this->identity()->id, 
	                                                        array(
	                                                            'module' => 'Admin',
	                                                            'controller' => 'content',
	                                                            'action' => 'delete',
	                                                            ) ,
	                                                        'Deleted Answer'
	                                                    );
	    } catch (\Exception $e) {
	        $success = 0;
	        $message = $e->getMessage();
	    }
	    return $this->viewResponse($success, $message, [
	        'redirect' => $this->url()->fromRoute('admin/actions', ['controller' => 'content', 'action' => 'answers']), 
	        'exception' => (isset($e)) ? $e : null
	        ]);     
	}

	public function deleteCommentAction()
	{
	    $id = $this->params()->fromRoute('id');
	    $source = $this->params()->fromQuery('source', 'local');
	    try {
	        if(!$id) throw new \Application\Exception\Exception("Comment id not provided", 1);
	        if($source == 'local') $table = $this->get('commentsTable');
	        elseif($source == 'vk') $table = $this->get('SocialCommentsTable');
	        
	        $table->save(['id' => $id, 'active' => 0]);
	        $success = 1;
	        $message = $this->translate('Comment Deleted');

	        $this->get('AdminActivityTable')->addActivity($this->identity()->id, 
	                                                        array(
	                                                            'module' => 'Admin',
	                                                            'controller' => 'content',
	                                                            'action' => 'delete',
	                                                            ) ,
	                                                        'Deleted Comment'
	                                                    );
	    } catch (\Exception $e) {
	        $success = 0;
	        $message = $e->getMessage();
	    }
	    return $this->viewResponse($success, $message, [
	        'redirect' => $this->url()->fromRoute('admin/actions', ['controller' => 'content', 'action' => 'comments']), 
	        'exception' => (isset($e)) ? $e : null
	        ]);     
	}


}