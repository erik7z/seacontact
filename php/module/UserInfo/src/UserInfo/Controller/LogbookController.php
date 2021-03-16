<?php
namespace UserInfo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class LogbookController extends AbstractController
{

	public function indexAction()
	{
		$user_login = $this->params()->fromRoute('user');
		$user = $this->get('UserTable')->getUserByLogin($user_login);
		$viewer_id = ($this->identity())? $this->identity()->id : 0;
		$logbookAccess = $this->get('UserDataAccessTable')->getAccess('logbook', $user->id, $viewer_id);

		$logbook = ($logbookAccess)? $this->get('LogBookTable')->getAllLogbooks($viewer_id, ['owner_id' => $user->id]) : array();
		$view = new ViewModel(array(
			'user' => $user,
			'logbook' => $logbook,
			));
		return $view;
	}

	public function showAction()
	{

		$id = $this->params()->fromRoute('id');
		if(!$id) $this->redirect()->toRoute('sc/userinfo', ['controller' => 'logbook'], [], true);

		$user_login = $this->params()->fromRoute('user');
		$user = $this->get('UserTable')->getUserByLogin($user_login);
		$viewer_id = ($this->identity())? $this->identity()->id : 0;

		if(!$this->get('UserDataAccessTable')->getAccess('logbook', $user->id, $viewer_id)) 
		{
			throw new \Application\Exception\Exception("You cannot access this page", 1);
		}

		$entry = $this->get('LogBookTable')->getAllLogbooks(null, ['logbook_id' => $id])->current();

		$view = new ViewModel(array(
			'user' => $user,
			'entry' => $entry,
			));
		return $view;

	}

}