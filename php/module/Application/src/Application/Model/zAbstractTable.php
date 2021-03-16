<?php
namespace Application\Model;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature\RowGatewayFeature as RowFeature;

use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;


abstract class zAbstractTable
{

	protected $tableGateway;
	protected $tableName;
	protected $hydrator;
	protected $dump = null;
	public $resultSet;
	public static $dbAdapter;

	protected $fields;
	protected $json_fields;
	protected $query_only;
	protected $count;
	protected $buffer;
	protected $order;
	protected $up;
	protected $order_string;
	protected $offset;
	protected $offset_string;
	protected $limit_string;



	public function __construct($table_name = null, $resultSet = true, $rowFeature = null, $featureIndex = 'id')
	{
		$this->resultSet = $resultSet;
		if($rowFeature) $rowFeature = new RowFeature($featureIndex);
		if($table_name) $this->init($table_name, $rowFeature);
	}


	public function translate($string)
	{
		return \Application\Translator\StaticTranslator::getTranslator()->translate($string);
	}

	protected function sl()
	{
		return \ServiceLocatorFactory\ServiceLocatorFactory::getInstance();
	}


	protected function getDumpCacher()
	{
		if(!$this->dump) {
			$this->dump = new \Application\zLibrary\RedisDump;
		}
		return $this->dump;
	}

	public function setDefaultOptions($options = [], $default_options = [])
	{
		$this->query_only = (isset($options['query_only'])) ? $options['query_only'] : (isset($default_options['query_only'])? $default_options['query_only'] : 0);
		$this->fields = (isset($options['_fields'])) ? $options['_fields'] : (isset($default_options['_fields'])? $default_options['_fields'] : null);
		$this->json_fields = (isset($options['json_fields'])) ? $options['json_fields'] : (isset($default_options['json_fields'])? $default_options['json_fields'] : 0);
		$this->count = (isset($options['count'])) ? $options['count'] : (isset($default_options['count'])? $default_options['count'] : null);
		$this->order = (isset($options['_order'])) ? $options['_order'] : (isset($default_options['_order'])? $default_options['_order'] : 'id');
		$this->up = (isset($options['up'])) ? $options['up'] : (isset($default_options['up'])? $default_options['up'] : null);
		$this->buffer = (isset($options['buffer'])) ? $options['buffer'] : (isset($default_options['buffer'])? $default_options['buffer'] : null);
		$this->limit = (isset($options['_limit'])) ? $options['_limit'] : (isset($default_options['_limit'])? $default_options['_limit'] : 50);
		$this->offset = (isset($options['_offset'])) ? $options['_offset'] : (isset($default_options['_offset'])? $default_options['_offset'] : null);
		$this->page = (isset($options['_page'])) ? $options['_page'] : (isset($default_options['_page'])? $default_options['_page'] : null);

		if($this->page && !$this->offset) $this->offset =  ($this->page - 1) * $this->limit;
		$this->limit_string = ($this->limit) ? ' LIMIT '.$this->limit : '';
		$this->offset_string = ($this->offset) ? ' OFFSET '.$this->offset : '';
		if(!$this->up) $this->order .= ' DESC';
		$this->order_string = "ORDER BY $this->order";
	}


	public function getFieldCountForSelect($field, $filters = [], $options = [])
	{
		$more_than = (isset($options['more_than']))? $options['more_than'] : null;
		$use_cache = (isset($options['use_cache']))? $options['use_cache'] : 1;
		$show_count = (isset($options['show_count']))? $options['show_count'] : 1;
		$for_select = (isset($options['for_select']))? $options['for_select'] : 1;
		$alias = (isset($options['alias']))? $options['alias'] : 'tn';
		$table_name = (isset($options['table_name']))? $options['table_name'] : $this->tableName;

		$this->setDefaultOptions($options, ['_limit' => 999, '_order' => 'id']);

		$having = ($more_than)?  " HAVING count > '$more_than'" : '';
		$join_string = '';
		$field_alias = $field;
		$count_distinct = '';

		if($field == 'all_ranks') {
			$field = 'rank';
			$table_name = 'user_experience';
			$alias = 'ue';
		} else if($field == 'last_rank') {
			$field = 'rank';
			$table_name = 'user_experience';
			$join_string .= 'INNER JOIN (SELECT max(date_from) last_con, user FROM `user_experience` GROUP BY user) x ON (ue.user = x.user AND ue.date_from = x.last_con)';
			$alias = 'ue';
		} else if($field == 'last_ship_type') {
			$field = 'ship_type';
			$table_name = 'user_experience';
			$join_string .= 'INNER JOIN (SELECT max(date_from) last_con, user FROM `user_experience` GROUP BY user) x ON (ue.user = x.user AND ue.date_from = x.last_con)';
			$alias = 'ue';
		}
		$count_field = $field;

		$alias_dot = ($alias)? $alias.'.' : '';
		$where = '';
		foreach ($filters as $filter => $value) {
			if(!$value) next($filters);
			if($value) $where .= ($where == '') ? ' WHERE ' : ' AND ';
			if ($filter == 'type_company') {
				if($table_name == 'user') $where .= ' '.$alias_dot."type = 'company' ";
				else $where .= ' 1 = 1 '; // kostil
			} else if ($filter == 'type') {
				$where .= ' '.$alias_dot."type = '$value' ";
			} else if ($filter == 'ship_type') {
				$where .= ' '.$alias_dot."ship_type = '$value' ";
			} else if ($filter == 'only_active') {
				$where .= $alias_dot.'time <= '.time().' AND '.$alias_dot.'active = 1';
			} else if ($filter == 'company_db') {
				$user_field = ($table_name == 'user_experience')? 'user' : 'id';
				$where .= ' '.$alias_dot.$user_field." IN (SELECT user_id FROM `company_users` WHERE company_id = '$value') ";
				$count_field = $user_field;
				$count_distinct = 'DISTINCT';
			} else throw new \Application\Exception\Exception("Filter not recognized", 1);
		}

		$hash = ($where || $more_than)? '_'.md5($where.$more_than) : '';
		$predicat = $field_alias.$hash;

		$select = "SELECT $alias_dot{$field}, COUNT($count_distinct $alias_dot{$count_field}) count
				FROM $table_name $alias
				$join_string
				$where
				GROUP BY $alias_dot{$field}
				$having
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
				ORDER BY $alias_dot{$field} ASC
				$this->limit_string $this->offset_string
			";
		}


