<?php
namespace Application\Model;

use Zend\Db\TableGateway\Feature\RowGatewayFeature as RowFeature;

use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\ResultSet\ResultSet;


class UserTable extends zAbstractTable
{

	protected $avatarTable;

	CONST TYPE_USER = 'user';
	CONST TYPE_COMPANY = 'company';
	CONST TYPE_OWNER = 'owner';


	public function __construct()
	{
		$this->init('user', new RowFeature('id'));
	}


 	public static function getUserTypes()
 	{
 		return [
 			self::TYPE_USER => self::TYPE_USER,
 			self::TYPE_COMPANY => self::TYPE_COMPANY,
 		];
 	}

	public function getLatestCvs($limit = 12, $offset = 0)
	{
		return $this->query(
			"SELECT u.id, u.name, u.surname, u.login, u.avatar, u.cv_avatar, u.role, u.full_name, u.desired_rank,
					u.nationality, u.cv_last_update, ue.ship_type, (SELECT COUNT(ue2.id) FROM user_experience ue2 WHERE ue2.user = u.id) contracts
				FROM `user` u
				INNER JOIN `user_experience` ue ON (ue.user = u.id)
				WHERE u.type != 'company'
				AND u.cv_avatar != ''
				GROUP BY u.id
				ORDER BY u.id DESC
				LIMIT $limit
				OFFSET $offset
			"
			);
	}

	public function getDbInfoForSelect($viewer_id = 0, $filters = [], $options = [])
	{
		$home_country_fields = $this->generateJsonFields(['nationality'], '', 200, ' ORDER BY u.nationality ');


		$this->query("SET SESSION group_concat_max_len = 1000000;");
		$home_country_string = "(
					SELECT $home_country_fields
					FROM `user` u
					)";

		$result = $this->query("SELECT
					$home_country_string countries
				");
		d($result->toArray());
	}

	public function getUsersForVkParsing()
	{
		return $this->query(
			"SELECT id, login, name, surname, full_name, email, social_vk,  social_vk_parsed, social_vk_token, social_vk_secret, social_vk_domain
				FROM `$this->tableName` u
				WHERE social_vk_parsing = 1
			");
	}

	public function getStandartFields($use_cache = 1, $all_fields = 0)
	{
		if($all_fields) return parent::getStandartFields($use_cache);
		return ['id','login', 'role', 'type', 'sex', 'avatar', 'cv_avatar', 'name', 'surname', 'full_name','email', 'contact_mobile','contact_mobile_2', 'contact_phone', 'contact_phone_2', 'desired_rank', 'minimum_salary', 'salary_currency', 'visa_usa', 'visa_usa_exp', 'visa_shenghen', 'visa_shenghen_exp', 'dob', 'nationality', 'place_of_residence', 'home_city', 'home_address', 'contact_mobile', 'contact_mobile_2', 'contact_phone', 'contact_phone_2', 'english_knowledge', 'marital_status', 'user_notes', 'last_activity', 'in_db_date', 'reg_date', 'readiness_date', 'company_name', 'company_description', 'company_license', 'social_vk_domain'];
	}

	// public function getStandartFields($use_cache = 1, $all_fields = 0)
	// {
	// 	$fields = parent::getStandartFields();
	// 	$k = array_search('password', $fields); unset($fields[$k]);
	// 	$k = array_search('password_reset_key', $fields); unset($fields[$k]);
	// 	return $fields;
	// }

	public function getVkUsersMap()
	{
		$query = $this->getUsersList(null, ['have_vk_id' => 1], ['_user_fields' => ['id', 'social_vk', 'login', 'social_vk_domain'], 'query_only' => 1, '_limit' => 0]);
		$dump_name = z_generateNameFromMethod(get_class($this).'::'.__FUNCTION__, 0);
		$vk_users = $this->getCachedRequest($query, $dump_name, '');
		$tmp = [];
		foreach ($vk_users as $user) {
			$tmp[$user['social_vk']]['id'] = $user['id'];
			$tmp[$user['social_vk']]['login'] = ($user['login'])? $user['login'] : 'id'.$user['id'];
			$tmp[$user['social_vk']]['vk_domain'] = ($user['social_vk_domain'])? $user['social_vk_domain'] : 'id'.$user['social_vk'];
		}
		return $tmp;
	}


	public function getUserInfoByVkToken($vk_token)
	{
		return $this->getUsersList(null, ['social_vk_token' => $vk_token], [
			'_user_fields' => [
				'id', 'social_vk', 'social_vk_token', 'social_vk_secret', 'email'
				],
			'_limit' => 1
			])->current();
	}


	public function getUsersList($viewer_id = null, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 50, '_order' => '`u`.id']);

