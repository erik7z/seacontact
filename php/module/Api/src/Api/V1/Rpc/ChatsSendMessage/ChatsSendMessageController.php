<?php
namespace Api\V1\Rpc\ChatsSendMessage;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ChatsSendMessageController extends AbstractActionController
{
    public function chatsSendMessageAction()
    {
    	$detail = '';
    	$generated_id = 0;
    	try {
    		$event = $this->getEvent();
    		$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
    		if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
    		$data = $inputFilter->getValues();

            if(!$data['user_id'] && !$data['chat_id']) throw new \Application\Exception\Exception("User id or Chat id should be provided", 1);
    		$table = $this->get('MessageTable');
    	    $identity = $this->get('api-identity')->getAuthenticationIdentity();

            if(!$this->isPermitted('my\controller\messages.pm', null, null,$this->get('api-identity')->getRoleId(), $identity['user_id'])) 
                throw new \Application\Exception\Exception("You dont have rights to access this action", 401);

    	    if(!$data['chat_id']) $data['chat_id'] = $table->getChatIDBy2Users($identity['user_id'], $data['user_id']);
    	    if(!$table->getChatAccess($identity['user_id'], $data['chat_id'])) throw new \Application\Exception\Exception("You cannot send messages to this chat", 1);
    	    if(!$data['user_id']) {
    	    	$member = $table->getChatMembers($data['chat_id'], $identity['user_id'])->current();
    	    	$data['user_id'] = $member['user_id'];
    	    }
    	    if(!$table->validateMessage($identity['user_id'], $data['message'], $data['chat_id'],  $data['user_id'], time())) throw new \Application\Exception\Exception("Ooops, your message not sent", 1);
    	    $generated_id = $table->addMessage($identity['user_id'], $data['message'], $data['chat_id'],  $data['user_id']);
    	    if(!$generated_id) throw new \Application\Exception\Exception($this->translate("Message not sent, unknown error"), 1);
    	    $detail = $this->translate("Message sent!");
    	} catch (\Exception $e) {
    	    $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
    	    return new ApiProblemResponse(new ApiProblem(406, $detail));
    	}

    	return new ViewModel(array(
    	    'detail' => $detail,
            'generated_id' => $generated_id,
    	    'chat_id' => $data['chat_id'],
    	    ));
    }
}
