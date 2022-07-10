<?php
namespace Api\V1\Rpc\UsersInfoUnlock;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class UsersInfoUnlockController extends AbstractActionController
{
    public function usersInfoUnlockAction()
    {
    	$detail = '';
    	$status = null;
    	$user_info = null;
    	try {
    		$event = $this->getEvent();
    		$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
    		if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);

    		$data = $inputFilter->getValues();
    	    $identity = $this->get('api-identity')->getAuthenticationIdentity();
  			
            if(!$this->isPermitted('application\controller\seamansdb.unlock-user-info', null, null, $this->get('api-identity')->getRoleId(), $identity['user_id'])) 
                throw new \Application\Exception\Exception("You dont have rights to access this action", 401);

    	    $companyUsersTable = $this->get('CompanyUsersTable');
    	    $stats = $companyUsersTable->getCompanyStats($identity['user_id']);
    	    $role_limits = $this->get('access')->getAccessList()->getRolesLimits($this->get('api-identity')->getRoleId());
    	    if($stats->user_add_day >= $role_limits['USERS_ADD_DAY']) 
    	        throw new \Application\Exception\Exception(sprintf($this->translate('You cannot open more than %s CVs per day'), $role_limits['USERS_ADD_DAY']), 1);
    	    if($stats->user_add_week >= $role_limits['USERS_ADD_WEEK']) 
    	        throw new \Application\Exception\Exception(sprintf($this->translate('You cannot open more than %s CVs per week'), $role_limits['USERS_ADD_WEEK']), 1);
    	    if($stats->user_add_month >= $role_limits['USERS_ADD_MONTH']) 
    	        throw new \Application\Exception\Exception(sprintf($this->translate('You cannot open more than %s CVs per month'), $role_limits['USERS_ADD_MONTH']), 1);
    	    
    	    $companyUsersTable->addUser($identity['user_id'], $data['id']);
            $userTable = $this->get('UserTable');
    	    $user_info = $userTable->getUserById($data['id'], false, $userTable->getStandartFields());
    	    $status = 1;
    	    // $userNotTable = $this->get('UserNotificationsTable');
    	    // $this->addUserNotification($userNotTable::TYPE_UNLOCK_CV, \Application\Model\NewsTable::SECTION_USER, $identity['user_id'], $identity['user_id'], $user_id);
    	    // $this->get('AdminNotifTable')->addCompanyUnlockedUserNotification($identity['user_id'], $user_id);
    	    $detail = $this->translate('User personal info unlocked');     

    	} catch (\Exception $e) {
    	    $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
    	    return new ApiProblemResponse(new ApiProblem(406, $detail));
    	}

    	return new ViewModel(array(
    	    'detail' => $detail,
    	    'status' => $status,
    	    'user_info' => $user_info
    	    ));
    }
}
