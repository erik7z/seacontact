<?php
namespace Api\V1\Rpc\ProfilePassReset;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ProfilePassResetController extends AbstractActionController
{
    public function profilePassResetAction()
    {
    	// Any protection from attacks (bruteforce?) to this action
	    $detail = '';
	    try {
	        $event = $this->getEvent();
	        $inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
	        if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
	        $data = $inputFilter->getValues();
	        $options['_fields'] = ['id', 'email', 'name', 'surname', 'full_name', 'login'];
	        $user = $this->get('UserTable')->getItems(NULL,['password_reset_key' => $data['password_reset_key']], $options)->current();
	        
	        $user_data = array_filter($data);
	        $user_data['id'] = $user->id;
	        $user_data['password_reset_key'] = NULL;
	        $user_data['password'] = $this->get('salt')->hash($data['password']);
	        $this->get('UserTable')->save($user_data);
	    	$this->get('Mail')->sendNewPasswordMail($user->email, zgetUserName($user->name), $data['password']);                     
	    	$detail = $this->translate('Password change is Successfull! You can now enter with your email and password');
	    } catch (\Exception $e) {
	        $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
	        return new ApiProblemResponse(new ApiProblem(406, $detail));
	    }

	    return new ViewModel(array(
	        'detail' => $detail,
	        ));    	
    }
}
