<?php
namespace Api\V1\Rpc\LinksAdd;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class LinksAddController extends AbstractActionController
{
    public function linksAddAction()
    {
    	$detail = '';
    	$generated_id = 0;
    	try {
    		$event = $this->getEvent();
    		$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
    		if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
    		$data = $inputFilter->getValues();
    		$identity = $this->get('api-identity')->getAuthenticationIdentity();
    	    if(!$this->get(\Application\Model\NewsTable::getSectionTable($data['section']))->existsID($data['section_id'])) 
    	        throw new \Application\Exception\Exception("Article with such section and id not found", 1);

    	    $resource = \Application\Model\NewsTable::getSectionResource($data['section'], 'edit');
    	    if(!$this->isPermitted($resource, null, $data['section_id'],'admin', $identity['user_id'])) 
    	        throw new \Application\Exception\Exception("You dont have rights to access this action", 401);

    	    $table = $this->get('LinksTable');
    	    $data['user_id'] = $identity['user_id'];
    	    $data['time'] = time();
            
            // *** Any curl request to url, to get title, descr and image ? ***
    	    
            $generated_id = $data['id'] = $table->save($data);
    	    if(!$data['id']) throw new \Application\Exception\Exception($this->translate("Link not added, unknown error"), 1);
    	    $detail = $this->translate("Link added!");
    	} catch (\Exception $e) {
    	    $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
            if(strpos($detail, 'Duplicate') !== false) $detail = $this->translate('This url already attached to article');
    	    return new ApiProblemResponse(new ApiProblem(406, $detail));
    	}

    	return new ViewModel(array(
    	    'detail' => $detail,
    	    'generated_id' => $generated_id,
    	    ));    	
    }
}
