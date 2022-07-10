<?php
namespace My\Controller;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class ShipsController extends AbstractController
{

	
	/**
	 * Index action of some controller
	 * @return [type]
	 */
	public function indexAction()
	{

		$ships = $this->get('ShipsTable')->getUserShips($this->identity()->id);


		$view = new ViewModel(array(
			'ships' => $ships,
			));
		return $view;
	}



}