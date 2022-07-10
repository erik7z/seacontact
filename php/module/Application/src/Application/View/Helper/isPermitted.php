<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class isPermitted extends AbstractHelper
{

	private $access;

	public function __construct($helperManager)
	{
		$this->access = $helperManager->getServiceLocator()->get('Access');

	}


	public function __invoke($resource, $to_id = null, $resource_id = null, $role = null, $from_id = null)
	{
		return $this->access->isPermitted($resource, $to_id, $resource_id, $role, $from_id);
	}
}