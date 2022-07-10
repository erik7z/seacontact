<?php
namespace UserInfo\View\Helper;

use Zend\View\Helper\AbstractHelper;


// Get information About "Other" user from route match
class UserInfo extends AbstractHelper
{

	private $sl;
	private $routeMatch;

	public $userInfo;
	public function __construct($helperManager)
	{
		$this->sl = $helperManager->getServiceLocator();
		$this->routeMatch = $this->sl->get('Application')->getMvcEvent()->getRouteMatch();

	}


	public function __invoke()
	{
        try {
		        if($this->routeMatch->getMatchedRouteName() == 'sc/userinfo' || $this->routeMatch->getMatchedRouteName() == 'sc/companyinfo') {
		        	if(!$this->userInfo) {
		        		$user_login = $this->routeMatch->getParam('user');
		        		$userTable = $this->sl->get('UserTable');
		        		$this->userInfo = $userTable->getUserByLogin($user_login);
		        	}
		        }
        	} 
        	catch (\Exception $e) {
        		return false;     		
        	}
		return $this->userInfo;
	}
}