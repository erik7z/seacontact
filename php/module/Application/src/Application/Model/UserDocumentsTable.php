<?php
namespace Application\Model;

class UserDocumentsTable extends zEmptyTable
{

	public function __construct()
	{
		$this->init('user_documents');
	}

	public function getAllUserDocs($user_id, $format_date = 1)
	{
		$user_docs = $this->getAllOnField('user', $user_id)->toArray();
		if($format_date) {
			foreach ($user_docs as $key => $value) {
				if($user_docs[$key]['issue_date'] != 0) $user_docs[$key]['issue_date'] = date('d M y', $value['issue_date']);
				if($user_docs[$key]['expiry_date'] != 0) $user_docs[$key]['expiry_date'] = date('d M y', $value['expiry_date']);
			}
		}
		return $user_docs;
	}
	
	public function getUserDocument($document_id)
	{
		$document_id = (int)$document_id;
		$document =  $this->get($document_id);
		if(!$document) return false;

		$document['issue_date'] = date('Y-m-d', $document['issue_date']);
		$document['expiry_date'] = date('Y-m-d', $document['expiry_date']);

		return $document;
	}


	//new
	public function getDocuments($viewer_id = null, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 100, '_order' => '`time`']);
		
		$user_fields = isset($options['_user_fields'])? $options['_user_fields'] : ['name', 'surname', 'full_name', 'company_name', 'login', 'avatar', 'cv_avatar', 'type', 'role'];
		$user_fields = array_merge(['id' => 'user_id'], $user_fields);
		$user_fields_string =  $this->arrayToFields($user_fields,'u.');

		$db_fields = isset($options['_docs_fields'])? $options['_docs_fields'] : $this->getStandartFields();
		$db_fields = array_merge(['id' => 'doc_id'], $db_fields);
		$db_fields_fields_string =  $this->arrayToFields($db_fields, 'doc.');

		$where = '';
		foreach ($filters as $filter => $value) {
			if(!$value) next($filters);
			if($value) $where .= ($where == '') ? ' WHERE ' : ' AND ';

			if ($filter == 'owner_id') {
				$value = (int)$value;
				$where .= " `doc`.user = $value";
			} else throw new \Application\Exception\Exception("Filter not recognized", 1);
		}

		if ($this->count) {
			$fields_string = ' COUNT(*) count ';
			$this->order_string = '';
			$this->limit_string = '';
			$this->offset_string = '';
		} else { 
			$fields_string = $db_fields_fields_string;
			$fields_string .= ', '.$user_fields_string;
		}

		$join_string = 'INNER JOIN user u ON (doc.user = u.id)';	

		return $this->query(
			"SELECT $fields_string
				FROM `$this->tableName` doc
				$join_string
				$where
				$this->order_string
				$this->limit_string
				$this->offset_string
			"
			);
	}

	public function saveUserDocument($user_id, array $document)
	{
		$user_id = (int)$user_id;
		$document['issue_date'] = zconvertFormDate($document['issue_date']);
		$document['expiry_date'] = zconvertFormDate($document['expiry_date']);
		$document['user'] = $user_id;
		$document['time'] = time();
		return $this->save($document);		
	}




}