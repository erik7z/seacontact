<?php
namespace Api\V1;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;



class IndexController extends AbstractActionController
{
	public function indexAction()
	{
		return new ViewModel(array(
			'ack' => time()
			));
	}
}