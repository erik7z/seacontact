<?php
namespace Application\Model;


class UserUploadsTable extends zEmptyTable
{
	private $types = array('image', 'avatar', 'cv_avatar', 'cv_file', 'document', 'mail_attachment');
	
	private $error = array (
		'forbidden_type' => 'The file type is not in the list [UserUploadsTable]',
		);

	public function __construct()
	{
		$this->init('user_uploads');
	}


	public function recordUpload($file_name, $file_src, $file_type, $user_id) {
		$user_id = (int)$user_id;
		if(!in_array($file_type, $this->types))
			throw new \Application\Exception\Exception($this->error['forbidden_type']);

		return $this->insert(array(
			'file_name' => $file_name,
			'file_src' => $file_src,
			'file_type' => $file_type,
			'user_id' => $user_id,
			'time' => time(),
			));
	}

	public function getUploadUser($file_name)
	{
		return $this->getFieldByField('user_id', 'file_name', $file_name);
	}

	public function deleteUserFiles($user_id)
	{
		$flashMessenger = $this->sl()->get('ControllerPluginManager')->get('fm');
		$uploaded_files = $this->query(
			"SELECT * 
				FROM $this->tableName
				WHERE `user_id` = '$user_id'
			"
			);
		if(count($uploaded_files) > 0) {
			foreach ($uploaded_files as $file) {
				if(file_exists($file->file_src)) {
					z_delete($file->file_src);
					$flashMessenger->addSuccessMessage(sprintf($this->translate("User's uploaded File %s deleted"), $file->file_name));
				} else $flashMessenger->addErrorMessage(sprintf($this->translate("User's uploaded File %s for delete not found"), $file->file_name));		
			}
			$this->deleteOnFields(['user_id' => $user_id]);			
		}

		$user_files = $this->query(
			"SELECT u.avatar, u.cv_avatar, u.cv_file, u.user_files
				FROM user u
				WHERE `id` = '$user_id'
			"
			)->current();
		if($user_files) {
			if($user_files->cv_file != '' && file_exists(_FILESROOT_.$user_files->cv_file)) {
					z_delete(_FILESROOT_.$user_files->cv_file);
					$flashMessenger->addSuccessMessage(sprintf($this->translate("CV File %s deleted"), $user_files->cv_file));
			}
			if(isset($user_files->user_files) &&  $user_files->user_files != '' && file_exists(_FILESROOT_.$user_files->user_files)) {
				z_delete(_FILESROOT_.$user_files->user_files);
				$flashMessenger->addSuccessMessage(sprintf($this->translate("Zip Files %s deleted"), $user_files->user_files));
			}
			if(isset($user_files->cv_avatar) && $user_files->cv_avatar != '' && file_exists(_PICSROOT_.$user_files->cv_avatar)) {
				z_delete(_PICSROOT_.$user_files->cv_avatar);
				$flashMessenger->addSuccessMessage(sprintf($this->translate("CV Avatar File %s deleted"), $user_files->cv_avatar));
			}
			if(isset($user_files->avatar) && $user_files->avatar != '' && file_exists(_PICSROOT_.$user_files->avatar)) {
				z_delete(_PICSROOT_.$user_files->avatar);
				$flashMessenger->addSuccessMessage(sprintf($this->translate("Avatar File %s deleted"), $user_files->avatar));
			}

		}

		return true;
	}

	public function deleteUpload($file_name, $file_type = null, $strict = false)
	{
		try {
			if(!$file_name || $file_name == '') return array('file_name empty');
			if($file_type == 'avatar' || $file_type == 'cv_avatar' || $file_type == 'image') {
				$files = $this->query(
					"SELECT *
						FROM `$this->tableName`
						WHERE `file_name` LIKE '%$file_name%'
					"
					);
				foreach ($files as $file) {
					$result = $this->completeDelete($file, $file_name);
				}
			} else {
				$file = $this->getOnField($file_name, 'file_name');
				if(!$file) $result['error']['record'] = sprintf($this->translate("File record %s not found in uploads table"), $file_name);
				$result = $this->completeDelete($file, $file_name);
			}
			
			if($strict && $result['error']['user'])  throw new \Application\Exception\Exception($result['error']['user'], 1);

		} catch (\Exception $e) {
			$result['error']['record'] = $this->translate('Unknown error during file delete');
		}
		return $result;
	}

	private function completeDelete($file, $file_name)
	{
		if(is_object($file)) {
			if(file_exists($file->file_src)) {
				$result['file_deleted'] = z_delete($file->file_src);
			} else $result['error']['file'] = sprintf($this->translate("File %s for delete not found"), $file_name);
		}
		$result['record_deleted'] = $this->deleteOnFields(array('file_name' => $file_name));
	}



}