		$db_fields = (isset($options['_user_fields'])) ? $options['_user_fields'] : $this->getStandartFields();

		$stats_fields = isset($options['_stats_fields'])? $options['_stats_fields'] : [];
		$stats_fields = array_flip($stats_fields);

		$db_fields = array_merge(['id' => 'user_id'], $db_fields);
		$db_fields_string = $this->arrayToFields($db_fields, '`u`.');

		$unlocked_users_string = '';
		$unlocked_users_join = '';
		if(isset($options['company_id'])) {
			$company_id = $options['company_id'];
			$unlocked_users_string = ",(SELECT IF(id, true, false) id FROM `company_users` WHERE company_id = '$company_id' AND user_id = u.id) userinfo_unlocked";
		}
		if(isset($options['unlocked_users']) && isset($options['company_id'])) {
			$unlocked_users_string .= ", cu.time ";
			$unlocked_users_join = " INNER JOIN `company_users` cu ON (u.id = cu.user_id AND cu.company_id = $company_id) ";
		}

		$show_notes = (isset($options['show_notes']))? $options['show_notes'] : false;
		$ship_type_contracts = (isset($options['ship_type_contracts']))? $options['ship_type_contracts'] : false;
		$min_contracts = (isset($options['min_contracts']))? $options['min_contracts'] : 0;
		$user_type = (isset($options['user_type']))? $options['user_type'] : 'user';
		$show_not_confirmed = (isset($options['show_not_confirmed']))? $options['show_not_confirmed'] : 1;
		$admin_favorites = (isset($options['admin_favorites']))? $options['admin_favorites'] : 0;
		$show_stats = (isset($options['show_stats']))? $options['show_stats'] : 0;

		$use_cache = (isset($options['use_cache']))? $options['use_cache'] : 0;

		$notes_fields_string = '';
		$notes_join = '';
		if($show_notes || array_key_exists('notes', $filters)) {
			$notes_company_private = (isset($options['notes_company_private'])) ? $options['notes_company_private'] : false;
			$notes_admin_all = (isset($options['notes_admin_all'])) ? $options['notes_admin_all'] : false;
			$notes_admin_private = (isset($options['notes_admin_private'])) ? $options['notes_admin_private'] : false;
			$only_auto_notes = (isset($options['only_auto_notes'])) ? $options['only_auto_notes'] : false;
			$notes_fields_string = $this->getNotesFieldsString();
			$notes_join = $this->getNotesJoin($notes_company_private, $notes_admin_all, $notes_admin_private, $viewer_id, $only_auto_notes);
		}

		$ship_type_contracts_string = '';
		$experience_join = '';
		$having_contracts = '';
		if($ship_type_contracts) {
			$ship_type_contracts_string = ", ue.ship_type, ue.rank last_rank, (SELECT COUNT(ue2.id) FROM user_experience ue2 WHERE ue2.user = u.id) contracts";
			// $experience_join = ' LEFT JOIN (SELECT uc.ship_type, uc.id, uc.date_to, uc.user, uc.rank FROM `user_experience` uc ORDER BY uc.date_to DESC) ue ON (u.id = ue.user) ';
			$experience_join = '
				LEFT JOIN (
				           SELECT  user, MAX(date_to) MaxDate
				           FROM    `user_experience`
				           GROUP BY user
				       ) UeMaxDates ON u.id = UeMaxDates.user
				LEFT JOIN (
					SELECT ship_type, id, date_to, user, rank FROM `user_experience`
					) ue ON ( UeMaxDates.user = ue.user AND UeMaxDates.MaxDate = ue.date_to)
			';
			if($min_contracts >= 0) $having_contracts = " HAVING contracts > $min_contracts ";
		}


