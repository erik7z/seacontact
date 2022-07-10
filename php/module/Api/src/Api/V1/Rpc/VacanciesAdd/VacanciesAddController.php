<?php
namespace Api\V1\Rpc\VacanciesAdd;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class VacanciesAddController extends AbstractActionController
{
    public function vacanciesAddAction()
    {
    	$detail = '';
    	$generated_id = 0;
    	try {
    		$event = $this->getEvent();
    		$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
    		if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
    		$data = $inputFilter->getValues();
    		$table = $this->get('VacanciesTable');
    	    $identity = $this->get('api-identity')->getAuthenticationIdentity();

    	    $data['user'] = $identity['user_id'];
    	    $data['time'] = time();
    	    $data['active'] = 1;
    	    $data['title'] = (isset($data['title']))? $data['title'] : $data['rank'].'/ '.$data['ship_type'].'/ '.$data['salary'].$data['salary_unit'];
    	    $data['tags'] = $table->getTagString($data);
    	    
    	    $generated_id = $data['id'] = $table->save($data);
    	    $this->get('TagMapTable')->addTags(\Application\Model\NewsTable::SECTION_VACANCY, $generated_id, explode(',', $data['tags']));
    	   
    	    if(!$data['id']) throw new \Application\Exception\Exception($this->translate("Vacancy not saved, unknown error"), 1);

    	    $detail = $this->translate("Vacancy added!");
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
