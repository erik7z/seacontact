<?php
namespace Application\Access;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\Permissions\Acl\Acl;
use Application\Model\NewsTable;

class AccessList extends Acl
{

	const ROLE_UNREG = 'unreg';
	const ROLE_GUEST = 'guest';
	const ROLE_USER = 'user';
	const ROLE_COMPANY_UNKNOWN = 'company_unknown';
	const ROLE_COMPANY_BASIC = 'company_basic';
	const ROLE_COMPANY_TEST = 'company_test';
	const ROLE_COMPANY_PREMIUM = 'company_premium';

	const ROLE_SC_USER = 'sc_user';
	const ROLE_SC_COMPANY = 'sc_company';

	public $request_from_id = null;
	public $request_to_id = null;
	public $route_access = false;

	private $cached_ip = null;
	private $cached_from_id = null;
	private $cached_banned = null;

	public function __construct()
	{
		try {
			/*
				BE AWARE TO USE ASSERTIONS WITH MENU 
				check if request_from, request_to and resource_id is provided !	

			 */
			$this->setRoles($this->getConfigRoles());
			$this->setResources($this->getConfigResources());
			$this->setAccessRules($this->getConfigRules());
		} catch (\Exception $e) {
			d('ACCESS LIST: '.$e->getMessage());
		}
	}

	public function getResourceOwner($resource, $resource_id)
	{
		$request_to_id = null;
		if (strpos($resource, 'application\controller\vacancies') !== false) {
			$entity = $this->sm()->get('VacanciesTable')->get($resource_id);
			if($entity) $request_to_id = $entity->user;	
			if(!$request_to_id && isset($entity->soc_user_id)) $request_to_id = $entity->soc_user_id;	
		}
		else if (strpos($resource, 'application\controller\logbook') !== false){
			$entity = $this->sm()->get('LogBookTable')->get($resource_id);
			if($entity) $request_to_id = $entity->user;	
			if(!$request_to_id && isset($entity->soc_user_id)) $request_to_id = $entity->soc_user_id;	
		}
		else if (strpos($resource, 'my\controller\comments') !== false){
			$entity = $this->sm()->get('CommentsTable')->get($resource_id);
			if($entity) $request_to_id = $entity->user_id;	
			if(!$request_to_id && isset($entity->soc_user_id)) $request_to_id = $entity->soc_user_id;
		}
		else if (strpos($resource, 'application\controller\questions.answer') !== false){
			$entity = $this->sm()->get('QuestionAnswersTable')->get($resource_id);
			if($entity) $request_to_id = $entity->user;	
			if(!$request_to_id && isset($entity->soc_user_id)) $request_to_id = $entity->soc_user_id;
		}
		else if (strpos($resource, 'application\controller\questions') !== false){
			$entity = $this->sm()->get('QuestionsTable')->get($resource_id);
			if($entity) $request_to_id = $entity->user;	
			if(!$request_to_id && isset($entity->soc_user_id)) $request_to_id = $entity->soc_user_id;
		}
		else if (strpos($resource, 'my\controller\cv.docs') !== false){
			$entity = $this->sm()->get('DocumentsTable')->get($resource_id);
			if($entity) $request_to_id = $entity->user;	
			if(!$request_to_id && isset($entity->soc_user_id)) $request_to_id = $entity->soc_user_id;
		}
		else if (strpos($resource, 'my\controller\cv.experience') !== false){
			$entity = $this->sm()->get('ExperienceTable')->get($resource_id);
			if($entity) $request_to_id = $entity->user;	
			if(!$request_to_id && isset($entity->soc_user_id)) $request_to_id = $entity->soc_user_id;
		} else {
			// any error ????
			$request_to_id = null;
		}
		return $request_to_id;
	}