		$where = '';
		$any_contract_join = '';
		$last_contract_join = '';
		$filters_join = '';
		$filters_fields_string = '';
		if(count($filters) > 0) {
			$predicat = 0;
			$int_predicat = 0;
			$year  = (60*60*24*365);

			if(array_key_exists('real_last_rank', $filters)
				|| array_key_exists('last_ship_type', $filters)
				|| array_key_exists('last_dwt_from', $filters)
				|| array_key_exists('last_dwt_to', $filters)
				) {
				$int_predicat = 0;
				$int_where = '';
				if(array_key_exists('real_last_rank', $filters)) {
					$real_last_rank = $filters['real_last_rank'];
					unset($filters['real_last_rank']);
					if(is_array($real_last_rank)) {
						$int_where = " WHERE last_contract.`rank` IN ( ";
						for ($i=0; $i < count($real_last_rank); $i++) {
							if($i>0) $int_where .= ',';
							$int_where .= "'".$real_last_rank[$i]."'";
						}
						$int_where .= ')';
					} else $int_where = " WHERE last_contract.`rank` = '$real_last_rank' ";

					$int_predicat++;
				}

				if(array_key_exists('last_ship_type', $filters)) {
					$last_ship_type = $filters['last_ship_type'];
					unset($filters['last_ship_type']);
					$int_where .= ($int_predicat)? " AND " : " WHERE ";
					if (is_array($last_ship_type)) {
						$int_where .= " last_contract.`ship_type` IN ( ";
						for ($i=0; $i < count($last_ship_type); $i++) {
							if($i>0) $int_where .= ',';
							$int_where .= "'".$last_ship_type[$i]."'";
						}
						$int_where .= ')';
					} else $int_where .= " last_contract.`ship_type` = '$last_ship_type' ";


					$int_predicat++;
				}

				if(array_key_exists('last_dwt_from', $filters)) {
					$last_dwt_from = $filters['last_dwt_from'];
					unset($filters['last_dwt_from']);
					$int_where .= ($int_predicat)? " AND " : " WHERE ";
					$int_where .= " last_contract.`dwt` >= '$last_dwt_from' ";
					$int_predicat++;
				}

				if(array_key_exists('last_dwt_to', $filters)) {
					$last_dwt_to = $filters['last_dwt_to'];
					unset($filters['last_dwt_to']);
					$int_where .= ($int_predicat)? " AND " : " WHERE ";
					$int_where .= " last_contract.`dwt` <= '$last_dwt_to' ";
					$int_predicat++;
				}

				$last_contract_join = "INNER JOIN (SELECT ue.date_from, ue.user, ue.ship_type, ue.rank, ue.dwt
										FROM (
											SELECT max(date_from) last_con, user FROM `user_experience` GROUP BY user) x
											INNER JOIN `user_experience` ue ON (ue.user = x.user AND ue.date_from = x.last_con)
										) last_contract ON (u.id = last_contract.user)";
				$where .= " $int_where";
				$predicat++;
			}


			$any_contract_select = 0;
			if(array_key_exists('ship_type', $filters)
				|| array_key_exists('worked_in_psn', $filters)
				|| array_key_exists('ship_name', $filters)
				|| array_key_exists('company', $filters)
				|| array_key_exists('dwt_from', $filters)
				|| array_key_exists('dwt_to', $filters)
				) {

				$any_contract_select = 1;

				$int_where = '';
				if(array_key_exists('worked_in_psn', $filters)) {
					$worked_in_psn = $filters['worked_in_psn'];
					unset($filters['worked_in_psn']);
					$int_where .= ($int_predicat)? " AND " : " WHERE ";
					if (is_array($worked_in_psn)) {
						$int_where .= " any_contract.`rank` IN ( ";
						for ($i=0; $i < count($worked_in_psn); $i++) {
							if($i>0) $int_where .= ',';
							$int_where .= "'".$worked_in_psn[$i]."'";
						}
						$int_where .= ')';
					} else $int_where .= " any_contract.`rank` = '$worked_in_psn' ";


					$int_predicat++;
				}

				if(array_key_exists('ship_name', $filters)) {

					$s_value = $filters['ship_name'];
					unset($filters['ship_name']);
					$int_where .= ($int_predicat)? " AND " : " WHERE ";
					$int_where .= " any_contract.`ship_name` LIKE '%$s_value%' ";
					$int_predicat++;
				}

				if(array_key_exists('company', $filters)) {

					$s_value = $filters['company'];
					unset($filters['company']);
					$int_where .= ($int_predicat)? " AND " : " WHERE ";
					$int_where .= " any_contract.`company` LIKE '%$s_value%' ";
					$int_predicat++;
				}

				if(array_key_exists('dwt_from', $filters)) {
					$dwt_from = $filters['dwt_from'];
					unset($filters['dwt_from']);
					$int_where .= ($int_predicat)? " AND " : " WHERE ";
					$int_where .= " any_contract.`dwt` >= '$dwt_from' ";
					$int_predicat++;
				}

				if(array_key_exists('dwt_to', $filters)) {
					$dwt_to = $filters['dwt_to'];
					unset($filters['dwt_to']);
					$int_where .= ($int_predicat)? " AND " : " WHERE ";
					$int_where .= " any_contract.`dwt` <= '$dwt_to' ";
					$int_predicat++;
				}


				if(array_key_exists('ship_type', $filters)) {
					$ship_type = $filters['ship_type'];
					unset($filters['ship_type']);
					$int_where .= ($int_predicat)? " AND " : " WHERE ";
					if (is_array($ship_type)) {
						$int_where .= " any_contract.`ship_type` IN ( ";
						for ($i=0; $i < count($ship_type); $i++) {
							if($i>0) $int_where .= ',';
							$int_where .= "'".$ship_type[$i]."'";
						}
						$int_where .= ')';
					} else $int_where .= " any_contract.`ship_type` = '$ship_type' ";


				}

				$where .= " $int_where";
				$predicat++;
			}

