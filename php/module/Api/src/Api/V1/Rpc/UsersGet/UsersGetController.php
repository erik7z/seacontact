<?php
namespace Api\V1\Rpc\UsersGet;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class UsersGetController extends AbstractActionController
{
    public function usersGetAction()
    {
        try {
            $table = $this->get('UserTable');
            $permitted_fields = ['_user_fields' => $table->getStandartFields()];
            $default_options = [
            '_user_fields' => ['id','login']
            ];            
            $identity = $this->get('api-identity')->getAuthenticationIdentity();
            
            $request = $this->apiQoptions($default_options, $permitted_fields);

            // show self profile if no filters sent
            // if(!count($request['filters']) && $identity['user_id']) $request['filters']['id'] = $identity['user_id'];
            if(isset($request['filters']['type'])) {
                $request['q_options']['user_type'] = $request['filters']['type'];
                unset($request['filters']['type']);
            }

            if(isset($request['filters']['unlocked_users'])) {
                $request['q_options']['unlocked_users'] = $request['filters']['unlocked_users'];
                if($identity['user_id']) $request['q_options']['company_id'] = $identity['user_id'];
                unset($request['filters']['unlocked_users']);
            }
            $data_list = $table->getUsersList($identity['user_id'],$request['filters'], $request['q_options'])->toArray();
            $data_list = $this->ApiValidateResponse($data_list, $request['q_options']);
            
            $request['q_options']['count'] = 1;
            $total_results = $table->getUsersList($identity['user_id'], $request['filters'], $request['q_options'])->current()->count;

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