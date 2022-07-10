<?php
namespace Application\Model;

class QuestionAnswersTable extends zAbstractTable
{

	public function __construct()
	{
		$this->init('question_answers');
	}


	public function getAnswers($viewer_id = null, $filters = null, $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 20, '_order'=> 'total_rating desc, time ']);
		$user_fields = isset($options['_user_fields'])? $options['_user_fields'] : ['name', 'surname', 'full_name', 'company_name', 'login', 'avatar', 'cv_avatar', 'type', 'role'];
		$user_fields = array_merge(['id' => 'user_id'], $user_fields);

		$db_fields = isset($options['_answer_fields'])? $options['_answer_fields'] : $this->getStandartFields();
		$db_fields = array_merge(['id' => 'answer_id'], $db_fields);

		$stats_fields = isset($options['_stats_fields'])? $options['_stats_fields'] : $this->getStatsFields();
		$stats_fields = array_flip($stats_fields);

		// removing coments list 
		unset($stats_fields['comments_list']);
		
		$show_social = (isset($options['show_social'])) ? ($options['show_social']) : 1;

		$where = '';
		if ($filters) {
			foreach ($filters as $filter => $value) {
				if($value == '' || $value == null || $value == 0 || $value == false) next($filters);

				if($value) $where .= ($where == '') ? ' WHERE ' : ' AND ';

				if ($filter == 'user_role') {
					$where .= " `u`.role = '$value' ";
				} else if ($filter == 'owner_id') {
					$where .= " `qa`.user = '$value' ";
				} else if ($filter == 'question_id') {
					$where .= " `qa`.question_id = '$value' ";
				} else if ($filter == 'answer_id') {
					$where .= " `qa`.id = '$value' ";
				} 
			}		
		}

		$where .= ($where == '') ? ' WHERE ' : ' AND ';
		$where .= ' qa.active = 1 ';

		$fields_string ='';
		$joins = ' INNER JOIN `questions` q ON (qa.question_id = q.id) ';
		if ($this->count) {
			$db_str = ' qa.id ';
			$soc_str = ' qa.id ';
			$soc_user_fields = '';
			$db_user_fields = '';
		} else {
	
			$q_fields = [
				'user' => 'q_author_id',
				'title' => 'q_title',
				'text' => 'q_text',
				'anonym' => 'q_anonym',
				'time' => 'q_time',
				'tags' => 'q_tags',
			];
			$q_user_fields = [
				'name' => 'q_name',
				'surname' => 'q_surname',
				'full_name' => 'q_full_name',
				'login' => 'q_login',
				'avatar' => 'q_avatar',
				'cv_avatar' => 'q_cv_avatar',
			];
			$soc_u_fields = [
				'soc_name',
				'soc_user_id',
				'name' => 'soc_name',
				'surname' => 'soc_surname',
				'soc_domain',
				'soc_page',
				'avatar' => 'soc_avatar',
			];

			if(isset($stats_fields['pics'])) $db_fields[$this->getPicsSelect('', 'answers','qa')] = 'pics';
			if(isset($stats_fields['likes'])) $db_fields[$this->getLikesCountSelect('answers','qa')] = 'likes';
			if(isset($stats_fields['total_comments'])) $db_fields[$this->getCommentsCountSelect('answers','qa')] = 'total_comments';
			if(isset($stats_fields['comments_list'])) $db_fields[$this->getCommentsSelect($viewer_id,'answers','qa')] = 'comments_list';
			if(isset($stats_fields['likers'])) $db_fields[$this->getLikersSelect('answers','qa')] = 'likers';
			if(isset($stats_fields['like_status'])) $db_fields[$this->getLikeStatusSelect($viewer_id, 'answers','qa')] = 'like_status';
			if(isset($stats_fields['vote_status'])) $db_fields[$this->getVoteStatusSelect($viewer_id, 'answers', 'qa')] = 'vote_status';
			$db_fields['@p1:= '.$this->getUpVotesSelect('answers', 'qa')] = 'up_votes';
			$db_fields['@p2:= '.$this->getDownVotesSelect('answers', 'qa')] = 'down_votes';
			$db_fields[' (@p1 - @p2 + IFNULL(`soc_likes`,0)) '] = 'total_rating';


			$union_str = $this->getUnionFieldsStrings(['soc_u' => ['alias' => 'su', 'fields' => $soc_u_fields], 'db_u' => ['alias' => 'u', 'fields' => $user_fields]]);
			$db_user_fields = ', '.$union_str['db_u'];
			$soc_user_fields = ', '.$union_str['soc_u'];

			$q_fields_str = $this->arrayToFields($q_fields, 'q.');
			$q_user_fields_str =  $this->arrayToFields($q_user_fields,'qu.');

			$db_str = $this->arrayToFields($db_fields,'qa.');
			$db_str = 
				$db_str.' 
				,'.$q_fields_str.' 
				,'.$q_user_fields_str;
			$joins .= "LEFT JOIN `user` qu ON (qu.id = (SELECT user FROM `questions` WHERE id = qa.question_id))";
		}



		$this->query("SET SESSION group_concat_max_len = 1000000;");

		$select =
			"SELECT 
				$db_str
				$db_user_fields
				FROM `{$this->tableName}` qa
				$joins
				INNER JOIN `user` u ON (u.id = qa.user)
				$where 
			";
		if($show_social) {
			$select .= "
			UNION 

			SELECT 
				$db_str
				$soc_user_fields
				FROM `{$this->tableName}` qa
				$joins
				INNER JOIN `social_users` su ON (su.soc_user_id = qa.soc_user_id)
				$where 
				";
		}
		if($this->count){
			$query = "
				SELECT COUNT(*) count
				FROM (
					$select
					) x
				";
		} else {
			$query = 
			" $select
			$this->order_string
			$this->limit_string $this->offset_string
			";
			// d($query);
		}

		return $this->query($query);
	}

	public function getQuestionByAnswerId($answer_id)
	{
		return $this->query(
			"SELECT q.*
				, u.id user_id, u.login, u.name, u.surname, u.full_name, u.role, u.type, u.avatar, u.cv_avatar
				, qa.text answer_text, qa.correct, qa.user answer_by, qa.time answer_time
				FROM `question_answers` qa
				INNER JOIN `questions` q ON (q.id = qa.question_id)
				INNER JOIN `user` u ON (u.id = q.user)
				WHERE qa.id = '$answer_id'
			");
	}

	public function accept($answer_id, $question_id)
	{
		return $this->query(
			"START TRANSACTION;".
				"UPDATE `question_answers` 
					SET `correct`= NULL
					WHERE `question_id` = '$question_id';".
				"UPDATE `question_answers` 
					SET `correct`= 1
					WHERE `id` = '$answer_id';".
			"COMMIT;"
			);
	}

	public function removeAccept($question_id)
	{
		return $this->query(
			"UPDATE `question_answers` 
				SET `correct`= NULL
				WHERE `question_id` = '$question_id';"
			);
	}

	public function delete($id, $viewer_id = 0)
	{
		// Revise delete procedure!!!!
		parent::delete($id);
		$picsTable = $this->getTable('PicsTable');
		$pics_list = $picsTable->getArticlePics('answers', $id);
		foreach ($pics_list as $pic) {
			$picsTable->delete($pic['id'], ['delete_from_article_table' => false, 'article_table_name' => false, 'user_id' => $viewer_id]);
		}
		return true;

	}






}