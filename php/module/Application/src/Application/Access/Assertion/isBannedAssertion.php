<?php
namespace Application\Access\Assertion;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class isBannedAssertion implements \Zend\Permissions\Acl\Assertion\AssertionInterface
{
	private $cached_ip;
	private $cached_from_id;
	private $cached_banned;

	public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
	{
		// $request_from_id = (int)$acl->request_from_id;
		// $ip = $_SERVER['REMOTE_ADDR'];
		// $sm = \ServiceLocatorFactory\ServiceLocatorFactory::getInstance();
		// //checking for cached values
		// if($this->cached_ip == $ip || $this->cached_from_id == $request_from_id) {
		// 	$banned = $this->cached_banned;
		// } else {
		// 	$banned = $sm->get('BanListTable')->isBanned($ip, $request_from_id);
		// 	$this->cached_ip = $ip;
		// 	$this->cached_banned = $banned;
		// }
		// if(true === $banned) {
		// 	if($acl->route_access === true) {
		// 		$fm = $sm->get('ControllerPluginManager')->get('fm');
		// 		$translator = $sm->get('translator');
		// 		$fm->addErrorMessage($translator->translate('You are banned'));
		// 	}
		// 	return true;
		// }
		return false;
	}

	
}