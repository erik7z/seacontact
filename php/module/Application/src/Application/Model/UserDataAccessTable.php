<?php
namespace Application\Model;


class UserDataAccessTable extends zEmptyTable
{

	private $UserContactsTable;

	public function __construct()
	{
		$this->init('user_data_access');
	}

	/*
		Checking is access granted by resource owner to the apropriate user
		if resource user_id is 0 access is granted to everyone
		if resource user_id is 1 access granted only to friends (sub query to contacts table)
		if resource user_id is other , access granted to user with the that id
		returns bool
	*/
	public function getAccess($resource, $owner_id, $user_id)
	{
		if(is_bool($user_id) || is_null($user_id)) throw new \Application\Exception\Exception("No user id supplied", 000);
		if(is_bool($owner_id) || is_null($owner_id)) throw new \Application\Exception\Exception("No owner id supplied", 000);
		if(!is_string($resource)) throw new \Application\Exception\Exception('$resource should be string', 000);
		$result = $this->query(
			"SELECT uda.*
			FROM `user_data_access` as uda, `user_contacts` as uc
			WHERE `uda`.`resource` = '$resource' 
			AND `uda`.`owner_id` = $owner_id
			AND (
				`uda`.`user_id` = $user_id OR `uda`.`user_id` = 0 
				OR (`uda`.`user_id` = 1 
					AND (`uc`.`request_from` IN (SELECT `uc`.`request_to` FROM `user_contacts` as uc WHERE `uc`.`request_from` = $owner_id ) AND `uc`.`request_to` = $owner_id AND `uc`.`request_from` = $user_id)
					)
				) 
			"
			);
		return (bool)$result->toArray();
	}

	public function setAccess($resource, $owner_id, $user_id)
	{
		if(!$this->getAccess($resource, $owner_id, $user_id)) {
			return $this->insert(array('resource' => $resource, 'owner_id' => $owner_id, 'user_id' => $user_id));
		}
		return true;
	}

	public function openAccessToAll($resource, $owner_id)
	{
		return $this->setAccess($resource, $owner_id, 0);
	}

	public function openAccessToFriends($resource, $owner_id)
	{
		$this->removeAccess($resource, $owner_id, 0);
		return $this->setAccess($resource, $owner_id, 1);
	}

	public function removeAccess($resource, $owner_id, $user_id = null)
	{
		if(is_bool($owner_id) || is_null($owner_id)) throw new \Application\Exception\Exception("No owner id supplied", 000);
		if(!is_string($resource)) throw new \Application\Exception\Exception('$resource should be string', 000);
		$delete_array = array('resource' => $resource, 'owner_id' => $owner_id);
		if($user_id && !is_bool($user_id)) $delete_array['user_id'] = $user_id;

		return $this->deleteOnFields($delete_array);		
	}



	protected function getUserContactsTable()
	{
		if(!$this->UserContactsTable) $this->UserContactsTable = $this->getTable('UserContactsTable');
		return $this->UserContactsTable;		
	}


}
