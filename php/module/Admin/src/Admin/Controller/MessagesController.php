<?php
namespace Admin\Controller;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class MessagesController extends AbstractController
{

	public function indexAction()
	{

		$view = new ViewModel(array(
			));
		return $view;
	}


}