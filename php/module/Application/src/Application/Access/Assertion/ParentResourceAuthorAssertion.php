<?php
namespace Application\Access\Assertion;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class ParentResourceAuthorAssertion implements \Zend\Permissions\Acl\Assertion\AssertionInterface
{

	public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
	{
		/*
			BE AWARE TO USE WITH MENU 

			when use in viewscipts check if request_to_id is provided !!

			BE AWARE TO USE WITH CONTROLLERS - resource_id should be provided by routematch!!!! 

		 */

		$request_from_id = (int)$acl->request_from_id;
		$request_to_id = (int)$acl->request_to_id;
		$resource_id = (int)$acl->resource_id;
		
		// trying to get owner of resource
		// be aware with resource names they can match to diferent names
		if($request_to_id == 0) {
			$sl = \ServiceLocatorFactory\ServiceLocatorFactory::getInstance();
			if (strpos($resource, 'application\controller\questions.toggle-accept') !== false)
				$question = $sl->get('QuestionAnswersTable')->getQuestionByAnswerId($resource_id)->current();
			if(!$question) return false;
			$request_to_id = $question->user;
		}


		// if no request to id, means menu request or owner_id not found (BE AWARE TO PROVIDE request_to_id WHENEVER POSSIBLE!!!)
		if(!$request_to_id) return true;

		// if no request from id, means not authorised
		if(!$request_from_id) return false;
		
		if($request_from_id == $request_to_id) return true;

		return false;
	}

	
}