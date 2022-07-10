<?php
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class routeMatch extends AbstractPlugin
{
	private $routeMatch;

	public function __construct($serviceLocator)
	{
		$this->routeMatch = $serviceLocator->get('Application')->getMvcEvent()->getRouteMatch();
	}

	public function __invoke($params = null)
	{
		return $this->routeMatch;
	}


}