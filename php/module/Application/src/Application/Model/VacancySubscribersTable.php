<?php
namespace Application\Model;

class VacancySubscribersTable extends zEmptyTable
{

	public function __construct()
	{
		$this->init('vacancy_subscribers');
	}


	public function subscribe($user_id, $vacancy_id)
	{
		if($this->isSubscribed($user_id, $vacancy_id) === true) return false;
		$this->tableGateway->insert(array(
			'user_id' => $user_id, 
			'vacancy_id' => $vacancy_id,
			'time' => time()
			));
		return true;
	}
	
	public function unsubscribe($user_id, $vacancy_id)
	{
		if($this->isSubscribed($user_id, $vacancy_id) === false) return false;
		$this->tableGateway->delete(array(
			'user_id' => $user_id, 
			'vacancy_id' => $vacancy_id
				));
		return true;
	}
	
	public function isSubscribed($user_id, $vacancy_id)
	{
		$id = $this->getIDByFields(array('user_id' => $user_id, 'vacancy_id' => $vacancy_id), false, false);
		if(is_object($id)) {
			$this->delete($id->current()->id);
			return $this->isSubscribed($user_id, $vacancy_id);
		}
		if($id !== false) return true;
		return false;
	}

	public function getVacancySubscribers($viewer_id = 0, $filters = [], $options = []) 
	{
		if(!isset($filters['id'])) throw new \Exception("Vacancy id should be provided", 1);
		
		$this->setDefaultOptions($options, ['_limit' => 200, '_order' => 'subscribed_time']);
		$user_fields = ['id' => 'user_id', 'name', 'surname', 'full_name', 'login', 'avatar', 'cv_avatar', 'email', 'desired_rank', 'nationality', 'cv_last_call', 'cv_last_update'];
		$user_fields_string =  $this->arrayToFields($user_fields,'u.');
		$db_fields = isset($options['_v_subs_fields'])? $options['_v_subs_fields'] : $this->getStandartFields();
		$db_fields = array_merge(['time' => 'subscribed_time'], $db_fields);
		$db_fields_string =  $this->arrayToFields($db_fields, 'vs.');

		$include_notes = isset($options['include_notes'])? $options['include_notes'] : 0;

		$where ='';
		foreach ($filters as $filter => $value) {
			if($value == '' || $value == null || $value == 0 || $value == false) next($filters);
			if($value) $where .= ($where == '') ? ' WHERE ' : ' AND ';

			if ($filter == 'id' || $filter = 'vacancy_id') {
				$where .= " vs.`vacancy_id` = '$value' ";
			} else throw new \Application\Exception\Exception("Filter not recognized", 1);
		}

		$join_string = '';
		$group_by = '';
		if($this->count) {
			$fields_string = ' COUNT(*) count ';
			$this->order_string = '';
			$this->limit_string = '';
			$this->offset_string = '';
		} else {

			$fields_string = $db_fields_string;
			$fields_string .= ' ,'. $user_fields_string;

			$join_string .= "INNER JOIN user u ON (vs.user_id = u.id) ";
			$group_by = ' GROUP BY u.id ';

			if($include_notes) {
				$fields_string .= $this->getNotesFieldsString();
				$join_string .= $this->getNotesJoin(1,1,1, $viewer_id);
			}

		}
		
		$result = $this->query(
			"SELECT $fields_string
				FROM `vacancy_subscribers` vs
				$join_string
				$where
				$group_by
				$this->order_string
				$this->limit_string $this->offset_string
			");
		if($this->buffer) $result->buffer();
		return $result;

	}

}