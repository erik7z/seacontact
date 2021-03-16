<?php
namespace Application\Model;

class AdminUserSettingsTable extends zEmptyTable
{

	public function __construct()
	{
		$this->init('admin_user_settings');
	}

	public function getDefaultSettings()
	{
		return $this->getOnField(0, 'user_id');
	}

	public function getUserSettings($user_id)
	{
		$user_id = (int)$user_id;
		$result =  $this->getOnField($user_id, 'user_id');
		if(!$result) $result = $this->getDefaultSettings();
		return $result;
	}
}