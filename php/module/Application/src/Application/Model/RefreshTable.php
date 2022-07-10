<?php
namespace Application\Model;

class RefreshTable extends zAbstractTable
{


	public function __construct()
	{
		$this->init('refresh');
	}


	public function refresh($resource_name)
	{
		$time = time();
		return $this->query(
						"INSERT INTO `$this->tableName` 
						(resource_name, refresh_time) 
						VALUES
						('$resource_name', '$time')
						ON DUPLICATE KEY UPDATE
						refresh_time = VALUES(refresh_time)
						");
	}

	public function getLastRefresh($resource_name)
	{
		return $this->getFieldByField('refresh_time', 'resource_name', $resource_name);
	}

}