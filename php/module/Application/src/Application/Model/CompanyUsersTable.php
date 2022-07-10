<?php
namespace Application\Model;

class CompanyUsersTable extends zAbstractTable
{


	public function __construct()
	{
		$this->init('company_users');
	}


	public function addUser($company_id, $user_id)
	{
		$time = time();
		return $this->query(
			"INSERT INTO $this->tableName (company_id, user_id, time)
				SELECT * FROM (SELECT '$company_id', '$user_id', '$time') as tmp
				WHERE NOT EXISTS (
				    SELECT id FROM $this->tableName 
				    WHERE company_id = '$company_id' 
				    AND user_id = '$user_id'
				) LIMIT 1
			"
			);
	}

	public function getCompanyUsers($company_id, $viewer_id = 0, $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 100, '_order' => 'cu.`time`']);

		$user_fields = (isset($options['_user_fields'])) ? $options['_user_fields'] : ['name', 'surname','full_name', 'login', 'email', 'avatar', 'cv_avatar', 'desired_rank', 'nationality'];
		$user_fields_string = $this->getUserFields($user_fields);


		$notes_fields_string = '';
		$notes_join = '';
		if(isset($options['show_notes']) && $options['show_notes'] == 1) {
			$notes_company_private = (isset($options['notes_company_private'])) ? $options['notes_company_private'] : false;
			$notes_admin_all = (isset($options['notes_admin_all'])) ? $options['notes_admin_all'] : false;
			$notes_admin_private = (isset($options['notes_admin_private'])) ? $options['notes_admin_private'] : false;
			$only_auto_notes = (isset($options['only_auto_notes'])) ? $options['only_auto_notes'] : false;
			$notes_fields_string = $this->getNotesFieldsString();
			$notes_join = $this->getNotesJoin($notes_company_private, $notes_admin_all, $notes_admin_private, $viewer_id, $only_auto_notes);
		}

		if($this->count) {
			$fields_string = ' COUNT(*) count ';
			$this->order_string = '';
			$this->limit_string = '';
			$this->offset_string = '';
			$notes_join = '';
			$group_by = '';
		} else {
			$fields_string = "
					cu.user_id, cu.time unlocked_time, 
					$user_fields_string
					$notes_fields_string
					";
			$group_by = ' GROUP BY cu.user_id ';
		}


		return $this->query(
			"SELECT $fields_string
				FROM $this->tableName cu
				INNER JOIN `user` u ON (u.id = cu.user_id)
				$notes_join
				WHERE `company_id` = '$company_id'
				$group_by
				$this->order_string
				$this->limit_string $this->offset_string
			"
			);
	}


	public function isCompanyUser($company_id, $user_id)
	{
		$result = $this->query(
			"SELECT id
				FROM $this->tableName
				WHERE `company_id` = '$company_id'
				AND `user_id` = '$user_id'
				LIMIT 1
			"
			);
		return (bool)$result->count();
	}

	public function getCompanyStats($company_id)
	{
		$last_day = time() - 86400;
		$last_week = time() - 604800;
		$last_month = time() - 259200;
		return $this->query(
			"SELECT 
				(SELECT COUNT(user_id)
						FROM $this->tableName
						WHERE `company_id` = '$company_id' 
						AND `time` >= '$last_day'
						) user_add_day,
				(SELECT COUNT(user_id)
						FROM $this->tableName
						WHERE `company_id` = '$company_id' 
						AND `time` >= '$last_week'
						) user_add_week,
				(SELECT COUNT(user_id)
						FROM $this->tableName
						WHERE `company_id` = '$company_id' 
						AND `time` >= '$last_month'
						) user_add_month,
				(SELECT COUNT(user_id)
						FROM $this->tableName
						WHERE `company_id` = '$company_id' 
						) user_add_all,
				(SELECT COUNT(DISTINCT to_id)
						FROM `user_messages`
						WHERE `from_id` = '$company_id'
						AND `time` >= '$last_day'
						) messages_day

			FROM $this->tableName
			LIMIT 1
			"
			)->current();
	}


}