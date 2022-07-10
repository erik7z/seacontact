<?php
namespace Api\V1\Rpc\ProfileCvAvatarRemove;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ProfileCvAvatarRemoveController extends AbstractActionController
{
    public function profileCvAvatarRemoveAction()
    {
    	$detail = '';
    	try {
    	    $identity = $this->get('api-identity')->getAuthenticationIdentity();
    	    $this->get('UserTable')->deleteCvAvatar($identity['user_id']);
    	    $detail = $this->translate("CV Avatar removed");
    	} catch (\Exception $e) {
    	    $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
    	    return new ApiProblemResponse(new ApiProblem(406, $detail));
    	}

    	return new ViewModel(array(
    	    'detail' => $detail,
    	    )); 	
    }
}
