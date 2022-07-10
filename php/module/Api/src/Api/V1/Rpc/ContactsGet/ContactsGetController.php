<?php
namespace Api\V1\Rpc\ContactsGet;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use OAuth2\Request as OAuth2Request;
use OAuth2\Server;

class ContactsGetController extends AbstractActionController
{
    public function contactsGetAction()
    {
		try {
		    $table = $this->get('ContactsTable');
		    $permitted_fields = [
		        '_user_fields' => $this->get('UserTable')->getStandartFields(),
		        '_contacts_fields' => $table->getStandartFields(),
		    ];
		    $default_options = [
		    '_user_fields' => ['id'],
		    '_contacts_fields' => ['worked_together', 'relations'],
		    ];
		    $request = $this->apiQoptions($default_options, $permitted_fields);

		    if(isset($request['filters']['relations'])) {
		    	$request['q_options']['relations'] = $request['filters']['relations'];
		    	unset($request['filters']['relations']);
		    }
		    $identity = $this->get('api-identity')->getAuthenticationIdentity();
		    $owner_id = $identity['user_id'];
		    if(isset($request['filters']['owner_id'])) {
		    	$owner_id = $request['filters']['owner_id'];
		    	unset($request['filters']['owner_id']);
		    }

		    $data_list = $table->getContacts($owner_id ,$request['filters'], $request['q_options'])->toArray();
		    $data_list = $this->ApiValidateResponse($data_list, $request['q_options']);
		    $request['q_options']['count'] = 1;
		    $total_results = $table->getContacts($owner_id, $request['filters'], $request['q_options'])->current()->count;

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
