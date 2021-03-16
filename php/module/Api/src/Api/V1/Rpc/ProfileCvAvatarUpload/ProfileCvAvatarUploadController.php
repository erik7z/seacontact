<?php
namespace Api\V1\Rpc\ProfileCvAvatarUpload;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ProfileCvAvatarUploadController extends AbstractActionController
{
    public function profileCvAvatarUploadAction()
    {
		$detail = '';
		try {
			$event = $this->getEvent();
			$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
			if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
			$data = $inputFilter->getValues();
			$table = $this->get('UserTable');
		    $identity = $this->get('api-identity')->getAuthenticationIdentity();
			$img_data = $this->get('UploadImage')->upload($data['cv_avatar'],true, $identity['user_id']);
	        $table->save(['id' => $identity['user_id'], 'cv_avatar' => $img_data['img']]);
	        unset($img_data['img_src'], $img_data['thumb_src'], $img_data['crop_w'], $img_data['crop_h']);
	        $detail = $this->translate("CV Avatar image saved!");
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
