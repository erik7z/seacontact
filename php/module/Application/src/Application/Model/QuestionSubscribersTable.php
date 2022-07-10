<?php
namespace Application\Model;

class QuestionSubscribersTable extends zEmptyTable
{

	public function __construct()
	{
		$this->init('question_subscribers');
	}


	public function subscribe($user_id, $question_id)
	{
		if($this->isSubscribed($user_id, $question_id) === true) return false;
		$this->tableGateway->insert(array(
			'user_id' => $user_id, 
			'question_id' => $question_id,
			'time' => time()
			));
		return true;
	}
	
	public function unsubscribe($user_id, $question_id)
	{
		if($this->isSubscribed($user_id, $question_id) === false) return false;
		$this->tableGateway->delete(array(
			'user_id' => $user_id, 
			'question_id' => $question_id
				));
		return true;
	}
	
	public function isSubscribed($user_id, $question_id)
	{
		$id = $this->getIDByFields(array('user_id' => $user_id, 'question_id' => $question_id), false, false);
		if(is_object($id)) {
			$this->delete($id->current()->id);
			return $this->isSubscribed($user_id, $question_id);
		}
		if($id !== false) return true;
		return false;
	}


	public function getSubscribers($question_id,  $user_fields = null)
	{
		if(!$user_fields) 
			$user_fields = [
			'id' => 'user_id', 'name', 'surname', 'full_name', 
			'login', 'avatar', 'cv_avatar', 'email', 
			'desired_rank', 'nationality', 'cv_last_call', 'cv_last_update'
			];


		return $this->query(
			"SELECT question_id, v.time subscribed_time
				, {$this->getUserFields($user_fields)}
				FROM `question_subscribers` v
				INNER JOIN user u ON (v.user_id = u.id)
				WHERE v.question_id = $question_id
				GROUP BY u.id
				ORDER BY subscribed_time DESC
			"
			);
	}


}