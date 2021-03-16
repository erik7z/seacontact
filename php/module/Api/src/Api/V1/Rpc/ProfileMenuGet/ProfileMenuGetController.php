<?php
namespace Api\V1\Rpc\ProfileMenuGet;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ProfileMenuGetController extends AbstractActionController
{
    public function profileMenuGetAction()
    {
		try {
			$identity = $this->get('api-identity')->getAuthenticationIdentity();
		    $options['_fields'] = ['role'];
	        $user = $this->get('UserTable')->getItems(NULL,['id' => $identity['user_id']], $options)->current();
	        $role = ($user)? $user->role : 'guest';
	        $data_list['navigation'] = $this->nav($role);

		} catch (\Exception $e) {
		    $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		    return new ApiProblemResponse(new ApiProblem(406, $message));
		}

		return new ViewModel(array(
		    'data_list' => $data_list,
		    ));
    }
}
