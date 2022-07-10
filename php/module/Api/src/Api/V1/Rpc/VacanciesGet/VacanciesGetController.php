<?php
namespace Api\V1\Rpc\VacanciesGet;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class VacanciesGetController extends AbstractActionController
{
    public function vacanciesGetAction()
    {
    	try {
    	    $table = $this->get('VacanciesTable');
    	    $permitted_fields = [
    	        '_company_fields' => $this->get('UserTable')->getStandartFields(),
    	        '_vacancy_fields' => $table->getStandartFields(),
    	        '_stats_fields' => $table->getStatsFields(),
    	    ];
    	    $default_options = [
    	    '_company_fields' => ['id'],
    	    '_vacancy_fields' => ['id', 'title', 'user' => 'company_id'],
    	    '_stats_fields' => ['likes'],
    	    ];
    	    $request = $this->apiQoptions($default_options, $permitted_fields);

            $identity = $this->get('api-identity')->getAuthenticationIdentity();
    	    $data_list = $table->getAllVacancies($identity['user_id'],$request['filters'], $request['q_options'])->toArray();
    	    $data_list = $this->ApiValidateResponse($data_list, $request['q_options']);
    	    $request['q_options']['count'] = 1;
    	    $total_results = $table->getAllVacancies($identity['user_id'], $request['filters'], $request['q_options'])->current()->count;

    	} catch (\Exception $e) {
    	    $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
    	    return new ApiProblemResponse(new ApiProblem(406, $message));
    	}

    	return new ViewModel(array(
    	    'data_list' => $data_list,
    	    'total_results' => $total_results,
    	    '_page' => $request['q_options']['_page'],
    	    '_limit' => $request['q_options']['_limit'],
            'img_base_url' => '/'._PICSWWW_,
    	    ));
    }
}
