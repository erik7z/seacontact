<?php
namespace Application\Model;


class NewsTable extends zEmptyTable
{

	CONST SECTION_VACANCY = 'vacancy';
	CONST SECTION_LOGBOOK = 'logbook';
	CONST SECTION_USER = 'user';
	CONST SECTION_QUESTIONS = 'questions';
	CONST SECTION_ANSWERS = 'answers';
	CONST SECTION_COMMENTS = 'comments';
	CONST SECTION_SOC_COMMENTS = 's_comments';

	public function __construct()
	{
		$this->init('news');
	}

	// returns some class CONSTANT
	protected function con($const)
	{
		return constant($const);
	}

	public static function getSections(){
		return [
            self::SECTION_VACANCY,
            self::SECTION_LOGBOOK,
            self::SECTION_USER,
            self::SECTION_QUESTIONS,
            self::SECTION_ANSWERS,
            self::SECTION_COMMENTS,
            self::SECTION_SOC_COMMENTS,
        ];
	}

	public static function getSectionResourceMap()
	{
		return [
		self::SECTION_COMMENTS => 'my\controller\comments',
		self::SECTION_LOGBOOK => 'application\controller\logbook',
		self::SECTION_VACANCY => 'application\controller\vacancies',
		self::SECTION_QUESTIONS => 'application\controller\questions',
		self::SECTION_ANSWERS => 'application\controller\questions.answer',
		];
	}

	public static function getSectionResource($section, $action = null)
	{
		$map = self::getSectionResourceMap();
		if(!isset($map[$section])) throw new \Application\Exception\Exception("Resource not found in section resources map", 1);
		$resource = $map[$section];
		if($action) {
			if($section == 'answers') $resource .= '-'.$action;
			else $resource .= '.'.$action;
		}
		return $resource;
	}

	public static function getSectionTableMap()
	{
		return [
		self::SECTION_COMMENTS => 'CommentsTable',
		self::SECTION_LOGBOOK => 'LogbookTable',
		self::SECTION_VACANCY => 'VacanciesTable',
		self::SECTION_QUESTIONS => 'QuestionsTable',
		self::SECTION_ANSWERS => 'QuestionAnswersTable',
		];
	}

	public static function getSectionTable($section)
	{
		$map = self::getSectionTableMap();
		if(!isset($map[$section])) throw new \Application\Exception\Exception("Table not found in section resources map", 1);
		return $map[$section];
	}

