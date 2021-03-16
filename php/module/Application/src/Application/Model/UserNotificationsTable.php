<?php
namespace Application\Model;

class UserNotificationsTable extends zAbstractTable
{
	const TYPE_LIKE = 'like';
	const TYPE_COMMENT = 'comment';
	const TYPE_ANSWER = 'answer';
	const TYPE_VOTE = 'vote';
	const TYPE_SUBSCRIBER = 'subscriber';

	
	const TYPE_UNLOCK_CV = 'unlock_cv';
	const TYPE_PRIVATE_MESSAGE = 'private_message';
	const TYPE_NEW_CONTACT = 'new_contact';

	
	public function __construct()
	{
		$this->init('user_notif');
	}

	// returns some CONSTANT
	protected function con($const)
	{
		return constant($const);
	}

	public function addNotification($type, $section, $section_id, $from_user_id, $to_user_id, $not_message = NULL, $mail_sent = null, $time = null)
	{
		$time = ($time)? $time : time();
		return $this->insert(array(
							'not_type' => $type, 
							'not_section' => $section,
							'not_section_id' => $section_id,
							'from_user_id' => $from_user_id,
							'to_user_id' => $to_user_id,
							'not_message' => $not_message,
							'mail_sent' => $mail_sent,
							'time' => $time
							));
	}

	public function updateMailNotificationTime($time, $not_type, $not_section, $not_section_id, $from_user_id, $to_user_id)
	{
		$this->query(
			"UPDATE `{$this->tableName}` 
				SET `mail_sent`='{$time}' 
				WHERE `not_type` = '$not_type'
				AND `not_section` = '$not_section'
				AND `not_section_id` = '$not_section_id'
				AND `to_user_id` = '$to_user_id'
				AND `from_user_id` = '$from_user_id'
			");
	}

	public function getLastMailNotificationTime($to_user_id, $not_type = null, $from_user_id = null)
	{
		$user_str = '';
		$not_type_str = '';
		if($from_user_id) $user_str = " AND `from_user_id` = '$from_user_id' ";
		if($not_type) $not_type_str = " AND `not_type` = '$not_type' ";
		$result = $this->query(
			"SELECT `mail_sent`
			FROM {$this->tableName}
			WHERE `to_user_id` = '$to_user_id'
			$not_type_str
			$user_str
			ORDER BY `mail_sent` DESC
			LIMIT 1
			")->current();
		if($result) return $result['mail_sent'];
		else return false;
	}

	public function updateLastView($to_user_id)
	{
		$this->query(
			"UPDATE `{$this->tableName}` 
				SET `readed` = 1 
				WHERE `to_user_id` = '$to_user_id'
				AND `readed` = 0
			");
	}

	public function getNotifications($to_user_id, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 200, '_order' => 'time']);

		$db_fields = isset($options['_notif_fields'])? $options['_notif_fields'] : $this->getStandartFields();
		$db_fields = array_merge(['id' => 'notif_id'], $db_fields);
		$db_fields_fields_string =  $this->arrayToFields($db_fields, 'n.');

		$user_fields = isset($options['_user_fields'])? $options['_user_fields'] : ['name', 'surname', 'full_name', 'company_name', 'login', 'avatar', 'cv_avatar', 'type', 'role'];
		$user_fields = array_merge(['id' => 'user_id'], $user_fields);
		$user_fields =  $this->arrayToFields($user_fields,'u.');

		$user_joins ="  
			INNER JOIN user u ON (u.id = n.from_user_id)
		";
		$to_user_and ="  
			AND n.to_user_id = '$to_user_id'
		";


		$and = '';
		foreach ($filters as $filter => $value) {
			if(!$value) next($filters);
			else $and .= ' AND ';

			if ($filter == 'not_readed') {
				$and .= " `n`.readed = 0 ";
			} else {
				$and = '';
			}

		}

		$select = 	"SELECT $db_fields_fields_string
			, $user_fields
			,v.title
			,v.text
			FROM $this->tableName n
			$user_joins
			INNER JOIN vacancies v ON (v.id = n.not_section_id)
			WHERE n.not_section = '{$this->con('\Application\Model\NewsTable::SECTION_VACANCY')}'
			$and
			$to_user_and

			UNION
			SELECT $db_fields_fields_string
			, $user_fields
			,lb.title
			,lb.text
			FROM $this->tableName n
			$user_joins
			INNER JOIN user_logbook lb ON (lb.id = n.not_section_id)
			WHERE n.not_section = '{$this->con('\Application\Model\NewsTable::SECTION_LOGBOOK')}'
			$and
			$to_user_and

			UNION
			SELECT $db_fields_fields_string
			, $user_fields
			,q.title
			,q.text
			FROM $this->tableName n
			$user_joins
			INNER JOIN questions q ON (q.id = n.not_section_id)
			WHERE n.not_section = '{$this->con('\Application\Model\NewsTable::SECTION_QUESTIONS')}'
			$and
			$to_user_and

			UNION
			SELECT $db_fields_fields_string
			, $user_fields
			,NULL title
			,NULL text
			FROM $this->tableName n
			$user_joins
			WHERE n.not_section = '{$this->con('\Application\Model\NewsTable::SECTION_USER')}'
			$and
			$to_user_and
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
			$this->limit_string 
			$this->offset_string
			";
		}
		if($this->query_only) return $query;
		return $this->query($query);
	}





}