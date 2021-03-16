<?php
namespace Api\V1\Access;
use ZF\MvcAuth\MvcAuthEvent;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class Authorization
{
	protected $sc_acl;
	protected $api_acl;
	protected $sm;

	public function __invoke(MvcAuthEvent $mvcAuthEvent)
	{
		$this->sm = $mvcAuthEvent->getMvcEvent()->getApplication()->getServiceManager();
		$this->sc_acl = $this->sm->get('Access')->getAccessList();
		$this->api_acl = $mvcAuthEvent->getAuthorizationService();
		
		$this->setRoles($this->sc_acl->getConfigRoles());
		$this->setAccessRules($this->getConfigRules());

		// d($this->api_acl->isAllowed('user', 'Api\V1\Rpc\QuestionsAdd\Controller::questionsAdd'));
		// d($mvcAuthEvent->getIdentity(), 1);
		// d($this->api_acl->getResources());
		// d($this->sc_acl->getConfigRules());
		// d($this->sc_acl->getInfoResources());
		// d($this->sc_acl->getFieldsResources());

		// $this->api_acl->addRole('admin');

		// // $authentication = $mvcAuthEvent->getAuthenticationService();
		// 
		// // d(($mvcAuthEvent->getAuthenticationResult()));
		// d(($mvcAuthEvent->getMvcEvent()->getApplication()->getServiceManager()->get('api-identity')->getAuthenticationIdentity()));

	}

	// Access

	public function getConfigRules()
	{
		$api = include __DIR__.'/config_rules_api.php';
		return $api;
	}

	public function setAccessRules($rules_config = [])
	{
		foreach ($rules_config as $rule) {
			$resource = isset($rule[2])? $rule[2] : null;
			$method = isset($rule[3])? $rule[3] : null;
			$assertion = isset($rule[4])? $this->sm->get($rule[4]) : null;
			if($rule[0] == 'allow') $this->api_acl->allow($rule[1], $resource, $method, $assertion);
			else $this->api_acl->deny($rule[1], $resource, $method, $assertion);
		}
	}

	private function setRoles(array $roles)
	{
		foreach ($roles as $role => $inherit_from) {
			$this->pushRole($role, $inherit_from);
		}
	}

	private function setResources(array $resources)
	{
		foreach ($resources as $resource => $inherit_from) {
			$this->pushResource($resource, $inherit_from);
		}
	}

	private function pushRole($role, $inherit_from = null)
	{
		if(!$this->api_acl->hasRole($role)) $this->api_acl->addRole(new Role($role), $inherit_from);
	}
	private function pushResource($resource, $inherit_from = null)
	{
		if(!$this->api_acl->hasResource($resource)) $this->api_acl->addResource(new Resource($resource), $inherit_from);
	}
}