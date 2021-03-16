<?php
namespace Api\V1\Rpc\QuestionsEdit;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class QuestionsEditController extends AbstractActionController
{
    public function questionsEditAction()
    {
	    $detail = '';
	    $affected_id = 0;
	    try {
	    	$event = $this->getEvent();
	    	$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
	    	if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
	    	$data = $inputFilter->getValues();
	        $identity = $this->get('api-identity')->getAuthenticationIdentity();

	        if(!$this->isPermitted('application\controller\questions.edit', null, $this->get('api-identity')->getRoleId(), $identity['user_id'])) 
	        	throw new \Application\Exception\Exception("You dont have rights to access this action", 401);
	        
	        $this->get('QuestionsTable')->save(array_filter($data));
	        $affected_id = $data['id'];
	        
	        if($data['tags']) {
	            $tags = explode(',', $data['tags']);
	            $tagsTable = $this->get('TagMapTable');
	            $tagsTable->addTags(\Application\Model\NewsTable::SECTION_QUESTIONS, $data['id'], $tags);
	        }
	        $detail = $this->translate("Question saved!");
	    } catch (\Exception $e) {
	        $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
	        return new ApiProblemResponse(new ApiProblem(406, $detail));
	    }

	    return new ViewModel(array(
	        'detail' => $detail,
	        'affected_id' => $affected_id,
	        ));
	}
}
