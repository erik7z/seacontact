<?php
namespace Api\V1\Rpc\LikesIsLiked;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class LikesIsLikedController extends AbstractActionController
{
    public function likesIsLikedAction()
    {
    	$detail = '';
    	$result = 0;
    	$generated_id = 0;
    	try {
    		$request = $this->apiQoptions();
    		$identity = $this->get('api-identity')->getAuthenticationIdentity();
    	    $result = $this->get('LikesTable')->isLiked($identity['user_id'], $request['filters']['section'], $request['filters']['section_id']);
    	    $detail = ($result)? $this->translate("Like exist") : $this->translate("Like Not exist");
    	} catch (\Exception $e) {
    	    $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
    	    return new ApiProblemResponse(new ApiProblem(406, $detail));
    	}

    	return new ViewModel(array(
    	    'detail' => $detail,
    	    'result' => $result
    	    ));
    }
}
