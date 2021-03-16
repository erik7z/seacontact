<?php
namespace Application\Model;

class AdminTasksTable extends zEmptyTable
{

	const STATUS_ACTIVE = 0;
	const STATUS_POSTPONED = 1;
	const STATUS_CLOSED = 2;
	const PRIORITY_MIN = 0;
	const PRIORITY_MAX = 10;


	public function __construct()
	{
		$this->init('admin_tasks');
	}

	public static function getTasksStatusList($word_key = 0)
	{
		$translator = \Application\Translator\StaticTranslator::getTranslator();
		if($word_key) {
			return [
				'active' => self::STATUS_ACTIVE,
				'postponed' => self::STATUS_POSTPONED,
				'closed' => self::STATUS_CLOSED,
			];
		}
		return [
			self::STATUS_ACTIVE => $translator->translate('Active'),
			self::STATUS_POSTPONED => $translator->translate('Postponed'),
			self::STATUS_CLOSED => $translator->translate('Closed'),
		];
	}

	public function getTask($task_id, $user_id, $rowFeature = 0)
	{
		if($rowFeature) {
			$this->init('admin_tasks', new \Zend\Db\TableGateway\Feature\RowGatewayFeature('id'));
			return $this->tableGateway->select(array('id' => $task_id))->current();
		}

		$user_fields = $this->generateJsonFields(array('u.id' => 'user_id', 'login','name', 'surname', 'role'), '', 5);
		return $this->query(
			"SELECT t.*, u.login, u.name, u.surname, u.full_name, u.avatar, u.cv_avatar, u.role
				, {$this->getTaskCountResponseString()} count_responsible
				, {$this->getTaskCountVisibilityString()} count_visible
				, {$this->getTaskResponseString($user_fields)} responsible
				, {$this->getTaskVisibilityString($user_fields)} visible
				FROM `$this->tableName` t
				INNER JOIN `user` u ON (u.id = t.user_id)
				INNER JOIN `admin_tasks_visibility` tv ON (tv.task_id = t.id AND (tv.user_id = '$user_id' OR tv.user_id = 0))
				WHERE t.id = '$task_id'
				LIMIT 1
			")->current();
	}

	public function getTasks($user_id, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_order' => 'priority', 'up' => 0]);

		$only_active = (isset($options['only_active'])) ? $options['only_active'] : 1;
		$only_active_string = ($only_active && !isset($filters['status']))? " AND `status` = '".self::STATUS_ACTIVE."' " : '';

		$user_fields = $this->generateJsonFields(array('u.id' => 'user_id', 'login','name', 'surname', 'role'), '', 5);

		$my_task_join = '';
		$and = '';
		foreach ($filters as $filter => $value) {
			if($value == '' || $value == null || $value == 0 || $value == false) next($filters);

			if ($filter == 'status') {
				if($value) $and = ' AND ';
				$value = $this->getTasksStatusList(1)[$value];
				$and .= " `status` = '$value'";
			} 
			else if ($filter == 'my_tasks') {
				$my_task_join = " INNER JOIN `admin_tasks_response` tr ON (t.id = tr.task_id AND tr.user_id = $user_id) ";
			}
			else if ($filter == 'responsible') {
				$my_task_join = " INNER JOIN `admin_tasks_response` tr ON (t.id = tr.task_id AND tr.user_id = $value) ";
			}
		}

		$select = "SELECT t.*
				, u.login, u.name, u.surname, u.full_name, u.avatar, u.cv_avatar, u.role
				, tau.login act_login, tau.name act_name, tau.surname act_surname, tau.full_name act_full_name, tau.avatar act_avatar, tau.cv_avatar act_cv_avatar
				, ta.text act_text, ta.time act_time
				, {$this->getTaskCountResponseString()} count_responsible
				, {$this->getTaskCountVisibilityString()} count_visible
				, {$this->getTaskResponseString($user_fields)} responsible
				, {$this->getTaskVisibilityString($user_fields)} visible
				FROM `$this->tableName` t
				INNER JOIN `admin_tasks_visibility` tv ON (tv.task_id = t.id)
				INNER JOIN `user` u ON (u.id = t.user_id)
				LEFT JOIN (
				           SELECT  task_id, MAX(time) MaxDate
				           FROM    `admin_tasks_activity`
				           GROUP BY task_id
				           ORDER BY MaxDate DESC
				       ) MaxDates ON t.id = MaxDates.task_id 
				LEFT JOIN (
					SELECT task_id, user_id, text, time
					FROM `admin_tasks_activity`
					ORDER BY time DESC
					) ta ON ( MaxDates.task_id = ta.task_id AND MaxDates.MaxDate = ta.time)
				LEFT JOIN `user` tau ON (tau.id = ta.user_id)
				$my_task_join
				WHERE (tv.user_id = '$user_id' OR tv.user_id = 0)
				$and
				$only_active_string
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

	public function getTaskActivity($task_id)
	{
		return $this->query(
			"SELECT ta.*, u.login, u.name, u.surname, u.full_name, u.avatar, u.cv_avatar, u.role
				FROM `admin_tasks_activity` ta
				LEFT JOIN `user` u ON (u.id = ta.user_id)
				WHERE ta.task_id = '$task_id'
				ORDER BY ta.time DESC
			");
	}

	public function addTask($user_id, $task_data, $responsible, $visible)
	{
		$time = time();
		$result = $this->query(
			"  INSERT INTO `admin_tasks`
				(`user_id`, `title`, `text`, `status`, `priority`, `due_date`, `time`) 
				VALUES 
				('$user_id','{$task_data['title']}','{$task_data['text']}','{$task_data['status']}','{$task_data['priority']}','{$task_data['due_date']}','$time')

			");
		if(!$result) throw new \Application\Exception\Exception("Error during adding task into db", 1);
		$task_id = $result->getGeneratedValue();

		$resp_string = '';
		$c = 0;
		foreach ($responsible as $key => $value) {
			if($value && $key) {
				if($c > 0) $resp_string .= ', ';
				$resp_string .= "('$task_id', '".$key."')";
				$c++;
			}
		}
		if($resp_string) {
			$this->query(
				"  INSERT INTO `admin_tasks_response`
					(`task_id`, `user_id`) 
					VALUES 
					$resp_string
				");
		}

		$vis_string = '';
		$c = 0;
		foreach ($visible as $key => $value) {
			if($value && $key && $key != 'everybody') {
				if($c > 0) $vis_string .= ', ';
				$vis_string .= "('$task_id', '".$key."')";
				$c++;
			} 
		}
		
		if($vis_string == '') $vis_string = "('$task_id', '0')";
		else $vis_string .= ", ('$task_id', '$user_id')";

		$this->query(
			"  INSERT INTO `admin_tasks_visibility`
				(`task_id`, `user_id`) 
				VALUES 
				$vis_string
			");

		return $task_id;

	}

	public function deleteTask($task_id)
	{
		return $this->query(
			"DELETE t.* FROM `admin_tasks` t WHERE t.id = '$task_id';
			DELETE t.* FROM `admin_tasks_activity` t WHERE t.task_id = '$task_id';
			DELETE t.* FROM `admin_tasks_visibility` t WHERE t.task_id = '$task_id';
			DELETE t.* FROM `admin_tasks_response` t WHERE t.task_id = '$task_id';
			"
			);
	}

	public function addTaskActivity($task_id, $user_id, $activity)
	{
		if ($activity['text'] != '') {
			$time = time();
			$this->query(
				"INSERT INTO `admin_tasks_activity`
					(`task_id`, `user_id`, `text`, `time`) 
					VALUES 
					('$task_id','$user_id','{$activity['text']}','$time')

				");
		}

		$this->query(
			"UPDATE `admin_tasks` 
				SET `status`='{$activity['status']}' 
				WHERE `id` = '$task_id'
			");
		return true;
	}

	protected function getTaskResponseString($user_fields)
	{
		return "CAST((SELECT $user_fields
						FROM `user` u
						INNER JOIN `admin_tasks_response` tr ON (u.id = tr.user_id)
						WHERE tr.task_id = t.id
					) as char(5000))";
	}

	protected function getTaskCountResponseString()
	{
		return "(SELECT COUNT(*) FROM `admin_tasks_response` tr WHERE tr.task_id = t.id ) ";;
	}

	protected function getTaskCountVisibilityString()
	{
		return "(SELECT COUNT(*) FROM `admin_tasks_visibility` tv WHERE tv.task_id = t.id ) ";
	}


	protected function getTaskVisibilityString($user_fields)
	{
		return "CAST((SELECT $user_fields
						FROM `user` u
						INNER JOIN `admin_tasks_visibility` tv ON (u.id = tv.user_id)
						WHERE tv.task_id = t.id
					) as char(5000))";
	}

}