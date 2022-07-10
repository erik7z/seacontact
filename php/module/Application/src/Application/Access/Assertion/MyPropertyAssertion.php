<?php
namespace Application\Access\Assertion;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class MyPropertyAssertion implements \Zend\Permissions\Acl\Assertion\AssertionInterface
{

	public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
	{

		/*
			BE AWARE TO USE WITH MENU 

			when use in viewscipts check if request_to_id is provided !!
			check if resource_id is provided !!

			BE AWARE TO USE WITH CONTROLLERS - resource_id should be provided by routematch!!!! 

		 */

		$request_from_id = isset($acl->request_from_id)? (int)$acl->request_from_id : 0;
		$request_to_id = isset($acl->request_to_id)? (int)$acl->request_to_id : 0;
		$resource_id = isset($acl->resource_id)? (int)$acl->resource_id : 0;


		// trying to get owner of resource
		if($request_to_id == 0) $request_to_id = $acl->getResourceOwner($resource, $resource_id);
		// if no request to id, means menu request or owner_id not found (BE AWARE TO PROVIDE request_to_id WHENEVER POSSIBLE!!!)
		if(!$request_to_id) return true;


		// if no request from id, means not authorised
		if(!$request_from_id) return false;
		

		if($request_from_id == $request_to_id) return true;
		return false;
	}

	
}