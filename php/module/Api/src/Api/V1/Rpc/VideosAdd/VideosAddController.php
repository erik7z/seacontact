<?php
namespace Api\V1\Rpc\VideosAdd;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class VideosAddController extends AbstractActionController
{
    public function videosAddAction()
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

    	    $table = $this->get('VideosTable');
    	    $data['user_id'] = $identity['user_id'];
    	    $data['embed_url'] = $data['url'];
    	    $data['url'] = $inputFilter->get('url')->getRawValue();
    	    $data['time'] = time();

    	    $generated_id = $data['id'] = $table->save($data);
    	    if(!$data['id']) throw new \Application\Exception\Exception($this->translate("Video not added, unknown error"), 1);
    	    $detail = $this->translate("Video added!");
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
