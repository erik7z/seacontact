<?php
namespace Application\Model;
use Application\Access\AccessList;

class FUsersActivityTable extends zEmptyTable
{
	CONST ACT_TYPE_ONLINE = 'online';
	CONST ACT_TYPE_LIKE = 'like';
	CONST ACT_TYPE_MAIL = 'mail';
	CONST ACT_TYPE_VOTES = 'vote';
	CONST ACT_TYPE_VIEWS = 'view';


	public function __construct()
	{
		$this->init('f_users_activity');
	}

	// returns some CONSTANT
	protected function con($const)
	{
		return constant($const);
	}

	public function getFUsers($viewer_id = null, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 20, '_order' => 'last_activity', 
			'_fields' => ['u.last_activity', 'u.id', 'u.id' =>'user_id', 'u.name', 'u.surname', 'u.full_name','u.company_name', 'u.login', 'u.avatar', 'u.cv_avatar', 'u.type', 'u.role']
			]);
		$act_start_date = isset($options['act_start_date'])? $options['act_start_date'] : time() - _F_USER_ACT_PERIOD_; 

		$where = " WHERE u.role = 'sc_user' OR u.role = 'sc_company' ";

		foreach ($filters as $filter => $value) {
			if(!$value) next($filters);
			if($value) $where = ' WHERE ';

			if ($filter == 'role') {
				$where .= " `u`.role = '$value' ";
			} else throw new \Application\Exception\Exception("Filter not recognized", 1);
			

		}

		if($this->count) {
			$select = " COUNT(*) count";
			$fields_string = '';
			$this->limit_string = '';
			$this->offset_string = '';
			$this->order_string = '';
		} else {
			$select = ($this->json_fields)? $this->generateJsonFields($this->fields, '', $this->limit) : $this->arrayToFields($this->fields);
			$fields_string = " 
			, IF ( fuo.user_id , true, false) online
			, ( SELECT SUM(act_value) FROM `f_users_activity` fua 
				WHERE fua.act_type = '{$this->con('Application\Model\FUsersActivityTable::ACT_TYPE_ONLINE')}' 
				AND fua.`act_time` > $act_start_date 
				AND fua.user_id = u.id
				) period_online
			, @p1:= ( SELECT COUNT(*) FROM `f_users_activity` fua 
				WHERE fua.act_type = '{$this->con('Application\Model\FUsersActivityTable::ACT_TYPE_LIKE')}' 
				AND fua.`act_time` > $act_start_date 
				AND fua.user_id = u.id
				) period_likes
			, @p2:= ( SELECT COUNT(*) FROM `f_users_activity` fua 
				WHERE fua.act_type = '{$this->con('Application\Model\FUsersActivityTable::ACT_TYPE_VOTES')}' 
				AND fua.`act_time` > $act_start_date 
				AND fua.user_id = u.id
				) period_votes
			, @p3:= ( SELECT COUNT(*) FROM `f_users_activity` fua 
				WHERE fua.act_type = '{$this->con('Application\Model\FUsersActivityTable::ACT_TYPE_VIEWS')}' 
				AND fua.`act_time` > $act_start_date 
				AND fua.user_id = u.id
				) period_views
			, @p3:= ( SELECT COUNT(*) FROM `f_users_activity` fua 
				WHERE fua.act_type = '{$this->con('Application\Model\FUsersActivityTable::ACT_TYPE_MAIL')}' 
				AND fua.`act_time` > $act_start_date 
				AND fua.user_id = u.id
				) period_mails
			, (@p1 + @p2 + @p3) period_activity
			, ( SELECT SUM(act_value) FROM `f_users_activity` fua 
				WHERE fua.act_type = '{$this->con('Application\Model\FUsersActivityTable::ACT_TYPE_ONLINE')}' 
				AND fua.user_id = u.id
				) total_online
			, @t1:= ( SELECT COUNT(*) FROM `f_users_activity` fua 
				WHERE fua.act_type = '{$this->con('Application\Model\FUsersActivityTable::ACT_TYPE_LIKE')}' 
				AND fua.user_id = u.id
				) total_likes
			, @t2:= ( SELECT COUNT(*) FROM `f_users_activity` fua 
				WHERE fua.act_type = '{$this->con('Application\Model\FUsersActivityTable::ACT_TYPE_VOTES')}' 
				AND fua.user_id = u.id
				) total_votes
			, @t3:= ( SELECT COUNT(*) FROM `f_users_activity` fua 
				WHERE fua.act_type = '{$this->con('Application\Model\FUsersActivityTable::ACT_TYPE_VIEWS')}' 
				AND fua.user_id = u.id
				) total_views
			, @t3:= ( SELECT COUNT(*) FROM `f_users_activity` fua 
				WHERE fua.act_type = '{$this->con('Application\Model\FUsersActivityTable::ACT_TYPE_MAIL')}' 
				AND fua.user_id = u.id
				) total_mails
			, (@t1 + @t2 + @t3) total_activity
			, ( SELECT COUNT(*) FROM `user_messages` um 
				WHERE to_id = u.id
				) total_messages
			, ( SELECT COUNT(*) FROM `user_messages` um 
				WHERE um.to_id = u.id AND um.`readed` is NULL
				) new_messages

			";
		}

		// force to write limit string, even if limit 0	
		// $this->query("SET @p1=0, @p2=0, 0;");
		return $this->query(
			"SELECT $select
				$fields_string
				FROM `user` u
				LEFT JOIN `f_users_online` fuo ON (u.id = fuo.user_id)
				
				$where
			$this->order_string
			$this->limit_string $this->offset_string
			");
	}

	public function getNewFUsers($filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 20, '_order' => 'RAND()', 'up' => 1]);
		$max_activity = isset($options['max_activity'])? $options['max_activity'] : _F_USER_ACT_TOTAL_TIME_;
		$act_start_date = isset($options['act_start_date'])? $options['act_start_date'] : time() - _F_USER_ACT_PERIOD_; // last 3 days
		$role = isset($options['role'])? $options['role'] : AccessList::ROLE_SC_USER; 

		// force to write limit string, even if limit 0	
		$this->limit_string = ' LIMIT '.$this->limit;

		return $this->query(
			"SELECT u.id, u.name, u.surname, u.full_name, u.company_name, u.role
			FROM `user` u
			WHERE u.id NOT IN ( SELECT user_id 
					FROM (	SELECT user_id, SUM(act_value) sum_activity
							FROM `f_users_activity` fua
							WHERE `act_time` > $act_start_date
							GROUP BY user_id 
							HAVING sum_activity > $max_activity
							UNION 
							SELECT user_id, null sum_activity
							FROM `f_users_online`
					) x
				)
			AND u.role = '$role'

			$this->order_string
			$this->limit_string $this->offset_string
			");
	}

}