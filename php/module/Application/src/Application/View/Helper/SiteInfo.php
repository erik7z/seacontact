<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use \Application\Model\UserTable;

class SiteInfo extends AbstractHelper
{
    protected $sl;
    protected $infoTable;
    protected $userTable;

    public $home_address;
    public $contact_phone;
    public $contact_mobile;
    public $contact_mobile_2;
    public $email;
    public $contact_email;

    public $identity;
	public $online_users;
    public $online_seamans;
    public $online_seamans_count;
    public $online_companies;
    public $online_companies_count;
    public $online_guests;
    public $online_friends;
    public $online_admins;
    public $online_friends_count;

    public $new_msgs;
    public $new_notif;
    public $new_contacts;


	public function __construct($helperManager)
	{
		$this->sl = $helperManager->getServiceLocator();
		$this->infoTable = $this->sl->get('InfoTable');
		$this->userTable = $this->sl->get('UserTable');

		$site_info = $this->userTable->getUserById(_COMPANY_ID_, false, [
			'home_address',
			'contact_phone', 
			'contact_mobile', 
			'contact_mobile_2', 
			'email',
			'contact_email']
			);

		$this->home_address = $site_info->home_address;
		$this->contact_phone = $site_info->contact_phone;
		$this->contact_mobile = $site_info->contact_mobile;
		$this->contact_mobile_2 = $site_info->contact_mobile_2;
		$this->email = $site_info->email;
		$this->contact_email = $site_info->contact_email;

		$this->identity = $this->sl->get('AuthService')->getIdentity();
		$viewer_id = ($this->identity) ? $this->identity->id : 0;

		if($viewer_id) {
			$role = $this->userTable->getFieldByField('role', 'id', $viewer_id);
			if($role != $this->identity->role) $this->sl->get('RefreshUserId');
		} else {
			$this->infoTable->updateStatus();
		}

		$siteInfo = $this->infoTable->getSiteInfo($viewer_id);
		$this->new_msgs = ($siteInfo['new_msgs'])? $siteInfo['new_msgs'] : '';
		$this->new_notif = ($siteInfo['new_notif'])? $siteInfo['new_notif'] : '';
		$this->new_contacts = ($siteInfo['new_contacts'])? $siteInfo['new_contacts'] : '';
		$this->online_guests = ($siteInfo['online_guests'])? $siteInfo['online_guests'] : '';
		$this->online_friends_count = $siteInfo['online_friends_count'];
		$this->online_friends = ($siteInfo['online_friends'])?  json_decode($siteInfo['online_friends']) : [];
		$this->online_admins = ($siteInfo['online_admins'])? json_decode($siteInfo['online_admins']) : [];
		$this->online_seamans = ($siteInfo['online_seamans'])? json_decode($siteInfo['online_seamans']) : [];
		$this->online_seamans_count = $siteInfo['online_seamans_count'];
		$this->online_companies = ($siteInfo['online_companies'])? json_decode($siteInfo['online_companies']) : [];
		$this->online_companies_count = $siteInfo['online_companies_count'];
	}



		public function updateUserActivity()
		{
			if($this->identity)
				return $this->userTable->updateUserLastActivity($this->identity->id);
			else return false;
		}


		public function updateAdminActivity()
		{	
			if($this->identity)
				return $this->userTable->updateAdminLastActivity($this->identity->id);
			else return false;
		}


	public function __invoke()
	{
		return $this;
	}

}