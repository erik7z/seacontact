<?php
namespace Application\Model;

class AdminMailAccountsTable extends zAbstractTable
{

	public function __construct()
	{
		parent::__construct('admin_mail_accounts');
	}

	public function getAccounts($viewer_id = null, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 50, '_order' => 'priority', 'up' => 0]);

		$fields = (isset($options['_fields'])) ? $options['_fields'] : null;
		if($fields && is_array($fields)) {
			$fields_string = $this->arrayToFields($fields, 'ma.');
		} else $fields_string = 'ma.*';

		$where = '';
		foreach ($filters as $filter => $value) {
			if(!$value) next($filters);
			if($value) $where .= ($where == '') ? ' WHERE ' : ' AND ';

			if ($filter == 'id') {
				$where .= " `ma`.id = '$value' ";
			} else {
				throw new \Application\Exception\Exception("Filter not recognised", 1);
			}
		}

		if ($this->count) {
			$fields_string = ' COUNT(*) count ';
			$this->order_string = '';
			$this->limit_string = '';
			$this->offset_string = '';
		} else {
			$folders_fields = $this->generateJsonFields([
				'folder','folder_full',
				'(SELECT COUNT(*) FROM `admin_mail_box` mb WHERE mb.mail_box = ma.mail_box AND mb.folder = mbf.folder)' => 'count',
				'(SELECT COUNT(*) FROM `admin_mail_box` mb WHERE mb.mail_box = ma.mail_box AND mb.folder = mbf.folder AND mb.flag_seen != 1)' => 'count_new',
				], '', null, 'ORDER BY priority DESC');
			$fields_string .= "   
				, (SELECT COUNT(*) FROM `admin_mail_box` mb WHERE mb.mail_box = ma.mail_box AND mb.flag_seen != 1) unreaded
				, (SELECT $folders_fields FROM `admin_mail_box_folders` mbf WHERE  mbf.mail_box = ma.mail_box) folders
			";
		}
		$join_string = "";

		$this->query("SET SESSION group_concat_max_len = 1000000;");
		return $this->query(
			"SELECT $fields_string
				FROM `{$this->tableName}` ma
				$join_string
				$where 
				$this->order_string
				$this->limit_string
				$this->offset_string
			");
	}
}