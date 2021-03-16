<?php
namespace Application\Access;


class Access
{
	protected $controller;
	protected $action;
	protected $identity;
	protected $user_page;
	protected $serviceLocator;
	protected $role;
	protected $access_list;
	protected $cached_from_id;
	protected $cached_to_id;
	protected $cached_are_friends;
	protected $cached_are_partners;



	protected $parents_resources;
	protected $personal_resources;
	protected $tree_resources_list = null;
	protected $tree_roles_list = null;
	protected $table_roles = null;
	protected $navigation;
	protected $url;


	public function __construct($serviceLocator)
	{	
		$this->serviceLocator = $serviceLocator;
		$this->identity = $serviceLocator->get('AuthService')->getIdentity();
		$this->getAccessList();
	}
	


	public function initOnLoad($routeMatch)
	{
		$this->controller = strtolower($routeMatch->getParam('controller'));
		$this->action = strtolower($routeMatch->getParam('action'));
		$this->resource_id = strtolower($routeMatch->getParam('id', null));
		$this->user_page = ($u = $routeMatch->getParam('user')) ? strtolower($u) : null;

		$auth = $this->serviceLocator->get('AuthService');
		
		$this->role = \Application\Access\AccessList::ROLE_GUEST;
		if($this->identity && isset($this->identity->role)) $this->role = $this->identity->role;
		return $this;
	}


	public function checkUserPage()
	{
		if($this->user_page) {
			$userTable = $this->serviceLocator->get('UserTable');
			$result = $userTable->checkUserByLogin($this->user_page);
			if(!$result) {
				d('user page not found :', 1);
				d($result);
			}
			return ($result)? true : false;
		}
		return true;
	}
	

	public function accessRoute()
	{
		
		$this->access_list->request_from_id = (isset($this->identity->id)) ? $this->identity->id : null;

		// !!!!! resource_id has to be provided by routematch !!!!!!!!!!
		$this->access_list->resource_id = $this->resource_id;

		if ($this->access_list->isBanned()) {
			$requested_resources = array_keys($this->getResourceParents($this->controller.'.'.$this->action));
			$allowed_resources = $this->access_list->getBanAllowedResources();
			//if any parent resource allowed return allow
			if (array_intersect($requested_resources, $allowed_resources)) return true;
			else throw new \Application\Exception\Exception($this->translate("You are banned"), 1);
		}

		//start route access check
		$this->access_list->route_access = true;
		if($this->access_list->hasResource($this->controller.'.'.$this->action)) {
			$allowed = ($this->access_list->isAllowed($this->role, $this->controller.'.'.$this->action))? true : false;
		} else if($this->access_list->hasResource($this->controller)) {
			$allowed = ($this->access_list->isAllowed($this->role, $this->controller))? true : false;
		} else $allowed = false;

		// accept all api routes (more logic to be provided here)
		if(strpos($this->controller, 'api\v') !== false) $allowed =  true;
		if(strpos($this->controller, 'zf\oauth2') !== false) $allowed =  true;
		// for development mode
		if(strpos($this->controller, 'zf\apigility') !== false) $allowed =  true;

		// complete route access check
		$this->access_list->route_access = false;
		
		if(!$this->checkUserPage()) throw new \Application\Exception\Exception("Page Not Found [404] ", 404);
		if(!$allowed) {
			if($this->role == \Application\Access\AccessList::ROLE_GUEST) throw new \Application\Exception\Exception($this->translate("You cannot access this page. Authorisation required"), 401);
			else if($this->role == \Application\Access\AccessList::ROLE_UNREG) throw new \Application\Exception\Exception($this->translate("You cannot access this page. Please confirm your email"), 401);
			else throw new \Application\Exception\Exception($this->translate('You cannot access this page with level').': '.'['.$this->role.'] '.' [401] ', 401);
		}
		return $allowed;	  
	}


