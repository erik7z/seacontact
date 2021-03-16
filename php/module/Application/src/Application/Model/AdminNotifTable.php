<?php
namespace Application\Model;

class AdminNotifTable extends zAbstractTable
{
	const NOT_SECTION_USER = 'user';
	const NOT_SECTION_CV = 'cv';
	const NOT_SECTION_COMPANY = 'company';
	const NOT_TYPE_COMPANY_NOTE = 'company_note';
	const NOT_TYPE_ADMIN_NOTE = 'admin_note';

	const NOT_SECTION_QUESTION = 'question';
	const NOT_SECTION_VACANCY = 'vacancy';
	const NOT_SECTION_MAIL = 'mail';
	const NOT_SECTION_CREWING = 'crewing';
	const NOT_SECTION_ADMIN_TASK = 'admin_task';

	const NOT_TYPE_NEW_SUBSCRIBER = 'new_subscriber';
	const NOT_TYPE_NEW_TASK = 'new_task';
	const NOT_TYPE_TASK_ACTIVITY = 'task_activity';
	const NOT_TYPE_TASK_CLOSED = 'task_closed';

	public function __construct()
	{
		$this->init('admin_notif');
	}


	// returns some CONSTANT
	protected function con($const)
	{
		return constant($const);
	}


	public function addCvUploadedNotification($user_id)
	{
		$time = time();
		$this->query(
			"INSERT INTO $this->tableName (not_type, not_section, not_section_id, user_id, not_message, time)
				VALUES ('cv_uploaded', 'cv', '$user_id', '$user_id', 'New Cv File Uploaded !', '$time')
			"
			);
	}


	public function addCvEditNotification($user_id)
	{
		$time = time();
		$last_15_mins = $time - 900;
		$result = $this->query(
			"INSERT INTO $this->tableName (not_type, not_section, not_section_id, user_id, not_message, time)
				SELECT * FROM (SELECT 'cv_edit', 'cv', '$user_id' not_section_id, '$user_id' user_id, 'User edited his CV', '$time') as tmp
				WHERE NOT EXISTS (
				    SELECT id FROM $this->tableName
				    WHERE not_type = 'cv_edit'
				    AND not_section_id = '$user_id'
				    AND user_id = '$user_id'
				    AND time > $last_15_mins
				) LIMIT 1
			"
			);
	}

	public function addVacancySubNotification($vacancy_id, $user_id)
	{
		$time = time();
		$result = $this->query(
			"INSERT INTO $this->tableName (not_type, not_section, not_section_id, user_id, not_message, time)
				SELECT * FROM (SELECT 'subscribe_vacancy', 'vacancy', '$vacancy_id', '$user_id', 'Subscribed To Vacancy', '$time') as tmp
				WHERE NOT EXISTS (
				    SELECT id FROM $this->tableName
				    WHERE not_type = 'subscribe_vacancy'
				    AND not_section_id = '$vacancy_id'
				    AND user_id = '$user_id'
				) LIMIT 1

			"
			);
	}


	public function addNewVacancyNotification($vacancy_id, $user_id)
	{
		$time = time();
		$result = $this->query(
			"INSERT INTO $this->tableName (not_type, not_section, not_section_id, user_id, not_message, time)
				SELECT * FROM (SELECT 'new_vacancy', 'vacancy', '$vacancy_id', '$user_id', 'New Vacancy Added !', '$time') as tmp
				WHERE NOT EXISTS (
				    SELECT id FROM $this->tableName
				    WHERE not_type = 'new_vacancy'
				    AND not_section_id = '$vacancy_id'
				    AND user_id = '$user_id'
				) LIMIT 1

			"
			);
	}

	public function addCompanyUnlockedUserNotification($company_id, $user_id)
	{
		$time = time();
		$result = $this->query(
			"INSERT INTO $this->tableName (not_type, not_section, not_section_id, user_id, not_message, time)
				SELECT * FROM (SELECT 'add_company_user', 'company', '$company_id', '$user_id', 'Company Added User To Db', '$time') as tmp
				WHERE NOT EXISTS (
				    SELECT id FROM $this->tableName
				    WHERE not_type = 'add_company_user'
				    AND not_section_id = '$company_id'
				    AND user_id = '$user_id'
				) LIMIT 1

			"
			);
	}
	public function addMailNotification($unique_id, $mail_box, $user_id = null, $subject = null)
	{
		$subject = ($subject)? $subject : 'New Mail received';
		$time = time();
		$result = $this->query(
			"INSERT INTO $this->tableName (not_type, not_section, not_section_id, user_id, not_message, time)
				SELECT * FROM (SELECT '$mail_box', 'mail', '$unique_id', '$user_id', '$subject', '$time') as tmp
				WHERE NOT EXISTS (
				    SELECT id FROM $this->tableName WHERE not_type = 'new_mail' AND not_section_id = '$unique_id'
				) LIMIT 1

			"
			);
	}


	public function addNotification($type, $section, $section_id, $user_id, $message)
	{
		return $this->insert(array(
							'not_type' => $type,
							'not_section' => $section,
							'not_section_id' => $section_id,
							'user_id' => $user_id,
							'not_message' => $message,
							'time' => time()
							));
	}

