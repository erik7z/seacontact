<?php
namespace Api\V1\Rpc\PicsGet;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class PicsGetController extends AbstractActionController
{
    public function picsGetAction()
    {
		try {
		    $table = $this->get('PicsTable');
		    $permitted_fields = [
		        '_pics_fields' => $table->getStandartFields(),
		    ];
		    $default_options = [
		    '_pics_fields' => ['id', 'img', 'thumb'],
		    ];
		    $request = $this->apiQoptions($default_options, $permitted_fields);
		    if(isset($request['filters']['pics_ids'])) {
		    	$request['filters']['pics_ids'] = array_values(array_filter(explode(',', $request['filters']['pics_ids'])));
		    }
	        $identity = $this->get('api-identity')->getAuthenticationIdentity();
		    $data_list = $table->getPics($identity['user_id'],$request['filters'], $request['q_options'])->toArray();
		    $data_list = $this->ApiValidateResponse($data_list, $request['q_options']);
		    $request['q_options']['count'] = 1;
		    $total_results = $table->getPics($identity['user_id'], $request['filters'], $request['q_options'])->current()->count;

		} catch (\Exception $e) {
		    $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		    return new ApiProblemResponse(new ApiProblem(406, $message));
		}

		return new ViewModel(array(
		    'img_base_url' => '/'._PICSWWW_,
		    'data_list' => $data_list,
		    'total_results' => $total_results,
		    '_page' => $request['q_options']['_page'],
		    '_limit' => $request['q_options']['_limit'],
		    ));
    }
}
