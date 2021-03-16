<?php

namespace Application\Model;

class UserContactsTable extends zAbstractTable
{

	const STATUS_NULL = NULL;
	const STATUS_PENDING = 0;
	const STATUS_CONFIRMED = 1;
	const STATUS_DENIED = 2;

	const RELATION_COLLEGUE = 'collegues';
	const RELATION_FRIENDS = 'friends';
	const RELATION_RCVD = 'reqrcvd';
	const RELATION_SENT = 'reqsent';
	const RELATION_FOLLOWING = 'following';
	const RELATION_FOLLOWER = 'follower';

	public function __construct()
	{
		$this->init('user_contacts');
	}

	// returns some CONSTANT
	protected function con($const)
	{
		return constant($const);
	}

	public function getStandartFields($use_cache = 1, $all_fields = 0)
	{
		return ['worked_together','worked_on', 'worked_together_from', 'worked_together_to', 'relations'];
	}

	public function getContacts($user_id, $filters = null, $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 50, '_order' => 'pi.id']);

		$user_fields = isset($options['_user_fields'])? $options['_user_fields'] : ['name', 'surname', 'full_name', 'company_name', 'login', 'avatar', 'cv_avatar', 'type', 'role', 'last_activity', 'desired_rank'];
		$user_fields = array_merge(['id' => 'user_id'], $user_fields);
		$user_fields_string =  $this->arrayToFields($user_fields,'pi.');

		$db_fields = isset($options['_contacts_fields'])? $options['_contacts_fields'] : $this->getStandartFields();
		$db_fields = array_flip($db_fields);
		
		if(isset($options['no_limits']) && $options['no_limits'] == 1) {
			$this->limit_string = '';
			$this->offset_string = '';
		}

		$relations = (isset($options['relations'])) ? $options['relations'] : null;

		$where = '';
		if(count($filters)) {
			$year  = (60*60*24*365);
			foreach ($filters as $filter => $value) {
				$where .= ($where == '')? ' WHERE ' : ' AND ';

				if($filter == 'name') {
					$en_value = ztranslit($value);
					$ru_value = ztranslit($value, 1);
					$where .= " ( 
						pi.`name` LIKE '$en_value%' OR pi.`name` LIKE '$ru_value%'
						OR pi.`surname` LIKE '$en_value%' OR pi.`surname` LIKE '$ru_value%' 
						OR pi.`full_name` LIKE '%$en_value%' OR pi.`full_name` LIKE '%$ru_value%' 
						) ";
				} else if($filter == 'ship_name') {
					$where .= " partn_exp.`ship_name` LIKE '%$value%' ";
				} else if($filter == 'ship_type') {
					$where .= " partn_exp.`ship_type` = '$value' ";
				} else if($filter == 'worked_in_psn') {
					$where .= " partn_exp.`rank` = '$value' ";
				} else if($filter == 'desired_rank') {
					$where .= " pi.`desired_rank` = '$value' ";
				} else if($filter == 'nationality') {
					$where .= " pi.`nationality` LIKE '%$value%' ";
				} else if($filter == 'home_city') {
					$where .= " pi.`home_city` LIKE '%$value%' ";
				} else if($filter == 'age_from') {
					$age_from = time() - ($year * $value);
					$where .= " pi.`dob` <= '$age_from' ";
				} else if($filter == 'age_to') {
					$age_to = time() - ($year * $value);
					$where .= " pi.`dob` >= '$age_to'";
				} else if($filter == 'user_id') {
					$where .= " pi.`id` = '$value'";
				} else if($filter == 'online') {
					$latest_time = time() - _ONLINE_TIME_;
					if($value == 'online') $where .= " (`pi`.`last_activity` > {$latest_time}) ";
					else $where .= " (`pi`.`last_activity` < {$latest_time} OR `pi`.`last_activity` is NULL) ";
				} else throw new \Application\Exception\Exception("Filter not recognized", 1);
				
			}
		}

		$fields = "	pi.id user_id, $user_fields_string";
		if(isset($db_fields['worked_together'])) 
			$fields .= ", IF ( (my_exp.date_from BETWEEN partn_exp.date_from AND partn_exp.date_to)
							OR (my_exp.date_to BETWEEN partn_exp.date_from AND partn_exp.date_to)
							OR (partn_exp.date_to BETWEEN my_exp.date_from AND my_exp.date_to)
							OR (partn_exp.date_from BETWEEN my_exp.date_from AND my_exp.date_to)
							, true, false) worked_together";
		if(isset($db_fields['worked_together_from'])) 
			$fields .= ", IF (  my_exp.date_from  > partn_exp.date_from AND my_exp.ship_name IS NOT NULL , my_exp.date_from, partn_exp.date_from) worked_together_from";
		if(isset($db_fields['worked_together_to'])) 
			$fields .= ", IF ( my_exp.date_to < partn_exp.date_to AND my_exp.ship_name IS NOT NULL , my_exp.date_to, partn_exp.date_to) worked_together_to";
		if(isset($db_fields['worked_on'])) 
			$fields .= ", my_exp.ship_name worked_on";
		if(isset($db_fields['relations'])) 
			$fields .= ", (CASE 
						WHEN (in_req.status = '{$this->con('self::STATUS_CONFIRMED')}' OR out_req.status = '{$this->con('self::STATUS_CONFIRMED')}') THEN '{$this->con('self::RELATION_FRIENDS')}' 
						WHEN (in_req.status = '{$this->con('self::STATUS_PENDING')}') THEN '{$this->con('self::RELATION_RCVD')}' 
						WHEN (out_req.status = '{$this->con('self::STATUS_PENDING')}') THEN '{$this->con('self::RELATION_SENT')}' 
						WHEN (out_req.status = '{$this->con('self::STATUS_DENIED')}') THEN '{$this->con('self::RELATION_FOLLOWING')}' 
						WHEN (in_req.status = '{$this->con('self::STATUS_DENIED')}') THEN '{$this->con('self::RELATION_FOLLOWER')}'
						ELSE NULL 
						END
						) relations";

		$partn_exp_join = "  LEFT JOIN `user_experience` partn_exp ON (pi.id = partn_exp.user) ";
		$my_exp_join = " LEFT JOIN `user_experience` my_exp ON (my_exp.ship_name = partn_exp.ship_name AND my_exp.user = '$user_id') ";
		$contacts_join = "
			LEFT JOIN `user_contacts` in_req ON (in_req.r_from = pi.id AND in_req.r_to = '$user_id')
			LEFT JOIN `user_contacts` out_req ON (out_req.r_from = '$user_id' AND out_req.r_to = pi.id)
		";
		$group_by = "GROUP BY pi.id";
		
		$union_friends = 0;
		if($relations){
			
			if($relations == self::RELATION_FRIENDS) {
				$union_friends = 1;
			} else {
				$where .= ($where == '')? ' WHERE ' : ' AND ';
				if($relations == self::RELATION_RCVD) $where .= " (in_req.status = '{$this->con('self::STATUS_PENDING')}') ";
				else if($relations == self::RELATION_SENT) $where .= " (out_req.status = '{$this->con('self::STATUS_PENDING')}') ";
				else if($relations == self::RELATION_FOLLOWING) $where .= " (out_req.status = '{$this->con('self::STATUS_DENIED')}') ";
				else if($relations == self::RELATION_FOLLOWER) $where .= " (in_req.status = '{$this->con('self::STATUS_DENIED')}') ";
				else if($relations == self::RELATION_COLLEGUE) $where .= 
					" ( 
						out_req.status IS NULL AND in_req.status IS NULL
						AND my_exp.ship_name IS NOT NULL  
						AND ( 
								(my_exp.date_from BETWEEN partn_exp.date_from AND partn_exp.date_to)
								OR (my_exp.date_to BETWEEN partn_exp.date_from AND partn_exp.date_to)

								OR (partn_exp.date_to BETWEEN my_exp.date_from AND my_exp.date_to)
								OR (partn_exp.date_from BETWEEN my_exp.date_from AND my_exp.date_to)
							)
						
					) ";
			}

		}
		
		// removing companies and myself from query 
		$where .= ($where == '')? ' WHERE ' : ' AND ';
		$where .= " pi.type != 'company' AND pi.id != '$user_id' ";

		if($this->count) {
			$fields = ' pi.id ';
			$my_exp_join = (strpos($where, 'my_exp') !== false)? $my_exp_join : '';
			$partn_exp_join = (strpos($where, 'partn_exp') !== false || strpos($where, 'my_exp') !== false)? $partn_exp_join : '';
			$contacts_join = (strpos($where, 'status') !== false || $relations)? $contacts_join : '';
		}

		// selecting friends require union
		if($union_friends) {
			
			$where1 = $where;
			$where1 .= ($where1 == '')? ' WHERE ' : ' AND ';
			$where1 .= " in_req.status = '{$this->con('self::STATUS_CONFIRMED')}' ";
			$select = "SELECT $fields
						FROM `user` pi
							$partn_exp_join
							$my_exp_join
							$contacts_join
							$where1
							$group_by
						";
			$where2 = $where;
			$where2 .= ($where2 == '')? ' WHERE ' : ' AND ';
			$where2 .= " out_req.status = '{$this->con('self::STATUS_CONFIRMED')}' ";
			$select .= " 
							UNION 
						";
			$select .= "SELECT $fields
						FROM `user` pi
							$partn_exp_join
							$my_exp_join
							$contacts_join
							$where2
							$group_by
						";
		} else {
			$select = "SELECT $fields
						FROM `user` pi
							$partn_exp_join
							$my_exp_join
							$contacts_join
							$where
							$group_by
							$this->order_string

						";
		}


		if($this->count){
			$select = "SELECT COUNT(*) count FROM ( $select ) c ";
			$this->limit_string = '';
			$this->offset_string = '';
		} 

		$query = "$select
					$this->limit_string
					$this->offset_string
				";
		if($this->query_only) return $query;
		return  $this->query($query);
	}

	public function addFriendship($me, $other_user)
	{
		$time = time();
		return $this->query(
						"INSERT INTO `user_contacts` (r_from, r_to, status, time) 
							SELECT '$me', '$other_user', '{$this->con('self::STATUS_PENDING')}', '$time'
							FROM DUAL 
							WHERE NOT EXISTS (SELECT `r_from`, `r_to` FROM `user_contacts` WHERE r_from = $other_user AND r_to = $me);
							UPDATE `user_contacts` SET status = '{$this->con('self::STATUS_CONFIRMED')}', time = '$time' WHERE r_from = $other_user AND r_to = $me;
							ON DUPLICATE KEY UPDATE status = VALUES(status)
						"
						);
	}

	public function denyFriendship($me, $other_user)
	{
		/*	if my request delete, if not my move to subscribers */
		$time = time();
		return $this->query(
			"
			START TRANSACTION;
				DELETE uc.* FROM `user_contacts` uc 
				WHERE uc.`r_from` = '$me' AND uc.`r_to` = '$other_user'; 

				UPDATE `user_contacts` 
				SET status = '{$this->con('self::STATUS_DENIED')}', time = '$time' 
				WHERE r_from = $other_user AND r_to = $me;
			COMMIT;
			");
	}



	// insert on duplicate entry (not key) update. using 2 keys as primary
	// public function setRelationStatus($r_from, $r_to, $status)
	// {
	// 	$time = time();
	// 	return $this->query(
	// 					"INSERT INTO `user_contacts` (r_from, r_to, status, time) 
	// 						SELECT '$r_from', '$r_to', '$status', '$time'
	// 						FROM DUAL 
	// 						WHERE NOT EXISTS (SELECT `r_from`, `r_to` FROM `user_contacts` WHERE r_from = $r_from AND r_to = $r_to);
	// 						UPDATE `user_contacts` SET status = '$status', time = '$time' WHERE r_from = $r_from AND r_to = $r_to;
	// 					"
	// 					);
	// }






//	Getting new received requests which are not yet accepted as "readed", from not friends


	public function getUserOnlineFriends($user_id, $options = [])
	{
		$this->setDefaultOptions($options, [
			'_limit' => 10, 
			'_fields' => ['u.last_activity', 'u.id', 'user_id', 'u.name', 'u.surname', 'u.full_name', 'u.login', 'u.avatar', 'u.cv_avatar', 'u.type', 'u.role']
			]);

		if($this->count) {
			$select = " COUNT(*) count";
			$this->limit_string = '';
			$this->offset_string = '';
		} else {
			$select = ($this->json_fields)? $this->generateJsonFields($this->fields, '', $this->limit) : $this->arrayToFields($this->fields);
		}
		$latest_time = time() - 900;
		$query = "SELECT 
					$select
					FROM `user_contacts` uc 
					INNER JOIN `user` u ON (u.id = IF(uc.r_from = '$user_id', uc.r_to, uc.r_from)) 
					WHERE uc.status = '{$this->con('self::STATUS_CONFIRMED')}'
					AND (uc.r_from = '$user_id' OR uc.r_to = '$user_id')
					AND (u.last_activity > {$latest_time})

				$this->limit_string
				$this->offset_string
				";
		if($this->query_only) return $query;
		return $this->query($query);
	}

	public function arePartners($user_id, $other_user_id)
	{
		return (bool) $this->query(
				"SELECT partn_exp.ship_name, partn_exp.user
				FROM `user_experience` partn_exp
				INNER JOIN `user_experience` my_exp ON (my_exp.ship_name = partn_exp.ship_name AND my_exp.user = $user_id)
				WHERE partn_exp.user = $other_user_id
				AND ( 
						(my_exp.date_from BETWEEN partn_exp.date_from AND partn_exp.date_to)
						OR (my_exp.date_to BETWEEN partn_exp.date_from AND partn_exp.date_to)

						OR (partn_exp.date_to BETWEEN my_exp.date_from AND my_exp.date_to)
						OR (partn_exp.date_from BETWEEN my_exp.date_from AND my_exp.date_to)
					)
				GROUP BY partn_exp.user
				LIMIT 1
				"
			)->count();
	}


	public function getPartnershipInfo($user_id, $other_user_id)
	{
		if (!$user_id || !$other_user_id) return array();
		return $this->query(
				"SELECT partn_exp.ship_name, partn_exp.ship_type, partn_exp.flag, partn_exp.company, partn_exp.rank, partn_exp.user user_id, 
				IF ( my_exp.date_from > partn_exp.date_from , my_exp.date_from, partn_exp.date_from) worked_together_from,
				IF ( my_exp.date_to < partn_exp.date_to , my_exp.date_to, partn_exp.date_to) worked_together_to,
				partn_userinfo.name, partn_userinfo.surname, partn_userinfo.full_name, partn_userinfo.login, partn_userinfo.avatar, partn_userinfo.cv_avatar

				FROM `user_experience` partn_exp
				INNER JOIN `user_experience` my_exp ON (my_exp.ship_name = partn_exp.ship_name AND my_exp.user = '$user_id')
				LEFT JOIN  `user` partn_userinfo ON (partn_exp.user = partn_userinfo.id)
				WHERE partn_exp.user = '$other_user_id'
				AND ( 
						(my_exp.date_from BETWEEN partn_exp.date_from AND partn_exp.date_to)
						OR (my_exp.date_to BETWEEN partn_exp.date_from AND partn_exp.date_to)

						OR (partn_exp.date_to BETWEEN my_exp.date_from AND my_exp.date_to)
						OR (partn_exp.date_from BETWEEN my_exp.date_from AND my_exp.date_to)
					)
				LIMIT 10
				"
			)->buffer();
	}


	public function areFriends($user_id, $other_user_id)
	{
		return (bool) $this->query(
			"SELECT *
				FROM `user_contacts` uc
				WHERE ( (uc.r_from = $user_id AND uc.r_to = $other_user_id) OR (uc.r_to = $user_id AND uc.r_from = $other_user_id) )
				AND uc.status = '{$this->con('self::STATUS_CONFIRMED')}'
				
			")->count();	
	}

}