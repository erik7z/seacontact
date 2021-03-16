<?php
namespace Api\V1\Rpc\NewsGet;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use OAuth2\Request as OAuth2Request;
use OAuth2\Server;

class NewsGetController extends AbstractActionController
{
    public function newsGetAction()
    {
    	try {
    	    $table = $this->get('NewsTable');
    	    $permitted_fields = [
    	        '_user_fields' => $this->get('UserTable')->getStandartFields(),
    	        '_stats_fields' => $table->getStatsFields(),
    	    ];
    	    $default_options = [
    	    '_user_fields' => ['id'],
    	    '_stats_fields' => ['likes'],
    	    ];
    	    $request = $this->apiQoptions($default_options, $permitted_fields);

    	    if(isset($request['filters']['only_later'])) {
    	    	$request['q_options']['only_later'] = 1;
    	    	unset($request['filters']['only_later']);
    	    }
    	    if(isset($request['filters']['only_current'])) {
    	    	$request['q_options']['only_current'] = 1;
    	    	unset($request['filters']['only_current']);
    	    }
            $identity = $this->get('api-identity')->getAuthenticationIdentity();
            $request['q_options']['dump_time'] = 10;
            
    	    $data_list = $table->getNews($identity['user_id'],$request['filters'], $request['q_options'])->toArray();
    	    $data_list = $this->ApiValidateResponse($data_list, $request['q_options']);
    	    $request['q_options']['count'] = 1;
    	    $total_results = $table->getNews($identity['user_id'], $request['filters'], $request['q_options'])->current()->count;

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
