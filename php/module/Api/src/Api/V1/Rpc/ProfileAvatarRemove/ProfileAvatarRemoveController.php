<?php
namespace Api\V1\Rpc\ProfileAvatarRemove;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ProfileAvatarRemoveController extends AbstractActionController
{
    public function profileAvatarRemoveAction()
    {
    	$detail = '';
    	try {
    	    $identity = $this->get('api-identity')->getAuthenticationIdentity();
    	    $this->get('UserTable')->deleteAvatar($identity['user_id']);
    	    $detail = $this->translate("Avatar removed");
    	} catch (\Exception $e) {
    	    $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
    	    return new ApiProblemResponse(new ApiProblem(406, $detail));
    	}

    	return new ViewModel(array(
    	    'detail' => $detail,
    	    ));
    }
}
