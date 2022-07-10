<?php
namespace Api\V1\Rpc\QuestionsToggleSubscribe;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class QuestionsToggleSubscribeController extends AbstractActionController
{
    public function questionsToggleSubscribeAction()
    {
    	$detail = '';
    	$status = null;
    	$count = null;
    	try {
    		$event = $this->getEvent();
    		$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
    		if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
    		$data = $inputFilter->getValues();
    	    $identity = $this->get('api-identity')->getAuthenticationIdentity();
    	
    	    $table = $this->get('QuestionSubsTable');
    	    if($table->isSubscribed($identity['user_id'], $data['id'])) {
    	        $table->unsubscribe($identity['user_id'], $data['id']);
    	        $detail = $this->translate('You are un-subscribed to this question, no more notifications for this question would be received');
    	        $status = 'unsubscribed';
    	    } else {
    	        $table->subscribe($identity['user_id'], $data['id']);
    	        $detail = $this->translate('You are subscribed to this question, you will be informed when answers found');
    	        $status = 'subscribed';
    	    }
    	    $count = $table->getSubscribers($data['id'], ['id'])->count();

    	} catch (\Exception $e) {
    	    $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
    	    return new ApiProblemResponse(new ApiProblem(406, $detail));
    	}

    	return new ViewModel(array(
    	    'detail' => $detail,
    	    'status' => $status,
    	    'subscribers_count' => $count
    	    ));
    }
}
