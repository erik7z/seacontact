<?php
namespace Application\zLibrary;

class Afakefolder
{
	public $folder_name;

	public function __construct($folder_name)
	{
		$this->folder_name = $folder_name;
	}

	public function getLocalName()
	{
		return $this->folder_name;
	}

	public function getGlobalName()
	{
		if($this->folder_name != 'INBOX') return 'INBOX.'.$this->folder_name;
		return $this->folder_name;
	}
}