	public function getNotifications($user_id)
	{
		$last_3_days = time() - 259200;

		$query = "SELECT n.*
		,
		n.not_section_id user_id,
		u.name,
		u.surname,
		u.full_name,
		u.login
		FROM $this->tableName n
		LEFT JOIN admin_notif_readed nr ON (n.id = nr.not_id AND nr.user_id = $user_id)
		LEFT JOIN user u ON (u.id = n.not_section_id)
		WHERE n.not_section = '{$this->con('self::NOT_SECTION_USER')}'
		AND nr.not_id IS NULL
		AND n.time > $last_3_days
		GROUP BY n.not_section_id
		-- UNION

		-- SELECT n.*
		-- ,
		-- n.not_section_id user_id,
		-- u.name name,
		-- u.surname surname,
		-- u.full_name full_name,
		-- u.login,
		-- u.company_name,
		-- NULL title,
		-- NULL rank,
		-- NULL salary,
		-- NULL ship_type,
		-- NULL unique_id,
		-- NULL from_mail,
		-- NULL from_name,
		-- NULL subject,
		-- NULL mail_box
		-- FROM $this->tableName n
		-- LEFT JOIN admin_notif_readed nr ON (n.id = nr.not_id AND nr.user_id = $user_id)
		-- LEFT JOIN user u ON (u.id = n.not_section_id)
		-- WHERE n.not_section = '{$this->con('self::NOT_SECTION_COMPANY')}'
		-- AND nr.not_id IS NULL
		-- AND n.time > $last_3_days

		-- UNION

		-- SELECT n.*
		-- ,
		-- n.user_id user_id,
		-- u.name,
		-- u.surname,
		-- u.full_name,
		-- u.login,
		-- NULL company_name,
		-- v.title,
		-- v.rank,
		-- v.salary,
		-- v.ship_type,
		-- NULL unique_id,
		-- NULL from_mail,
		-- NULL from_name,
		-- NULL subject,
		-- NULL mail_box
		-- FROM $this->tableName n
		-- LEFT JOIN admin_notif_readed nr ON (n.id = nr.not_id AND nr.user_id = $user_id)
		-- LEFT JOIN user u ON (u.id = n.user_id)
		-- LEFT JOIN vacancies v ON (v.id = n.not_section_id)
		-- WHERE n.not_section = '{$this->con('self::NOT_SECTION_CREWING')}'
		-- AND nr.not_id IS NULL
		-- AND n.time > $last_3_days

		-- UNION

		-- SELECT n.*
		-- ,
		-- n.not_section_id user_id,
		-- u.name,
		-- u.surname,
		-- u.full_name,
		-- u.login,
		-- NULL company_name,
		-- NULL title,
		-- NULL rank,
		-- NULL salary,
		-- NULL ship_type,
		-- NULL unique_id,
		-- NULL from_mail,
		-- NULL from_name,
		-- NULL subject,
		-- NULL mail_box
		-- FROM $this->tableName n
		-- LEFT JOIN admin_notif_readed nr ON (n.id = nr.not_id AND nr.user_id = $user_id)
		-- LEFT JOIN user u ON (u.id = n.not_section_id)
		-- WHERE n.not_section = '{$this->con('self::NOT_SECTION_CV')}'
		-- AND nr.not_id IS NULL
		-- AND n.time > $last_3_days
		-- GROUP BY n.not_section_id

		-- UNION

		-- SELECT n.*
		-- ,
		-- n.user_id user_id,
		-- NULL name,
		-- NULL surname,
		-- NULL full_name,
		-- NULL login,
		-- NULL company_name,
		-- v.title,
		-- v.rank,
		-- v.salary,
		-- v.ship_type,
		-- NULL unique_id,
		-- NULL from_mail,
		-- NULL from_name,
		-- NULL subject,
		-- NULL mail_box
		-- FROM $this->tableName n
		-- LEFT JOIN admin_notif_readed nr ON (n.id = nr.not_id AND nr.user_id = $user_id)
		-- LEFT JOIN vacancies v ON (v.id = n.not_section_id)
		-- WHERE n.not_section = '{$this->con('self::NOT_SECTION_VACANCY')}'
		-- AND nr.not_id IS NULL
		-- AND n.time > $last_3_days

		-- UNION

		-- SELECT n.*
		-- ,
		-- n.user_id user_id,
		-- NULL name,
		-- NULL surname,
		-- NULL full_name,
		-- NULL login,
		-- NULL company_name,
		-- t.title,
		-- NULL rank,
		-- NULL salary,
		-- NULL ship_type,
		-- NULL unique_id,
		-- NULL from_mail,
		-- NULL from_name,
		-- NULL subject,
		-- NULL mail_box
		-- FROM $this->tableName n
		-- LEFT JOIN admin_notif_readed nr ON (n.id = nr.not_id AND nr.user_id = $user_id)
		-- LEFT JOIN `admin_tasks` t ON (t.id = n.not_section_id)
		-- WHERE n.not_section = '{$this->con('self::NOT_SECTION_ADMIN_TASK')}'
		-- AND nr.not_id IS NULL
		-- AND n.time > $last_3_days

		-- UNION

		-- SELECT n.*
		-- ,
		-- n.user_id user_id,
		-- u.name,
		-- u.surname,
		-- u.full_name,
		-- u.login,
		-- u.company_name,
		-- NULL title,
		-- u.desired_rank rank,
		-- u.minimum_salary salary,
		-- NULL ship_type,
		-- mb.unique_id,
		-- mb.from_mail,
		-- mb.from_name,
		-- mb.subject,
		-- mb.mail_box
		-- FROM $this->tableName n
		-- LEFT JOIN admin_notif_readed nr ON (n.id = nr.not_id AND nr.user_id = $user_id)
		-- LEFT JOIN admin_mail_box mb ON (mb.unique_id = n.not_section_id)
		-- LEFT JOIN user u ON (u.id = n.user_id)
		-- WHERE n.not_section = '{$this->con('self::NOT_SECTION_MAIL')}'
		-- AND nr.not_id IS NULL
		-- AND n.time > $last_3_days

		ORDER BY time DESC
		LIMIT 1000
		";

		return $this->query($query);
	}

}