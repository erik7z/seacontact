<?php
namespace Api\V1\Rpc\LogbooksEdit;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class LogbooksEditController extends AbstractActionController
{
    public function logbooksEditAction()
    {
	    $detail = '';
	    $affected_id = 0;
	    try {
	    	$event = $this->getEvent();
	    	$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
	    	if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
	    	$data = $inputFilter->getValues();
	        $identity = $this->get('api-identity')->getAuthenticationIdentity();

	        if(!$this->isPermitted('application\controller\logbook.edit', null, $data['id'],$this->get('api-identity')->getRoleId(), $identity['user_id'])) throw new \Application\Exception\Exception("You dont have rights to access this action", 401);
       
	        $this->get('LogBookTable')->save(array_filter($data));
	        $affected_id = $data['id'];
	        if(!$data['id']) throw new \Application\Exception\Exception($this->translate("Logbook not saved, unknown error"), 1);
	        if($data['tags']) {
	            $tags = explode(',', $data['tags']);
	            $tagsTable = $this->get('TagMapTable');
	            $tagsTable->addTags(\Application\Model\NewsTable::SECTION_LOGBOOK, $data['id'], $tags);
	        }
	        $detail = $this->translate("Logbook saved!");
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