	public function isPermitted($resource, $request_to_id = null, $resource_id = null, $role = null, $request_from_id = null)
	{
		$allowed = false;
		$this->access_list->route_access = false;

		try {
			if(!$role) $role = (isset($this->identity->role)) ? $this->identity->role : \Application\Access\AccessList::ROLE_GUEST;
			if(!$request_from_id) $request_from_id = (isset($this->identity->id)) ? $this->identity->id : null;
			if($this->access_list->hasResource($resource)) {
				//setting request ids
				$this->access_list->request_from_id = $request_from_id;
				$this->access_list->request_to_id = $request_to_id;
				$this->access_list->resource_id = $resource_id;
				$allowed = ($this->access_list->isAllowed($role, $resource))? true : false;
				
				// cleaning request id cache
				$this->access_list->resource_id = null;
				$this->access_list->request_from_id = null;
				$this->access_list->request_to_id = null;
			} 
		} catch (\Exception $e) {
			$sl = \ServiceLocatorFactory\ServiceLocatorFactory::getInstance();
			$fm = $sl->get('ControllerPluginManager')->get('fm');
			$fm->addErrorMessage($e->getMessage());
		}
		return $allowed;
	}
	



	public function getResourceChildsList($parent_resource, $add_parent = true)
	{
		$resources = $this->access_list->getResources();
		$childs = [];
		if($add_parent) $childs[] = $parent_resource;
		$count = count($resources);
		$parent_object = $this->access_list->getResource($parent_resource);
		for ($k=0; $k < $count; $k++) {
			$child_object = $this->access_list->getResource($resources[$k]);
			if($this->access_list->inheritsResource($child_object, $parent_object)) $childs[] = $resources[$k];
		}
		return $childs;
	}

	public function getRoleChildsList($parent_role, $add_parent = true)
	{
		$roles = $this->access_list->getRoles();
		$childs = [];
		if($add_parent) $childs[] = $parent_role;
		$count = count($roles);
		$parent_object = $this->access_list->getRole($parent_role);
		for ($k=0; $k < $count; $k++) {
			$child_object = $this->access_list->getRole($roles[$k]);
			if($this->access_list->inheritsRole($child_object, $parent_object)) $childs[] = $roles[$k];
		}
		return $childs;
	}


	public function getTableRoles()
	{
		if(!$this->table_roles){
			$userTable = $this->serviceLocator->get('UserTable');
			$table_roles = $userTable->getAllGroupCount('role');
			foreach ($table_roles as $value) {
				$this->table_roles[$value->role] = $value->count;
			}
		}
		return $this->table_roles;
	}

	public function getRolesTree($roles, $selected = null)
	{
		$count_roles = count($roles);
		$root_roles = [];
		$this->tree_roles_list = [];
		for ($i=0; $i < $count_roles; $i++) {
			$c = 0;
			$parent_object = $this->access_list->getRole($roles[$i]);
			for ($k=0; $k < $count_roles; $k++) { 
				$child_object = $this->access_list->getRole($roles[$k]);
				if($this->access_list->inheritsRole($parent_object, $child_object)) $c++;
			}
			if($c == 0) $root_roles[] = $roles[$i];
			else $this->tree_roles_list[] = $roles[$i];
		}
		$table_roles = $this->getTableRoles();
		for ($i=0; $i < count($root_roles); $i++) { 
			$tree[$i]['text'] = $root_roles[$i];
			$tree[$i]['nodes'] = $this->getRolesTreeChilds($root_roles[$i], $selected);
			$tree[$i]['icon'] = 'fa fa-user';
			if(isset($table_roles[$root_roles[$i]])) $tree[$i]['tags'][] = $table_roles[$root_roles[$i]];
			$tree[$i]['href'] = $this->getUrl()->fromRoute('admin/actions', ['controller' => 'access', 'action' => 'roles'], ['query' => ['filter' => $tree[$i]['text']]]);
			if($tree[$i]['text'] == $selected) $tree[$i]['state'] = ['expanded' => true, 'selected' => true];
			else $tree[$i]['state'] = ['expanded' => true];
		}

		return $tree;
	}

