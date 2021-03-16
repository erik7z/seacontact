<?php
namespace Application\Model;

class UserCvNotesTable extends zEmptyTable
{

	const VIS_ADMIN_PRIVATE = 3;
	const VIS_ADMIN_ALL = 2;
	const VIS_COMPANY_PRIVATE = 1;
	const VIS_COMPANY_ALL = 0;

	public function __construct()
	{
		$this->init('user_cv_notes');
	}

	public function saveUserCvNote($user_id, $owner_id, $text, $visibility)
	{
		if(!$user_id || !$owner_id || !$text) throw new \Application\Exception\Exception("More data should be provided in this request", 1);
		
		$cv_note['user_id'] = $user_id;
		$cv_note['owner_id'] = $owner_id;
		$cv_note['text'] = $text;
		$cv_note['visibility'] = $visibility;
		$cv_note['time'] = time();
		return $this->save($cv_note);
	}

	public function getUserCvNotes($user_id, $filters = [], $options = [], $owner_id = null)
	{
		$this->setDefaultOptions($options, ['_limit' => 200, '_order' => 'time']);


		$show_auto_notes = (isset($options['show_auto_notes'])) ? $options['show_auto_notes'] : true;

		$only_admin_all = (isset($filters['only']) && $filters['only'] == 'admin_all') ? true : false;
		$only_admin_private = (isset($filters['only']) && $filters['only'] == 'admin_private') ? true : false;
		$only_company_all = (isset($filters['only']) && $filters['only'] == 'company_all') ? true : false;
		$only_company_private = (isset($filters['only']) && $filters['only'] == 'company_private') ? true : false;
	
		$only = ($only_admin_all || $only_admin_private || $only_company_all || $only_company_private)? true : false;

		$and = " AND ( ";
		if(!$only) $and .= " `visibility` = ".$this::VIS_COMPANY_ALL." ";
		$show_company_notes = (isset($options['show_company_notes'])) ? $options['show_company_notes'] : true;
		$show_company_private_notes = (isset($options['show_company_private_notes'])) ? $options['show_company_private_notes'] : false;
		$show_admin_notes = (isset($options['show_admin_notes'])) ? $options['show_admin_notes'] : false;
		$show_admin_private_notes = (isset($options['show_admin_private_notes'])) ? $options['show_admin_private_notes'] : false;

		$or_string = '';
		if ($show_admin_notes || $show_admin_private_notes || $show_company_notes || $show_company_private_notes) {
			$or = ($only) ? '' : ' OR ';

			// Filter only all companies notes
			if ($show_company_notes && !$only_admin_private && !$only_admin_all && !$only_company_private) 
				$or_string .= $or." `visibility` = ".$this::VIS_COMPANY_ALL." ";

			// Filter only companies private notes
			if ($show_company_private_notes && !$only_admin_all && !$only_company_all && !$only_admin_private) {
				$or_string .= $or;
				$or_string .= " ( ";
				$or_string .= " `visibility` = ".$this::VIS_COMPANY_PRIVATE." ";
				if(!$show_admin_notes && $owner_id) $or_string .= " AND `owner_id` = ".$owner_id." ";
				$or_string .= " ) ";
			}
			// Filter only all admin notes
			if ($show_admin_notes  && !$only_admin_private && !$only_company_all && !$only_company_private) 
				$or_string .= $or." `visibility` = ".$this::VIS_ADMIN_ALL." ";

			// Filter only admin private notes
			if ($show_admin_private_notes && !$only_admin_all && !$only_company_all && !$only_company_private) {
				$or_string .= $or;
				$or_string .= " ( ";
				$or_string .= " `visibility` = ".$this::VIS_ADMIN_PRIVATE." ";
				if($owner_id) $or_string .= " AND `owner_id` = ".$owner_id." ";
				$or_string .= " ) ";
			}



		}
		$and .= $or_string." ) ";

		$manual_notes_select = "SELECT
				'user_note' type,
				NULL section,
				NULL sub_section,
				NULL section_id,
				n.id, n.user_id, n.owner_id, n.text, n.visibility, n.time,
				u.name author_name, u.surname author_surname, u.full_name author_full_name, u.type author_type, u.company_name author_company_name, u.login author_login, u.avatar author_avatar
				FROM `$this->tableName` n
				INNER JOIN `user` u ON (u.`id` = n.`owner_id`)
				WHERE n.`user_id` = '$user_id'
				$and
				";

		$only_auto_notes = (isset($filters['only']) && $filters['only'] == 'auto_notes') ? true : false;
		$union = '';
		$auto_notes_select = '';
		if($show_auto_notes && (!$only || $only_auto_notes)) {	
			$seacontact_id = _COMPANY_ID_;
			
			$auto_notes_and = '';
			if(!$show_admin_notes) $auto_notes_and = "AND ( notif.not_section = 'user' OR notif.not_section = 'company' OR notif.not_section = 'vacancy' OR notif.not_section = 'cv') ";
			$notification_section_crewing = AdminNotifTable::NOT_SECTION_CREWING;
			$auto_notes_select = "
				SELECT
				'notification' type,
				notif.not_section section,
				notif.not_type sub_section,
				notif.not_section_id section_id,
				NULL id, 
				user_id, $seacontact_id owner_id, notif.not_message text, IF(notif.not_section = '$notification_section_crewing', 2, 0) visibility, time,
				u.name author_name, u.surname author_surname, u.full_name author_full_name, u.type author_type, u.company_name author_company_name, u.login author_login, u.avatar author_avatar
				FROM `admin_notif` notif
				INNER JOIN user u ON (u.`id` = $seacontact_id)
				WHERE notif.user_id = '$user_id'
				$auto_notes_and
				AND notif.not_section != 'crewing'
				";							
			if (!$only_auto_notes) $union = ' UNION ';
		}	



		$select = ($only_auto_notes)? $auto_notes_select : $manual_notes_select.$union.$auto_notes_select;

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

	public function getLastUserCvNote($user_id, $owner_id = null, $show_company_private = true, $show_admin_all = true, $show_admin_private = false)
	{
		$and = ' AND ( ';
		$and .= ' `visibility` = '.UserCvNotesTable::VIS_COMPANY_ALL;
		if($show_company_private && $owner_id) {
			$and .= ' OR ( ';
			$and .= " `visibility` = ".UserCvNotesTable::VIS_COMPANY_PRIVATE;
			if(!$show_admin_all) $and .= " AND `owner_id` = $owner_id ";
			$and .= ' ) ';
		}

		$auto_notes_and = '';
		if(!$show_admin_all) {
			$auto_notes_and = "AND ( notif.not_section = 'user' OR notif.not_section = 'company' OR notif.not_section = 'vacancy' OR notif.not_section = 'cv') ";
		}

		if($show_admin_all) {
			$and .= ' OR ';
			$and .= " `visibility` = ".UserCvNotesTable::VIS_ADMIN_ALL;
		}
		if($show_admin_private && $owner_id) {
			$and .= ' OR ( ';
			$and .= " `visibility` = ".UserCvNotesTable::VIS_ADMIN_PRIVATE;
			$and .= " AND `owner_id` = $owner_id ";
			$and .= ' ) ';
		}

		$and .= ' ) ';

		$seacontact_id = _COMPANY_ID_;
		$result = $this->query(
				"SELECT 
				'user_note' type,
				NULL section,
				NULL sub_section,
				NULL section_id,
				n.id, n.user_id, n.owner_id, n.text, n.visibility, n.time,
				u.name author_name, u.surname author_surname, u.full_name author_full_name, u.type author_type, u.company_name author_company_name, u.login author_login, u.avatar author_avatar
				FROM `$this->tableName` n
				INNER JOIN `user` u ON (u.`id` = n.`owner_id`)
				WHERE n.`user_id` = '$user_id'
				$and

				UNION

				SELECT
				'notification' type,
				notif.not_section section,
				notif.not_type sub_section,
				notif.not_section_id section_id,
				NULL id, 
				user_id, $seacontact_id owner_id, notif.not_message text, '0' visibility, time,
				u.name author_name, u.surname author_surname, u.full_name author_full_name, u.type author_type, u.company_name author_company_name, u.login author_login, u.avatar author_avatar
				FROM `admin_notif` notif
				INNER JOIN `user` u ON (u.`id` = $seacontact_id)
				WHERE notif.user_id = '$user_id'
				$auto_notes_and

				ORDER BY `time` DESC
				LIMIT 1
				"
			);
		if($result) return $result->current();
		return false;
	}

}