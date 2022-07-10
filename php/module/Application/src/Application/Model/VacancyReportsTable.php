<?php
namespace Application\Model;

class VacancyReportsTable extends zEmptyTable
{

	public function __construct()
	{
		$this->init('vacancy_reports');
	}


	public function report($user_id, $vacancy_id)
	{
		if($this->isReported($user_id, $vacancy_id) === true) return false;
		$this->tableGateway->insert(array(
			'user_id' => $user_id, 
			'vacancy_id' => $vacancy_id,
			'time' => time()
			));
		return true;
	}
	
	public function unreport($user_id, $vacancy_id)
	{
		if($this->isReported($user_id, $vacancy_id) === false) return false;
		$this->tableGateway->delete(array(
			'user_id' => $user_id, 
			'vacancy_id' => $vacancy_id
				));
		return true;
	}
	
	public function isReported($user_id, $vacancy_id)
	{
		$id = $this->getIDByFields(array('user_id' => $user_id, 'vacancy_id' => $vacancy_id), false, false);
		if(is_object($id)) {
			$this->delete($id->current()->id);
			return $this->isReported($user_id, $vacancy_id);
		}
		if($id !== false) return true;
		return false;
	}


	public function getVacancyReports($vacancy_id, $admin_id = null, $user_fields = null)
	{
		if(!$user_fields) 
			$user_fields = [
			'id' => 'user_id', 'name', 'surname', 'full_name', 
			'login', 'avatar', 'cv_avatar', 'email', 
			'desired_rank', 'nationality', 'cv_last_call', 'cv_last_update'
			];

			$notes_fields_string = $this->getNotesFieldsString();
			$notes_join = $this->getNotesJoin(1,1,1, $admin_id);

		return $this->query(
			"SELECT vacancy_id, v.time subscribed_time
				$notes_fields_string
				, {$this->getUserFields($user_fields)}
				FROM `$this->tableName` v
				INNER JOIN user u ON (v.user_id = u.id)
				$notes_join
				WHERE v.vacancy_id = $vacancy_id
				GROUP BY u.id
				ORDER BY subscribed_time DESC
			"
			);
	}


}