	protected function getRolesTreeChilds($root_role, $selected = null)
	{
			$childs = null;
			$childs_tree = null;
			$parent_object = $this->access_list->getRole($root_role);
			$m = 0;
			$table_roles = $this->getTableRoles();
			foreach($this->tree_roles_list as $key => $role) {

				$child_object = $this->access_list->getRole($role);
				if($this->access_list->inheritsRole($child_object, $parent_object, true)) {
					$childs[$m]['text'] = $role;
					unset($this->tree_roles_list[$key]);
					$m++;
				}
			}
			for ($k=0; $k < count($childs); $k++) {
				$childs_tree[$k]['text'] = $childs[$k]['text'];
				$childs_tree[$k]['icon'] = 'fa fa-user';
				if(isset($table_roles[$childs_tree[$k]['text']])) $childs_tree[$k]['tags'][] = $table_roles[$childs_tree[$k]['text']];
				$nodes = $this->getrolesTreeChilds($childs[$k]['text'], $selected);
				if($nodes) $childs_tree[$k]['nodes'] = $nodes;
				$childs_tree[$k]['href'] = $this->getUrl()->fromRoute('admin/actions', ['controller' => 'access', 'action' => 'roles'], ['query' => ['filter' => $childs[$k]['text']]]); 
				if($childs_tree[$k]['text'] == $selected) $childs_tree[$k]['state'] = ['expanded' => true, 'selected' => true];
				else $childs_tree[$k]['state'] = ['expanded' => true];
			}
		return $childs_tree;
	}



	public function getResoucesTree($resources, $selected = null, $role = null)
	{
		$count_resources = count($resources);
		$root_resources = [];
		$this->tree_resources_list = [];
		if($role) $this->setRolesPersonalResources();
		for ($i=0; $i < $count_resources; $i++) {
			$c = 0;
			$parent_object = $this->access_list->getResource($resources[$i]);
			for ($k=0; $k < $count_resources; $k++) { 
				$child_object = $this->access_list->getResource($resources[$k]);
				if($this->access_list->inheritsResource($parent_object, $child_object)) $c++;
			}
			if($c == 0) $root_resources[] = $resources[$i];
			else $this->tree_resources_list[] = $resources[$i];
		}

		for ($i=0; $i < count($root_resources); $i++) { 
			$tree[$i]['resource'] = $root_resources[$i];
			$nav = zgetNav($root_resources[$i]);
			$container = $this->getNavigation($nav)->findOneByResource($root_resources[$i]);
			$tree[$i]['text'] = ($container) ? ' <b> '.$container->getLabel().' </b> '.' ('.$root_resources[$i].')' : $root_resources[$i];
			$child_nodes = $this->getResourcesTreeChilds($root_resources[$i], $selected, $role);
			$tree[$i]['nodes'] = $child_nodes['content'];
			if($child_nodes['state']['expanded'] == true) $tree[$i]['state']['expanded'] = true;
			if(!$role) $tree[$i]['icon'] = ($container) ? $container->get('icon') : null; 
			$tree[$i]['href'] = $this->getUrl()->fromRoute('admin/actions', ['controller' => 'access', 'action' => 'resources'], ['query' => ['filter' => $tree[$i]['resource']]]);
			if($selected) {
				if($tree[$i]['resource'] == $selected) $tree[$i]['state'] = ['expanded' => true, 'selected' => true];
				else if(strpos($selected, $tree[$i]['resource']) !== false) $tree[$i]['state'] = ['expanded' => true];
			}
			if($role){
				$tree[$i]['status']['denied'] = false;
				$tree[$i]['status']['inherit'] = false;
				if($this->access_list->isAllowed($role, $tree[$i]['resource'])) {
					if(isset($this->personal_resources[$role][$tree[$i]['resource']])) {
						$tree[$i]['backColor'] = '#E0F1DD';
						$tree[$i]['tags'][] = $this->translate('personal');
						$tree[$i]['status']['inherit'] = 'personal';
					} else {
						$tree[$i]['backColor'] = '#EDEFEF';
						if(array_intersect_assoc($this->getResourceParents($tree[$i]['resource']), $this->personal_resources[$role])) {
							$tree[$i]['tags'][] = $this->translate('inherit from resource');
							$tree[$i]['status']['inherit'] = 'resource';
						} else {
							$tree[$i]['tags'][] = $this->translate('inherit from role');
							$tree[$i]['status']['inherit'] = 'role';
						}
					}
					$tree[$i]['state']['checked'] = true;
					$tree[$i]['state']['expanded'] = true;
				} else {
					// if this resource is allowed to any parent role
					if(isset($this->parents_resources[$role])) {
						if(isset($this->parents_resources[$role][$tree[$i]['resource']])) {
							$tree[$i]['backColor'] = '#FFCCCC';
							$tree[$i]['tags'][] = 'denied';
							$tree[$i]['tags'][] = $this->translate('inherit from role');
							$tree[$i]['status']['inherit'] = 'role';
							$tree[$i]['status']['denied'] = true;
						}
					}
				}
			}
		}

		return $tree;
	}

