<?php
namespace My\Controller;

use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class CommentsController extends AbstractController
{
	public function getAction()
	{
		try {
			$q_options = $this->setDefaultOptions(['_limit' => _VIEW_COMMENT_LIMIT_]);
			$success = 1;
			$message = '';
			$commentsTable = $this->get('commentsTable');
			$section = $this->params()->fromRoute('section');
			$section_id = $this->params()->fromRoute('id');

			if(!$section || !$section_id) throw new \Application\Exception\Exception($this->translate('section and section id required'), 1);

			$form = $this->get('EmptyForm');
			$form->add($this->get('CommentsFields'));
			$form->addSubmit($this->translate('Add Comment'));
			$form->setAttribute('action', $this->url()->fromRoute('sc/comments/actions', array('action' =>'get', 'section' => $section, 'id' => $section_id)));
			$flag_redirect = 0;
			if($this->request->isPost()){
				if(!$this->identity()) throw new \Application\Exception\Exception($this->translate('Authorisation Required'), 401);
				$data = $this->request->getPost()->toArray();
			    $form->setData($data);

			    if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 100);
			    $data = $form->getData();
			    $data = $data['comments'];
			    $commentsTable->addComment($this->identity()->id, $data['comment'], $section, $section_id, $data['reply_on']);
			    $to_id = $this->getPostAuthorBySectionId($section, $section_id);
			    if($to_id) $this->addUserNotification(\Application\Model\UserNotificationsTable::TYPE_COMMENT, $section, $section_id, $this->identity()->id, $to_id, ['text' => $data['comment']]);

			    $success = 1;
			    $message = $this->translate('Comment Added');
			    $flag_redirect = 1;
			    $form->get('comments')->get('comment')->setValue('');
			    $form->get('comments')->get('id')->setValue('');
			    $form->get('comments')->get('reply_on')->setValue('');
			}

		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 100)? unserialize($e->getMessage()) : $e->getMessage();
			$flag_redirect = 1;
		}

		$q_options['count'] = 1;
		$total_results = $commentsTable->getComments($section, $section_id, [], $q_options)->current()->count;
		
		if($q_options['_offset'] === null) {
			$q_options['_offset'] = ($total_results > $q_options['_limit'])? $total_results - $q_options['_limit'] : 0;
		}
		$q_options['viewer_id'] = ($this->identity())? $this->identity()->id : 0;
		$q_options['count'] = 0;
		$data_list = $commentsTable->getComments($section, $section_id, $q_options['filters'], $q_options);
		
		$viewModel = 0;
		if(isset($form)) 
			$viewModel = new ViewModel(array(
				'section' => $section,
				'section_id' => $section_id,
				'form' => $form,
				'data_list' => $data_list,
				'q_options' => $q_options,
				'offset' => $q_options['_offset'],
				'total_results' => $total_results
				));
		return $this->viewResponse($success, $message, [
			'view_data' => $viewModel, 
			'template' => 'my/comments/get',
			'extra_data' => ['count' => $total_results],
			'redirect' => ($flag_redirect) ? $this->url()->fromRoute('sc/news') : null,
			'exception' => (isset($e)) ? $e : null
			]);
	}


	public function viewAction()
	{
		try {
			$success = 1;
			$message = '';
			$id = $this->params()->fromRoute('id');
		    if(!$id) throw new \Application\Exception\Exception("Comment id not provided", 1);
		    $viewer_id = ($this->identity())? $this->identity()->id : 0;
		    $commentsTable = $this->get('commentsTable');
		    $comment = $commentsTable->getComment($id, $viewer_id);

		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 100)? unserialize($e->getMessage()) : $e->getMessage();
		}

		$viewModel = 0;
		if(isset($comment)) 
			$viewModel = new ViewModel(array(
				'comment' => $comment,
				));

		return $this->viewResponse($success, $message, [
			'view_data' => $viewModel, 
			'template' => 'my/comments/view',
			'exception' => (isset($e)) ? $e : null
			]);
	}


	public function editAction()
	{
		try {
			$success = 1;
			$message = '';
			$id = $this->params()->fromRoute('id');
		    if(!$id) throw new \Application\Exception\Exception("Comment id not provided", 1);
		    $commentsTable = $this->get('commentsTable');
		    $comment = $commentsTable->get($id);
		    
		    $form = $this->get('EmptyForm');
			$form->add($this->get('CommentsFields'));
			$form->addSubmit($this->translate('Save'));
			$form->setAttribute('action', $this->url()->fromRoute('sc/comments/actions', array('action' =>'edit', 'id' => $id)));
			$form->setAttribute('data-editcomment', $id);
			$form->setData(['comments' => $comment]);
			
			if($this->request->isPost()){
				if(!$this->identity()) throw new \Application\Exception\Exception($this->translate('Authorisation Required'), 1);
			    $data = $this->request->getPost()->toArray();
			    $form->setData($data);
			    if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 100);
			    $data = $form->getData();
			    $comment->comment = $data['comments']['comment'];
			    $commentsTable->save((array) $comment);
			    $success = 1;
			    $message = $this->translate('Comment Saved');
			}

		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 100)? unserialize($e->getMessage()) : $e->getMessage();
		}

		$viewModel = 0;
		if(isset($form)) 
			$viewModel = new ViewModel(array(
				'form' => $form,
				'submit_color' => 'success',
				'submit_icon' => 'fa fa-pencil',
				'extra_data' => ['count' => 5],
				));

		return $this->viewResponse($success, $message, [
			'view_data' => $viewModel, 
			'template' => 'my/comments/edit',
			'exception' => (isset($e)) ? $e : null
			]);
	}


	public function deleteAction()
	{
	    $id = $this->params()->fromRoute('id');
	    $source = $this->params()->fromQuery('source', 'local');
	    try {
	        if(!$id) throw new \Application\Exception\Exception("Comment id not provided", 1);
	        if($source == 'local') $table = $this->get('commentsTable');
	        elseif($source == 'vk') $table = $this->get('SocialCommentsTable');

	        if(!$this->isPermitted('my\controller\comments.delete',null, $id)) throw new \Application\Exception\Exception($this->translate('You cannot delete this comment', 1));
	        
	        $table->save(['id' => $id, 'active' => 0]);
	        
	        $success = 1;
	        $message = $this->translate('Comment Deleted');
	    } catch (\Exception $e) {
	        $success = 0;
	        $message = $e->getMessage();
	    }
	    return $this->viewResponse($success, $message, [
	        'redirect' => $this->url()->fromRoute('sc/news'), 
	        'exception' => (isset($e)) ? $e : null
	        ]);     
	}


	public function changeRatingAction()
	{
	    try {
	        $success = 1;
	        $message = '';
	        $id = (int)$this->params()->fromRoute('id');
	        $rating = $this->params()->fromQuery('rating');
	        if(!$id) throw new \Application\Exception\Exception("Comment id not provided", 1);
	        if(!$rating) throw new \Application\Exception\Exception("rating Not provided", 1);
	        if(!in_array($rating, ['up', 'down'])) throw new \Application\Exception\Exception("rating unknown", 1);
	        if(!$this->get('CommentsTable')->get($id)) throw new \Application\Exception\Exception('Item with such id not found', 404);

	        $votesTable = $this->get('ActivityVotesTable');

	        $result = $votesTable->addVote($rating, \Application\Model\NewsTable::SECTION_COMMENTS, $id, $this->identity()->id);
	 
	    } catch (\Exception $e) {
	        $success = 0;
	        $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
	    }
	    
	    return $this->viewResponse($success, $message, [
	        'redirect' => $this->url()->fromRoute('sc/news'), 
	        'exception' => (isset($e)) ? $e : null
	        ]);
	}


}