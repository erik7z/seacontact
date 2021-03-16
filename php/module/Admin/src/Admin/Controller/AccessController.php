<?php
namespace Admin\Controller;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class AccessController extends AbstractController
{

	
	/**
	 * Index action of some controller
	 * @return [type]
	 */

	public function indexAction()
	{

	}


	public function banListAction()
	{
		$success = 1;
		$message ='';
		$q_options = $this->setDefaultOptions(['_limit' => 5]);
		$ban_list = $this->get('BanListTable')->getBannedUsersList($this->identity()->id,$q_options['filters'],$q_options);
		$q_options['count'] = 1;
		$total_results = $this->get('BanListTable')->getBannedUsersList($this->identity()->id,$q_options['filters'],$q_options)->current()->count;
		
		$view = new ViewModel([
			'ban_list' => $ban_list,
			'total_results' => $total_results,
			'q_options' => $q_options
			]);
		return $view;
	}

	public function banIpAction()
	{
		$form = $this->get('EmptyForm');
		$form->add([
			'name' => 'user_ip',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('User Ip'),
				),
			'attributes' => array(
				'required' => 'required',
				'placeholder' => '192.168.1.0',
				),
			]);

		$form->setAttribute('action', $this->url()->fromRoute('admin/actions', array('controller' => 'access', 'action' => 'ban-ip')));
		$form->addSubmit($this->translate('Ban Ip'));

		if($this->request->isPost()) {
			try {
				$data = $this->request->getPost();
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
				$banTable = $this->get('BanListTable');
				$banTable->ban($data['user_ip']);
				$success = 1;
				$message = $this->translate('Ip added to ban list');
				$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
																array(
																	'module' => 'Admin',
																	'controller' => $this->routeMatch()->getParam('__CONTROLLER__'),
																	'action' => $this->routeMatch()->getParam('action'),
																	'id' => $data['user_ip']
																	) ,
																$message
															);
			} catch (\Exception $e) {
				$message = ($e->getCode() == 777) ? unserialize($e->getMessage()) : $e->getMessage();
				$success = 0;
			}