			if(array_key_exists('notes', $filters)) {
				$notes_status = $filters['notes'];
				unset($filters['notes']);
				$where .= ($predicat)? " AND " : " WHERE ";
				$where .= ($notes_status == 'have_notes')? " un.text != '' " : " un.text IS NULL ";
				$predicat++;
			}

			foreach ($filters as $filter => $value) {
				if($value == '' || $value == null || $value == 0 || $value == false) next($filters);

				if($value) {
					if($predicat == 0) {
						$where = "WHERE ";
						$predicat++;
					} else $where .= " AND ";
				}

				if($where == '') $where = 'WHERE ';

				if ($filter == 'home_city') {
					if(is_array($value)) {
						$where .= " `home_city` IN ( ";
						for ($i=0; $i < count($value); $i++) {
							if($i>0) $where .= ',';
							$where .= "'".$value[$i]."'";
						}
						$where .= ')';
					} else $where .= " u.`$filter` LIKE '%$value%' ";
				}
				else if ($filter == 'desired_rank') {
					if(is_array($value)) {
						$where .= " `desired_rank` IN ( ";
							for ($i=0; $i < count($value); $i++) {
								if($i>0) $where .= ',';
								$where .= "'".$value[$i]."'";
							}
							$where .= ')';
						} else $where .= " u.`$filter` = '$value' ";

				}
				else if ($filter == 'home_country') {
					if(is_array($value)) {
						$where .= " `home_country` IN ( ";
							for ($i=0; $i < count($value); $i++) {
								if($i>0) $where .= ',';
								$where .= "'".$value[$i]."'";
							}
							$where .= ')';
						} else $where .= " u.`$filter` = '$value' ";

				}
				else if ($filter == 'notes_text') {
					$where .= " u.id IN (SELECT user_id FROM `user_cv_notes` n WHERE n.`text` LIKE '%$value%') ";
				} else if ($filter == 'worked_for') {
					$where .= " u.id IN (SELECT user_id FROM `user_worked_for` wf WHERE wf.`company_id` = '$value') ";
				} else if ($filter == 'english_from') {
					$where .= " `english_knowledge` >= '$value'";
				} else if ($filter == 'english_to') {
					$where .= " `english_knowledge` <= '$value'";
				} else if ($filter == 'minimum_salary_from') {
					$where .= " `minimum_salary` >= '$value'";
				} else if ($filter == 'minimum_salary_to') {
					$where .= " `minimum_salary` <= '$value'";
				} else if ($filter == 'age_from') {
					$age_from = time() - ($year * $value);
					$where .= " `dob` <= '$age_from'";
				} else if ($filter == 'age_to') {
					$age_to = time() - ($year * $value);
					$where .= " `dob` >= '$age_to'";
				} else if ($filter == 'dob') {
					$age_from = $value - (60*60*24);
					$age_to = $value + (60*60*24);
					$where .= " `dob` >= '$age_from' AND `dob` <= '$age_to'";
				} else if ($filter == 'readiness_date_from') {
					$where .= " `readiness_date` >= '$value'";
				} else if ($filter == 'readiness_date_to') {
					$where .= " `readiness_date` <= '$value'";
				} else if ($filter == 'in_db_from') {
					$where .= " `in_db_date` >= '$value'";
				} else if ($filter == 'in_db_to') {
					$where .= " `in_db_date` <= '$value'";
				} else if ($filter == 'cv_last_view_from') {
					$where .= " `cv_last_view` >= '$value'";
				} else if ($filter == 'cv_last_view_to') {
					$where .= " `cv_last_view` <= '$value'";
				} else if ($filter == 'cv_last_call_from') {
					$where .= " `cv_last_call` >= '$value'";
				} else if ($filter == 'cv_last_call_to') {
					$where .= " `cv_last_call` <= '$value'";
				} else if ($filter == 'cv_last_update_from') {
					$where .= " `cv_last_update` >= '$value'";
				} else if ($filter == 'cv_last_update_to') {
					$where .= " `cv_last_update` <= '$value'";
				} else if ($filter == 'info_source') {
					$where .= " `info_source` = '$value'";
				} else if ($filter == 'registered') {
					if($value == 'yes') $where .= " (`u`.`reg_date` IS NOT NULL AND `u`.`reg_date` > 0) ";
					else $where .= " (`u`.`reg_date` IS NULL OR `u`.`reg_date` = 0) ";
				} else if ($filter == 'home_address') {
					$where .= " `u`.`home_address` LIKE '$value%' ";
				} else if ($filter == 'have_vk_id') {
					$where .= " `u`.`social_vk` IS NOT NULL AND `u`.social_vk != '' ";
				} else if ($filter == 'id') {
					$where .= " `u`.`id` = $value ";
				} else if($filter == 'online') {
					$latest_time = time() - _ONLINE_TIME_;
					if($value == 'online') $where .= " (`u`.`last_activity` > {$latest_time}) ";
					else $where .= " (`u`.`last_activity` < {$latest_time} OR `u`.`last_activity` is NULL) ";
				} else if ($filter == 'name') {
					$en_value = ztranslit($value);
					$ru_value = ztranslit($value, 1);
					$where .= " (
						u.`name` LIKE '$en_value%' OR u.`name` LIKE '$ru_value%'
						OR u.`surname` LIKE '$en_value%' OR u.`surname` LIKE '$ru_value%'
						OR u.`full_name` LIKE '%$en_value%' OR u.`full_name` LIKE '%$ru_value%'
						OR u.`company_name` LIKE '%$en_value%' OR u.`company_name` LIKE '%$ru_value%'
						) ";
				} else {
					$value = $this->wrapForLike($value);
					$where .= "u.`$filter` LIKE '$value' ";
				}
			}
			if($where != '' && $any_contract_select) $any_contract_join = ' INNER JOIN user_experience any_contract ON (u.id = any_contract.user) ';
		}


		// search in admin favorites
		if($admin_favorites) {
			$filters_join .= " INNER JOIN `admin_user_favorites` auf ON (auf.user_id = u.id) ";
			$filters_fields_string .= ', auf.user_id, auf.admin_id,auf.time fav_time ';
			$where .= ($where == '')? ' WHERE ' : ' AND ';
			$where .= " auf.admin_id = '$viewer_id' ";
		}
		if(isset($stats_fields['company_users'])) $filters_fields_string .= " , ( SELECT COUNT(*) FROM `company_users` WHERE `company_id` = u.id ) company_users ";
		if(isset($stats_fields['company_vacancies'])) $filters_fields_string .= " , ( SELECT COUNT(*) FROM `vacancies` WHERE `user` = u.id ) company_vacancies ";
		if(isset($stats_fields['vacancies_for_company'])) $filters_fields_string .= " , ( SELECT COUNT(*) FROM `vacancies` WHERE `for_company` = u.id ) vacancies_for_company ";

		if($user_type) {
			$where .= ($where == '')? ' WHERE ' : ' AND ';
			if(is_array($user_type)) {
				$where .= " ( ";
				$i = 0;
				foreach ($user_type as $type) {
					if($i > 0) $where .= ' OR ';
					$where .= " u.type = '$type' ";
					$i++;
				}
				$where .= " ) ";
			} else $where .= " u.type = '$user_type' ";
		}

		if(!$show_not_confirmed) $where .= " AND u.role != '".\Application\Access\AccessList::ROLE_COMPANY_UNKNOWN."' ";



		$stats_string = '';
		if($show_stats) {
			$stats_string = "
			, {$this->getLikesCountSelect('user','u')} likes
			, {$this->getCommentsCountSelect('user','u')} total_comments
			, {$this->getLikersSelect('user','u')} likers
			, {$this->getLikeStatusSelect($viewer_id, 'user','u')} like_status
			, {$this->getViewsCountSelect('user','u')} views
			";
		}

		if($this->count) {
			$count_string = ' COUNT(DISTINCT u.id) count';
			$fields_string = '';
			$limit_offset_order_string = '';
			$experience_join = '';
		} else {
			$count_string = '';
			$fields_string = "
				$db_fields_string
				$filters_fields_string
				$notes_fields_string
				$ship_type_contracts_string
				$unlocked_users_string
				$stats_string
				";
			$limit_offset_order_string = "
				GROUP BY `u`.id
				$having_contracts
				$this->order_string
				$this->limit_string $this->offset_string
			";
		}

		$query = "SELECT
			$count_string
			$fields_string
			FROM `user` u
			$filters_join
			$notes_join
			$experience_join
			$any_contract_join
			$last_contract_join
			$unlocked_users_join
			$where
			$limit_offset_order_string
			";
		if($this->query_only) return $query;

		if($use_cache) {
			return $this->getCachedRequest($query, z_generateNameFromMethod(get_class($this).'::'.__FUNCTION__), '_'.md5($where));
		}

		return $this->query($query);
	}


	public function getAdmins($admin_roles)
	{
		$roles_string = '';
		$c = count($admin_roles);
		for ($i=0; $i < $c; $i++) {
			if($i > 0) $roles_string .= ', ';
			$roles_string .= "'".$admin_roles[$i]."'";
		}
		return $this->query(
			"SELECT u.id, u.name, u.surname, u.full_name, u.login, u.type, u.role, u.avatar, u.cv_avatar
			FROM `$this->tableName` u
			WHERE u.role IN ($roles_string)
			");
	}


	public function refreshUserNotificationsLastView($user_id)
	{
		if(is_bool($user_id) || is_null($user_id)) throw new \Application\Exception\Exception("No user id supplied", 000);
		$user_id = (int) $user_id;
		return $this->setFieldOnId($user_id, 'last_view_notifications', time());
	}

	public function refreshUserCvLastView($user_id)
	{
		if(is_bool($user_id) || is_null($user_id)) throw new \Application\Exception\Exception("No user id supplied", 000);
		$user_id = (int) $user_id;
		return $this->setFieldOnId($user_id, 'cv_last_view', time());
	}

	public function refreshUserCvLastCall($user_id, $time = null)
	{
		if(is_bool($user_id) || is_null($user_id)) throw new \Application\Exception\Exception("No user id supplied", 000);
		if(!$time) $time = time();
		$user_id = (int) $user_id;
		return $this->setFieldOnId($user_id, 'cv_last_call', $time);
	}


	// $return_active_record required to return editable user data, used in registration
	public function getUserById($user_id, $return_active_record = false, $db_fields = null, $exception = 1)
	{
		if(is_bool($user_id) || is_null($user_id)) throw new \Application\Exception\Exception("No user id supplied", 000);

		$user_id = (int) $user_id;

		if($db_fields && is_array($db_fields)) {
			$db_fields_string = $this->arrayToFields($db_fields, '`u`.');
			$db_fields_string .= ', `u`.role, `u`.type ';
		} else $db_fields_string = 'u.*';

		if($return_active_record) $resultSet = $this->tableGateway->select(array('id' => $user_id));
		else $resultSet = $this->query(
				"SELECT u.id as user_id, $db_fields_string
				FROM `user` u
				WHERE u.id = $user_id
				"
			);

		$result = $resultSet->current();
		if(!$result && $exception)
			return [];
			// throw new \Application\Exception\Exception("No user info found", 010);
		return $result;
	}

	public function getUserIdByEmail($email, $exc = 1)
	{
		if(!is_string($email)) throw new \Application\Exception\Exception("Wrong input data supplied", 000);

		$result = $this->getIdByField('email', $email);
		if(!$result && $exc) throw new \Application\Exception\Exception("User id not found", 010);
		return (int)$result;
	}

	public function getUserEmailByLogin($login)
	{
		if(!is_string($login)) throw new \Application\Exception\Exception("Wrong input data supplied", 000);
		$result = $this->getFieldByField('email', 'login', $login);
		if(!$result) throw new \Application\Exception\Exception("User with such login not found", 010);
		return $result;
	}

	public function getUserIdByLogin($login, $exc = 1)
	{
		if(!is_string($login)) throw new \Application\Exception\Exception("Wrong input data supplied", 000);
		if(preg_match("/^id(?'id'[0-9]+)$/" , $login, $matches)) {
			$result = ($this->checkUserById($matches['id'])) ? $matches['id'] : 0;
		} else $result = $this->getIdByField('login', $login);
		if(!$result && $exc) throw new \Application\Exception\Exception("User id not found", 010);
		return (int)$result;
	}

	public function getUserByEmail($email, $exception = 1)
	{
		if(!is_string($email)) throw new \Application\Exception\Exception("Wrong input data supplied", 000);
		$user = $this->query(
			"SELECT *
				FROM {$this->tableName} u
				WHERE u.email = '$email'
			")->current();
		if(!$user && $exception) throw new \Application\Exception\Exception("User info not found", 704);
		return $user;
	}

	public function getUserByLogin($login, $exc = 1)
	{
		if(!is_string($login)) throw new \Application\Exception\Exception("Wrong input data supplied", 000);
		$result = $this->getUserById($this->getUserIdByLogin($login));
		if(!$result && $exc) throw new \Application\Exception\Exception("User info not found", 010);
		return $result;
	}

	public function updateUserLastActivity($user_id)
	{
		if(is_bool($user_id) || is_null($user_id)) throw new \Application\Exception\Exception("No user id supplied", 000);
		return $this->updateOnID($user_id, ['last_activity' => time(), 'last_ip' => $_SERVER['REMOTE_ADDR']]);
	}

	public function updateAdminLastActivity($user_id)
	{
		if(is_bool($user_id) || is_null($user_id)) throw new \Application\Exception\Exception("No user id supplied", 000);
		return $this->updateOnID($user_id, ['last_access_admin_panel' => time(), 'last_ip' => $_SERVER['REMOTE_ADDR']]);
	}

	public function getUserLastActivity($user_id)
	{
		return $this->getFieldById($user_id, 'last_activity');
	}


	public function checkUserByEmail($email)
	{
		if(!is_string($email)) throw new \Application\Exception\Exception("Wrong string data supplied", 000);
		$result = $this->getIdByField('email', $email);
		return ($result)? true : false;
	}

	public function checkUserByLogin($login)
	{
		if(!is_string($login)) throw new \Application\Exception\Exception("Login can be only string", 000);
		if(preg_match("/^id(?'id'[0-9]+)$/" , $login, $matches)) {
			return $this->checkUserById($matches['id']);
		}
		return $this->existsValue($login, 'login');
	}

	public function checkUserById($user_id)
	{
		$user_id = (int)$user_id;
		if(0 == $user_id) throw new \Application\Exception\Exception("Wrong user id supplied", 000);
		return $this->existsID($user_id);

	}


	public function delete($user_id, $field = 'id')
	{
		$user_id = (int) $user_id;
		if($user_id == 0) throw new \Application\Exception\Exception("Wrong user id supplied", 000);
		$delete_files = $this->sl()->get('UploadsTable')->deleteUserFiles($user_id);

		$delete = $this->query(
			"DELETE a.* FROM user_avatar a WHERE a.user = '$user_id';
			DELETE a.* FROM user_documents a WHERE a.user = '$user_id';
			DELETE a.* FROM user_company a WHERE a.user = '$user_id';
			DELETE a.* FROM user_experience a WHERE a.user = '$user_id';
			DELETE a.* FROM user_education a WHERE a.user = '$user_id';
			DELETE a.* FROM user_logbook a WHERE a.user = '$user_id';
			DELETE a.* FROM pics a WHERE a.user = '$user_id';
			DELETE a.* FROM company_users a WHERE a.user_id = '$user_id';
			DELETE a.* FROM admin_activity a WHERE a.user_id = '$user_id';
			DELETE a.* FROM activity_views a WHERE a.user_id = '$user_id';
			DELETE a.* FROM admin_user_favorites a WHERE a.user_id = '$user_id';
			DELETE a.* FROM admin_user_settings a WHERE a.user_id = '$user_id';
			DELETE a.* FROM admin_notif a WHERE a.user_id = '$user_id';
			DELETE a.* FROM admin_notif_readed a WHERE a.user_id = '$user_id';
			DELETE a.* FROM user_cv_notes a WHERE a.user_id = '$user_id';
			DELETE a.* FROM user_cv_notes a WHERE a.user_id = '$user_id';
			DELETE a.* FROM vacancy_subscribers a WHERE a.user_id = '$user_id';
			DELETE a.* FROM user a WHERE a.id = '$user_id';
			"
			);
		return true;
	}

	public function getUserAvatar($user_id)
	{
		if(is_bool($user_id) || is_null($user_id)) throw new \Application\Exception\Exception("No user id supplied", 000);
		$user_id = (int) $user_id;
		return $this->getAvatarTable()->getUserAvatar($user_id);
	}

	public function addNewAvatar($user_id, array $avatar)
	{
		if(is_bool($user_id) || is_null($user_id)) throw new \Application\Exception\Exception("No user id supplied", 000);
		$user_id = (int) $user_id;
		$this->setFieldOnId($user_id, 'avatar', $avatar['crop']);
		return $this->getAvatarTable()->addAvatar($user_id, $avatar['img'],$avatar['thumb'], $avatar['crop']);
	}


	public function addNewCvFile($user_id, $cv_file_name)
	{
		if(is_bool($user_id) || is_null($user_id)) throw new \Application\Exception\Exception("No user id supplied", 000);
		$user_id = (int) $user_id;
		return $this->setFieldOnId($user_id, 'cv_file', $cv_file_name);
	}

	public function deleteAvatar($user_id)
	{
		if(is_bool($user_id) || is_null($user_id)) throw new \Application\Exception\Exception("No user id supplied", 000);
		$user_id = (int) $user_id;
		$this->getAvatarTable()->deleteCurrentAvatar($user_id);
		$prev_avatar = $this->getAvatarTable()->getUserAvatar($user_id);
		if($prev_avatar) {
			$this->setFieldOnId($user_id, 'avatar', $prev_avatar['crop']);
		} else {
			$this->setFieldOnId($user_id, 'avatar', NULL);
		}
		return true;
	}

	public function deleteCvAvatar($user_id)
	{
		if(is_bool($user_id) || is_null($user_id)) throw new \Application\Exception\Exception("No user id supplied", 000);
		$user_id = (int) $user_id;
		$cv_avatar = $this->getFieldByID($user_id, 'cv_avatar');
		if($cv_avatar) {
			$this->sl()->get('UploadsTable')->deleteUpload($cv_avatar, 'cv_avatar');
			$this->setFieldOnId($user_id, 'cv_avatar', NULL);
		}
		return true;
	}

	public function deleteCv($user_id)
	{
		if(is_bool($user_id) || is_null($user_id)) throw new \Application\Exception\Exception("No user id supplied", 000);
		$user_id = (int) $user_id;
		$this->setFieldOnId($user_id, 'cv_file', '');
		return true;
	}

	public function getUserCv($user_id)
	{
		return $this->getFieldById($user_id, 'cv_file');
	}

	public function getUserName($user_id)
	{
		if(is_bool($user_id) || is_null($user_id)) throw new \Application\Exception\Exception("No user id supplied", 000);
		$user_id = (int) $user_id;
		$user = $this->getUserById($user_id);
		if($user->name) return $user->name.' '.$user->surname;
		else if($user->full_name) return $user->full_name;
		else return $user->login;
	}



	public function getUserIfRegConfirmed($email)
	{
		if(!is_string($email)) throw new \Application\Exception\Exception("Wrong input data supplied", 000);
		$user = $this->query(
			"SELECT *
				FROM {$this->tableName} u
				WHERE u.email = '$email'
				AND u.email_confirmation_key IS NULL
			")->current();
		return $user;
	}

	public function getActiveUserBySecretKey($code)
	{
		if(!is_string($code)) throw new \Application\Exception\Exception("No code supplied", 000);
		$id = $this->getIdByField('email_confirmation_key', $code);
		if(!$id) throw new \Application\Exception\Exception("User not found in database!", 1);
		$user = $this->getUserById($id, true);
		return $user;
	}

	public function getUserIdBySecretKey($code)
	{
		if(!is_string($code)) throw new \Application\Exception\Exception("No code supplied", 000);
		return $this->getIdByField('email_confirmation_key', $code);
	}


	public function getUserByPasswordResetKey($code)
	{
		if(!is_string($code)) throw new \Application\Exception\Exception("No code supplied", 000);
		$id = $this->getIdByField('password_reset_key', $code);
		if(!$id) throw new \Application\Exception\Exception("User not found in database!", 1);
		$user = $this->getUserById($id, true);
		return $user;
	}


	public function checkRegCode($code)
	{
		if(!is_string($code)) throw new \Application\Exception\Exception("No code supplied", 000);
		if($this->getIdByField('email_confirmation_key', $code)) return true;
		throw new \Application\Exception\Exception("Reg code is not correct!", 1);
		;
	}

	public function checkPassResetCode($code)
	{
		if(!is_string($code)) throw new \Application\Exception\Exception("No code supplied", 000);
		if($this->getIdByField('password_reset_key', $code)) return true;
		throw new \Application\Exception\Exception("Reset code is not correct!", 1);
		;
	}

	private function hashPassword($clearPass)
	{
		if(!is_string($clearPass)) throw new \Application\Exception\Exception("No password value supplied", 000);
		return 	md5($clearPass);
	}


	protected function getAvatarTable()
	{
		if(!$this->avatarTable) $this->avatarTable = new \Application\Model\UserAvatarTable;
		return $this->avatarTable;
	}



}