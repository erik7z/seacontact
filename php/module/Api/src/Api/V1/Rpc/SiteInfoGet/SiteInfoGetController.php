<?php
namespace Api\V1\Rpc\SiteInfoGet;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class SiteInfoGetController extends AbstractActionController
{
    public function siteInfoGetAction()
    {
		try {
			$identity = $this->get('api-identity')->getAuthenticationIdentity();
		    $table = $this->get('InfoTable');
	        $data_list = $table->getSiteInfo($identity['user_id'])->current();

	       	$userTable = $this->get('UserTable');
	        $options['_fields'] = ['role'];
	        $data_list['profile'] = $userTable->getItems(NULL,['id' => $identity['user_id']], ['_fields' => $userTable->getStandartFields()])->current();
	        $data_list['rating'] = ($identity['user_id']) ? $this->get('user_rating')->getUserRating($identity['user_id']) : null;
	        $role = ($data_list['profile'])? $data_list['profile']->role : 'guest';
	        $data_list['navigation'] = $this->nav($role);
	        $data_list['img_base_url'] = '/'._PICSWWW_;
		} catch (\Exception $e) {
		    $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		    return new ApiProblemResponse(new ApiProblem(406, $message));
		}

		return new ViewModel(array(
		    'data_list' => $data_list,
		    ));
    }



}