			return $this->viewResponse($success, $message, [
				'redirect' => $this->url()->fromRoute('admin/actions', array('controller' => 'access', 'action' => 'banned-list')), 
				'exception' => (isset($e)) ? $e : null
				]);

		} // end post



		$view = new ViewModel([
			'form' => $form,
			]);
		if($this->params()->fromQuery('response') == 'terminal') $view->setTerminal(true);
		return $view;
	}

	public function removeBanAction() {
		$ban_id = (int)$this->params()->fromRoute('id');
		try {
			if($ban_id == 0) throw new \Application\Exception\Exception("Ban id not suppplied", 1);
			$this->get('BanListTable')->removeBanId($ban_id);
			$success = 1;
			$message = $this->translate('Ban Removed');
		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}
		return $this->viewResponse($success, $message, [
			'redirect' => $this->url()->fromRoute('admin/actions', array('controller' => 'access', 'action' => 'banned-list')), 
			'exception' => (isset($e)) ? $e : null
			]);
	}

	public function toggleBanAction() {
		$user_id = (int)$this->params()->fromRoute('id');
		try {
			if($user_id == 0) throw new \Application\Exception\Exception("User Not Found", 1);
			$banTable = $this->get('BanListTable');
			$is_banned = $banTable->isBanned(null, $user_id);
			if($is_banned == false) {
				$banTable->ban(null, $user_id);
				$message = 'User Banned';
			} else {
				$banTable->unBan(null, $user_id);
				$message = 'User Ban Removed';
			}
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
															array(
																'module' => 'Admin',
																'controller' => $this->routeMatch()->getParam('__CONTROLLER__'),
																'action' => $this->routeMatch()->getParam('action'),
																'id' => $user_id
																) ,
															$message 
														);
			$success = 1;
		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}
		return $this->viewResponse($success, $message, [
			'redirect' => $this->url()->fromRoute('admin/actions', array('controller' => 'userdb', 'action' => 'user', 'id' => $user_id)), 
			'exception' => (isset($e)) ? $e : null
			]);
	}


	public function resourcesAction()
	{
		$access = $this->get('Access');
		$access_list = $access->getAccessList();
		$all_resources = $access_list->getResources();
		$filter = $this->params()->fromQuery('filter');
		$table_resources = ($filter)? $access->getResourceChildsList($filter) : $all_resources;

		
		$tree = json_encode($access->getResoucesTree($all_resources, $filter), 128);

		$view = new ViewModel([
			'resources' => $table_resources,
			'roles' => $access_list->getRoles(),
			'access' => $access_list,
			'tree' => $tree,
			'filter' => $filter
			]);
		return $view;
	}

	public function rolesAction()
	{
		$access = $this->get('Access');
		$access_list = $access->getAccessList();
		$access_list->updateRoles();
		$access_list->updateResources();
		$access->saveAccessList($access_list);

		// $access_list->deny('company', 'application\controller\vacancies');
		// $access->saveAccessList($access_list);

		$all_roles = $access_list->getRoles();
		$all_resources = $access_list->getResources();
		$role = $this->params()->fromQuery('filter');
		$roles_tree = json_encode($access->getRolesTree($all_roles, $role), 128);
		$resources_tree = json_encode($access->getResoucesTree($all_resources, null, $role), 128);

		$view = new ViewModel([
			'access' => $access_list,
			'roles_tree' => $roles_tree,
			'resources_tree' => $resources_tree,
			'role' => $role
			]);
		return $view;
	}


	public function deleteRoleAction()
	{
		$access = $this->get('Access');
		$access_list = $access->getAccessList();
		$roles = $access_list->getRoles();
		for ($i=0; $i < count($roles); $i++) { 
			$roles_list[$roles[$i]] = $roles[$i];
		}
		$fieldset = $this->get('AdminRoles')->remainFields(['roles']);
		$fieldset->get('roles')->setValueOptions($roles_list)->setLabel($this->translate('Choose Role'));
		$form = $this->get('EmptyForm');
		$form->add($fieldset);
		$form->getInputFilter()->get('admin_roles')->get('roles')->setRequired(true);
		$form->setAttribute('action', $this->url()->fromRoute('admin/actions', array('controller' => 'access', 'action' => 'delete-role')));
		$form->addSubmit($this->translate('Delete Role'));

		if($this->request->isPost()) {
			try {
				$data = $this->request->getPost();
				$form->setData($data);
				if($form->isValid()) {
					$data = $data['admin_roles'];
					if($data['roles'] != '') {
						if(!$this->isPermitted('_admin_\access\assign_roles\\'.$data['roles'])) 
								throw new \Application\Exception\Exception($this->translate("You cannot delete this role"), 1);
						$userTable = $this->get('UserTable');
						$users = $userTable->getCountWhere(['role' => $data['roles']]);
						if($users == 0) $access_list->removeRole($data['roles']);
						else throw new \Application\Exception\Exception($this->translate('You cannot delete role which assigned to users'), 1);
					}
					$access_list->removeResource('_admin_\access\assign_roles\\'.$data['roles']);
					$access->saveAccessList($access_list);
					$this->fm()->addSuccessMessage($this->translate('Role Deleted !'));
				} else $this->fm()->addErrorMessage($form->getMessages());
			} catch (\Exception $e) {
				$this->fm()->addErrorMessage($e->getMessage());
			}
			$this->redirect()->toRoute('admin/actions', array('controller' => 'access', 'action' => 'roles'));
		}


		$view = new ViewModel([
			'form' => $form,
			]);
		$view->setTemplate('admin/access/add-role');
		if($this->params()->fromQuery('response') == 'terminal') $view->setTerminal(true);
		return $view;
	}

	public function addRoleAction()
	{

		$access = $this->get('Access');
		$access_list = $access->getAccessList();
		$roles = $access_list->getRoles();
		for ($i=0; $i < count($roles); $i++) { 
			$roles_list[$roles[$i]] = $roles[$i];
		}
		$fieldset = $this->get('AdminRoles');
		$fieldset->get('roles')->setValueOptions($roles_list);
		$form = $this->get('EmptyForm');
		$form->add($fieldset);
		$form->setAttribute('action', $this->url()->fromRoute('admin/actions', array('controller' => 'access', 'action' => 'add-role')));
		$form->addSubmit($this->translate('Add Role'));

		if($this->request->isPost()) {
			try {
				$data = $this->request->getPost();
				$form->setData($data);
				if($form->isValid()) {
					$data = $data['admin_roles'];
					if($data['roles'] == '') {
						$access_list->addRole(new \Zend\Permissions\Acl\Role\GenericRole($data['new_role']));
					} else {
						if($this->isPermitted('_admin_\access\assign_roles\\'.$data['roles']))
							$access_list->addRole($data['new_role'], $data['roles']);
						else throw new \Application\Exception\Exception($this->translate("You cannot add role inherited from ").$data['roles'], 1);
					}
					$access_list->addResource(new \Zend\Permissions\Acl\Resource\GenericResource('_admin_\access\assign_roles\\'.$data['new_role']), '_admin_\access\assign_roles');
					$access->saveAccessList($access_list);
					$this->fm()->addSuccessMessage($this->translate('Role Added !'));
				} else $this->fm()->addErrorMessage($form->getMessages());
			} catch (\Exception $e) {
				$this->fm()->addErrorMessage($e->getMessage());
			}
			$url = $this->request->getServer()->HTTP_REFERER;
			if($url) return $this->redirect()->toUrl($url);
			else $this->redirect()->toRoute('admin/actions', array('controller' => 'access', 'action' => 'roles'));
		}


		$view = new ViewModel([
			'form' => $form,
			]);
		if($this->params()->fromQuery('response') == 'terminal') $view->setTerminal(true);
		return $view;
	}


	public function resetAccessListAction()
	{
		$access = $this->get('Access');
		$access->resetAccessList();
		return $this->redirect()->toRoute('admin/actions', array('controller' => 'access', 'action' => 'roles'));
		
	}


	public function editAccessRightsAction()
	{
		try {
			if($this->request->isPost()){
				$access = $this->get('Access');
				$access_list = $access->getAccessList();

				$data = $this->request->getPost()->toArray();
				if($data['role'] == '') throw new \Application\Exception\Exception("Role should be provided", 1);
				if(!$access_list->hasResource($data['resource'])) throw new \Application\Exception\Exception($this->translate("Resource Not Found"), 1);
				if(!$this->access->isPermitted($data['resource'])) throw new \Application\Exception\Exception($this->translate('You cannot edit rights that you dont have permission!'), 1);
				
				if($data['action'] == 'check') {
					if($data['denied'] == 'true') {
						$access_list->removeDeny($data['role'], $data['resource']);
					} else {
						$access_list->allow($data['role'], $data['resource']);
					}
				} else if($data['action'] == 'uncheck') {
					if ($data['inherit'] == 'personal') {
						$access_list->removeAllow($data['role'], $data['resource']);
					} else {
						$access_list->deny($data['role'], $data['resource']);
					}
				} else throw new \Application\Exception\Exception("Action not specified", 1);
				$access->saveAccessList($access_list);
				$response['success'] = true;
				$response['data'] = json_encode($access->getResoucesTree($access_list->getResources(), null, $data['role']), 128);
			} else throw new \Application\Exception\Exception("Only post requests are accepted", 1);
		} catch (\Exception $e) {
			$response['success'] = false;
			$response['data'] = $e->getMessage();
		}

		$view = new ViewModel(array(
			'response' => json_encode($response),
			));
		$view->setTemplate('/user-info/json');
		$view->setTerminal(true);
		return $view;
	}

	public function resourceAction()
	{
		$resource = $this->params()->fromQuery('resource');

		$access_list = $this->get('Access')->getAccessList();
		$view = new ViewModel([
			'resources' => $access_list->getResources(),
			'roles' => $access_list->getRoles(),
			'access' => $access_list,
			'resource' => $resource,
			]);
		return $view;
	}


	public function roleAction()
	{
		$role = $this->params()->fromQuery('role');

		$access_list = $this->get('Access')->getAccessList();
		$view = new ViewModel([
			'resources' => $access_list->getResources(),
			'roles' => $access_list->getRoles(),
			'access' => $access_list,
			'role' => $role,
			]);
		return $view;
	}


}