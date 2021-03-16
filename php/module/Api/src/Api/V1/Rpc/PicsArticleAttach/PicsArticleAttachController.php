<?php
namespace Api\V1\Rpc\PicsArticleAttach;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class PicsArticleAttachController extends AbstractActionController
{
    public function picsArticleAttachAction()
    {
    	$detail = '';
    	try {
    		$event = $this->getEvent();
    		$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
    		if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
    		$identity = $this->get('api-identity')->getAuthenticationIdentity();

    		$data = $inputFilter->getValues();
    		$data['pics_ids'] = array_values(array_filter(explode(',', $data['pics_ids'])));
    		$table = $this->get('PicsTable');

            $resource = \Application\Model\NewsTable::getSectionResource($data['section'], 'edit');
            if(!$this->isPermitted($resource, null, $data['section_id'],$this->get('api-identity')->getRoleId(), $identity['user_id'])) 
                throw new \Application\Exception\Exception("You dont have rights to access this action", 401);

            if(!$this->get(\Application\Model\NewsTable::getSectionTable($data['section']))->existsID($data['section_id'])) 
                throw new \Application\Exception\Exception("Article with such section and id not found", 1);

    		$new_pics = $table->getPics($identity['user_id'], ['pics_ids' => $data['pics_ids']], ['_pics_fields' => ['id']]);
    		foreach ($new_pics as $pic) {
    			$new_ids[] = $pic->id;
    		}
    		$not_found_pics = array_diff($data['pics_ids'], $new_ids);
    		if(count($not_found_pics)) 
    			throw new \Application\Exception\Exception(sprintf("Images with ids: %s not found in database and cannot be attached", implode(',', $not_found_pics)), 1);
    		
    		$old_count = $table->getPics($identity['user_id'], ['section' => $data['section'], 'section_id' => $data['section_id']], ['count' => 1])->current()->count;
    		if(count($data['pics_ids']) + $old_count > _PICS_MAX_ATTACH_) 
    			throw new \Application\Exception\Exception(sprintf("Maximum %s images can be attached to article, %s images already attached",_PICS_MAX_ATTACH_, $old_count), 1);
    		$table->attachPicsToArticle($data['pics_ids'], $data['section'], $data['section_id']);
    		
            $count = $table->getPics($identity['user_id'], ['section' => $data['section'], 'section_id' => $data['section_id']], ['count' => 1])->current()->count;
            
    		$detail = $this->translate("Images are attached to the article");
    	} catch (\Exception $e) {
    	    $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
    	    return new ApiProblemResponse(new ApiProblem(406, $detail));
    	}

    	return new ViewModel(array(
    	    'detail' => $detail,
    	    'total_pics' => $count
    	    ));
    }
}
