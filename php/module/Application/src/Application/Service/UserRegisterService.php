<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use \Application\Model\NewsTable;
use \Application\Model\SocialPublicsTable as PublicsTable;

class UserRegisterService
{
	protected $sl;
	
	public function __construct(ServiceLocatorInterface $serviceLocator)
	{
		$this->sl = $serviceLocator;
		return $this;
	}

	// used for auth and social auth actions
	public function saveUserData($user_data)
	{
		$cv_file = (isset($user_data['user']['cv_file']))? $user_data['user']['cv_file'] : null;
		unset($user_data['user']['cv_file']);
		$user_data['user']['id'] = $this->sl->get('UserTable')->save($user_data['user']);
		if($cv_file) $cv_file = $this->sl->get('UploadCv')->upload($cv_file, $user_data['user']['id']);
		if($cv_file) {
			// DELETE OLD CV
			$old_cv = $this->sl->get('UserTable')->getUserCv($user_data['user']['id']);
			$delete = $this->sl->get('UploadsTable')->deleteUpload($old_cv, 'cv_file');
			if($delete['error']) $this->sl->get('fm')->addErrorMessage($delete['error']);

			$this->sl->get('UserTable')->addNewCvFile($user_data['user']['id'], $cv_file['name']);
			$user_data['user']['cv_file'] = $cv_file['name'];
		}

		return $user_data;
	}

}

