<?php
namespace Api\V1\Rpc\VacanciesGetSubscribers;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class VacanciesGetSubscribersController extends AbstractActionController
{
    public function vacanciesGetSubscribersAction()
    {
    	    try {
    	        $uTable = $this->get('UserTable');
    	        $table = $this->get('VacancySubsTable');
    	        $permitted_fields = ['_user_fields' => $uTable->getStandartFields()];
    	        $default_options = [
    	        '_user_fields' => ['id','login']
    	        ];            
    	        $identity = $this->get('api-identity')->getAuthenticationIdentity();
    	        
    	        $request = $this->apiQoptions($default_options, $permitted_fields);

    	        $data_list = $table->getVacancySubscribers($identity['user_id'], $request['filters'], $request['q_options'])->toArray();
    	        $data_list = $this->ApiValidateResponse($data_list, $request['q_options']);
    	        
    	        $request['q_options']['count'] = 1;
    	        $total_results = $table->getVacancySubscribers($identity['user_id'], $request['filters'], $request['q_options'])->current()->count;

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
