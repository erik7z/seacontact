<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Config extends AbstractHelper
{

	private $helperManager;

	public function __construct($helperManager)
	{
		$this->sl = $helperManager->getServiceLocator();

	}


	public function __invoke()
	{
		// return $this->sl->get('config');
		return d('poka ne ispolzuyu');
	}
}