<?php
namespace Api\V1\Rpc\QanswersToggleAccept;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class QanswersToggleAcceptController extends AbstractActionController
{
    public function qanswersToggleAcceptAction()
    {
    	$detail = '';
    	$affected_id = 0;
    	$status = null;
    	try {
    		$event = $this->getEvent();
    		$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
    		if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
    		$data = $inputFilter->getValues();
    	    $identity = $this->get('api-identity')->getAuthenticationIdentity();

    	    if(!$this->isPermitted('application\controller\questions.toggle-accept', null, $data['id'],$this->get('api-identity')->getRoleId(), $identity['user_id'])) 
    	    	throw new \Application\Exception\Exception("You dont have rights to access this action", 401);

    	    $table = $this->get('QuestionAnswersTable');
    	    $answer = $table->get($data['id']);
    	    if($answer->correct)  {
    	    	$table->removeAccept($answer->question_id);
    	    	$detail = $this->translate("Accept removed !");
    	    	$status = 0;
    	    } else {
    	    	$table->accept($data['id'], $answer->question_id);
    	    	$detail = $this->translate("Answer accepted as correct!");
    	    	$status = 1;
    	    }
    	    $affected_id = $data['id'];

    	} catch (\Exception $e) {
    	    $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
    	    return new ApiProblemResponse(new ApiProblem(406, $detail));
    	}

    	return new ViewModel(array(
    	    'detail' => $detail,
    	    'affected_id' => $affected_id,
    	    'correct' => $status
    	    ));    	
    }
}
