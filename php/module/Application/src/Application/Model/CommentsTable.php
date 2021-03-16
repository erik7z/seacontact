<?php
namespace Application\Model;

class CommentsTable extends zAbstractTable
{
	public function __construct()
	{
		$this->init('comments');
	}


	public function addComment($user_id, $comment, $section, $section_id, $reply_on = null)
	{
		$time = time();
		$same_comment_not_often_than = 30;
		$last_time_same_comment = $time - $same_comment_not_often_than;
		return $this->query(
			"INSERT INTO `$this->tableName` (user_id, comment, section, section_id, reply_on, time)
				SELECT * FROM (SELECT '$user_id' user_id, '$comment' comment, '$section' section, '$section_id' section_id, '$reply_on' reply_on, '$time' time) as tmp
				WHERE NOT EXISTS (
				    SELECT id FROM `$this->tableName`
				    WHERE user_id = '$user_id'
				    AND section = '$section'
				    AND section_id = '$section_id'
				    AND comment = '$comment'
				    AND time > '$last_time_same_comment'
				) LIMIT 1
			");
	}


	public function deleteComment($comment_id)
	{
		return $this->query(
			"DELETE c.* 
				FROM `{$this->tableName}` c
				WHERE c.`id` = '$comment_id'
				");
	}

	public function hasComments($section, $section_id)
	{
		$result =  $this->query(
			"SELECT id
			FROM {$this->tableName}
			WHERE `section` = '$section'
			AND `section_id` = '$section_id'
			LIMIT 1
			")->current();
		return (bool) $result;
	}


	public function getComment($id, $viewer_id)
	{
		return $this->query(
			"SELECT c.*
				, u.id user_id, u.login, u.name, u.surname, u.full_name, u.avatar, u.cv_avatar
				, {$this->getVotesSelect('comments','c')}
				, {$this->getVoteStatusSelect($viewer_id, 'comments','c')} vote_status
				FROM `{$this->tableName}` c
				INNER JOIN `user` u ON (u.id = c.user_id)
				WHERE c.`id` = '$id'
			"
			)->current();
	}



	public function getComments($section = null, $section_id = null, $filters = [],  $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => _VIEW_COMMENT_LIMIT_, '_order' => 'time', 'up' => 1]);

		$viewer_id = (isset($options['viewer_id'])) ? ($options['viewer_id']) : 0;
		$user_fields = isset($options['_user_fields'])? $options['_user_fields'] : ['name', 'surname', 'full_name', 'company_name', 'login', 'avatar', 'cv_avatar', 'type', 'role'];
		$user_fields = array_merge(['id' => 'author_id'], $user_fields);

		$db_fields = isset($options['_comments_fields'])? $options['_comments_fields'] : $this->getStandartFields();
		$db_fields = array_merge(['id' => 'comment_id'], $db_fields);

		$show_social = (isset($options['show_social'])) ? ($options['show_social']) : 1;
		$only_active = (isset($options['only_active'])) ? ($options['only_active']) : 1;
		$show_article_fields = (isset($options['show_article_fields'])) ? ($options['show_article_fields']) : 0;

		$where = '';
		$outer_where = '';
		if($only_active) {
			$where .= ($where)? ' AND ' : ' WHERE ';
			$where .= " c.`active` = 1 ";
		}

		if($section) {
			$where .= ($where)? ' AND ' : ' WHERE ';
			$where .= " `section` = '$section' ";
		}

		if($section_id) {
			$where .= ($where)? ' AND ' : ' WHERE ';
			$where .= " `section_id` = '$section_id' ";
		}

		if ($filters) {
			foreach ($filters as $filter => $value) {
				$q_where = '';
				if($value == '' || $value == null || $value == 0 || $value == false) next($filters);
				if ($filter == 'user_id') {
					$q_where .= " u.id = '$value' ";
				} else if ($filter == 'comment_id') {
					$q_where .= " comment_id = '$value' ";
				} else if ($filter == 'user_role') {
					$q_where .= " `u`.role = '$value' ";
				} else if ($filter == 'section') {
					$q_where .= " `c`.section = '$value' ";
				} else if ($filter == 'source') {
					if($value == 'local') $outer_where .= " WHERE source = 'local' ";
					elseif ($value == 'vk') $outer_where .= " WHERE source = 'vk' ";
				} else throw new \Application\Exception\Exception("Filter not recognized", 1);

				if($q_where) {
					$where .= ($where == '') ? ' WHERE ' : ' AND ';
					$where .= $q_where;
				}
			}		
		}
		$fields_string ='';
		$joins = '';
		if($this->count) {
			$db_str = " c.id, 'local' source ";
			$soc_str = ' c.id, c.soc_name source';
		} else {
			$db_fields["'local'"] = 'source';
			$db_fields[$this->getUpVotesSelect('comments','c')] = 'up_votes';
			$db_fields[$this->getDownVotesSelect('comments','c')] = 'down_votes';
			$db_fields[$this->getVoteStatusSelect($viewer_id, 'comments','c')] = 'vote_status';

			$soc_fields = [
				'id',
				'soc_name' => 'source',
				'soc_com_id' => 'comment_id',
				$this->getSocCommPicsSelect('c') => 'pics',
				'user_id',
				'section',
				'section_id',
				'comment',
				'soc_likes' => 'up_votes',
				'soc_reply_to_post' => 'reply_on',
				'time',
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

			$user_fields_string =  $this->arrayToFields($user_fields,'u.', null, 1);
			$union_u_str = $this->getUnionFieldsStrings(['soc' => ['alias' => 'su', 'fields' => $soc_u_fields], 'db' => ['alias' => 'u', 'fields' => []]]);
			
			$union_str = $this->getUnionFieldsStrings(['soc' => ['alias' => 'c', 'fields' => $soc_fields], 'db' => ['alias' => 'c', 'fields' => $db_fields]]);
			$db_str = $union_str['db'].' ,'.$union_u_str['db'].' ,'.$user_fields_string;
			$soc_str = $union_str['soc'].' ,'.$union_u_str['soc'].' ,'.$user_fields_string;

			if($show_article_fields) {
				$fields_string .= "
								, v.title vacancy_title, v.text vacancy_text
								, lb.title logbook_title, lb.text logbook_text
								, q.title question_title, q.text question_text
								, qa.text answer_text, qa.question_id
								";

				$joins .= "
					LEFT JOIN `vacancies` v ON (section = 'vacancy' AND section_id = v.id)
					LEFT JOIN `user_logbook` lb ON (section = 'logbook' AND section_id = lb.id)
					LEFT JOIN `questions` q ON (section = 'questions' AND section_id = q.id)
					LEFT JOIN `question_answers` qa ON (section = 'answers' AND section_id = qa.id)
					";
			}
		}

		$joins .= ' LEFT JOIN `user` u ON (u.id = user_id) ';

		$select =
			"SELECT 
				$db_str
				$fields_string
				FROM `{$this->tableName}` c
				$joins
				$where 
			";
		if($show_social) {
			$select .= "
			UNION 

			SELECT  
				$soc_str
				$fields_string
				FROM `social_comments` c
				$joins
				INNER JOIN `social_users` su ON (su.soc_user_id = c.soc_user_id)
				$where 
				";
		}
		if($this->count){
			$query = "
				SELECT COUNT(*) count
				FROM ( 
					SELECT * FROM ( 
						$select
						) ix
						$outer_where
					) x
				";
		} else {


			$query = "
			SELECT * FROM ( 
				$select
			) ix
			$outer_where
			$this->order_string
			$this->limit_string $this->offset_string
			";

		}
		return $this->query($query);
	}


}