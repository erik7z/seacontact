<?php
namespace UserInfo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class PicsController extends AbstractController
{
	public function indexAction()
	{
		$user_login = $this->params()->fromRoute('user');
		$userTable = $this->get('UserTable');
		$user = $userTable->getUserByLogin($user_login);

		$guest_id = ($this->identity())? $this->identity()->id : 0;
		$pics = $this->get('PicsTable')->getUserPics($user->id);
		
		$avatars = $this->get('AvatarTable')->getUserAvatars($user->id);

		return new ViewModel(array(
			'pics' => $pics,
			'avatars' => $avatars,
			'current_user' => $user,
			));
	}


}