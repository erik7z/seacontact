<?php
namespace Api\V1\Rpc\VacanciesToggleReport;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class VacanciesToggleReportController extends AbstractActionController
{
    public function vacanciesToggleReportAction()
    {
    	$detail = '';
    	$status = null;
    	$count = null;
    	try {
    		$event = $this->getEvent();
    		$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
    		if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
    		$data = $inputFilter->getValues();
    	    $identity = $this->get('api-identity')->getAuthenticationIdentity();
    	    
            if(!$this->isPermitted('application\controller\vacancies.toggle-report', null, $data['id'],$this->get('api-identity')->getRoleId(), $identity['user_id'])) 
                throw new \Application\Exception\Exception("You dont have rights to access this action", 401);
            
    	    $table = $this->get('VacancyRepsTable');
    	    if($table->isReported($identity['user_id'], $data['id'])) {
    	        $table->unreport($identity['user_id'], $data['id']);
    	        $detail = $this->translate('You removed your report');
    	        $status = 'report_canceled';
    	    } else {
    	        $table->report($identity['user_id'], $data['id']);
    	        $detail = $this->translate('You are reported for this vacancy, company will be informed by email');
    	        $status = 'report_sent';
    	    }
    	    $count = $table->getVacancyReports($data['id'], null, ['id'])->count();

    	} catch (\Exception $e) {
    	    $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
    	    return new ApiProblemResponse(new ApiProblem(406, $detail));
    	}

    	return new ViewModel(array(
    	    'detail' => $detail,
    	    'status' => $status,
    	    'reports_count' => $count
    	    ));
    }
}
