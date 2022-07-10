<?php
namespace Application\zLibrary;

class Afakemailer
{


	protected $storage;
	protected $folders;
	protected $current_folder = null;
	protected $mailBox;
	protected $identity_id;
	protected $uploadsTable;
	protected $mailBoxTable;
	protected $mailerCustom;

	public function __construct()
	{
		return $this;
	}

		public function init($mailBox, $mailBoxHost, $mailBoxLogin, $mailBoxPassword, $identity_id)
		{
			$sl = \ServiceLocatorFactory\ServiceLocatorFactory::getInstance();

			$this->mailBoxTable = $sl->get('MailBoxTable');
			$this->uploadsTable = $sl->get('UploadsTable');
			$this->mailerCustom = new \Application\zLibrary\MailerCustom;
			$this->extMapping = new extMapping;
			$this->identity_id = $identity_id;
			$this->mailBox = $mailBox;
			$this->folders = $this->mailBoxTable->getMailBoxFolders($mailBox);
			$this->storage = new \Application\zLibrary\Afakestorage;
			return $this;
		}

		public function parseMailbox()
		{
			return array();
		}

	public function getFolders()
	{
		return $this->folders;
	}

	public function getStorage()
	{
		return $this->storage;
	}

	public function	getCurrentFolder()
	{
		return	$this->current_folder;
	}

	public function setCurrentFolder($folder)
	{
		$this->current_folder = $folder;
	}

	public function getMailBox()
	{
		return $this->MailBox;
	}
}