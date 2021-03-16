<?php
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class isPermitted extends AbstractPlugin
{
	private $access;


	public function __construct($sl)
	{
		$this->access = $sl->get('Access');
	}

	public function __invoke($resource, $to_id = null, $resource_id = null, $role = null, $from_id = null)
	{
		return $this->access->isPermitted($resource, $to_id, $resource_id, $role, $from_id);
	}
}