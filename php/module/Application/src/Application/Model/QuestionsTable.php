<?php
namespace Application\Model;

class QuestionsTable extends zAbstractTable
{

	public function __construct()
	{
		$this->init('questions');
	}


	public function getQuestions($viewer_id = null, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 200, '_order' => 'time']);
		$user_fields = isset($options['_user_fields'])? $options['_user_fields'] : ['name', 'surname', 'full_name', 'login', 'avatar', 'cv_avatar', 'type', 'role'];
		$user_fields = array_merge(['id' => 'user_id'], $user_fields);

		$question_fields = isset($options['_question_fields'])? $options['_question_fields'] : $this->getStandartFields();
		$question_fields = array_merge(['id' => 'question_id'], $question_fields);
		$stats_fields = isset($options['_stats_fields'])? $options['_stats_fields'] : $this->getStatsFields();
		$stats_fields = array_flip($stats_fields);
		$user_fields_string =  $this->arrayToFields($user_fields,'u.');
		$question_fields_string =  $this->arrayToFields($question_fields, 'q.');
	
		$where = '';
		foreach ($filters as $filter => $value) {
			if(!$value) next($filters);
			if($value) $where .= ($where == '') ? ' WHERE ' : ' AND ';

			if ($filter == 'question_id') {
				$where .= " `q`.id = '$value' ";
			} else if ($filter == 'user_id') {
				$where .= " `q`.user = '$value' ";
			} else if ($filter == 'user_role') {
				$where .= " `u`.role = '$value' ";
			} else if ($filter == 'unanswered') {
				$where .= " `q`.id NOT IN (SELECT question_id FROM `question_answers`) ";
			} else if ($filter == 'exclude') {
				$where .= " `q`.id != '$value' ";
			} else if ($filter == 'answered') {
				$where .= " `q`.id IN (SELECT question_id FROM `question_answers`) ";
			} else if ($filter == 'completed') {
				$where .= " `q`.id IN (SELECT question_id FROM `question_answers` WHERE correct = 1) ";
			} else if ($filter == 'tag' || $filter == 'tags') {
				if(is_array($value)) {
					$where .= " ( ";
						for ($i=0; $i < count($value); $i++) { 
							if($i>0) $where .= ' OR ';
							$where .= " `q`.tags LIKE '%{$value[$i]}%' "; 
						}
						$where .= ')';
				} else $where .= " `q`.tags LIKE '%$value%' ";
			} else if ($filter == 'query' || $filter == 'q') {
				$where .= " `q`.text LIKE '%$value%' OR `q`.tags LIKE '%$value%' OR `q`.title LIKE '%$value%' ";
			} else throw new \Application\Exception\Exception("Filter not recognized", 1);
		}

		$where .= ($where == '') ? ' WHERE ' : ' AND ';
		$where .= ' q.active = 1 ';
		$fields_string = '';
		if ($this->count) {
			$fields_string = ' COUNT(*) count ';
			$this->order_string = '';
			$this->limit_string = '';
			$this->offset_string = '';
		} else {
			$fields_string .= $question_fields_string;
			$fields_string .= ' ,'. $user_fields_string;
			if(isset($stats_fields['pics'])) $fields_string .= ", {$this->getPicsSelect('', 'questions','q')} pics ";
			if(isset($stats_fields['total_comments'])) $fields_string .= ", {$this->getCommentsCountSelect('questions','q')} total_comments";
			if(isset($stats_fields['comments_list'])) $fields_string .= ", {$this->getCommentsSelect($viewer_id,'questions','q')} comments_list ";
			if(isset($stats_fields['likes'])) $fields_string .= ", {$this->getLikesCountSelect('questions','q')} likes ";
			if(isset($stats_fields['views'])) $fields_string .= ", {$this->getViewsCountSelect('questions','q')} views ";
			if(isset($stats_fields['likers'])) $fields_string .= ", {$this->getLikersSelect('questions','q')} likers ";
			if(isset($stats_fields['like_status'])) $fields_string .= ", {$this->getLikeStatusSelect($viewer_id, 'questions','q')} like_status ";
			if(isset($stats_fields['subscribers'])) $fields_string .= ", (SELECT COUNT(DISTINCT `user_id`) FROM `question_subscribers` WHERE `question_id` = `q`.`id`) subscribers ";
			if(isset($stats_fields['subscribe_status'])) $fields_string .= ", (SELECT IF(id, true, false) id FROM `question_subscribers` WHERE user_id = '$viewer_id' AND question_id = q.id) subscribe_status ";
			if(isset($stats_fields['answers'])) $fields_string .= ", {$this->getAnswersCountSelect('q')} answers";
			if(isset($stats_fields['answered'])) $fields_string .= ", {$this->getAnsweredStatusSelect('q')} answered ";
			if(isset($stats_fields['vote_status'])) $fields_string .= ", {$this->getVoteStatusSelect($viewer_id, 'questions','q')} vote_status ";
			if(isset($stats_fields['soc_likers'])) $fields_string .= ", {$this->getSocialLikersSelect('questions','q')} soc_likers ";
			if(isset($stats_fields['vk_last_comment_id'])) $fields_string .= ", {$this->getVkLastCommentId('questions','q')} vk_last_comment_id ";
		
			$fields_string .= ", @p1:= {$this->getUpVotesSelect('questions', 'q')} up_votes";
			$fields_string .= ", @p2:= {$this->getDownVotesSelect('questions', 'q')} down_votes";
			$fields_string .= ", @p3:= {$this->getSocialLikesCountSelect('questions','q')} soc_likes ";
			$fields_string .= ", (@p1 - @p2 + @p3) total_rating ";
		
		}
		$join_string = "LEFT JOIN `user` u ON (u.id = q.user)";

		$this->query("SET SESSION group_concat_max_len = 1000000;");
		return $this->query(
			"SELECT $fields_string
				FROM `questions` q
				$join_string
				$where 
				$this->order_string
				$this->limit_string
				$this->offset_string
			");
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
		$answersTable = $this->getTable('QuestionAnswersTable');
		$answers_list = $answersTable->getAllOnField('question_id', $id);
		foreach ($answers_list as $answer) {
			$answersTable->delete($answer->id, $viewer_id);
		}
		$section = \Application\Model\NewsTable::SECTION_QUESTIONS;
		$tagsTable = $this->getTable('TagMapTable');
		$tagsTable->deleteArticleTags($section, $id);
		$this->sl()->get('SocialCommentsTable')->deleteOnFields(['section' => $section, 'section_id' => $id]);

		return true;

	}


}