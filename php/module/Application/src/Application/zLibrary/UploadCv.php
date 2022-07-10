<?php
namespace Application\zLibrary;

class UploadCv extends AbstractUpload {

	protected $types = array(
			'text/plain', 
			'text/vnd.rn-realtext',
			'application/x-rtf',
			'text/richtext',
			'application/rtf',
			'application/msword',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document',

			'application/excel',
			'application/x-excel',
			'application/x-msexcel',
			'application/vnd.ms-excel',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

			'application/pdf',
			'application/x-pdf',
			);

	protected $max_size = '5242880';
	protected $upload_dir;
	protected $www_dir;

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
		} else $this->init(_FILESROOT_);
	}

	public function init($optionsOrDir)
	{
		if(is_array($optionsOrDir)){
			if($optionsOrDir['upload_dir']) 
				$this->upload_dir = $optionsOrDir['upload_dir'];
				$this->www_dir = '/'._FILESWWW_;
				// $this->getWWWfromRoot($root);
			if($optionsOrDir['size']) {
				$this->max_size = $optionsOrDir['size'][0];
			}
		} else {
			$this->upload_dir = $optionsOrDir;
			$this->www_dir = '/'._FILESWWW_;
		}				
		z_createDir($this->upload_dir);		
	}


	public function upload($file, $user_id)
	{
		if(!isset($file['tmp_name']) || !$file['tmp_name']) return false;
		if(!in_array($file['type'], $this->types))
			throw new \Application\Exception\Exception($this->error['forbidden_type']);
		if($file['size'] > $this->max_size)
			throw new \Application\Exception\Exception($this->error['big_size']);
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);


		$file_name = z_generateName($this->upload_dir, $ext);
		if(move_uploaded_file($file['tmp_name'], $this->upload_dir.$file_name)) {
			$result['name'] = $file_name;
			$result['src'] = $this->upload_dir.$file_name;
			$result['www'] = $this->www_dir.$file_name;
			$this->UploadsTable->recordUpload($file_name, $result['src'], 'cv_file', $user_id);
			return $result;
		} else throw new \Application\Exception\Exception("File Not Uploaded !", 1);
		
	}



}