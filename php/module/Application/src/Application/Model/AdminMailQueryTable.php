<?php
namespace Application\model;

class AdminMailQueryTable extends zAbstractTable
{

	public function __construct()
	{
		$this->init('admin_mail_query');
	}

	public function getQuery($viewer_id = 0, $filters = [], $options = []) 
	{
		$this->setDefaultOptions($options, ['_limit' => 200, '_order' => 'mq.time']);
		$where = '';
		foreach ($filters as $filter => $value) {
		}

		if($this->count) {
			$fields_string = ' COUNT(*) count ';
			$join_string = '';
			$this->order_string = '';
			$this->limit_string = '';
			$this->offset_string = '';
		} else {
			$fields_string = "mq.*, mb.mail_box, mb.owner_id, mb.subject, mb.from_mail, mb.from_name, mb.mail_to, mb.mail_cc
					";
			$join_string = '
			LEFT JOIN `admin_mail_box` mb ON (mb.unique_id = mq.unique_id) 

			';
		}
		
		$result = $this->query(
			"SELECT $fields_string
				FROM `{$this->tableName}` mq 
				$join_string
				$where
				$this->order_string
				$this->limit_string $this->offset_string
			");
		if($this->buffer) $result->buffer();
		return $result;
	}


}