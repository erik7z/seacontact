<?php
namespace Application\Model;

class VacancyCandidatesTable extends zEmptyTable
{
	const STATUS_APPROVED = 3;
	const STATUS_DENIED = 1;
	const STATUS_CLOSED = 2;
	const STATUS_PENDING = 4;

	public function __construct()
	{
		$this->init('vacancy_candidates');
	}

	public static function getCandidateStatusList()
	{
		$translator = \Application\Translator\StaticTranslator::getTranslator();
		return [
			self::STATUS_PENDING => $translator->translate('Awaiting Confirmation'),
			self::STATUS_CLOSED => $translator->translate('Position Closed'),
			self::STATUS_DENIED => $translator->translate('Rejected'),
			self::STATUS_APPROVED => $translator->translate('Approved'),
		];
	}

	public function assignCandidate($vacancy_id, $user_id, $admin_id, $status = 0)
	{
		$time = time();
		return $this->query(
			"INSERT INTO $this->tableName (vacancy_id, user_id, admin_id, status, time)
				SELECT * FROM (SELECT '$vacancy_id', '$user_id' user_id, '$admin_id' admin_id, '$status', '$time') as tmp
				WHERE NOT EXISTS (
				    SELECT id FROM $this->tableName 
				    WHERE vacancy_id = '$vacancy_id' 
				    AND user_id = '$user_id'
				) LIMIT 1
			"
			);
	}
	

	public function setCandidateStatus($vacancy_id, $user_id, $status)
	{
		return $this->query(
			"UPDATE `{$this->tableName}` 
				SET `status` = '$status' 
				WHERE `vacancy_id` = '$vacancy_id' 
				AND `user_id` = '$user_id'
			");
	}

	public function removeCandidate($vacancy_id, $user_id)
	{
		return $this->query(
			"DELETE vc.* 
				FROM `{$this->tableName}` vc 
				WHERE `vacancy_id` = '$vacancy_id'
				AND `user_id` = '$user_id'
				");
	}


	public function getVacancyCandidates($viewer_id = null, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_order' => 'assigned_time']);

		$admin_id = (isset($options['admin_id'])) ? (int) $options['admin_id'] : 0;
		$user_fields = (isset($options['_user_fields'])) ? $options['_user_fields'] : [
			'id' => 'user_id', 'name', 'surname', 'full_name', 
			'login', 'avatar', 'cv_avatar', 'email', 
			'desired_rank', 'nationality', 'cv_last_call', 'cv_last_update'
			];

		$where = '';
		if ($filters) {
			foreach ($filters as $filter => $value) {
				$where .= ($where == '') ? ' WHERE ' : ' AND ';
				if ($filter == 'vacancy_id') {
					$where .= " vc.vacancy_id = '$value' ";
				} else if ($filter == 'admin_id') {
					$where .= " vc.admin_id = '$value' ";
				} else if ($filter == 'status') {
					$where .= " vc.status = $value ";
				} else throw new \Application\Exception\Exception("Filter not recognised", 1);
			}		
		}

		$fields_string = "
			vacancy_id, vc.user_id candidate_id, admin_id, status, vc.time assigned_time
			, v.title vacancy_title, v.text vacancy_text, v.time vacancy_time, v.salary vacancy_salary, v.ship_type vacancy_ship_type, v.rank vacancy_rank
			, {$this->getUserFields($user_fields)}
			, ua.name admin_name, ua.surname admin_surname, ua.full_name admin_full_name, ua.login admin_login, ua.avatar admin_avatar, ua.cv_avatar admin_cv_avatar
			{$this->getNotesFieldsString()}
		";

		$select = "SELECT 
			$fields_string
			FROM `vacancy_candidates` vc
			INNER JOIN user u ON (vc.user_id = u.id)
			INNER JOIN user ua ON (vc.admin_id = ua.id)
			INNER JOIN vacancies v ON (vc.vacancy_id = v.id)
			{$this->getNotesJoin(1,1,1, $viewer_id)}
			$where
			GROUP BY u.id
		";

		if($this->count){
			$query = "
				SELECT COUNT(*) count
				FROM (
					$select
					) x
				";
		} else {
			$query = "
			$select
			$this->order_string
			$this->limit_string $this->offset_string
			";
		}
		return $this->query($query);
	}

}