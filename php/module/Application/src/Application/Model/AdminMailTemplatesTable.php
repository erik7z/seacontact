<?php
namespace Application\Model;

class AdminMailTemplatesTable extends zEmptyTable
{

	public function __construct()
	{
		$this->init('admin_mail_templates');
	}

	public function getMailTemplates($owner_id = null, $section = null){
		$predicat = 0;
		$where = '';
		if($owner_id) {
			$where = " WHERE n.`owner_id` = '$owner_id' 
												OR n.`owner_id` = '0'
											";
			$predicat++;
		}
		if($section) {
			$where .= ($predicat == 0) ? " WHERE " : " AND ";
			$where .= " n.section = '$section' ";
		} 
		return $this->query(
				"SELECT n.*, CONCAT(u.name, ' ', u.surname) author_name, u.login author_login, u.avatar author_avatar
				FROM `$this->tableName` n
				LEFT JOIN `user` u ON (u.`id` = n.`owner_id`)
				$where
				ORDER BY `template_name` ASC
				"
				);
	}


	public function getCvMailTemplates($owner_id = null){
		if($owner_id) $and = " AND n.`owner_id` = '$owner_id' 
									OR n.`owner_id` = '0'
								";
		return $this->query(
				"SELECT n.*, CONCAT(u.name, ' ', u.surname) author_name, u.login author_login, u.avatar author_avatar
				FROM `$this->tableName` n
				LEFT JOIN `user` u ON (u.`id` = n.`owner_id`)
				WHERE n.section = 'crew@seacontact.com'
				$and
				"
				);
	}


	public function getOfficeMailTemplates($owner_id = null){
		if($owner_id) $and = " AND n.`owner_id` = '$owner_id' 
									OR n.`owner_id` = '0'
								";
		return $this->query(
				"SELECT n.*, CONCAT(u.name, ' ', u.surname) author_name, u.login author_login, u.avatar author_avatar
				FROM `$this->tableName` n
				LEFT JOIN `user` u ON (u.`id` = n.`owner_id`)
				WHERE n.section = 'office@seacontact.com'
				$and
				"
				);
	}

	public function getMailTemplate($template_id = 1){
		return $this->query(
				"SELECT n.*, CONCAT(u.name, ' ', u.surname) author_name, u.login author_login, u.avatar author_avatar
				FROM `$this->tableName` n
				LEFT JOIN `user` u ON (u.`id` = n.`owner_id`)
				WHERE n.`id` = '$template_id'
				"
				)->current();
	}

}