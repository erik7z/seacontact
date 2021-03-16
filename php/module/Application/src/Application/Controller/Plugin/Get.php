<?php
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Get extends AbstractPlugin
{
	private $serviceLocator;


	public function __construct($serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;
	}

	public function __invoke($params = null)
	{
		if($params && is_string($params)) return $this->serviceLocator->get($params);
		return $this->serviceLocator;
	}


}