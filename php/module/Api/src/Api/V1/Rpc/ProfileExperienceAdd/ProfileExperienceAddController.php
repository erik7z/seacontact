<?php
namespace Api\V1\Rpc\ProfileExperienceAdd;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ProfileExperienceAddController extends AbstractActionController
{
    public function profileExperienceAddAction()
    {
    	$detail = '';
    	$generated_id = 0;
    	try {
    		$event = $this->getEvent();
    		$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
    		if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
    		$data = $inputFilter->getValues();
    		$table = $this->get('ExperienceTable');
    	    $identity = $this->get('api-identity')->getAuthenticationIdentity();

    	    $data['user'] = $identity['user_id'];
    	    $data['time'] = time();
    	    
    	    $generated_id = $data['id'] = $table->save($data);
    	   
    	    if(!$data['id']) throw new \Application\Exception\Exception($this->translate("Experience not saved, unknown error"), 1);

    	    $detail = $this->translate("Experience added!");
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
