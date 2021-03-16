<?php
namespace Api\V1\Rpc\ProfileGet;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ProfileGetController extends AbstractActionController
{
    public function profileGetAction()
    {
	    try {
	        $table = $this->get('UserTable');
	        $permitted_fields = ['_user_fields' => $table->getStandartFields()];
	        $default_options = [
	        '_user_fields' => ['id','login']
	        ];            
	        $identity = $this->get('api-identity')->getAuthenticationIdentity();
	        
	        $request = $this->apiQoptions($default_options, $permitted_fields);
	        
	        $request['q_options']['user_type'] = false;
	        $request['filters']['id'] = $identity['user_id'];

	        $profile = $table->getUsersList(null,$request['filters'], $request['q_options'])->current();

	    } catch (\Exception $e) {
	        $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
	        return new ApiProblemResponse(new ApiProblem(406, $message));
	    }

		return new ViewModel($profile);
    }
}
