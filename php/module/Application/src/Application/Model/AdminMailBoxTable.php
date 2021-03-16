<?php
namespace Application\Model;

class AdminMailBoxTable extends zAbstractTable
{

	public function __construct()
	{
		parent::__construct('admin_mail_box');
	}


	public function updateMailBox(array $mail)
	{
		try {
			if(isset($mail['unique_id']) && $mail['unique_id']) {
				if($mail['flag_seen'] == false) {
					$user_id = null;
					$subject = (isset($mail['subject']))? $mail['subject'] : null;
					// request to usertable in cycle can slow down performance
					// $userTable = $this->sl()->get('UserTable');
					// if(isset($mail['from_mail'])) {
					// 	$user_id = $userTable->getIdByField('email', $mail['from_mail']);
					// 	$mail['owner_id'] = $user_id;
					// }
					$this->sl()->get('AdminNotifTable')->addMailNotification($mail['unique_id'], 'new_mail', $user_id, $subject);
				}
				$this->save($mail);
			}
		} catch (\Exception $e) {
			$mail['raw_content'] = '';
			if(isset($mail['unique_id']) && $mail['unique_id']) $this->save($mail);
		}
		return true;
	}

	public function getMails($viewer_id = null, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 200, '_order' => 'time']);
		$fields = (isset($options['_fields'])) ? $options['_fields'] : null;
		$join_users = (isset($options['join_users'])) ? $options['join_users'] : 1;
		if($fields && is_array($fields)) {
			$fields_string = $this->arrayToFields($fields, 'mb.');
		} else $fields_string = 'mb.*';

		$where = '';
		foreach ($filters as $filter => $value) {
			if(!$value) next($filters);
			if($value) $where .= ($where == '') ? ' WHERE ' : ' AND ';

			if ($filter == 'unique_id') {
				$where .= " `mb`.unique_id = '$value' ";
			} else if ($filter == 'mail_box') {
				$where .= " `mb`.mail_box = '$value' ";
			} else if($filter == 'folder') {
				$where .= " `mb`.folder = '$value' ";
			} else if($filter == 'unreaded') {
				$where .= " `mb`.flag_seen != 1 ";
			} else if($filter == 'location') {
				$where .= " `mb`.location = '$value' ";
			} else {
				throw new \Application\Exception\Exception("Filter not recognised", 1);
			}
		}

		if ($this->count) {
			$fields_string = ' COUNT(*) count ';
			$this->order_string = '';
			$this->limit_string = '';
			$this->offset_string = '';
		} else {
			if($join_users) $fields_string .= "   
				, u.avatar, u.cv_avatar, u.name, u.surname, u.full_name, u.login, u.id user_id
			";
		}
		$join_string = '';
		if($join_users) $join_string = "    
			LEFT JOIN `user` u ON (u.email = mb.from_mail)
		";

		$this->query("SET SESSION group_concat_max_len = 1000000;");
		return $this->query(
			"SELECT $fields_string
				FROM `{$this->tableName}` mb
				$join_string
				$where 
				AND `from_mail` != ''
				$this->order_string
				$this->limit_string
				$this->offset_string
			");
	}

	public function getMail($unique_id)
	{
		return $this->query(
				"SELECT mb.*
				, u.avatar, u.cv_avatar, u.name, u.surname, u.full_name, u.login, u.id user_id, u.type
				, au.avatar author_avatar, au.cv_avatar author_cv_avatar, au.name author_name, au.surname author_surname, au.full_name author_full_name, au.login author_login, au.type author_type
				FROM `$this->tableName` mb
				LEFT JOIN `user` u ON (u.email = mb.from_mail)
				LEFT JOIN `user` au ON (au.id = mb.owner_id)
				WHERE mb.`unique_id` = '$unique_id'
				"
				)->current();
	}

	public function getMailsFlow($viewer_id = null, $from_mail, $mail_to, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 20, '_order' => 'time']);
		$current_mail_id = (isset($options['current_mail_id'])) ? $options['current_mail_id'] : null;
		$buffer = (isset($options['buffer'])) ? $options['buffer'] : true;
		$where = '';
		$union_where = '';
		if(count($mail_to) > 0) {
			$k = 0;
			$where .= "WHERE ( mb.from_mail = '$from_mail'";
				foreach ($mail_to as $to) {
					if($from_mail != $to->email) {
						if($k == 0) $where .= " AND ( ";
						if($k == 0) $where .= " mb.mail_to LIKE '%$to->email%' ";
						else $where .= " OR mb.mail_to LIKE '%$to->email%'  ";
						$k++;
					} else {
						// if from_mail and to_mail are same adding some imposible requirement to limit the query
						if($k == 0) $where .= " AND ( ";
						if($k == 0) $where .= " 2 = 1 ";
						else $where .= " 2 = 1  ";
						$k++;
					}
				}
			if($k != 0) $where .= " ) ";
			if($current_mail_id) $where .= " AND mb.unique_id != '$current_mail_id' ";
			$where .= " ) ";
			$k = 0;
			$union_where .= " WHERE ( mb.mail_to LIKE '%$from_mail%' ";
				foreach ($mail_to as $to) {
					if($from_mail != $to->email) {
						if($k == 0) $union_where .= " AND ( ";
						if($k == 0) $union_where .= " mb.from_mail = '$to->email' ";
						else $union_where .= " OR mb.from_mail = '$to->email'  ";
						$k++;
					} else {
						// if from_mail and to_mail are same adding some imposible requirement to limit the query
						if($k == 0) $union_where .= " AND ( ";
						if($k == 0) $union_where .= " 2 = 1 ";
						else $union_where .= " 2 = 1  ";
						$k++;
					}
				}
			if($k != 0) $union_where .= " ) ";
			$union_where .= " ) ";
		} else {
			$where = "WHERE mb.from_mail = '$from_mail' ";
			if($current_mail_id) $where .= " AND mb.unique_id != '$current_mail_id' ";
		}

		$fields_string = "   
				mb.* 
				, u.avatar, u.cv_avatar, u.name, u.surname, u.full_name, u.login, u.id user_id, u.type
				, au.avatar author_avatar, au.cv_avatar author_cv_avatar, au.name author_name, au.surname author_surname, au.full_name author_full_name, au.login author_login, au.type author_type
			";
		$join_string = "    
			LEFT JOIN `user` u ON (u.email = mb.from_mail)
			LEFT JOIN `user` au ON (au.id = mb.owner_id)
		";

		$query = "SELECT $fields_string
				,'incoming' status
				FROM `{$this->tableName}` mb
				$join_string
				$where 
			";
		if(count($mail_to) > 0) {
			$query .= "UNION 
						SELECT $fields_string
						,'outgoing' status
						FROM `{$this->tableName}` mb
						$join_string
						$union_where 
				";
		} 

		if($this->count){
			$query = "
				SELECT COUNT(*) count
				FROM (
					$query
					) x
				";
		} else {
			$query = "
			$query
			$this->order_string
			$this->limit_string $this->offset_string
			";
		}
		$result = $this->query($query);
		return ($buffer)? $result->buffer() : $result;
	}


	public function saveSentMail($unique_id, 
									$owner_id, 
									$from_mail, 
									$from_name, 
									$mail_to, 
									$mail_cc = null, 
									$subject, 
									$text, 
									$attachments = null, 
									$other = null)
	{
		if(is_array($mail_to)) {
			foreach ($mail_to as $to) {
				$to_json[]['email'] = $to;
			}
		} else $to_json[]['email'] = $mail_to;

		if(is_array($mail_cc)) {
			foreach ($mail_cc as $cc) {
				$cc_json[]['email'] = $cc;
			}
		} else if($mail_cc) {
			$cc_json[]['email'] = $mail_cc;
		} else $cc_json = null;

		$to_json = json_encode($to_json);
		$cc_json = json_encode($cc_json);
		$mail['unique_id'] = $unique_id;
		$mail['generated_id'] = $unique_id;
		$mail['message_id'] = '';
		$mail['owner_id'] = $owner_id;
		$mail['message_number'] = '';
		$mail['mail_box'] = $from_mail;
		$mail['folder'] = 'Sent';
		$mail['from_mail'] = $from_mail;
		$mail['from_name'] = $from_name;
		$mail['mail_to'] = $to_json;
		$mail['mail_cc'] =  $cc_json;
		$mail['subject'] = $subject;
		$mail['text'] = $text;
		if($attachments) $mail['attachments'] = json_encode($attachments);
		if($other) $mail['other'] = $other;
		$mail['location'] = 'db';
		$mail['flag_seen'] = true;
		$mail['flag_recent'] = false;
		$mail['flag_answered'] = true;
		$mail['time'] = time();
		return $this->save($mail);
	}

	public function toFolder($mail, $folder_local_name, $folder_global_name)
	{
		$update = [
		'folder' => $folder_local_name, 
		'folder_full' => $folder_global_name,
		'flag_seen' => 1
		];

		return $this->tableGateway->update(
											$update, 
											['unique_id' => $mail->unique_id]
											);
	}

	public function setReaded($mail)
	{
		return $this->tableGateway->update(
											array('flag_seen' => 1), 
											array('unique_id' => $mail->unique_id)
											);
	}

	public function deleteMail($mail)
	{
		$attachments = json_decode($mail->attachments);
		if(count($attachments) > 0) {
			$uploadsTable = $this->sl->get('UploadsTable');
			foreach ($attachments as $attachment) {
				$user_id = $uploadsTable->getUploadUser($mail->generated_id.'/'.$attachment);
				$uploadsTable->deleteUpload($mail->generated_id.'/'.$attachment, 'mail_attachment');
			}
			if(is_dir(_MAILSROOT_.$mail->generated_id)) rmdir(_MAILSROOT_.$mail->generated_id);
		}
		$this->deleteOnFields(array('unique_id' => $mail->unique_id));
		return true;
	}

	// for fake mailer
	public function getMailBoxFolders($mail_box)
	{
		$f_folders = [];
		$folders =  $this->query(
				"SELECT n.folder
				FROM `$this->tableName` n
				WHERE n.mail_box = '$mail_box'
				GROUP BY n.folder
				"
				)->toArray();

		foreach ($folders as $value) {
			$f_folders[] = new \Application\zLibrary\Afakefolder($value['folder']);
		}
		return $f_folders;
	}

}