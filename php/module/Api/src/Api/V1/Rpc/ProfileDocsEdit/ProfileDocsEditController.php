<?php
namespace Api\V1\Rpc\ProfileDocsEdit;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ProfileDocsEditController extends AbstractActionController
{
    public function profileDocsEditAction()
    {
		$detail = '';
		$affected_id = 0;
		try {
			$event = $this->getEvent();
			$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
			if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
			$data = $inputFilter->getValues();
			$table = $this->get('DocumentsTable');
		    $identity = $this->get('api-identity')->getAuthenticationIdentity();

		    if(!$this->isPermitted('my\controller\cv.docs', null, $data['id'],$this->get('api-identity')->getRoleId(), $identity['user_id'])) 
		        throw new \Application\Exception\Exception("You dont have rights to access this action", 401);

	        $table->save(array_filter($data));
		    $affected_id = $data['id'];
		    $detail = $this->translate("Document saved!");
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
