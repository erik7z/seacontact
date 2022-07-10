<?php
namespace Api\V1\Rpc\ProfilePassForgot;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ProfilePassForgotController extends AbstractActionController
{
    public function profilePassForgotAction()
    {
    	$detail = '';
    	try {
    	    $event = $this->getEvent();
    	    $inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
    	    if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
    	    $data = $inputFilter->getValues();
    	    $options['_fields'] = ['id', 'email', 'name', 'surname', 'full_name'];
    	    $user = $this->get('UserTable')->getItems(NULL,['email' => $data['email']], $options)->current();
    	    
    	    $user_data['id'] = $user->id;
    	    $user_data['password_reset_key'] = $this->get('salt')->resetKey(6, true);
    	    $this->get('UserTable')->save($user_data);
    	    $mail = $this->get('Mail');
    	    $mail->sendResetPassMail($user->email, zgetUserName($user), $user_data['password_reset_key']);
    	    $detail = $this->translate('Please check e-mail to reset your password');
    	} catch (\Exception $e) {
    	    $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
    	    return new ApiProblemResponse(new ApiProblem(406, $detail));
    	}

    	return new ViewModel(array(
    	    'detail' => $detail,
    	    ));    	
    }
}
