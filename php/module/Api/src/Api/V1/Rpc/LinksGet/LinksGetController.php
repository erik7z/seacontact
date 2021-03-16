<?php
namespace Api\V1\Rpc\LinksGet;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class LinksGetController extends AbstractActionController
{
    public function linksGetAction()
    {
		try {
		    $table = $this->get('LinksTable');
		    $permitted_fields = [
		        '_links_fields' => $table->getStandartFields(),
		    ];
		    $default_options = [
		    '_links_fields' => ['id', 'url', 'title'],
		    ];
		    $request = $this->apiQoptions($default_options, $permitted_fields);

	        $identity = $this->get('api-identity')->getAuthenticationIdentity();
	        $request['q_options']['_fields'] = $request['q_options']['_links_fields'];
		    $data_list = $table->getItems(NULL,$request['filters'], $request['q_options'])->toArray();
		    $data_list = $this->ApiValidateResponse($data_list, $request['q_options']);
		    $request['q_options']['count'] = 1;
		    $total_results = $table->getItems(NULL,$request['filters'], $request['q_options'])->current()->count;

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
