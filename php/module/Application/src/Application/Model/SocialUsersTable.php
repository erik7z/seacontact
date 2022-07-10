<?php
namespace Application\Model;


class SocialUsersTable extends zEmptyTable
{

	public function __construct()
	{
		$this->init('social_users');
	}

	public function getUnkownSocialUsersIds($limit = 50)
	{
		return $this->query(
			"SELECT 
				soc_user_id, soc_name
				FROM `social_likes` sl
				WHERE sl.soc_user_id NOT IN (SELECT 
												soc_user_id 
												FROM `social_users` su 
												WHERE su.soc_name = sl.soc_name
											)
				GROUP BY soc_user_id
				LIMIT $limit
			");
	}
}
