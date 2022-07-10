<?php
namespace Api\V1\Rpc\ChatsGetList;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use OAuth2\Request as OAuth2Request;
use OAuth2\Server;

class ChatsGetListController extends AbstractActionController
{
    public function chatsGetListAction()
    {
    	try {
    	    $table = $this->get('MessageTable');
    	    $permitted_fields = [
                '_message_fields' => $table->getStandartFields(),
            ];
    	    $default_options = [
                '_message_fields' => ['id', 'text', 'chat_id'],
            ];
    	    $request = $this->apiQoptions($default_options, $permitted_fields);

    	    $identity = $this->get('api-identity')->getAuthenticationIdentity();
    	       
    	    $data_list = $table->getListOfChats($identity['user_id'], $request['filters'], $request['q_options'])->toArray();
            $data_list = $this->ApiValidateResponse($data_list, $request['q_options']);
    	    $request['q_options']['count'] = 1;
    	    $total_results = $table->getListOfChats($identity['user_id'], $request['filters'], $request['q_options'])->current()->count;

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