	protected function getResourcesTreeChilds($root_resource, $selected = null, $role = null)
	{
			$childs = null;
			$childs_tree = null;
			$result['state']['expanded'] = false;
			$expanded = 0;
			$parent_object = $this->access_list->getResource($root_resource);
			$m = 0;
			foreach($this->tree_resources_list as $key => $resource) { 
				$child_object = $this->access_list->getResource($resource);
				if($this->access_list->inheritsResource($child_object, $parent_object, true)) {
					$childs[$m]['resource'] = $resource;
					unset($this->tree_resources_list[$key]);
					$m++;
				}
			}
			if(count($childs) > 0) {
				for ($k=0; $k < count($childs); $k++) {
					$childs_tree[$k]['state']['expanded'] = false;
					$childs_tree[$k]['resource'] = $childs[$k]['resource'];
					$nav = zgetNav($childs[$k]['resource']);
					$container = $this->getNavigation($nav)->findOneByResource($childs[$k]['resource']);
					$childs_tree[$k]['text'] = ($container) ? ' <b> '.$container->getLabel().' </b> '.' ('.$childs[$k]['resource'].')' : $childs[$k]['resource'];
					if(!$role) $childs_tree[$k]['icon'] = ($container) ? $container->get('icon') : null; 
					$childs_tree[$k]['href'] = $this->getUrl()->fromRoute('admin/actions', ['controller' => 'access', 'action' => 'resources'], ['query' => ['filter' => $childs[$k]['resource']]]); 
					if(count($this->tree_resources_list) > 0) {
						$node = $this->getResourcesTreeChilds($childs[$k]['resource'], $selected, $role);
						if($node['content']) $childs_tree[$k]['nodes'] = $node['content'];
						if($node['state']['expanded']) $childs_tree[$k]['state']['expanded'] = true;
					}
					if($selected) {
						if($childs_tree[$k]['resource'] == $selected) $childs_tree[$k]['state'] = ['expanded' => true, 'selected' => true];
						else if(strpos($selected, $childs_tree[$k]['resource']) !== false) $childs_tree[$k]['state'] = ['expanded' => true];
					}
					if($role){
						$childs_tree[$k]['status']['denied'] = false;
						$childs_tree[$k]['status']['inherit'] = false;
						if($this->access_list->isAllowed($role, $childs_tree[$k]['resource'])) {
							$childs_tree[$k]['state']['checked'] = true;
							$childs_tree[$k]['state']['expanded'] = true;
							// if this resource is allowed to any parent role
							if(isset($this->parents_resources[$role]) && isset($this->parents_resources[$role][$childs_tree[$k]['resource']])) {
								$childs_tree[$k]['backColor'] = '#EDEFEF';
								$childs_tree[$k]['tags'][] = $this->translate('inherit from role');
								$childs_tree[$k]['status']['inherit'] = 'role';
							}
					
							// if any parent resources allowed to this role
							else if(array_intersect_assoc($this->getResourceParents($childs_tree[$k]['resource']), $this->personal_resources[$role])) {
								$childs_tree[$k]['backColor'] = '#EDEFEF';
								$childs_tree[$k]['tags'][] = $this->translate('inherit from resource');
								$childs_tree[$k]['status']['inherit'] = 'resource';
							} else {
									$childs_tree[$k]['backColor'] = '#E0F1DD';
									$childs_tree[$k]['tags'][] = 'personal';
									$childs_tree[$k]['status']['inherit'] = 'personal';
							}

						} else {

							// if this resource is allowed to any parent role
							if(isset($this->parents_resources[$role]) && isset($this->parents_resources[$role][$childs_tree[$k]['resource']])) {

								$childs_tree[$k]['backColor'] = '#FFCCCC';
								$childs_tree[$k]['tags'][] = 'denied';
								$childs_tree[$k]['tags'][] = $this->translate('inherit from role');
								$childs_tree[$k]['status']['inherit'] = 'role';
								$childs_tree[$k]['status']['denied'] = true;
							}
							// if any parent resources allowed to this role
							if(isset($this->personal_resources[$role]) && array_intersect_assoc($this->getResourceParents($childs_tree[$k]['resource']), $this->personal_resources[$role])) {
								$childs_tree[$k]['backColor'] = '#FFCC00';
								$childs_tree[$k]['tags'][] = 'denied';
								$childs_tree[$k]['tags'][] = $this->translate('inherit from resource');
								$childs_tree[$k]['status']['inherit'] = 'resource';
								$childs_tree[$k]['status']['denied'] = true;
							}
							// if any parent resources allowed to any parent roles
							if(isset($this->parents_resources[$role]) && array_intersect_assoc($this->getResourceParents($childs_tree[$k]['resource']), $this->parents_resources[$role])) {
								$childs_tree[$k]['tags'][] = $this->translate('denied to parent role / resource');
								$denied_key = array_search('denied', $childs_tree[$k]['tags']);
								if($denied_key !== false) unset($childs_tree[$k]['tags'][$denied_key]);
								$childs_tree[$k]['backColor'] = '#FFFFF';
								$childs_tree[$k]['status']['inherit'] = 'parent_deny';
								$childs_tree[$k]['status']['denied'] = false;
							} 
						}
					}

					if($childs_tree[$k]['state']['expanded'] == true) $result['state']['expanded'] = true;
				}
			}
		$result['content'] = $childs_tree;
		return $result;
	}

