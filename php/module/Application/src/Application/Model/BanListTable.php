<?php
namespace Application\Model;

class BanListTable extends zAbstractTable
{


	public function __construct()
	{
		$this->init('ban_list');
	}


	public function getBannedUsersList($viewer_id, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_order' => 'banned_time', 'up' => 0]);
		$where = '';
		if ($filters) {	
		}

		$select = "SELECT
				b.id ban_id, b.user_id, b.user_ip, b.time banned_time,
				u.login, u.role, u.type, u.name, u.surname, u.full_name, u.avatar, u.cv_avatar, u.last_activity, u.last_access_admin_panel, u.last_ip,
				un.text office_note, un.time office_note_time
				FROM `{$this->tableName}` b
				LEFT JOIN `user` u ON (u.id = b.user_id)
				LEFT JOIN (
				           SELECT  user_id, MAX(time) MaxDate
				           FROM    `user_cv_notes`
				           GROUP BY user_id
				       ) MaxDates ON b.user_id = MaxDates.user_id 
				LEFT JOIN (
					SELECT user_id, text, time
					FROM user_cv_notes
					) un ON ( MaxDates.user_id = un.user_id AND MaxDates.MaxDate = un.time)
				$where
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

	public function isBanned($user_ip = null, $user_id = null)
	{
		if(!$user_ip && !$user_id) throw new \Application\Exception\Exception("user id or user ip should be provided", 1);
		
		$where = '';
		if($user_ip) $where = " `user_ip` = '{$user_ip}'";
		if($user_id) {
			if($where != '') $where .= ' OR ';
			$where .= " `user_id` = '{$user_id}'";
		}
		$result =  $this->query(
			"SELECT id
			FROM {$this->tableName}
			WHERE $where
			LIMIT 1
			")->current();
		return (bool) $result;
	}

	public function ban($user_ip = null, $user_id = null)
	{
		if(!$user_ip && !$user_id) throw new \Application\Exception\Exception("user id or user ip should be provided", 1);
		return $this->insert(array(
								'user_ip' => $user_ip,
								'user_id' => $user_id,
								'time' => time()
								));
	}

	public function unBan($user_ip = null, $user_id = null)
	{
		if(!$user_ip && !$user_id) throw new \Application\Exception\Exception("user id or user ip should be provided", 1);
		$where = '';
		if($user_ip) $where = " `user_ip` = '{$user_ip}'";
		if($user_id) {
			if($where != '') $where .= ' OR ';
			$where = " `user_id` = '{$user_id}'";
		}

		return $this->query(
			"DELETE b.* 
				FROM `{$this->tableName}` b 
				WHERE $where
				");
	}

	public function removeBanId($ban_id)
	{
		if(!$ban_id) throw new \Application\Exception\Exception("ban id should be provided", 1);

		return $this->query(
			"DELETE b.* 
				FROM `{$this->tableName}` b 
				WHERE b.id = '$ban_id'
				");
	}

}