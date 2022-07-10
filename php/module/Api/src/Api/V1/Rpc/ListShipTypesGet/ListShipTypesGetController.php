<?php
namespace Api\V1\Rpc\ListShipTypesGet;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ListShipTypesGetController extends AbstractActionController
{
    public function listShipTypesGetAction()
    {
	    try {
        $table = $this->get('ExperienceTable');

        $permitted_fields = [];
        $default_options = [
        	'_limit' => 0
        ];           
        
        $request = $this->apiQoptions($default_options, $permitted_fields);
        $request['q_options']['for_select'] = 0;
		$request['q_options']['use_cache'] = 0;
        $request['q_options']['more_than'] = 1;
        $data_list = $table->getFieldCountForSelect('ship_type',$request['filters'], $request['q_options']);
		$request['q_options']['count'] = 1;
        $total_results = $table->getFieldCountForSelect('ship_type',$request['filters'], $request['q_options']);
        if(isset($total_results[0]['count'])) $total_results = $total_results[0]['count'];
    } catch (\Exception $e) {
        $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
        return new ApiProblemResponse(new ApiProblem(406, $message));
    }

	return new ViewModel(array(
        'data_list' => $data_list,
        'total_results' => $total_results,
        '_page' => $request['q_options']['_page'],
        '_limit' => $request['q_options']['_limit'],
		));
    }
}
