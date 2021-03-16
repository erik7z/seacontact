<?php
namespace Api\V1\Rpc\PicsUpload;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class PicsUploadController extends AbstractActionController
{
    public function picsUploadAction()
    {
		$detail = '';
		try {
			$event = $this->getEvent();
			$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
			if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
			$data = $inputFilter->getValues();
			$identity = $this->get('api-identity')->getAuthenticationIdentity();
			$img_data = $this->get('PicsTable')->uploadAndSave($data['pic'], $identity['user_id']);
	        unset($img_data['img_src'], $img_data['thumb_src'], $img_data['crop_w'], $img_data['crop_h']);
			$detail = $this->translate("Image uploaded !");
		} catch (\Exception $e) {
		    $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		    return new ApiProblemResponse(new ApiProblem(406, $detail));
		}

		return new ViewModel(array(
		    'detail' => $detail,
	        'img_data' => $img_data
		    ));
    }
}
