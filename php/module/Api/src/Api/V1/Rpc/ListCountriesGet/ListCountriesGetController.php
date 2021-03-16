<?php
namespace Api\V1\Rpc\ListCountriesGet;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ListCountriesGetController extends AbstractActionController
{
    public function listCountriesGetAction()
    {
	    try {
	        $table = new \Application\Model\zEmptyTable('list-countries');
	        $permitted_fields = ['_fields' => $table->getStandartFields()];
	        $default_options = [
	        	'_fields' => ['country_name'],
	        	'_limit' => 0
	        ];           
	        
	        $request = $this->apiQoptions($default_options, $permitted_fields);
	        $request['q_options']['_order'] = 'id';
	        $request['q_options']['up'] = 1;
	        $data_list = $table->getItems(NULL,$request['filters'], $request['q_options'])->toArray();
			$request['q_options']['count'] = 1;
	        $total_results = $table->getItems(NULL,$request['filters'], $request['q_options'])->current()->count;
	        
	        
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
