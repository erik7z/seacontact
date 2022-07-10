<?php
namespace Api\V1\Rpc\ProfileRegComplete;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ProfileRegCompleteController extends AbstractActionController
{
    public function profileRegCompleteAction()
    {
	    $detail = '';
	    try {
	        $event = $this->getEvent();
	        $inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
	        if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
	        $data = $inputFilter->getValues();
	        $options['_fields'] = ['id', 'in_db_date', 'email', 'name', 'surname'];
	        $user = $this->get('UserTable')->getItems(NULL,['email_confirmation_key' => $data['email_confirmation_key']], $options)->current();
	        
	        $user_data = array_filter($data);
	        $user_data['id'] = $user->id;
	        $user_data['email_confirmation_key'] = NULL;
	        $user_data['password'] = $this->get('salt')->hash($data['password']);
        	$user_data['reg_date'] = time();
        	if(!$user->in_db_date) $user_data['in_db_date'] = time();
        	$this->get('Mail')->sendSuccessMail($user->email, $user->name, $data['password']);                        
        	$detail = $this->translate('Registration Successfull! You can now enter with your email and password');
        	$this->get('UserTable')->save($user_data);
	    } catch (\Exception $e) {
	        $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
	        return new ApiProblemResponse(new ApiProblem(406, $detail));
	    }

	    return new ViewModel(array(
	        'detail' => $detail,
	        ));
    }
}
