<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use \Application\Model\AdminNotifTable;

class Notifications extends AbstractHelper
{

	private $sl;
    protected $identity;
    protected $userTable;

    public $all;
    public $array;
    public $user;
    public $company;
    public $vacancy;
    public $mail;
    public $crewing;
    public $tasks;

	public function __construct($helperManager)
	{
		$this->sl = $helperManager->getServiceLocator();
		$this->identity = $this->sl->get('AuthService')->getIdentity();

		if($this->identity) {
			$notifications = new \stdClass();
			$this->all = $this->sl->get('AdminNotifTable')->getNotifications($this->identity->id);

			$this->array = $this->all->toArray();

			$this->user = array_filter($this->array, function($i) {
				if($i['not_section'] == AdminNotifTable::NOT_SECTION_USER) return true;
				return false;
			  });

			$this->cv = array_filter($this->array, function($i) {
				if($i['not_section'] == AdminNotifTable::NOT_SECTION_CV) return true;
				return false;
			  });

			$this->company = array_filter($this->array, function($i) {
				if($i['not_section'] == AdminNotifTable::NOT_SECTION_COMPANY) return true;
				return false;
			  });

			$this->vacancy = array_filter($this->array, function($i) {
				if($i['not_section'] == AdminNotifTable::NOT_SECTION_VACANCY) return true;
				return false;
			  });


			$this->mail = array_filter($this->array, function($i) {
				if($i['not_section'] == AdminNotifTable::NOT_SECTION_MAIL) return true;
				return false;
			  });

			$this->crewing = array_filter($this->array, function($i) {
				if($i['not_section'] == AdminNotifTable::NOT_SECTION_CREWING) return true;
				return false;
			  });

			$this->tasks = array_filter($this->array, function($i) {
				if($i['not_section'] == AdminNotifTable::NOT_SECTION_ADMIN_TASK) return true;
				return false;
			  });
			}
	}

	public function __invoke()
	{
		return $this;
	}
}