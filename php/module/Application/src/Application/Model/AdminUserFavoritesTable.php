<?php
namespace Application\Model;

class AdminUserFavoritesTable extends zEmptyTable
{

	public function __construct()
	{
		$this->init('admin_user_favorites');
	}

	public function AddToFavorites($admin_id, $user_id)
	{
		$this->save(array(
			'admin_id' => $admin_id, 
			'user_id' => $user_id,
			'time' => time()
			));
		return true;
	}
	
	public function removeFromFavorites($admin_id, $user_id)
	{
		$this->tableGateway->delete(array(
			'admin_id' => $admin_id, 
			'user_id' => $user_id
				));
		return true;
	}

	public function isInFavorites($admin_id, $user_id)
	{
		$result =  $this->query(
			"SELECT *
				FROM admin_user_favorites auf
				WHERE auf.admin_id = $admin_id
				AND auf.user_id = $user_id
				LIMIT 1
			"
			);
		return $result->count();
	}
}