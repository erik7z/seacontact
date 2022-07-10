<?php
namespace Api\V1\Rpc\ChatsDeleteMessage;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ChatsDeleteMessageController extends AbstractActionController
{
    public function chatsDeleteMessageAction()
    {
    	$detail = '';
    	try {
    		$event = $this->getEvent();
    		$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
    		if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
    		$data = $inputFilter->getValues();
    	    $identity = $this->get('api-identity')->getAuthenticationIdentity();

            if(!$this->isPermitted('my\controller\messages.delete', null, null,$this->get('api-identity')->getRoleId(), $identity['user_id'])) 
                throw new \Application\Exception\Exception("You dont have rights to access this action", 401);

    	    // Any ACL checks before delete ? 
    	    if(!$this->get('MessageTable')->canDelete($identity['user_id'], $data['id']))  
                throw new \Application\Exception\Exception("You dont have rights to perform this operation", 1); 
    	    
    	    $this->get('MessageTable')->save(['id' => $data['id'], 'active' => 0]);
    	    $detail = $this->translate("Message Deleted");
    	} catch (\Exception $e) {
    	    $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
    	    return new ApiProblemResponse(new ApiProblem(406, $detail));
    	}

    	return new ViewModel(array(
    	    'detail' => $detail,
    	    ));
    }
}