	public function getResourceParents($resource)
	{
		$sections = explode('\\', $resource);

		$parents = [];
		$str = '';
		$count = count($sections);
		for ($i=0; $i < $count; $i++) {
			if($i == $count - 1) {
				if (strpos($sections[$i], '.') !== false) {
					$last = explode('.', $sections[$i]);
					$str .= $last[0];
					$parents[$str] = true;
				}

			} else {
				$str .= $sections[$i];
				$parents[$str] = true;
				$str .= '\\';
			}

		}
		return $parents;
	}

	protected function setRolesPersonalResources()
	{

		$roles = $this->access_list->getRoles();
		$resources = $this->access_list->getResources();
		$all_resources = [];
		foreach ($roles as $role) {
			for ($i=0; $i < count($resources); $i++) {
				if($this->access_list->isAllowed($role, $resources[$i])) {
					$all_resources[$role][$resources[$i]] = true;
				}
			}
		}
		$parents_resources = null;
		$personal_resources = $all_resources;
		foreach ($all_resources as $child_role => $resources) {
			$child_object = $this->access_list->getRole($child_role);
			for ($i=0; $i < count($roles); $i++) { 
				$parent_object = $this->access_list->getRole($roles[$i]);
				if($this->access_list->inheritsRole($child_object, $parent_object)) {
					if(isset($all_resources[$roles[$i]])) $parents_resources[$child_role] = $all_resources[$roles[$i]];
					if(isset($personal_resources[$child_role]) && isset($all_resources[$roles[$i]])) {
						$personal_resources[$child_role] = array_diff_assoc($personal_resources[$child_role], $all_resources[$roles[$i]]);
					}
					
				}
			}
		}
		$this->parents_resources = $parents_resources;
		$this->personal_resources = $personal_resources;
	}	

	public function getUrl()
	{
		if(!$this->url) {
			$this->url = $this->serviceLocator->get('controllerPluginManager')->get('url');
		}
		return $this->url;
	}

	public function getNavigation($container = 'navigation')
	{
		// if(!$this->navigation) {
			$this->navigation = $this->serviceLocator->get('viewHelperManager')->get('navigation')->setContainer($container);
		// }
		return $this->navigation;
	}

	public function translate($string)
	{
		return \Application\Translator\StaticTranslator::getTranslator()->translate($string);
	}

	public function getAccessList()
	{
		// if(file_exists(__DIR__.'/access.list')) {
		// 	$this->access_list = unserialize(file_get_contents(__DIR__.'/access.list'));
		// }
		// else $this->access_list = new AccessList();

		if(!$this->access_list) $this->access_list = new AccessList();
		return $this->access_list;
	}

	public function saveAccessList($access_list)
	{
		file_put_contents(__DIR__.'/access.list', serialize($access_list));
	}

	public function resetAccessList()
	{
		z_delete(__DIR__.'/access.list');
		$this->access_list = new AccessList();
		return $this->access_list;
	}



}