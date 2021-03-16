<?php
namespace Api\V1\Rpc\VacanciesRemove;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class VacanciesRemoveController extends AbstractActionController
{
    public function vacanciesRemoveAction()
    {
    	$detail = '';
    	try {
    		$event = $this->getEvent();
    		$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
    		if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
    		$data = $inputFilter->getValues();
    	    $identity = $this->get('api-identity')->getAuthenticationIdentity();

            if(!$this->isPermitted('application\controller\vacancies.delete', null, $data['id'],$this->get('api-identity')->getRoleId(), $identity['user_id'])) 
                throw new \Application\Exception\Exception("You dont have rights to access this action", 401);

            $this->get('VacanciesTable')->save(['id' => $data['id'], 'active' => 0]);
    	    $detail = $this->translate("Vacancy Record removed");
    	} catch (\Exception $e) {
    	    $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
    	    return new ApiProblemResponse(new ApiProblem(406, $detail));
    	}

    	return new ViewModel(array(
    	    'detail' => $detail,
    	    ));
    }
}
