<?php
namespace Application\Model;

class AdminActivityTable extends zEmptyTable
{

	public function __construct()
	{
		$this->init('admin_activity');
	}

	public function addActivity($user_id, $route, $note = null, $result = array()){
		if(!isset($route['module']) || !isset($route['controller']) || !isset($route['action']) ) 
			throw new \Application\Exception\Exception("Route params should be provided [AdminActivityTable | addActivity]", 1);
		
		return $this->insert(array(
			'user_id' => $user_id,
			'module' => $route['module'],
			'controller' => $route['controller'],
			'action' => $route['action'],
			'action_id' => (isset($route['id']))? $route['id'] : null,
			'query' => (isset($route['query']))? $route['query'] : null,
			'note' => $note,
			'ip' => $_SERVER['REMOTE_ADDR'],
			'time' => time(),
			'result' => (isset($result['result']))? $result['result'] : null,
			'result_controller' => (isset($result['controller']))? $result['controller'] : null,
			'result_action' => (isset($result['action']))? $result['action'] : null,
			'result_id' => (isset($result['id']))? $result['id'] : null,
			));
	}


	public function getActivity($viewer_id, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_order' => 'time']);
		$where = '';

		foreach ($filters as $filter => $value) {
			if($value == '' || $value == null || $value == 0 || $value == false) next($filters);

			if ($filter == 'admin_id') {
				$where = " WHERE `user_id` = '$value'";
			} 
		}

		if($this->count) {
			$fields_string = "COUNT(*) count";
			$this->limit_string = '';
			$this->offset_string = '';
			$this->order_string = '';
		} else $fields_string = "ac.* , u.login, u.name, u.surname, u.full_name, u.avatar, u.cv_avatar, u.role";

		return $this->query(
			"SELECT $fields_string
				FROM $this->tableName ac
				INNER JOIN `user` u ON (u.id = ac.user_id)
				$where
				$this->order_string
				$this->limit_string 
				$this->offset_string
			");
	}



}