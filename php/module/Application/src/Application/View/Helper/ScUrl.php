<?php
namespace Application\View\Helper;

use Zend\View\Helper\Url;

class ScUrl extends Url
{
	protected $router;
	protected $routeMatch;
	
	// public $query;

	public function __invoke($name = null, $params = null, $options = null, $reuseMatchedParams = true)
	{
		if(!$params) $params = [];
		if(!$options) $options = [];
		// if($reuseMatchedParams) $options = array_merge(['query' => $this->query], $options);
		return parent::__invoke($name, $params, $options, $reuseMatchedParams);
	}

}