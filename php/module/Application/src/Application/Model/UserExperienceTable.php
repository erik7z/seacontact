<?php
namespace Application\Model;

class UserExperienceTable extends zEmptyTable
{

	public function __construct()
	{
		$this->init('user_experience');
	}


	public function getUserExperience($user_id, $format_date = 1)
	{
		$data =  $this->getAllOnField('user', $user_id, 'date_from', false)->toArray();
		if($format_date) {
			foreach ($data as $key => $value) {
				$data[$key]['date_from'] = date('d M y', $value['date_from']);
				$data[$key]['date_to'] = date('d M y', $value['date_to']);
			}
		}
		return $data;
	}

	//new
	public function getExperience($viewer_id = null, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 100, '_order' => '`time`']);
		
		$user_fields = isset($options['_user_fields'])? $options['_user_fields'] : ['name', 'surname', 'full_name', 'company_name', 'login', 'avatar', 'cv_avatar', 'type', 'role'];
		$user_fields = array_merge(['id' => 'user_id'], $user_fields);
		$user_fields_string =  $this->arrayToFields($user_fields,'u.');

		$db_fields = isset($options['_experience_fields'])? $options['_experience_fields'] : $this->getStandartFields();
		$db_fields = array_merge(['id' => 'exp_id'], $db_fields);
		$db_fields_fields_string =  $this->arrayToFields($db_fields, 'exp.');

		$where = '';
		foreach ($filters as $filter => $value) {
			$where .= ($where == '') ? ' WHERE ' : ' AND ';
			if ($filter == 'owner_id') {
				$where .= " `exp`.user = '$value'";
			} else if ($filter == 'id') {
				$where .= " `exp`.id = '$value'";
			} else throw new \Application\Exception\Exception("Filter not recognized", 1);
		}

		if ($this->count) {
			$fields_string = ' COUNT(*) count ';
			$this->order_string = '';
			$this->limit_string = '';
			$this->offset_string = '';
		} else { 
			$fields_string = $db_fields_fields_string;
			$fields_string .= ', '.$user_fields_string;
		}

		$join_string = 'INNER JOIN user u ON (exp.user = u.id)';	

		return $this->query(
			"SELECT $fields_string
				FROM `$this->tableName` exp
				$join_string
				$where
				$this->order_string
				$this->limit_string
				$this->offset_string
			"
			);
	}

	public function getUserLastContract($user_id)
	{
		$result =  $this->query(
			"SELECT ue.*, IF (ue.date_from > ue.date_to, date_from, date_to) latest_date
				FROM `user_experience` ue
				WHERE ue.user = $user_id
				ORDER BY latest_date DESC
				LIMIT 1
			"
			);
		return $result;
	}
	
	public function getUserContract($contract_id)
	{
		$contract_id = (int)$contract_id;
		$data =  $this->get($contract_id);
		if(!$data) return false;

		$data['date_from'] = date('Y-m-d', $data['date_from']);
		$data['date_to'] = date('Y-m-d', $data['date_to']);

		return $data;
	}



	public function getShipTypes($viewer_id = null, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 200, '_order' => 'sg.id']);
	
		$where = '';
		foreach ($filters as $filter => $value) {
			if(!$value) next($filters);
			if($value) $where .= ($where == '') ? ' WHERE ' : ' AND ';

			if ($filter == 'ship_type_id') {
				$where .= " st.id = '$value' ";
			} else if ($filter == 'ship_type') {
				$where .= " st.ship_type LIKE '$value%' ";
			} else if ($filter == 'ship_group') {
				$where .= " sg.ship_group LIKE '$value%' ";
			} else throw new \Application\Exception\Exception("Filter not recognized", 1);
		}
		
		$fields_string = '';
		$join_string = '';
		if ($this->count) {
			$fields_string = ' COUNT(*) count ';
			$this->order_string = '';
			$this->limit_string = '';
			$this->offset_string = '';
		} else {
			$fields_string .= ' st.ship_type, sg.ship_group';
		}
		$join_string .= " INNER JOIN `list-ship_group` sg ON (sg.id = c.ship_group_id) ";
		$join_string .= " INNER JOIN `list-ship_type` st ON (st.id = c.ship_type_id) ";

		return $this->query(
			"SELECT $fields_string
				FROM `list-ship_type-ship_group` c
				$join_string
				$where 
				$this->order_string
				$this->limit_string
				$this->offset_string
			");
	}

}