		$result = $this->getCachedRequest($query, z_generateNameFromMethod(get_class($this).'::'.__FUNCTION__), $predicat, $use_cache);
		if($for_select) $result = $this->resultToFormSelect($result, $field, $show_count);
		return $result;
	}

	public function getCachedRequest($query, $dump_name, $predicat = '', $use_cache = 1, $cache_exp = _DUMP_CACHE_EXP_)
	{

		try {
			$dump_name = ($predicat)? $dump_name.'_'.$predicat : $dump_name;
			if($use_cache && $this->getDumpCacher()->checkDump($dump_name)) $result = $this->getDumpCacher()->getDump($dump_name);
			else {
				$this->query("SET SESSION group_concat_max_len = 1000000;");
				$result = $this->query($query)->toArray();
				if($use_cache) $this->getDumpCacher()->createDump($result, $dump_name, $cache_exp);
			}
		} catch (\Exception $e) {
			$redis_error = $e->getMessage();
			d("Caching error: $redis_error", 1);
			$result = $this->query($query)->toArray();
		}

		return $result;
	}

	public function resultToFormSelect($result, $id_field, $show_count = 1, $value_name = 'count', $value_field = null)
	{
		$items = [];
		foreach ($result as $item) {
			$item_name = ($item[$id_field] == '') ? '*'.$this->translate('Not Stated') : $item[$id_field];
			if($value_field && isset($item[$value_field]))  $item_name = $item[$value_field];
			// if($item[$id_field] == '') $item[$id_field] = 'not_stated';
			if($show_count && isset($item[$value_name]))  $item_name .= ' ('.$item[$value_name].') ';
			$items[$item[$id_field]] = $item_name;
		}
		return $items;
	}


	public function getCompanyFields($fields = null)
	{
		if($fields && is_array($fields)) {
			$fields_string = $this->arrayToFields($fields, '`u`.');
		} else $fields_string = 'u.id company_id, u.login,
										u.role,
										u.type,
										u.email,
										u.company_name,
										u.company_description,
										u.company_license,
										u.home_country,
										u.home_city,
										u.home_address,
										u.contact_phone,
										u.contact_phone_2,
										u.contact_mobile,
										u.contact_mobile_2,
										u.contact_email,
										u.info_website,
										u.user_notes,
										u.last_activity,
										u.reg_date,
										u.avatar';

		return $fields_string;
	}

	public function getCompanyVacanciesCountSelect($company_table_alias = 'u', $company_table_id_field = 'id', $only_active = 1)
	{
		$time = time();
		$and = '';
		if ($only_active) {
			$and = "
				AND `active` = 1
				AND `time` <= '$time'
			";
		}
		return "( SELECT COUNT(*) FROM `vacancies` WHERE `user` = `$company_table_alias`.`$company_table_id_field` $and)";
	}


	public function getVacanciesFields($fields = null, $request = 'all')
	{
		if($fields && is_array($fields)) {
			$fields_string = $this->arrayToFields($fields, '`v`.');
		} else $fields_string = " v.* ";

		$fields_string .=", ('$request') request";
		return $fields_string;
	}


	public function getUserFields($fields = null)
	{
		if($fields && is_array($fields)) {
			$fields_string = $this->arrayToFields($fields, '`u`.');
		} else $fields_string = " u.*, u.id user_id ";
		return $fields_string;
	}


	protected function getNotesFieldsString($predicat = '')
	{
		return ', un.text '.$predicat.'office_note'.', un.time '.$predicat.'office_note_time ';
	}


	protected function getNotesJoin($show_company_private = true, $show_admin_all = true, $show_admin_private = true, $owner_id = null, $only_auto_notes = false, $options = [])
	{
		$t_alias = (isset($options['t_alias']))? $options['t_alias'] : 'u';
		$t_field = (isset($options['t_field']))? $options['t_field'] : 'id';

		$where = ' WHERE `visibility` = '.UserCvNotesTable::VIS_COMPANY_ALL;

		if($show_company_private && $owner_id) {
			$where .= ' OR ( ';
			$where .= " `visibility` = ".UserCvNotesTable::VIS_COMPANY_PRIVATE;
			if(!$show_admin_all) $where .= " AND `owner_id` = $owner_id ";
			$where .= ' ) ';
		}

		if($show_admin_all) {
			$where .= ' OR ';
			$where .= " `visibility` = ".UserCvNotesTable::VIS_ADMIN_ALL;
		}

		if($show_admin_private && $owner_id) {
			$where .= ' OR ( ';
			$where .= " `visibility` = ".UserCvNotesTable::VIS_ADMIN_PRIVATE;
			$where .= " AND `owner_id` = $owner_id ";
			$where .= ' ) ';
		}

		$auto_notes_where = '';
		if(!$show_admin_all) $auto_notes_where = "WHERE ( notif.not_section = 'user' OR notif.not_section = 'company' OR notif.not_section = 'vacancy' OR notif.not_section = 'cv') ";

		$notes_select = '';
		$notes_sort_select = '';
		if(!$only_auto_notes) {
			$notes_select = "SELECT
								user_id, MAX(time) MaxDate
					           FROM    `user_cv_notes`
							   $where
					           GROUP BY user_id
					           UNION";
			$notes_sort_select = "SELECT
						user_id, text, time
						FROM user_cv_notes
						$where
						UNION";
		}


		return "
				LEFT JOIN (
				           $notes_select
				           SELECT  user_id, MAX(time) MaxDate
				           FROM    `admin_notif` notif
				           $auto_notes_where
				           GROUP BY user_id
				           ORDER BY MaxDate DESC
				       ) MaxDates ON $t_alias.$t_field = MaxDates.user_id
				LEFT JOIN (
					$notes_sort_select
					SELECT user_id, not_message text, time
					FROM admin_notif notif
					$auto_notes_where
					ORDER BY time DESC
					) un ON ( MaxDates.user_id = un.user_id AND MaxDates.MaxDate = un.time)
			";
	}


	public function getStandartFields($use_cache = 1)
	{
		$query = "DESCRIBE `{$this->tableName}`";
		$dump_name = z_generateNameFromMethod(get_class($this).'::'.__FUNCTION__, 0);
		$result = $this->getCachedRequest($query, $dump_name,null, $use_cache, 300);
		$fields = [];
		$c = count($result);
		for ($i=0; $i < $c; $i++) {
			$fields[] = $result[$i]['Field'];
		}
		return $fields;
	}

	public function getStatsFields()
	{
		return ['pics', 'likes', 'likers', 'like_status', 'views','subscribers', 'subscribe_status', 'answers', 'answered', 'votes', 'up_votes', 'down_votes', 'total_rating', 'vote_status', 'videos', 'links','soc_likes', 'soc_likers', 'total_comments', 'comments_list','soc_comments_list', 'vk_last_comment_id'];
	}

	public function getPicsSelect($pics_fields = null, $section, $table_alias, $table_id = 'id')
	{
		if(!$pics_fields) $pics_fields = $this->generateJsonFields(array('id','img', 'thumb', 'alt', 'time'), '', 10);
		$ref_table = 'pics-'.$section;
		return "(	SELECT $pics_fields
					FROM pics p
					WHERE p.id IN (SELECT pics_id FROM `$ref_table` WHERE `article_id` = `$table_alias`.$table_id)
				)";
	}

	public function getVideosSelect($section, $section_alias, $fields = null, $limit = 3)
	{
		if(!$fields) $fields = $this->generateJsonFields(array('id', 'title', 'description','embed_url', 'url', 'thumb', 'time'), '', $limit, 'ORDER BY l.time DESC');

		return "CAST((SELECT $fields
						FROM `article_videos` l
						WHERE section = '$section' AND section_id = $section_alias.id
					) as char(2000)) ";
	}

	public function getLinksSelect($section, $section_alias, $fields = null, $limit = 3)
	{
		if(!$fields) $fields = $this->generateJsonFields(array('id', 'title', 'description', 'url', 'thumb', 'time'), '', $limit, 'ORDER BY l.time DESC');

		return "(SELECT $fields
						FROM `article_links` l
						WHERE section = '$section' AND section_id = $section_alias.id
					) ";
	}


	public function getSocCommPicsSelect($section_alias, $limit = 10)
	{
		return "(SELECT substring_index(GROUP_CONCAT(img),',', $limit) FROM `pics` p WHERE p.id IN (SELECT pics_id FROM `pics-s_comments` WHERE `article_id` = $section_alias.id) )";
	}




	public function getLikersSelect($section, $section_alias, $likers_fields = null, $limit = _VIEW_LIKERS_LIMIT_)
	{
		if(!$likers_fields) $likers_fields = $this->generateJsonFields(array(
			'u.id' => 'user_id', 'login','name', 'surname', 'full_name', 'avatar','cv_avatar'
			), '', $limit, 'ORDER BY l.time DESC');

		return "CAST((SELECT $likers_fields
						FROM `user` u
						INNER JOIN `likes` l ON (u.id = l.user_id)
						WHERE section = '$section' AND l.section_id = $section_alias.id
						ORDER BY l.id ASC
					) as char(2000)) ";
	}

	public function getSocialLikersSelect($section, $section_alias, $likers_fields = null, $limit = _VIEW_LIKERS_LIMIT_)
	{
		if(!$likers_fields) $likers_fields = $this->generateJsonFields(array(
			'u.id' => 'user_id', 'login'
			, 'soc_page','su.name' => 'name', 'su.surname' => 'surname', 'su.avatar' => 'avatar',
			), '', $limit, 'ORDER BY l.id DESC');

		return " (SELECT $likers_fields
						FROM `social_likes` l
						INNER JOIN `user` u ON (u.id = l.user_id)
						INNER JOIN `social_users` su ON (su.soc_user_id = l.soc_user_id)
						WHERE l.section = '$section' AND l.section_id = $section_alias.id
						ORDER BY l.id ASC
					) ";
	}


	public function getCommentsSelect($viewer_id = 0, $section, $section_alias, $limit = _VIEW_COMMENT_LIMIT_)
	{
		$comments_fields = $this->generateJsonFields(array(
			$this->getUpVotesSelect('comments', 'l') => 'up_votes',
			$this->getDownVotesSelect('comments', 'l') => 'down_votes',
			$this->getVoteStatusSelect($viewer_id, 'comments', 'l') => 'vote_status',
			"'local'" => 'source',
			'u.id' => 'user_id', 'login','name', 'surname', 'full_name', 'avatar','cv_avatar',
			'l.id' => 'id', 'l.section' => 'section', 'l.section_id' => 'section_id', 'comment', 'l.time' => 'time'
			), '', $limit, 'ORDER BY l.time ASC');

		return "(SELECT $comments_fields
					FROM `comments` l
					INNER JOIN `user` u ON (u.id = l.user_id)
					WHERE l.section = '$section' AND l.section_id = $section_alias.id AND l.active = 1
				) ";
	}
	public function getSocialCommentsSelect($viewer_id = 0, $section, $section_alias, $limit = _VIEW_COMMENT_LIMIT_)
	{
		$soc_com_fields = $this->generateJsonFields(array(
			'soc_likes' => 'up_votes',
			"'0'" => 'down_votes',
			"null" => 'vote_status',
			"l.soc_name" => 'source',
			$this->getSocCommPicsSelect('l') => 'pics',
			'u.id' => 'user_id', 'login', 'u.name' => 'name', 'u.surname' => 'surname', 'full_name', 'u.avatar' => 'avatar', 'cv_avatar',
			'soc_domain', 'soc_page','su.name' => 'soc_name', 'su.surname' => 'soc_surname', 'su.avatar' => 'soc_avatar',
			'l.id' => 'id', 'l.comment' => 'comment', 'l.time' => 'time'
			), '', $limit, 'ORDER BY l.time DESC');

		return "(SELECT $soc_com_fields
					FROM `social_comments` l
					LEFT JOIN `user` u ON (u.id = l.user_id)
					INNER JOIN `social_users` su ON (su.soc_user_id = l.soc_user_id)
					WHERE l.section = '$section' AND l.section_id = $section_alias.id
					) ";
	}

	public function getCommentsCountSelect($section, $section_alias, $section_id_field = 'id')
	{
		return "(
			(SELECT COUNT(*) FROM `comments` WHERE `section` = '$section' AND `section_id` = `$section_alias`.`$section_id_field` AND `active` = 1) +
			(SELECT COUNT(*) FROM `social_comments` WHERE `section` = '$section' AND `section_id` = `$section_alias`.`$section_id_field` AND `active` = 1 )
			)
		";
	}

	public function getSocialCommentsCountSelect($section, $section_alias, $section_id_field = 'id')
	{
		return "( SELECT COUNT(*) FROM `social_comments` WHERE `section` = '$section' AND `section_id` = `$section_alias`.`$section_id_field` AND `active` = 1 )";
	}

	public function getVkLastCommentId($section, $section_alias, $section_id_field = 'id')
	{
		return "( SELECT soc_com_id FROM `social_comments` WHERE `section` = '$section' AND `section_id` = `$section_alias`.`$section_id_field` ORDER BY `soc_com_id` DESC LIMIT 1 )";
	}

	public function getLikesCountSelect($section, $section_alias, $section_id_field = 'id')
	{
		return "( SELECT COUNT(*) FROM `likes` WHERE `section` = '$section' AND `section_id` = `$section_alias`.`$section_id_field` )";
	}

	public function getSocialLikesCountSelect($section, $section_alias, $section_id_field = 'id')
	{
		$s_table = 'user_logbook';
		if($section == 'vacancy') $s_table = 'vacancies';
		if($section == 'questions') $s_table = 'questions';
		if($section == 'answers') $s_table = 'question_answers';
		return "( SELECT COUNT(*) FROM `social_likes`
					WHERE `section` = '$section' AND `section_id` = `$section_alias`.`$section_id_field`
				)";
	}


	public function getViewsCountSelect($section, $section_alias, $section_id_field = 'id')
	{
		return "( SELECT COUNT(DISTINCT `user_ip`)
					FROM `activity_views` WHERE `section_name` = '$section' AND `resource_id` = `$section_alias`.`$section_id_field` )";
	}

	public function getLikeStatusSelect($viewer_id, $section, $section_alias, $section_id_field = 'id')
	{
		return "(SELECT IF(id, true, false) id FROM `likes` l WHERE l.user_id = '$viewer_id' AND l.`section` = '$section' AND l.section_id = `$section_alias`.`$section_id_field`)";
	}

	public function getAnsweredStatusSelect($section_alias, $section_id_field = 'id')
	{
		return "(SELECT IF(id, true, false) id FROM `question_answers` l WHERE l.`correct` = '1' AND l.question_id = `$section_alias`.`$section_id_field`)";
	}


	public function getAnswersCountSelect($section_alias, $section_id_field = 'id')
	{
		return "( SELECT COUNT(*) FROM `question_answers` WHERE `question_id` = `$section_alias`.`$section_id_field` AND `active` = 1) ";
	}

	public function getUpVotesSelect($section, $section_alias, $section_id_field = 'id')
	{
		return "( SELECT COUNT(*) FROM `activity_votes` WHERE `section` = '$section' AND `section_id` = `$section_alias`.`$section_id_field` AND `up_vote` = 1 )";
	}

	public function getDownVotesSelect($section, $section_alias, $section_id_field = 'id')
	{
		return "( SELECT COUNT(*) FROM `activity_votes` WHERE `section` = '$section' AND `section_id` = `$section_alias`.`$section_id_field` AND `down_vote` = 1 )";
	}


	public function getVotesSelect($section, $section_alias, $section_id_field = 'id')
	{
		return "
			( SELECT COUNT(*) FROM `activity_votes` WHERE `section` = '$section' AND `section_id` = `$section_alias`.`$section_id_field` AND `up_vote` = 1 ) up_votes
			, ( SELECT COUNT(*) FROM `activity_votes` WHERE `section` = '$section' AND `section_id` = `$section_alias`.`$section_id_field` AND `down_vote` = 1 ) down_votes

		";
	}


	public function getVoteStatusSelect($viewer_id, $section, $section_alias, $section_id_field = 'id')
	{
		return "(SELECT (CASE WHEN (l.up_vote = 1) THEN 'up' WHEN (l.down_vote = 1) THEN 'down' ELSE NULL END) status
			FROM `activity_votes` l WHERE l.user_id = '$viewer_id' AND l.`section` = '$section' AND l.section_id = `$section_alias`.`$section_id_field`)";
	}

	public function getItems($viewer_id = null, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, [
			'_limit' => 100,
			'_fields' => ['id'],
			'_order' => 'id'
			]);

		$where = '';
		foreach ($filters as $filter => $value) {
			if($value === null) next($filters);
			$where .= ($where == '') ? ' WHERE ' : ' AND ';

			if ($filter == 'id') {
				$where .= " `id` = '$value' ";
			} else if ($filter == 'user_id') {
				$where .= " `user_id` = '$value' ";
			} else if ($filter == 'time_more') {
				$where .= " `time` > '$value' ";
			} else if ($filter == 'time_less') {
				$where .= " `time` < '$value' ";
			} else {
				$permitted_fields = array_flip($this->getStandartFields(1, 1));
				if(!isset($permitted_fields[$filter])) throw new \Application\Exception\Exception("Filter not recognized", 1);
				$where .= " `$filter` = '$value' ";
			}

		}

		if($this->count) {
			$select = " COUNT(*) count";
			$this->limit_string = '';
			$this->offset_string = '';
			$this->order_string = '';
		} else {
			$select = ($this->json_fields)? $this->generateJsonFields($this->fields, '', $this->limit) : $this->arrayToFields($this->fields);
		}

		$this->query("SET SESSION group_concat_max_len = 1000000;");
		return $this->query(
			"SELECT $select
			FROM `{$this->tableName}` tn
			$where
			$this->order_string
			$this->limit_string
			$this->offset_string
			");
	}

	public function updateDb()
	{

			// d('update db');

		// $this->query(
		// 	"INSERT INTO `list-rank`(`rank`)
		// 	VALUES
		// 	('Master'),
		// 	('Chief Officer'),
		// 	('Boatswain'),			"
		// 	);

		// $this->query(
		// 	"UPDATE `user` SET `nationality` = 'Ukraine'
		// 		WHERE `nationality` = 'Украина АРК Крым'
		// 		-- OR `nationality` = ''
		// 		-- WHERE `rank` LIKE '%MES%'
		// 	"
		// 	);

		// $result =  $this->query(
		// 	"SELECT CONCAT('[',u.nationality,']') nationality, COUNT(u.nationality) count
		// 		FROM `user` u
		// 		GROUP BY u.nationality
		// 		ORDER BY count DESC
		// 	"
		// 	);
		// $result =  $this->query(
		// 	"SELECT CONCAT('[',ue.rank,']') rank, COUNT(u.id) count
		// 		FROM `user_experience` ue
		// 		INNER JOIN `user` u ON u.id = ue.user
		// 		GROUP BY ue.rank
		// 		ORDER BY count DESC
		// 	"
		// 	);


		d($result->toArray());
	}

	public function init($table_name, $rowFeature = null)
	{
		$this->tableGateway = new TableGateway($table_name, $this->getAdapter(), $rowFeature);
		$this->tableName = $table_name;
		$this->hydrator = new zHydrator($table_name, $this->tableGateway);

	}

	// safe insert function to use from controller or else
	// removing fields which are not in dbtable, returns inserted id
	public function insert(array $data)
	{
		$data = $this->hydrator->hydrate($data);
		$this->tableGateway->insert($data);
		return $this->tableGateway->getLastInsertValue();
	}

	public function insertMultiple($fields, $values, $options = [])
	{
		$ignore = (isset($options['ignore']) && $options['ignore'])? ' IGNORE ' : '';
		$upd_dupl = (isset($options['upd_dupl']))? $options['upd_dupl'] : null;
		$upd_dupl_str = '';
		if($upd_dupl) {
			$upd_dupl_str = ' ON DUPLICATE KEY UPDATE ';
			$i = 0;
			foreach ($upd_dupl as $field => $f_option) {
				if($i > 0) $upd_dupl_str .= ' , ';
				if(is_array($f_option)) {
					if(isset($f_option['increase'])) {
						$upd_dupl_str .= " {$field} = {$field} + 1 ";
					}
				} else $upd_dupl_str .= " {$f_option} = VALUES({$f_option}) ";
				$i++;
			}
		}

		return $this->query(
			"INSERT $ignore INTO `$this->tableName`
				{$this->getInsertFieldsString($fields)}
				VALUES
				{$this->getInsertValuesString($values)}
				$upd_dupl_str
			"
			);
	}

	public function insertSingle($fieldsValues, $options = [])
	{
		return $this->insertMultiple(array_keys($fieldsValues), [array_values($fieldsValues)], $options);
	}




	// получение всех значений поля
	public function get($value, $field = 'id')
	{
		return $this->getOnField($value, $field);
	}

	// получение элемента по значению какого то поля
	public function getOnField($value, $field)
	{
		$rowset = $this->tableGateway->select(array($field => $value));
		$result = $rowset->current();
		return $this->result($result);
	}

	// получение значения какого то поля по значению другого поля
	public function getFieldByField($field_out, $field_where, $value_where)
	{
		$result = $this->getOnField($value_where, $field_where);
		$result = $result[$field_out];
		return $this->result($result);
	}

	public function getFieldByID($id, $field_out)
	{
		return $this->getFieldByField($field_out, 'id', $id);
	}

	public function getFieldsByID($id, array $fields_out)
	{
		return $this->getFields($fields_out, 'id', $id);
	}

	public function getFields(array $fields_out, $field_where, $value_where)
	{
		$result = $this->tableGateway->select(
			function(\Zend\Db\Sql\Select $select) use ($fields_out, $field_where, $value_where) {
				$select->columns($fields_out)->where(array($field_where => $value_where));
			});
		return $this->result($result);
	}

	public function getFieldsByFields($fields_out, array $fields)
	{
		if(!is_array($fields_out)) $fields_out = array($fields_out);
		$result = $this->tableGateway->select(
			function(\Zend\Db\Sql\Select $select) use($fields_out, $fields){
				$select->columns($fields_out)->where($fields);
			});
		return $this->result($result);
	}

	public function getIdByField($field, $value)
	{
		return $this->getFieldByField('id', $field, $value);
	}


	// getting id by some other fields, if more than one id matching criteria, there are options to return the first one or set of ids. if none matching returns false
	public function getIDByFields(array $fields, $strict = false, $single = true)
	{
		$result = $this->getFieldsByFields('id', $fields);
		if($result->count() == 0) return false;
		if($result->count() == 1) return (int)$result->current()->id;
		else if(!$strict) {
			if($single) return (int)$result->current()->id;
			return (int)$result;
		}
		return false;
	}

	public function getLastByField($field_where, $value_where)
	{
		return $this->tableGateway->select(
			function(\Zend\Db\Sql\Select $select) use ($field_where, $value_where) {
				$select->where(array($field_where => $value_where))
				->order(array('id' => 'desc'))
				->limit(1);
		})->current();
	}


	// Добавить Лимит !!!!!!
	public function getAll($order = 'id', $up = true, $buffer = false, $limit = 0, $offset = 0)
	{
		$result = $this->tableGateway->select(
			function(\Zend\Db\Sql\Select $select) use ($order, $up, $limit, $offset) {
				if(is_array($order)) {
					$select->order($order);
				} else {
					$up = ($up)? 'asc' : 'desc';
					$select->order(array($order => $up))
					// ->limit($limit)
					// ->offset($offset)
					;
				}
			});
		if($buffer) $result->buffer();
		return $this->result($result);
	}


	public function getAllOnField($field_where, $value_where, $order = 'id', $up = true)
	{
		$result = $this->tableGateway->select(
			function(\Zend\Db\Sql\Select $select) use($value_where, $field_where, $order, $up) {
				$up = ($up)? 'asc' : 'desc';
				$select->where(array($field_where => $value_where))
					->order(array($order => $up));
			});
		return $this->result($result);
	}


	public function getAllGroupCount($groupByField, $value = null, $count_more = 0)
	{
		$table = $this->tableName;
		$and = ($value)? " AND `$table`.`$groupByField` LIKE '$value' " : '';
		$having = ($count_more)? "HAVING count > $count_more" : '';
		return $this->query(
				"SELECT `$table`.`$groupByField` , COUNT(`$table`.`$groupByField`) count
				FROM `$table`
				WHERE `$table`.`$groupByField` != ''
				$and
				GROUP BY `$table`.`$groupByField`
				$having
				ORDER BY count DESC
				"
				);
	}


	public function getCount()
	{
		$rowSet = $this->query('SELECT COUNT(`id`) FROM `'.$this->tableName.'`');
		$result = $rowSet->current();
		$result = $result['COUNT(`id`)'];
		return $this->result($result);
	}

	public function getCountWhere($where)
	{
		$resultSet = $this->tableGateway->select($where);
		return $resultSet->count();
	}

	public function getMaxFieldValue($field)
	{
		$rowSet = $this->query('SELECT MAX(`'.$field.'`) FROM `'.$this->tableName.'`');
		$result = $rowSet->current();
		$result = $result['MAX(`'.$field.'`)'];
		return $this->result($result);
	}



	public function existField($field_name)
	{
		$result = $this->query('DESCRIBE `'.$this->tableName.'`');
		$table_fields = $result->toArray();
		for ($i=0; $i < count($table_fields); $i++) {
			if($table_fields[$i]['Field'] == $field_name) return true;
		}
		return false;
	}

	public function existsValue($value, $field)
	{
		$result = $this->get($value, $field);
		if(empty($result)) return false;
		return true;
	}

	public function existsID($id)
	{
		$id = (int)$id;
		if(0 == $id) throw new \Application\Exception\Exception("Id should be only integer", 000);
		return $this->existsValue($id, 'id');
	}

	public function delete($value, $field = 'id')
	{
		return $this->tableGateway->delete(array($field => $value));
	}

	public function deleteOnFields(array $fields)
	{
		return $this->tableGateway->delete($fields);
	}

	public function setField($field, $value, $field_in, $value_in)
	{
		return $this->tableGateway->update(array($field => $value), array($field_in => $value_in));
	}

	public function setFieldOnID($id, $field, $value)
	{
		if(!$this->existsID($id)) return false;
		return $this->setField($field, $value, 'id', $id);
	}

	public function updateOnID($id, array $fields_values)
	{
		if(!$this->existsID($id)) return false;
		return $this->tableGateway->update($fields_values, array('id' => $id));
	}

	public function update($array_or_ID, $fields_values)
	{
		if(!is_array($array_or_ID)) return $this->updateOnID($array_or_ID, $fields_values);
		return $this->tableGateway->update($fields_values, $array_or_ID);
	}


	public function save(array $array)
	{
		foreach ($array as $key => $value) {
			// checking for internal table
			if(is_array($value)) unset($array[$key]);
		}
		$array = $this->hydrator->hydrate($array);
		if(!empty($array)){
			$primaryKey = $this->getPrimaryKey($this->tableName);
			$table_index = $primaryKey['key'];
			$this->checkPrimaryValue($primaryKey, $array);
			$saved_id = $this->writeToTable($this->tableGateway, $array, $table_index);
		} else throw new \Application\Exception\Exception("No any appropriate fields found in DB table", 1);

		return (isset($saved_id))? $saved_id : true;
	}



	// Saving multiple nested arrays with values to different DB tables
	// getting and writing m:n refference to connected tables if proper configured nested array sent
	// if array is not nested, using constructor values (or sent values) to write in current DB table
	// returns inserted value for single array, or true for nested arrays entry
	public function multiSave(array $array, $tableGateway = null, $tableName = null)
	{
		$mult_cache = array();
		foreach ($array as $key => $value) {
			// checking for internal table
			if(is_array($value)){
				if(empty($value)) continue;
				// Checking if the inner array is the other table name, or rows for the current table
				if(is_int(key($value))){
					// assigning name of the table for internal values
					$int_table = $key;
				foreach ($value as $innerValue) {
						// caching multiple entries, for getting primary table ID
						$mult_cache[$int_table][] = $innerValue;
					}
					// removing multiple sets from primary array(to avoid infinite recursion)
					unset($array[$int_table]);
				} else {
					// if inner array is other table value, write to other table and getting saved id, for current table reference
					$other_tableGateway = new TableGateway($key, $this->getAdapter());
					$saved_id = $this->multiSave($value,$other_tableGateway, $key);
					$array[$key] = $saved_id;
				}
	 		}
		}
		// does the table name sent (recursion case), or use constructor values
		$tableName = ($tableName)? $tableName : $this->tableName;
		$tableGateway = ($tableGateway)? $tableGateway : $this->tableGateway;
		$hydrator = new zHydrator($tableName, $tableGateway);
		// cleaning array from values that not belong to that table (my view of "hydrating)")
		$array = $hydrator->hydrate($array);
		if(!empty($array)){
			$primaryKey = $this->getPrimaryKey($tableName);
			$table_index = $primaryKey['key'];
			$this->checkPrimaryValue($primaryKey, $array);
			$saved_id = $this->writeToTable($tableGateway, $array, $table_index);
		}

		//writing multiple cache
		foreach ($mult_cache as $mult_tableName => $fields) {
			$tableGateway = new TableGateway($mult_tableName, $this->getAdapter());
			for ($i=0; $i < count($fields); $i++) {
				$m_field = $mult_cache[$mult_tableName][$i];
				// adding reference id leading to this table
				if($saved_id) $m_field[$tableName] = $saved_id;
				$this->multiSave($m_field, $tableGateway, $mult_tableName);
			}
		}
		return (isset($saved_id))? $saved_id : true;
	}


	protected function writeToTable($tableGateway, $data, $table_index)
	{

		if(isset($data[$table_index]) && $data[$table_index] != false) {
			if($this->getOnIndex($table_index, $data[$table_index], $tableGateway))
			{
				// if index sent and foud in db, means should be updated
				$tableGateway->update($data, array($table_index => $data[$table_index]));
				$saved_id = $data[$table_index];
			} else {
				//  if index sent and not found in dbtable, means insert new value with that index
				$tableGateway->insert($data);
				$saved_id = $data[$table_index];
			}
		} else {
			// if index not sent or empty, means insert and return autoincrement
			$tableGateway->insert($data);
			$saved_id = $tableGateway->getLastInsertValue();
		}

		return $saved_id;
	}

	// getting row on some fields value, tablegateway sent to fetch data from different tables
	protected function getOnIndex($table_index, $index_value, $tableGateway)
	{
		$rowset = $tableGateway->select(array($table_index => $index_value));
		$row = $rowset->current();
		return $row;
	}

	protected function getPrimaryKey($table_name)
	{
		$result = $this->query('DESCRIBE `'.$table_name.'`');
		$table_fields = $result->toArray();
		$primaryKey = array();
		foreach ($table_fields as $value) {
			if($value['Key'] == 'PRI') {
				$primaryKey['key'] = $value['Field'];
				$primaryKey['auto_i'] = ($value['Extra'] == 'auto_increment')? true : false;
			}
		}
		return $primaryKey;
	}

	protected function checkPrimaryValue($primaryKey, $value)
	{
		if($primaryKey['auto_i'] == false){
			if(!$value[$primaryKey['key']]){
				throw new \Application\Exception\Exception("Field is not auto incremented, so value for: {$primaryKey['key']} should be supplied");
			}
		}
		return true;
	}

	protected function result($result)
	{
		return $result;
	}


	public function query($query)
	{
		$dbAdapter = $this->getAdapter();
		return $dbAdapter->query($query, $dbAdapter::QUERY_MODE_EXECUTE);
	}

	public function getInsertFieldsString(array $fields)
	{
		$fields_str = '(';
		$f = count($fields);
		for ($i=0; $i < $f; $i++) {
			if($i > 0) $fields_str .= ', ';
			$fields_str .= $fields[$i];
		}
		$fields_str .= ')';

		return $fields_str;
	}

	public function getInsertValuesString(array $values_array)
	{
		$values_str = '';

		$c = count($values_array);
		for ($i=0; $i < $c; $i++) {
			if($i > 0) $values_str .= ', ';
			$k = 0;
				$values_str .= ' (';
				foreach ($values_array[$i] as $key => $value) {
					if($k > 0) $values_str .= ', ';
					if($value) $values_str .= "'".addslashes($value)."'";
					else $values_str .= " NULL";
					$k++;
				}
				$values_str .= ') ';
		}

		return $values_str;
	}

	protected function wrapForLike($string)
	{
		$str = '%';
		foreach(zbreakToWords($string) as $word) {
			$str .= $word;
			$str .= '%';
		}
		return $str;
	}

	// replacing fields in array with aliases
	public function replaceWithAliases($fields, $aliases)
	{
		foreach ($aliases as $key => $value) {
			$arr_key = array_search($key, $fields);
			if($arr_key !== false) {
				unset($fields[$arr_key]);
				if(is_array($value)) {
					$fields = array_merge($fields, $value);
				} else {
					$fields[$key] = $value;
				}
			}
		}
		return $fields;
	}

	public function getUnionFieldsStrings($arrays_collection)
	{
		$all_fields = [];
		foreach ($arrays_collection as $key => $array) {
			$arrays_collection[$key]['fields'] = array_flip($array['fields']);
			$all_fields = array_merge($all_fields, $arrays_collection[$key]['fields']);
		}
		$result = [];
		foreach ($arrays_collection as $union_name => $union_array) {
			$result[$union_name] = '';
			foreach ($all_fields as $key => $field) {
				if($result[$union_name] != '') $result[$union_name] .= ', ';
				if (isset($union_array['fields'][$key])) {
					if(is_string($union_array['fields'][$key])) {
						if(strpos($union_array['fields'][$key], '(') === false
							&& strpos($union_array['fields'][$key], "'") === false
							&& strpos($union_array['fields'][$key], ".") === false
						)
							$result[$union_name] .= $union_array['alias'].'.';
						$result[$union_name] .= $union_array['fields'][$key];
						$result[$union_name] .= ' '.$key;
					} else {
						if(strpos($union_array['fields'][$key], ".") === false)
							$result[$union_name] .= $union_array['alias'].'.';
						$result[$union_name] .= $key;
					}

				} else $result[$union_name] .= 'NULL '.$key;
			}
		}
		return $result;
	}

	protected function arrayToFields($fields_array, $alias = null, $predicat = null, $single = false)
	{
		$fields_string = '';
		$i = 0;
		foreach ($fields_array as $key => $value) {
			if(is_string($key)) {
			if($i > 0) $fields_string .= ', ';
				if(strpos($key, '(') === false && strpos($key, "'") === false)
					$value = $alias.$key.' '.$predicat.$value.' ';
				else $value = $key.' '.$predicat.$value.' ';
				$fields_string .= $value;
			} else {
				if(isset($fields_array[$value]) && $single) {
					//do nothing
				} else {
					if($i > 0) $fields_string .= ', ';
					if($predicat) $value = $alias.$value.' '.$predicat.$value.' ';
					else $value = $alias.$value;
					$fields_string .= $value;
				}

			}
			$i++;
		}
		return $fields_string;
	}

	protected function generateJsonFields($fields, $predicat = '', $res_count = 0, $order_string = '') {
		$separator = ($res_count)? '{%}' : '';
		$str = '';
		if($res_count) $str .= "REPLACE(";
		$str .= "CONCAT('[',";
		if($res_count) $str .= "substring_index(";
		$str .= "GROUP_CONCAT(DISTINCT CONCAT('{\"";
		$i = 0;
		$count = count($fields);
		foreach ($fields as $key => $value) {
			if(is_string($key)) {
				$str.= $value . "\":','\"'," . 'IFNULL('.$predicat.$key.', "")';
			} else $str.= $value . "\":','\"'," . 'IFNULL('.$predicat.$value.', "")';


			if ($i != $count - 1) {
				$str.= ",'\",\"";
			}
			$i++;
		}
		$str .= ",'\"}$separator') $order_string)";
		if($res_count) $str .= ",'$separator', $res_count)";
		$str .= ",']')";
		if($res_count) $str .= ", '$separator', '')";
		return $str;
	}


	// Getting a table for a subqueries cross tables requests
	protected function getTable($table_name)
	{
		$class_name = __NAMESPACE__.'\\'.$table_name;
		if(class_exists($class_name)) {
			return new $class_name;
		}

		try {
			$this->query('DESCRIBE `'.$table_name.'`');
			return new \Application\Model\zEmptyTable($table_name);

		} catch (\Exception $e) {
			$pdo_err = $e->getMessage();
			throw new \Application\Exception\Exception("Subquery table not found : $pdo_err", 1);
		}
	}


	public static function getAdapter()
	{
		if(!self::$dbAdapter) {
			// $config = include _ROOT_.'/config/autoload/db.local.php';
			$config = include __DIR__ .'/../../../../../config/autoload/db.local.php';
			self::$dbAdapter = new \Zend\Db\Adapter\Adapter($config['db']);
		}
		return self::$dbAdapter;
	}


}