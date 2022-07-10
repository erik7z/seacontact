<?php
namespace Application\zLibrary;

abstract class AbstractUpload {

	protected $types;
	protected $max_size = '15242880';
	protected $upload_dir;
	protected $www_dir;

	protected $error = array (
		'big_size' => 'The file size is too big',
		'forbidden_type' => 'The file type is not acceptable',
		);

	protected $UploadsTable;

	public function __construct($optionsOrDir = null)
	{
		$this->UploadsTable = new \Application\Model\UserUploadsTable;
	}


}