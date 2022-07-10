<?php
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\Url;

class ScUrl extends Url
{
	public function fromRoute($route = null, $params = null, $options = null, $reuseMatchedParams = true)
	{
		if(!$params) $params = [];
		if(!$options) $options = [];
		return parent::fromRoute($route, $params, $options, $reuseMatchedParams);
	}


}