<?php
namespace Application\Model;
use Application\Access\AccessList;

class FUsersOnlineTable extends zEmptyTable
{

	public function __construct()
	{
		$this->init('f_users_online');
	}

	// returns some CONSTANT
	protected function con($const)
	{
		return constant($const);
	}

	public function getFusersOnline($filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_order' => 'time_start']);
		$act_start_date = isset($options['act_start_date'])? $options['act_start_date'] : time() - _F_USER_ACT_PERIOD_; // last 3 days
		$role = isset($options['role'])? $options['role'] : AccessList::ROLE_SC_USER; 
		$user_fields = isset($options['_user_fields'])? $options['_user_fields'] : ['id'];
		$user_fields = array_merge(['id' => 'user_id', 'last_ip', 'last_activity'], $user_fields);
		$user_fields_string =  $this->arrayToFields($user_fields,'u.');
		

		return $this->query(
			"SELECT 
				fuo.*
				, $user_fields_string
				, ( SELECT SUM(act_value) FROM `f_users_activity` fua 
					WHERE fua.act_type = '{$this->con('Application\Model\FUsersActivityTable::ACT_TYPE_ONLINE')}' 
					AND fua.`act_time` > $act_start_date 
					AND fua.user_id = fuo.user_id
					) period_online
				, ( SELECT SUM(act_value) FROM `f_users_activity` fua 
					WHERE fua.act_type = '{$this->con('Application\Model\FUsersActivityTable::ACT_TYPE_LIKE')}' 
					AND fua.`act_time` > $act_start_date 
					AND fua.user_id = fuo.user_id
					) period_likes
				, ( SELECT SUM(act_value) FROM `f_users_activity` fua 
					WHERE fua.act_type = '{$this->con('Application\Model\FUsersActivityTable::ACT_TYPE_VOTES')}' 
					AND fua.`act_time` > $act_start_date 
					AND fua.user_id = fuo.user_id
					) period_votes
				, ( SELECT SUM(act_value) FROM `f_users_activity` fua 
					WHERE fua.act_type = '{$this->con('Application\Model\FUsersActivityTable::ACT_TYPE_VIEWS')}' 
					AND fua.`act_time` > $act_start_date 
					AND fua.user_id = fuo.user_id
					) period_views
				FROM `f_users_online` fuo
				LEFT JOIN `user` u ON (u.id = fuo.user_id)
				WHERE u.role = '$role'
				$this->order_string
				$this->limit_string $this->offset_string
			");
	}

	public function cleanUpTable()
	{
		return $this->query("DELETE FROM `f_users_online` WHERE `user_id` > 0");
	}

}