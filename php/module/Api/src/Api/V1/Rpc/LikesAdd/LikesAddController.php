<?php
namespace Api\V1\Rpc\LikesAdd;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class LikesAddController extends AbstractActionController
{
    public function likesAddAction()
    {
	    $detail = '';
	    $generated_id = 0;
	    try {
	    	$event = $this->getEvent();
	    	$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
	    	if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
	    	$data = $inputFilter->getValues();
	        $identity = $this->get('api-identity')->getAuthenticationIdentity();
	        $generated_id = $this->get('LikesTable')->like($identity['user_id'], $data['section'], $data['section_id'])->getGeneratedValue();
	        if(!$generated_id) throw new \Application\Exception\Exception($this->translate("Like already exist"), 1);
	        $detail = $this->translate("Like added!");
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
