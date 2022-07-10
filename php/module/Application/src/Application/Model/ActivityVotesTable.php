<?php
namespace Application\Model;

class ActivityVotesTable extends zAbstractTable
{


	public function __construct()
	{
		$this->init('activity_votes');
	}

	public function getRating($section, $section_id, $user_id)
	{
		return $this->query(
			"SELECT 
				(SELECT COUNT(*) FROM `activity_votes` WHERE `section` = '$section' AND `section_id` = '$section_id' AND `up_vote` = 1 ) up_votes
				, (SELECT COUNT(*) FROM `activity_votes` WHERE `section` = '$section' AND `section_id` = '$section_id' AND `down_vote` = 1 ) down_votes
				, ( SELECT COUNT(*) FROM `social_likes` WHERE `section` = '$section' AND `section_id` = '$section_id' ) soc_likes
				, (SELECT (CASE WHEN (l.up_vote = 1) THEN 'up' WHEN (l.down_vote = 1) THEN 'down' ELSE NULL END) status 
					FROM `activity_votes` l 
					WHERE l.user_id = '$user_id' AND l.`section` = '$section' AND l.section_id = '$section_id'
					) vote_status
			FROM `$this->tableName`
			LIMIT 1
			");
	}

	public function addVote($vote, $section, $section_id, $user_id, $user_ip = null)
	{
		if(!$user_ip) $user_ip = $_SERVER['REMOTE_ADDR'];
		$time = time();
		if($vote == 'down') {
			$up_vote = NULL;
			$down_vote = 1;
		} else {
			$up_vote = 1;
			$down_vote = NULL;
		}
		return $this->query(
			"INSERT INTO `$this->tableName` (section, section_id, up_vote, down_vote, user_id, user_ip, time)
				SELECT * FROM (SELECT '$section', '$section_id' section_id, '$up_vote' up_vote, '$down_vote' down_vote, '$user_id' user_id, '$user_ip', '$time') as tmp
				ON DUPLICATE KEY UPDATE
				up_vote = VALUES(up_vote),
				down_vote = VALUES(down_vote),
				time = VALUES(time)
			");

	}

}