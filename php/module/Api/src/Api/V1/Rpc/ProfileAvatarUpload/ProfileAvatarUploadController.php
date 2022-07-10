<?php
namespace Api\V1\Rpc\ProfileAvatarUpload;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ProfileAvatarUploadController extends AbstractActionController
{
    public function profileAvatarUploadAction()
    {
		$detail = '';
		try {
			$event = $this->getEvent();
			$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
			if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
			$data = $inputFilter->getValues();
			$table = $this->get('UserTable');
		    $identity = $this->get('api-identity')->getAuthenticationIdentity();
		    $this->get('UploadImage')->init(['size' => [180, 270], 'thumb' => [90, 135]]);
			$img_data = $this->get('UploadImage')->upload($data['avatar'],true, $identity['user_id']);
			$img_data['crop'] = $img_data['img'];
			$table->addNewAvatar($identity['user_id'],$img_data);
	        unset($img_data['img_src'], $img_data['thumb_src'], $img_data['crop_w'], $img_data['crop_h']);
	        $detail = $this->translate("Avatar image saved!");
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
