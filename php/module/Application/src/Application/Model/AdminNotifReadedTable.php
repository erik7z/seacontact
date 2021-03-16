<?php
namespace Application\Model;

class AdminNotifReadedTable extends zAbstractTable
{


	public function __construct()
	{
		$this->init('admin_notif_readed');
	}


	public function readNotification($user_id, $not_id)
	{
		return $this->insert(array(
							'user_id' => $user_id, 
							'not_id' => $not_id,
							'time' => time()
							));
	}



	public function readNotifications($user_id, $not_type = null, $not_section = null, $not_section_id = null)
	{
		$where = '';
		if($not_type) {
			$where = " WHERE admin_notif.not_type LIKE '%$not_type%'";
		}
		if($not_section) {
			$where .= ($where == '') ? ' WHERE ' : ' AND ';
			$where .= " admin_notif.not_section = '$not_section' ";
		}
		if($not_section_id) {
			$where .= ($where == '') ? ' WHERE ' : ' AND ';
			$where .= " admin_notif.not_section_id = '$not_section_id' ";
		}
		$time = time();
		return $this->query(
			"INSERT INTO admin_notif_readed (not_id, user_id, time)
				SELECT id, '$user_id', '$time'
				FROM   admin_notif
				$where
				AND NOT EXISTS (
				    SELECT id FROM admin_notif_readed WHERE user_id = '$user_id' AND  not_id = admin_notif.id 
				)

			");
	}

}