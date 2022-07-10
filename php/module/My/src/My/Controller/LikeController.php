<?php
namespace My\Controller;

use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class LikeController extends AbstractController
{
	public function indexAction()
	{
		try {
			if(!$this->identity()) throw new \Application\Exception\Exception($this->translate('Authorisation Required'), 1);
			$user_id = $this->identity()->id;
			$section = $this->params()->fromRoute('section');
			$section_id = $this->params()->fromRoute('id');
			if(!$section || !$section_id) throw new \Application\Exception\Exception($this->translate('section and section id required'), 1);
			$likesTable = $this->get('LikesTable');
			if ($likesTable->isLiked($user_id, $section, $section_id)) {
				$likesTable->unLike($user_id, $section, $section_id);
				$message = $this->translate('Unliked');
			} else {
				$likesTable->like($user_id, $section, $section_id);
				$message = $this->translate('Liked');
				$to_id = $this->getPostAuthorBySectionId($section, $section_id);
				if($to_id) $this->addUserNotification(\Application\Model\UserNotificationsTable::TYPE_LIKE, $section, $section_id, $user_id, $to_id);
			}
			$likes_data = $likesTable->getLikes($section, $section_id);
			$total_likes = $likesTable->getLikes($section, $section_id, [], ['count' => 1])->current()->count;
			$success = 1;
		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
			$likes_data = null;
		}
		if(isset($likes_data)) {
			$likesView = new ViewModel(array(
				'likes_data' => $likes_data,
				));
			$likesView->setTemplate('my/like/index');
			$likesView->setOption('has_parent', true);
			$likesView = $this->get('ViewManager')->getView()->render($likesView);
		}

		return $this->viewResponse($success, $message, [
			'redirect' => $this->url()->fromRoute('sc/news'),
			'exception' => (isset($e)) ? $e : null,
			'view_data' => ['count' => $total_likes, 'view' => $likesView]
			]);
	}


}