<?php
namespace Api\V1\Rpc\VacanciesToggleSubscribe;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class VacanciesToggleSubscribeController extends AbstractActionController
{
    public function vacanciesToggleSubscribeAction()
    {
    	$detail = '';
    	$status = null;
    	$count = null;
    	try {
    		$event = $this->getEvent();
    		$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
    		if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
    		$data = $inputFilter->getValues();
    	    $identity = $this->get('api-identity')->getAuthenticationIdentity();
    	    $identity_info = $this->get('UserTable')->getUserById($identity['user_id'], false, ['id','name', 'surname', 'full_name','login', 'email', 'avatar', 'cv_avatar']);
    	    $table = $this->get('VacancySubsTable');
    	    if($table->isSubscribed($identity['user_id'], $data['id'])) {
    	        $table->unsubscribe($identity['user_id'], $data['id']);
    	        $detail = $this->translate('You are un-subscribed from this vacancy, company will be informed by email');
    	        $status = 'unsubscribed';
    	    } else {
    	        $table->subscribe($identity['user_id'], $data['id']);
    	        $this->get('AdminNotifTable')->addVacancySubNotification($data['id'], $identity['user_id']);
                $vacancy = $this->get('VacanciesTable')->getAllVacancies(null, ['id' => $data['id']])->current();
    	        $this->get('Mail')->sendSubToVacancyUserMail($identity_info->email, zgetUserName($identity_info), $data['id'], $vacancy->title);
    	        if($vacancy->email) {
    	            $experience_info = $this->get('ExperienceTable')->getUserExperience($identity['user_id']);
    	            $this->get('Mail')->sendSubToVacancyCompanyMail($vacancy, $identity_info,$experience_info);
    	        }
    	        $detail = $this->translate('You are subscribed to this vacancy, your CV sent to Company');
    	        $status = 'subscribed';
    	    }

    	    $count = $table->getVacancySubscribers($identity['user_id'], ['id' => $data['id']], ['count' => 1])->current()->count;


    	} catch (\Exception $e) {
    	    $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
    	    return new ApiProblemResponse(new ApiProblem(406, $detail));
    	}

    	return new ViewModel(array(
    	    'detail' => $detail,
    	    'status' => $status,
    	    'subscribers_count' => $count
    	    ));
    }
}
