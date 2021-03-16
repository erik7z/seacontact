<?php
namespace Application\Access\Assertion;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class PartnersAssertion implements \Zend\Permissions\Acl\Assertion\AssertionInterface
{
	private $cached_to_id;
	private $cached_from_id;
	private $cached_are_partners;


	public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
	{
		$request_from_id = (int)$acl->request_from_id;
		$request_to_id = (int)$acl->request_to_id;
		if($request_from_id == 0 || $request_to_id == 0) return false;

		//own information request
		if($request_from_id === $request_to_id) return true;

		//checking for cached values
		if($this->cached_to_id == $request_to_id && $this->cached_from_id == $request_from_id) {
			$are_partners = $this->cached_are_partners;
		} else {
			$are_partners = \Application\Model\zStaticTables::getUserContactsTable()->arePartners($request_from_id, $request_to_id);
			$this->cached_to_id = $request_to_id;
			$this->cached_from_id = $request_from_id;
			$this->cached_are_partners = $are_partners;
		}
		if(true === $are_partners) return true;
		return false;
	}

	
}