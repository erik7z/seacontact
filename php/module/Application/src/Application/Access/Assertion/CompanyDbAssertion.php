<?php
namespace Application\Access\Assertion;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class CompanyDbAssertion implements \Zend\Permissions\Acl\Assertion\AssertionInterface
{
	private $cached_to_id;
	private $cached_from_id;
	private $cached_is_in_db;


	public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
	{
		$request_from_id = (int)$acl->request_from_id;
		$request_to_id = (int)$acl->request_to_id;
		if($request_from_id == 0 || $request_to_id == 0) return false;

		//own information request
		if($request_from_id === $request_to_id) return true;

		//checking for cached values
		if($this->cached_to_id == $request_to_id && $this->cached_from_id == $request_from_id) {
			$is_in_db = $this->cached_is_in_db;
		} else {
			$is_in_db = \Application\Model\zStaticTables::getCompanyUsersTable()->isCompanyUser($request_from_id, $request_to_id);
			$this->cached_to_id = $request_to_id;
			$this->cached_from_id = $request_from_id;
			$this->cached_is_in_db = $is_in_db;
		}
		if(true === $is_in_db) return true;
		return false;
	}

	
}