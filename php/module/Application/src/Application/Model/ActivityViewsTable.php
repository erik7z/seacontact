<?php
namespace Application\Model;

class ActivityViewsTable extends zAbstractTable
{


	public function __construct()
	{
		$this->init('activity_views');
	}


	public function getViews($section_name, $resource_id)
	{
		return $this->query(
			"SELECT COUNT(`id`) 
			FROM $this->tableName
			WHERE `section_name` = '$section_name'
			AND `resource_id` = '$resource_id'
			GROUP BY `user_ip`
			")->current();
	}

	public function getViewsByUser($section_name, $resource_id, $user_ip, $user_id = null)
	{
		// if($user_id) $and = "(`user_ip` = '$user_ip' OR `user_id` = '$user_id')";
		// else $and = "`user_ip` = '$user_ip'";
		$and = "`user_ip` = '$user_ip'";

		return $this->query(
			"SELECT * 
			FROM $this->tableName
			WHERE `section_name` = '$section_name'
			AND `resource_id` = '$resource_id'
			AND $and
			")->count();
	}

	public function addView($section_name, $resource_id, $user_id = null, $user_ip = null)
	{
		if(!$user_ip) $user_ip = $_SERVER['REMOTE_ADDR'];
		// $check = $this->getViewsByUser($section_name, $resource_id, $user_ip, $user_id);
		return $this->insert(array(
											'section_name' => $section_name, 
											'resource_id' => $resource_id,
											'user_ip' => $user_ip,
											'user_id' => $user_id,
											'time' => time()
											));
	}

}