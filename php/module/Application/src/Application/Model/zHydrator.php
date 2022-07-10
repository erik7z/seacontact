<?php
namespace Application\Model;

class zHydrator
{
	protected $tableGateway;

	public function __construct($table, $tableGateway)
	{
		$this->table = $table;
		$this->tableGateway = $tableGateway;
	}

	public function hydrate($data) 
	{
		$adapter = $this->tableGateway->getAdapter();
		$result = $adapter->query('DESCRIBE `'.$this->table.'`', $adapter::QUERY_MODE_EXECUTE);
		$table_fields = $result->toArray();
			
		$fields = array();
		foreach($table_fields as $field){
			$fields[] = $field['Field'];
		}
		foreach($data as $key => $value) {
			if(!in_array($key, $fields)) unset($data[$key]);
		}
		return $data;
	}

}