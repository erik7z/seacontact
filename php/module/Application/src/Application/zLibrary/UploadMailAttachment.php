<?php
namespace Application\zLibrary;

class UploadMailAttachment extends AbstractUpload {

	protected $types = array(
			);

	protected $max_size = '10242880';
	protected $upload_dir;
	protected $www_dir;
	protected $folder = null;

	protected $error = array (
		'big_size' => 'The file size is too big',
		'forbidden_type' => 'The file type is not acceptable',
		);

	protected $UploadsTable;


	public function __construct($optionsOrDir = null)
	{
		parent::__construct($optionsOrDir);
		if(isset($optionsOrDir)){
			$this->init($optionsOrDir);
		}
	}

	public function init($optionsOrDir)
	{
		if(is_array($optionsOrDir)){
			if($optionsOrDir['upload_dir']) 
				$this->upload_dir = _MAILSROOT_.$optionsOrDir['upload_dir'];
				$this->www_dir = '/'._MAILSWWW_.$optionsOrDir['upload_dir'];
				$this->folder = $optionsOrDir['upload_dir'];
			if($optionsOrDir['size']) {
				$this->max_size = $optionsOrDir['size'][0];
			}
		} else {
			$this->upload_dir = _MAILSROOT_.$optionsOrDir;
			$this->www_dir = '/'._MAILSWWW_.$optionsOrDir;
			$this->folder = $optionsOrDir;
		}
		if($this->folder == null) throw new \Application\Exception\Exception("Folder not set [UploadMailAttachment]", 1);
					
		if(!file_exists($this->upload_dir)) z_createDir($this->upload_dir);	
	}


	public function upload($file, $user_id)
	{
		if(!$file['tmp_name']) return false;
		// if(!in_array($file['type'], $this->types))
		// 	throw new \Application\Exception\Exception($this->error['forbidden_type']);
		if($file['size'] > $this->max_size)
			throw new \Application\Exception\Exception($this->error['big_size']);
		$file_name = $file['name'];
		if(move_uploaded_file($file['tmp_name'], $this->upload_dir.'/'.$file_name)) {
			$result['name'] = $file_name;
			$result['src'] = $this->upload_dir.'/'.$file_name;
			$result['www'] = $this->www_dir.'/'.$file_name;
			$this->UploadsTable->recordUpload($this->folder.'/'.$file_name, $result['src'], 'mail_attachment', $user_id);
			return $result;
		} else throw new \Application\Exception\Exception("File Not Uploaded !", 1);
		
	}



}