	public function isBanned()
	{
		$request_from_id = (int)$this->request_from_id;
		$ip = $_SERVER['REMOTE_ADDR'];
		//checking for cached values
		if($this->cached_ip === $ip || $this->cached_from_id === $request_from_id) {
			$banned = $this->cached_banned;
		} else {
			$banned = $this->sm()->get('BanListTable')->isBanned($ip, $request_from_id);
			$this->cached_ip = $ip;
			$this->cached_from_id = $request_from_id;
			$this->cached_banned = $banned;
		}
		if(true === $banned) {
			if($this->route_access === true) {
				$fm = $this->sm()->get('ControllerPluginManager')->get('fm');
				$translator = $this->sm()->get('translator');
				$fm->addErrorMessage($translator->translate('You are banned'));
			}
			return true;
		}
		return false;
	}

	public function getBanAllowedResources()
	{
		return [
			'application\controller\index',
			'application\controller\auth',
			'admin\controller\auth'
		];
	}

	private function sm()
	{
		return \ServiceLocatorFactory\ServiceLocatorFactory::getInstance();
	}

	// Access

	public function getConfigRules($merge = 1)
	{
		$app = include __DIR__.'/config_rules_app.php';
		$fields = include __DIR__.'/config_rules_fields.php';
		$info = include __DIR__.'/config_rules_info.php';
		$admin = include __DIR__.'/config_rules_admin.php';

		if($merge) return array_merge($app, $info, $fields, $admin);
		else return compact('app', 'info', 'fields', 'admin');
	}

	public function setAccessRules($rules_config = [])
	{
		foreach ($rules_config as $rule) {
			$resource = isset($rule[2])? $rule[2] : null;
			$method = isset($rule[3])? $rule[3] : null;
			$assertion = isset($rule[4])? $this->sm()->get($rule[4]) : null;
			if($rule[0] == 'allow') $this->allow($rule[1], $resource, $method, $assertion);
			else $this->deny($rule[1], $resource, $method, $assertion);
		}
	}

	// Roles

	// updating roles list (for access controller)
	public function updateRoles()
	{
		$config_roles = $this->getConfigRoles();
		
		$curr_roles = $this->getRoles();
		$conf_roles = array_keys($config_roles);
		$new_roles = array_diff($conf_roles, $curr_roles);
		$old_roles = array_diff($curr_roles, $conf_roles);

		// dynamically added roles would be deleted
		// foreach ($old_roles as $old_role) {
		// 	if($this->hasRole($old_role)) $this->removeRole($old_role);
		// }

		foreach ($new_roles as $new_role) {
			$this->pushRole($new_role, $config_roles[$new_role]);
		}
		return $this;
	}

	public function getRolesLimits($role)
	{
		$limits = include __DIR__.'/config_roles_limits.php';
		if($role && isset($limits[$role])) $limits = $limits[$role];
		return $limits;
	}

	public function getConfigRoles($merge = true)
	{
		$roles = include __DIR__.'/config_roles.php';
		if($merge) $roles = array_merge($roles['users'], $roles['companies'], $roles['office'], $roles['admins']);
		return $roles;
	}

	public function getUserRoles()
	{
		$roles = $this->getConfigRoles(false);
		return array_keys(array_merge($roles['users'], $roles['office']));
	}

	public function getAdminRoles()
	{
		$roles = $this->getConfigRoles(false);
		return array_keys(array_merge($roles['admins'], $roles['office']));
	}

	public function getCompanyRoles()
	{
		$roles = $this->getConfigRoles(false);
		// unset($roles['companies']['company_sc']);
		$company_roles = array_keys($roles['companies']);
		$company_roles[] = 'unreg';
		return $company_roles;
	}

	private function setRoles(array $roles)
	{
		foreach ($roles as $role => $inherit_from) {
			$this->pushRole($role, $inherit_from);
		}
	}

	private function pushRole($role, $inherit_from = null)
	{
		if(!$this->hasRole($role)) $this->addRole(new Role($role), $inherit_from);
	}


	// Resources

