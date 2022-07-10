<?php
namespace Api\V1\Rpc\PicsArticleRemove;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class PicsArticleRemoveController extends AbstractActionController
{
    public function picsArticleRemoveAction()
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

    		$table->removePicsFromArticle($data['pics_ids'], $data['section'], $data['section_id']);
    		$count = $table->getPics($identity['user_id'], ['section' => $data['section'], 'section_id' => $data['section_id']], ['count' => 1])->current()->count;
    		
    		$detail = $this->translate("Images are detached from the article");
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
