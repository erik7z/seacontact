<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class RouteMatch extends AbstractHelper
{

	public $routeMatch;
	private $hm;

	public function __construct($helperManager)
	{
		$this->hm = $helperManager;
		$this->routeMatch = $this->hm->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch();
		// $request = $this->sl->get('request');
		// $routeMatch = $this->sl->get('router')->match($request);
	}


	public function __invoke()
	{
		return $this;
	}

	public function getRoute()
	{
		return $this->routeMatch->getMatchedRouteName();
	}

	public function getController()
	{
		$controller = $this->routeMatch->getParam('controller');
		if (strpos($controller,'\\') !== false) {
			$params = explode('\\', $controller);
			$controller = strtolower(end($params));
		}
		return $controller;
	}

	public function getAction()
	{
		return $this->routeMatch->getParam('action');
	}

	public function getSection()
	{
		return $this->routeMatch->getParam('section');
	}

	public function getUser()
	{
		return $this->routeMatch->getParam('user');
	}

	public function getId()
	{
		return $this->routeMatch->getParam('id');
	}

	public function getQuery($param = null, $default = null)
	{
	    if ($param === null) {
	        return $this->hm->getServiceLocator()->get('Application')->getRequest()->getQuery($param, $default)->toArray();
	    }
	    return $this->hm->getServiceLocator()->get('Application')->getRequest()->getQuery($param, $default);
	}

	public function getRouteMatch(){
		return $this->routeMatch;
	}

	public function getMatchedRouteName()
	{
		return $this->routeMatch->getMatchedRouteName();
	}


	public function getParam($string)
	{
		return $this->routeMatch->getParam($string);
	}

	public function getParams()
	{
		return $this->routeMatch->getParams();
	}

	public function getUrl($include = ['route','controller', 'section', 'action', 'user','id', 'query'], $exclude = [])
	{
		$params = [];
		$query = [];
		$include = array_flip($include);
		$route = (isset($include['route']))? $this->getRoute() : null;
		if(isset($include['controller'])) $params['controller'] = $this->getController();
		if(isset($include['section'])) $params['section'] = $this->getSection();
		if(isset($include['action'])) $params['action'] = $this->getAction();
		if(isset($include['user'])) $params['user'] = $this->getUser();
		if(isset($include['id'])) $params['id'] = $this->getId();
		if(isset($include['query'])) $query['query'] = $this->getQuery();
		return $this->hm->get('url')->__invoke($route, $params, $query);
	}


	public function getRouteParams()
	{
		return [
           'controller' => $this->getController(),
           'section' => $this->getSection(), 
           'action' => $this->getAction(), 
           'user' => $this->getUser(), 
           'id' => $this->getId()
           ];
	}




}