	// updating Resources list (for access controller)
	public function updateResources()
	{
		$config_resources = $this->getConfigResources();
		
		$curr_res = $this->getResources();
		$conf_res = array_keys($config_resources);
		$new_resources = array_diff($conf_res, $curr_res);
		$old_resources = array_diff($curr_res, $conf_res);

		foreach ($old_resources as $old_resource) {
			if($this->hasResource($old_resource)) $this->removeResource($old_resource);
		}

		foreach ($new_resources as $new_resource) {
			$this->pushResource($new_resource, $config_resources[$new_resource]);
		}
		return $this;
	}

	public function getConfigResources()
	{
		return array_merge(
			$this->getModuleResources(),
			$this->getFieldsResources(), 
			$this->getInfoResources(), 
			$this->getAdminResources()
			);
	}

	private function setResources(array $resources)
	{
		foreach ($resources as $resource => $inherit_from) {
			$this->pushResource($resource, $inherit_from);
		}
	}

	private function pushResource($resource, $inherit_from = null)
	{
		if(!$this->hasResource($resource)) $this->addResource(new Resource($resource), $inherit_from);
	}

	public function getFieldsResources()
	{
		$res = include __DIR__.'/config_resources_fields.php';
		return $res;
	}

	public function getInfoResources()
	{
		$res = include __DIR__.'/config_resources_info.php';
		return $res;
	}

	public function getAdminResources()
	{
		$res = include __DIR__.'/config_resources_admin.php';
		$roles_resources = [];
		$roles = $this->getRoles();
		for ($i=0; $i < count($roles); $i++) { 
			$roles_resources['_admin_\access\assign_roles\\'.$roles[$i]] = '_admin_\access\assign_roles';
		}
		$res = array_merge($res, $roles_resources);
		return $res;
	}

	public function getModuleResources()
	{
		return array_merge(
			$this->getControllersResources(_ROOT_.'module/Application/config/module.config.php', 'application\controller'),
			$this->getControllersResources(_ROOT_.'module/My/config/module.config.php', 'my\controller'),
			$this->getControllersResources(_ROOT_.'module/Company/config/module.config.php', 'company\controller'),
			$this->getControllersResources(_ROOT_.'module/UserInfo/config/module.config.php', 'userinfo\controller'),
			$this->getControllersResources(_ROOT_.'module/CompanyInfo/config/module.config.php', 'companyinfo\controller'),
			$this->getControllersResources(_ROOT_.'module/Admin/config/module.config.php', 'admin\controller'),
			$this->getControllersResources(_ROOT_.'module/Api/config/module.config.php', 'api\v1')
		);
	}

	public function getControllersResources($moduleConfig, $resourceName)
	{
		$controller_resources = [];
		$resources = [];
		$moduleConfig = require $moduleConfig;
		$controller_resources[$resourceName] = null;
		foreach ($moduleConfig['controllers']['invokables'] as $controller => $className) {
			$controller_resources[strtolower($controller)] = $resourceName;
			$resources = array_merge($resources, array_merge($controller_resources, $this->getActionResources($controller, $className)));
		}
		if(isset($moduleConfig['controllers']['factories'])) {
			foreach ($moduleConfig['controllers']['factories'] as $controller => $className) {
				$controller_resources[strtolower($controller)] = $resourceName;
				$resources = array_merge($resources, array_merge($controller_resources, $this->getActionResources($controller, $className)));
			}
		}

		return $resources;
	}

	public function getActionResources($controller, $className)
	{
		$action_resources = [];
		$actions = get_class_methods($className);
		for ($i=0; $i < count($actions); $i++) { 
			if(strpos($actions[$i], 'Action') !== false && $actions[$i] != 'getMethodFromAction' && $actions[$i] != 'notFoundAction' ) {
				$action = zcamel2dashed(str_replace('Action', '', $actions[$i]));
				$action_resources[strtolower($controller.'.'.$action)] = strtolower($controller);
			}
		}
		return $action_resources;
	}




}