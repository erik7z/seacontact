<?php
namespace Api\V1\Rpc\ProfileRegStart;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ProfileRegStartController extends AbstractActionController
{
    public function profileRegStartAction()
    {
	    $detail = '';
	    try {
	        $event = $this->getEvent();
	        $inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
	        if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
	        $data = $inputFilter->getValues();
	        $user_data['user'] = array_filter($data);
	        $user_data['user']['email_confirmation_key'] = $this->get('salt')->regKey(6, true);
	        $user_data['user']['info_source'] = (isset($user_data['user']['info_source']))? $user_data['user']['info_source'] : 'registration_mobile';
	        $user_data['user']['role'] = \Application\Access\AccessList::ROLE_UNREG;
	        $user_data = $this->get('user_reg')->saveUserData($user_data);
	        $mail = $this->get('Mail');
	        $mail->sendConfMail($user_data['user']['email'], '', $user_data['user']['email_confirmation_key']);

	        $detail = $this->translate('We send a confirmation code to you. Please check your email, to complete registration !');
	    } catch (\Exception $e) {
	        $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
	        return new ApiProblemResponse(new ApiProblem(406, $detail));
	    }

	    return new ViewModel(array(
	        'detail' => $detail,
	        ));
    }
}
