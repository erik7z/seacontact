<?php
namespace Application\Model;

class UserWorkedForTable extends zAbstractTable
{

	public function __construct()
	{
		$this->init('user_worked_for');
	}

	public function getWorkedFor($viewer_id = null, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 20, '_order' => 'id']);

		$user_fields = isset($options['_user_fields'])? $options['_user_fields'] : ['name', 'surname', 'full_name', 'email', 'login', 'avatar', 'cv_avatar', 'type', 'role'];
		$user_fields = array_merge(['id' => 'user_id'], $user_fields);
		$user_fields =  $this->arrayToFields($user_fields, 'u.', 'user_');

		$company_fields = isset($options['_company_fields'])? $options['_company_fields'] : [ 'email','contact_email', 'company_name', 'home_country','home_city', 'login', 'avatar', 'type', 'role'];
		$company_fields = array_merge(['id' => 'company_id'], $company_fields);
		$company_fields =  $this->arrayToFields($company_fields, 'c.', 'company_');

		$db_fields = isset($options['_worked_for_fields'])? $options['_worked_for_fields'] : $this->getStandartFields();
		$db_fields = array_merge(['id' => 'wf_id'], $db_fields);
		$db_fields_string =  $this->arrayToFields($db_fields, 'wf.');

		$notes_join_field = isset($options['notes_field'])? $options['notes_field'] : 'user_id';
		$notes_predicat = ($notes_join_field == 'user_id')? 'user_' : 'company_';
		$where = '';

		foreach ($filters as $filter => $value) {
			if($value == '' || $value == null || $value == 0 || $value == false) next($filters);
			if($value) $where .= ($where == '') ? ' WHERE ' : ' AND ';

			if ($filter == 'id') {
				$where .= " `id` = '$value' ";
			} else {
				$permitted_fields = array_flip($this->getStandartFields(1, 1));
				if(!isset($permitted_fields[$filter])) throw new \Application\Exception\Exception("Filter not recognized", 1);
				$where .= " wf.`$filter` = '$value' ";
			}
		}

		if($this->count) {
			$fields_string = ' COUNT(*) count ';
			$join_string = '';
			$this->order_string = '';
			$this->limit_string = '';
			$this->offset_string = '';
		} else {
			$fields_string = $db_fields_string;
			$fields_string .= ' ,'. $user_fields.' ,'. $company_fields;
			$fields_string .= $this->getNotesFieldsString($notes_predicat);
		
			$join_string = "INNER JOIN user u ON (wf.user_id = u.id) ";
			$join_string .= "INNER JOIN user c ON (wf.company_id = c.id) ";

			$notes_company_private = (isset($options['notes_company_private'])) ? $options['notes_company_private'] : false;
			$notes_admin_all = (isset($options['notes_admin_all'])) ? $options['notes_admin_all'] : false;
			$notes_admin_private = (isset($options['notes_admin_private'])) ? $options['notes_admin_private'] : false;
			$only_auto_notes = (isset($options['only_auto_notes'])) ? $options['only_auto_notes'] : false;

			$join_string .= $this->getNotesJoin(1, 1, 1, $viewer_id, $only_auto_notes,['t_alias' => 'wf', 't_field' => $notes_join_field]);
		}
		
		return $this->query(
			"SELECT DISTINCT $fields_string
				FROM `$this->tableName` wf 
				$join_string
				$where
				$this->order_string
				$this->limit_string $this->offset_string
			");
	}


	public function addWorkedFor($user_id, $company_ids)
	{
		if(!is_array($company_ids)) $company_ids = [$company_ids];

		$values_str = '';
		$c = count($company_ids);
		for ($i=0; $i < $c; $i++) { 
			if($i > 0) $values_str .= ', ';
			$values_str .= " ('{$user_id}','{$company_ids[$i]}') ";
		}

		return $this->query(
			"INSERT IGNORE INTO `$this->tableName` (user_id, company_id)
				VALUES $values_str
			");
	}

}