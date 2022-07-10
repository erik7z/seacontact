<?php
namespace Api\V1\Rpc\QanswersAdd;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class QanswersAddController extends AbstractActionController
{
    public function qanswersAddAction()
    {
    	$detail = '';
    	$generated_id = 0;
    	try {
    		$event = $this->getEvent();
    		$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
    		if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
    		$data = $inputFilter->getValues();
    	    $identity = $this->get('api-identity')->getAuthenticationIdentity();
    	    $data['user'] = $identity['user_id'];
    	    $data['time'] = time();
    	    $generated_id = $data['id'] = $this->get('QuestionAnswersTable')->save($data);
    	    if(!$data['id']) throw new \Application\Exception\Exception($this->translate("Answer not saved, unknown error"), 1);
    	    $detail = $this->translate("Answer added!");
    	} catch (\Exception $e) {
    	    $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
    	    return new ApiProblemResponse(new ApiProblem(406, $detail));
    	}

    	return new ViewModel(array(
    	    'detail' => $detail,
    	    'generated_id' => $generated_id,
    	    ));
    }
}