	public function getNews($viewer_id, $filters = array(), $options = array()){
		$this->setDefaultOptions($options, ['_limit' => 200, '_order' => 'time']);
		
		$show_hidden = (isset($options['show_hidden']))? $options['show_hidden'] : false;
		$only_later = (isset($options['only_later']))? $options['only_later'] : false;
		$only_current = (isset($options['only_current']))? $options['only_current'] : false;
		$dump_time = (isset($options['dump_time']))? $options['dump_time'] : 10;

		$stats_fields = isset($options['_stats_fields'])? $options['_stats_fields'] : $this->getStatsFields();
		$stats_fields = array_flip($stats_fields);

		$user_fields = isset($options['_user_fields'])? $options['_user_fields'] : ['name', 'surname', 'full_name', 'company_name', 'login', 'avatar', 'cv_avatar', 'type', 'role'];
		$user_fields = array_merge(['id' => 'user_id'], $user_fields);
		$user_fields_string = $this->arrayToFields($user_fields, '`u`.');


		$time = time();
		if(!$viewer_id) $viewer_id = 0;

		$where = '';
		// if(!$show_hidden) {
			$where .= ' WHERE `active` = 1';
		// }



		foreach ($filters as $filter => $value) {
			if($filter == 'tag') {
				$where .= ($where == '')? ' WHERE ' : ' AND ';
				$where .= "`tags` LIKE '%$value%'";
				unset($filters[$filter]);
			} else if($filter == 'owner_id') {
				$where .= ($where == '')? ' WHERE ' : ' AND ';
				$where .= " `user` = '$value' ";
				unset($filters[$filter]);
			}
		}
		$req_where = $where;

		if($only_later) {
			$where .= ($where == '')? ' WHERE ' : ' AND ';
			$where .= ' time > '.time().' ';
		}

		if($only_current) {
			$where .= ($where == '')? ' WHERE ' : ' AND ';
			$where .= ' time <= '.time().' ';
		}

		$ul_fields = ['id']; 
		if (!$this->count) {
			$ul_fields = [
				"'logbook'" => 'post_type',
				'id', 'id' => 'article_id', 'title', 'text', 'video', 'tags', 'user', 'time','active', 'location',
			];
			if(isset($stats_fields['total_comments'])) $ul_fields[$this->getCommentsCountSelect('logbook','ul')] = 'total_comments';
			if(isset($stats_fields['likes'])) $ul_fields[$this->getLikesCountSelect('logbook','ul')] = 'likes';
			if(isset($stats_fields['likers'])) $ul_fields[$this->getLikersSelect('logbook','ul')] = 'likers';
			if(isset($stats_fields['like_status'])) $ul_fields[$this->getLikeStatusSelect($viewer_id, 'logbook','ul')] = 'like_status';
			if(isset($stats_fields['soc_likes'])) $ul_fields[$this->getSocialLikesCountSelect('logbook','ul')] = 'soc_likes';
			if(isset($stats_fields['soc_likers'])) $ul_fields[$this->getSocialLikersSelect('logbook','ul')] = 'soc_likers';
			if(isset($stats_fields['views'])) $ul_fields[$this->getViewsCountSelect('logbook','ul')] = 'views';
			if(isset($stats_fields['pics'])) $ul_fields[$this->getPicsSelect('', 'logbook','ul')] = 'pics';
			if(isset($stats_fields['videos'])) $ul_fields[$this->getVideosSelect('logbook','ul')] = 'videos';
			if(isset($stats_fields['links'])) $ul_fields[$this->getLinksSelect('logbook','ul')] = 'links';
		}

		$vacancies_table = $this->sl()->get('VacanciesTable');
		$v_fields = ['id']; 
		if (!$this->count) {
			$v_fields = [
				"'vacancy'" => 'post_type',
				'id', 'id' => 'article_id', 'title', 'text', 'video', 'tags', 'user', 'time','active', 
				'rank','salary','salary_unit','ship_name','ship_type','ship_dwt','ship_built',
				'date_join','contract_length','crew_nationality','english','comments','urgent',
				'user' => 'company_id',
			];
			if(isset($stats_fields['total_comments'])) $v_fields[$this->getCommentsCountSelect('vacancy','v')] = 'total_comments';
			if(isset($stats_fields['likes'])) $v_fields[$this->getLikesCountSelect('vacancy','v')] = 'likes';
			if(isset($stats_fields['likers'])) $v_fields[$this->getLikersSelect('vacancy','v')] = 'likers';
			if(isset($stats_fields['like_status'])) $v_fields[$this->getLikeStatusSelect($viewer_id, 'vacancy','v')] = 'like_status';
			if(isset($stats_fields['views'])) $v_fields[$this->getViewsCountSelect('vacancy','v')] = 'views';
			if(isset($stats_fields['subscribers'])) $v_fields['('.$vacancies_table->getVacanciesSubsCountQuery().')'] = 'subscribers';
			if(isset($stats_fields['subscribe_status'])) $v_fields["(SELECT IF(id, true, false) id FROM `vacancy_subscribers` WHERE user_id = $viewer_id AND vacancy_id = v.id)"] = 'subscribe_status';
			if(isset($stats_fields['reports'])) $v_fields['('.$vacancies_table->getVacanciesRepsCountQuery().')'] = 'reports';
			if(isset($stats_fields['report_status'])) $v_fields["(SELECT IF(id, true, false) id FROM `vacancy_reports` WHERE user_id = $viewer_id AND vacancy_id = v.id)"] = 'report_status';
			if(isset($stats_fields['pics'])) $v_fields[$this->getPicsSelect('', 'vacancy','v')] = 'pics';

		}

		$q_fields = ['id']; 
		if (!$this->count) {
			$q_fields = [
				"'questions'" => 'post_type',
				'id', 'id' => 'article_id', 'title', 'text', 'video', 'tags', 'user', 'time','active', 
				'post_vk',	'post_vk_id', 'post_vk_time', 'anonym'
			];
			if(isset($stats_fields['total_comments'])) $q_fields[$this->getCommentsCountSelect('questions','q')] = 'total_comments';
			if(isset($stats_fields['likes'])) $q_fields[$this->getLikesCountSelect('questions','q')] = 'likes';
			if(isset($stats_fields['likers'])) $q_fields[$this->getLikersSelect('questions','q')] = 'likers';
			if(isset($stats_fields['like_status'])) $q_fields[$this->getLikeStatusSelect($viewer_id, 'questions','q')] = 'like_status';
			if(isset($stats_fields['soc_likes'])) $q_fields['@p3:= '.$this->getSocialLikesCountSelect('questions','q')] = 'soc_likes';
			if(isset($stats_fields['views'])) $q_fields[$this->getViewsCountSelect('questions','q')] = 'views';
			if(isset($stats_fields['subscribers'])) $q_fields['(SELECT COUNT(DISTINCT `user_id`) FROM `question_subscribers` WHERE `question_id` = `q`.`id`)'] = 'subscribers';
			if(isset($stats_fields['subscribe_status'])) $q_fields["(SELECT IF(id, true, false) id FROM `question_subscribers` WHERE user_id = '$viewer_id' AND question_id = q.id)"] = 'subscribe_status';
			if(isset($stats_fields['up_votes'])) $q_fields['@p1:= '.$this->getUpVotesSelect('questions', 'q')] = 'up_votes';
			if(isset($stats_fields['down_votes'])) $q_fields['@p2:= '.$this->getDownVotesSelect('questions', 'q')] = 'down_votes';
			if(isset($stats_fields['vote_status'])) $q_fields[$this->getVoteStatusSelect($viewer_id, 'questions','q')] = 'vote_status';
			if(isset($stats_fields['total_rating'])) $q_fields['(@p1 - @p2 + @p3)'] = 'total_rating';
			if(isset($stats_fields['answers'])) $q_fields[$this->getAnswersCountSelect('q')] = 'answers';
			if(isset($stats_fields['answered'])) $q_fields[$this->getAnsweredStatusSelect('q')] = 'answered';
			if(isset($stats_fields['pics'])) $q_fields[$this->getPicsSelect('', 'questions','q')] = 'pics';
		}



		$a_fields = ['id']; 
		if (!$this->count) {
			$a_fields = [
				"'answer'" => 'post_type',
				'id','id' => 'article_id', 'text', 'video', 'tags', 'user', 'time','active', 
				'question_id', 'correct', 'anonym',
				'(SELECT q.text FROM `questions` q WHERE q.id = qa.question_id)' => 'q_text',
			];

			if(isset($stats_fields['total_comments'])) $a_fields[$this->getCommentsCountSelect('answers','qa')] = 'total_comments';
			if(isset($stats_fields['likes'])) $a_fields[$this->getLikesCountSelect('answers','qa')] = 'likes';
			if(isset($stats_fields['likers'])) $a_fields[$this->getLikersSelect('answers','qa')] = 'likers';
			if(isset($stats_fields['like_status'])) $a_fields[$this->getLikeStatusSelect($viewer_id, 'answers','qa')] = 'like_status';
			if(isset($stats_fields['up_votes'])) $a_fields['@p1:= '. $this->getUpVotesSelect('answers', 'qa')] = 'up_votes';
			if(isset($stats_fields['down_votes'])) $a_fields['@p2:= '. $this->getDownVotesSelect('answers', 'qa')] = 'down_votes';
			if(isset($stats_fields['vote_status'])) $a_fields[$this->getVoteStatusSelect($viewer_id, 'answers','qa')] = 'vote_status';
			if(isset($stats_fields['pics'])) $a_fields[$this->getPicsSelect('', 'answers','qa')] = 'pics';
		}

		$union_str = $this->getUnionFieldsStrings([
			'ul' => ['alias' => 'ul', 'fields' => $ul_fields], 
			'v' => ['alias' => 'v', 'fields' => $v_fields],
			'q' => ['alias' => 'q', 'fields' => $q_fields],
			'qa' => ['alias' => 'qa', 'fields' => $a_fields]
		]);

		$user_fields_string = ($this->count)? '' : $user_fields_string.', ';

		$logbook_select = 
				"SELECT 
					$user_fields_string
					{$union_str['ul']}
					FROM `user_logbook` ul
					LEFT JOIN `user` u ON (u.`id` = ul.`user`)
					$where
					";

		$vacancy_select = 
				"SELECT 
					$user_fields_string
					{$union_str['v']}
					FROM `vacancies` v
					LEFT JOIN `user` u ON (u.`id` = v.`user`)
					$where
					";

		$questions_select = 
				"SELECT 
					$user_fields_string
					{$union_str['q']}
					FROM `questions` q
					LEFT JOIN `user` u ON (u.`id` = q.`user`)
					$where
					";

		$answers_select = 
				"SELECT 
					$user_fields_string
					{$union_str['qa']}
					FROM `question_answers` qa
					LEFT JOIN `user` u ON (u.`id` = qa.`user`)
					$where
					AND `soc_name` IS NULL
					";

		if(count($filters) == 0) 
			$select = $logbook_select." 
				UNION ".$vacancy_select." 
				UNION ".$questions_select." 
				UNION ".$answers_select;
		else foreach ($filters as $filter => $value) {
				$select = $logbook_select." UNION ".$vacancy_select." UNION ".$questions_select." UNION ".$answers_select;
			if($filter == 'section') {
					switch ($value) {
						case 'logbook':
							$select = $logbook_select;
							break;
						case 'vacancies':
							$select = $vacancy_select;
							break;
						case 'questions':
							$select = $questions_select;
							break;
						case 'answers':
							$select = $answers_select;
							break;
					}
				} 
			}

		if($this->count){
			return $this->query(
				"SELECT COUNT(*) count
					FROM (
						$select
						) x
				");
		} else {
				$query = "$select
					$this->order_string
					$this->limit_string $this->offset_string
				";
				// d($query);
			$predicat = '_'.md5($req_where);
			$dump_name = z_generateNameFromMethod(get_class($this).'::'.__FUNCTION__, 0);
			// d($query);
			return $this->getCachedRequest($query, $dump_name, $predicat, 0, $dump_time);

		}
	}






	public function getVkParsedNews($filters = [], $options = [])
	{
		$this->setDefaultOptions($options, [
			'_limit' => 100, 
			'_order' => 'time'
			]);

		$where = '';
		if(count($filters)) {
			foreach ($filters as $filter => $value) {
				if($value) $where .= ($where == '') ? ' WHERE ' : ' AND ';

				if ($filter == 'wall_id') {
					$where .= " post_vk_wall = '$value' ";
				} else if ($filter == 'post_vk_id') {
					$where .= " post_vk_id = '$value' ";
				} else throw new \Application\Exception\Exception("Filter not recognized", 1);
			}
		} else {
			$where = "  
				WHERE post_vk_wall IS NOT NULL
				AND post_vk_wall != ''
				AND post_vk_id IS NOT NULL
				AND post_vk_id != ''
			";
		}




		$this->query("SET SESSION group_concat_max_len = 1000000;");
		return $this->query(
			"SELECT
				'logbook' section
				, id, post_vk_id, time
				, {$this->getSocialCommentsCountSelect('logbook','ul')} soc_comments
				, {$this->getVkLastCommentId('logbook','ul')} vk_last_comment_id
				, {$this->getSocialLikesCountSelect('logbook','ul')} soc_likes
				FROM `user_logbook` ul
				$where
	
				UNION
				SELECT 
				'vacancy' section
				, id, post_vk_id, time
				, {$this->getSocialCommentsCountSelect('vacancy','v')} soc_comments
				, {$this->getVkLastCommentId('vacancy','v')} vk_last_comment_id
				, {$this->getSocialLikesCountSelect('vacancy','v')} soc_likes
				FROM `vacancies` v
				$where

				UNION
				SELECT
				'questions' section 
				, id, post_vk_id, time
				, {$this->getSocialCommentsCountSelect('questions','q')} soc_comments
				, {$this->getVkLastCommentId('questions','q')} vk_last_comment_id
				, {$this->getSocialLikesCountSelect('questions','q')} soc_likes
				FROM `questions` q
				$where

				UNION
				SELECT
				'answers' section 
				, id, post_vk_id, time
				, {$this->getSocialCommentsCountSelect('answers','qa')} soc_comments
				, {$this->getVkLastCommentId('answers','qa')} vk_last_comment_id
				, {$this->getSocialLikesCountSelect('answers','qa')} soc_likes
				FROM `question_answers` qa
				$where

				$this->order_string
				$this->limit_string
				$this->offset_string
			");
	}

}