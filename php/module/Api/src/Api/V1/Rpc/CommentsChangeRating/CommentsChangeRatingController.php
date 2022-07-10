<?php
namespace Api\V1\Rpc\CommentsChangeRating;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class CommentsChangeRatingController extends AbstractActionController
{
    public function commentsChangeRatingAction()
    {
    	$detail = '';
    	$generated_id = 0;
    	try {
    		$event = $this->getEvent();
    		$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
    		if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
    		$data = $inputFilter->getValues();
    	    $identity = $this->get('api-identity')->getAuthenticationIdentity();

            if(!$this->isPermitted('my\controller\comments.change-rating', null, $data['id'],$this->get('api-identity')->getRoleId(), $identity['user_id'])) 
                throw new \Application\Exception\Exception("You dont have rights to access this action", 401);

    	    $table = $this->get('ActivityVotesTable');
    	    $table->addVote($data['rating'], \Application\Model\NewsTable::SECTION_COMMENTS, $data['id'], $identity['user_id']);
    	    $rating = $table->getRating(\Application\Model\NewsTable::SECTION_COMMENTS, $data['id'], $identity['user_id'])->current();
    	    $detail = $this->translate("Success!");
    	} catch (\Exception $e) {
    	    $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
    	    return new ApiProblemResponse(new ApiProblem(406, $detail));
    	}

    	return new ViewModel(array(
    	    'detail' => $detail,
    	    'rating' => $rating,
    	    ));
    }
}
