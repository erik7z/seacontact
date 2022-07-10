<?php
namespace Api\V1\Rpc\UserRatingGet;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use OAuth2\Request as OAuth2Request;
use OAuth2\Server;

class UserRatingGetController extends AbstractActionController
{
    public function userRatingGetAction()
    {
		try {
			$request = $this->apiQoptions();
			$identity = $this->get('api-identity')->getAuthenticationIdentity();
			$user_id = $identity['user_id'];
			if(isset($request['filters']['user_id']) && $request['filters']['user_id']) 
				$user_id = $request['filters']['user_id'];
		    $data_list = $this->get('user_rating')->getUserRating($user_id);
		} catch (\Exception $e) {
		    $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		    return new ApiProblemResponse(new ApiProblem(406, $message));
		}

		return new ViewModel(array(
		    'data_list' => $data_list,
		    ));
    }
}
