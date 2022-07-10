<?php
namespace Application\Model;

class UserMessagesTable extends zAbstractTable
{
	public function __construct()
	{
		$this->init('user_messages');
	}

	public function addMessage($from_id, $text, $chat_id = null, $to_id = null)
	{
		if($to_id == null && $chat_id == null) throw new \Application\Exception\Exception("Message should be private or group");
		
		if(!$chat_id) {
			$chat_id = $this->getChatIDBy2Users($from_id, $to_id);
		} 

		$this->tableGateway->insert(array(
			'from_id' => $from_id,
			'to_id'  => $to_id,
			'text' => $text,
			'chat_id' => $chat_id,
			'time' => time()
			));
		return $this->tableGateway->getLastInsertValue();
	}


/*
	Selecting list of last messages from all chats where current user
	sent or received any messages before
	* if last user who sent message, deleted his account, message
	will shown, but user info would be empty
*/
	public function getListOfChats($viewer_id, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 200, '_order' => 'time']);
		$user_fields = isset($options['_user_fields'])? $options['_user_fields'] : ['name', 'surname', 'full_name', 'login', 'avatar', 'cv_avatar', 'type', 'role'];
		$user_fields = array_merge(['id' => 'user_id'], $user_fields);

		$db_fields = isset($options['_message_fields'])? $options['_message_fields'] : $this->getStandartFields();
		$db_fields = array_merge(['id' => 'message_id'], $db_fields);
		$db_fields_fields_string =  $this->arrayToFields($db_fields, 'cm.');

		$where = '';
		foreach ($filters as $filter => $value) {
			if(!$value) next($filters);
			if($value) $where .= ($where == '') ? ' WHERE ' : ' AND ';

			if ($filter == 'name') {
				$en_value = ztranslit($value);
				$ru_value = ztranslit($value, 1);
				$where .= " ( 
					ui2.`name` LIKE '$en_value%' OR ui2.`name` LIKE '$ru_value%'
					OR ui2.`surname` LIKE '$en_value%' OR ui2.`surname` LIKE '$ru_value%' 
					OR ui2.`full_name` LIKE '%$en_value%' OR ui2.`full_name` LIKE '%$ru_value%' 
					OR ui2.`company_name` LIKE '%$en_value%' OR ui2.`company_name` LIKE '%$ru_value%' 
					) ";
			} else if ($filter == 'text') {
				$where .= " `cm`.text LIKE '%$value%' ";
			} else throw new \Application\Exception\Exception("Filter not recognized", 1);
		}

		if ($this->count) {
			$fields_string = 'cm.id';
		} else { 
			$fields_string = "  
				$db_fields_fields_string
				, ui2.id member_id
				, ui.id last_msg_user_id, ui.name last_msg_name, ui.surname last_msg_surname, ui.full_name last_msg_full_name
				, ui.avatar last_msg_avatar, ui.cv_avatar last_msg_avatar, ui.login last_msg_login

				, ui2.name chat_with_name, ui2.surname chat_with_surname, ui2.full_name chat_with_full_name 
				, ui2.avatar chat_with_avatar, ui2.cv_avatar chat_with_cv_avatar, ui2.login chat_with_login
				, ( SELECT COUNT(*)
						FROM user_messages
						WHERE `to_id` = $viewer_id
						AND `readed` IS NULL
						AND `chat_id` = cm.chat_id
						AND `active` = 1
				) unreaded_count
			";
		}

		$select = "SELECT 
			$fields_string
			FROM (
				SELECT * FROM
				user_messages AS cm
				WHERE `chat_id` IN (
					SELECT `chat_id` 
					FROM user_messages
					WHERE
					`to_id` = $viewer_id 
					OR 
					`from_id` = $viewer_id
					)
				AND `active` = 1
			ORDER BY `id` DESC
			) as cm
			LEFT JOIN `user` AS ui ON (ui.id = `from_id`)			
			LEFT JOIN `user` AS ui2 ON (ui2.id = (SELECT DISTINCT IF(from_id = $viewer_id, to_id, from_id) member_id 
															FROM user_messages
															WHERE chat_id = cm.chat_id
															-- AND from_id != $viewer_id
															LIMIT 1))	
			$where		
			GROUP BY `chat_id`
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

	public function getChat($chat_id, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 200, '_order' => 'cm.id', 'up' => 0]);

		$db_fields = isset($options['_message_fields'])? $options['_message_fields'] : $this->getStandartFields();
		$db_fields = array_merge(['id' => 'message_id'], $db_fields);
		$db_fields_fields_string =  $this->arrayToFields($db_fields, 'cm.');

		if ($this->count) {
			$fields_string = ' COUNT(*) count ';
			$this->order_string = '';
			$this->limit_string = '';
			$this->offset_string = '';
		} else { 
			$fields_string = "  
				$db_fields_fields_string 
				, ui.name from_name, ui.surname from_surname, ui.full_name from_full_name
				, ui.avatar from_avatar, ui.cv_avatar from_cv_avatar, ui.login from_login
			";
		}
		
		return $this->query(
			"SELECT 
				$fields_string
				FROM user_messages cm
				INNER JOIN `user` ui ON (ui.id = `from_id`)
				WHERE `chat_id` = $chat_id
				AND `active` = 1
				$this->order_string
				$this->limit_string
				$this->offset_string
			");
	}


	public function getMessagesCount($viewer_id)
	{
		return $this->query(
			"SELECT COUNT(*) count
				FROM user_messages
				WHERE `to_id` = $viewer_id
				OR `from_id` = $viewer_id
				AND `active` = 1
			");
	}


	public function getUnreadedMessages($viewer_id)
	{
		return $this->query(
			"SELECT *
				FROM user_messages
				WHERE `to_id` = $viewer_id
				AND `readed` IS NULL
				AND `active` = 1
			");
	}

	public function setReadedMessages($viewer_id, $chat_id)
	{
		return $this->tableGateway->update(array('readed' => true), array('to_id' => $viewer_id, 'chat_id' => $chat_id));
	}






	public function getLastUserMessageInChat($viewer_id, $chat_id)
	{
		return $this->query(
			"SELECT * 
				FROM user_messages cm
				WHERE `chat_id` = $chat_id
				AND `from_id` = $viewer_id
				AND `active` = 1
				ORDER BY `time` DESC
				LIMIT 1
			");
	}


	public function generateChatID()
	{
		$last = $this->getMaxFieldValue('chat_id');
		return $last + 1;
	}

	public function getChatIDByMsgID($msg_id)
	{
		return $this->getFieldByID($msg_id, 'chat_id');
	}

	public function getChatIDBy2Users($from_id, $to_id)
	{
		$msg_id = ($this->getIDByFields(array('from_id' => $from_id, 'to_id' => $to_id)))? : $this->getIDByFields(array('from_id' => $to_id, 'to_id' => $from_id));
		if(!$msg_id) return $this->generateChatID();
		return $this->getChatIDByMsgID($msg_id);
	}

	public function checkChatIdBy2Users($from_id, $to_id)
	{
		$msg_id = ($this->getIDByFields(array('from_id' => $from_id, 'to_id' => $to_id)))? : $this->getIDByFields(array('from_id' => $to_id, 'to_id' => $from_id));
		return (bool)$msg_id;
	}


	public function getChatMembers($chat_id, $viewer_id = 0)
	{
		return $this->query(
			"SELECT member_id user_id, user.name ,user.surname, user.full_name, user.login
			FROM (
				SELECT DISTINCT IF(from_id = $viewer_id, to_id, from_id) member_id
				FROM user_messages cm
				WHERE chat_id = $chat_id
				) AS cm
			INNER JOIN user ON (cm.member_id = user.id)
			");
	}

	public function isChatMember($viewer_id, $chat_id)
	{
		$result = $this->query(
			"SELECT from_id, to_id 
				FROM user_messages
				WHERE chat_id = $chat_id
				AND (from_id = $viewer_id OR to_id = $viewer_id)
				LIMIT 1
			");
		return (bool)$result->toArray();
	}

	public function isChat($chat_id)
	{
		return (bool)$this->getOnField($chat_id, 'chat_id');
	}

	public function getChatAccess($viewer_id, $chat_id)
	{
		//если не существует такого чата, значит это новый чат
		if (!$this->isChat($chat_id)) return true;
		return $this->isChatMember($viewer_id, $chat_id);
	}

	public function deleteMessage($message_id)
	{
		return $this->delete($message_id);
	}

	public function canDelete($viewer_id, $message_id)
	{
		$message = $this->getFieldsByID($message_id, array('from_id', 'to_id'))->current();
		if($viewer_id == $message->from_id) return true;
		return false;
	}


	public function validateMessage($from_id, $text, $chat_id, $to_id, $time)
	{
		$lastMsg = $this->getLastUserMessageInChat($from_id,$chat_id)->current();
		if(!$lastMsg) return true;
		if($lastMsg['text'] == $text && ($time - $lastMsg['time']) < 100) {
			throw new \Application\Exception\Exception("Too fast!", 1);
		}
		return true;	
	}




	/* selectig with IF */
	// public function getListOfChats($viewer_id, $limit = 10, $offset =0)
	// {
	// 	$res =  $this->query(
	// 		"SELECT cm.*, cm.id message_id,
	// 		ui.name last_msg_name, ui.avatar last_msg_avatar, ui.login last_msg_login,
	// 		ui2.name chat_with_name, ui2.avatar chat_with_avatar, ui2.login chat_with_login
	// 			FROM (
	// 				SELECT * FROM
	// 				user_messages AS cm
	// 				WHERE `chat_id` IN (
	// 					SELECT `chat_id` 
	// 					FROM user_messages
	// 					WHERE
	// 					`to_id` = $viewer_id 
	// 					OR 
	// 					`from_id` = $viewer_id
	// 					) 
	// 			ORDER BY `id` DESC
	// 			) as cm
	// 			LEFT JOIN `user` AS ui ON (ui.id = `from_id`)			
	// 			LEFT JOIN `user` AS ui2 ON (ui2.id = (SELECT DISTINCT IF(from_id = $viewer_id, to_id, from_id)))			
	// 			GROUP BY `chat_id`
	// 			LIMIT $limit OFFSET $offset
	// 		");
	// 	// d($res->toArray());
	// 	return $res;
	// }


	// public function getListOfMessages($viewer_id, $limit = 10, $offset =0)
	// {
	// 	return $this->query(
	// 		"SELECT cm1.*, cm1.id message_id,
	// 		ui.name from_name
	// 		FROM user_messages AS cm1
	// 			INNER JOIN (
	// 				SELECT MAX(`id`) lastmsg
	// 				FROM user_messages AS cm
	// 				WHERE `to_id` = $viewer_id OR `from_id` = $viewer_id
	// 				GROUP BY `chat_id`
	// 			) AS conv 
	// 			ON cm1.id = conv.lastmsg
	// 			INNER JOIN `user` AS ui ON (ui.id = `from_id`)
	// 			ORDER BY `time` DESC
	// 			LIMIT $limit OFFSET $offset
	// 		");
	// }

}