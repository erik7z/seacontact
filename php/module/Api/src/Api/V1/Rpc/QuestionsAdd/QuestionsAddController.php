<?php
namespace Api\V1\Rpc\QuestionsAdd;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class QuestionsAddController extends AbstractActionController
{
    public function questionsAddAction()
    {
        $detail = '';
        $generated_id = 0;
        try {
        	$event = $this->getEvent();
        	$inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
        	if(!$inputFilter) throw new \Application\Exception\Exception("No any valid parameters sent", 1);
        	$data = $inputFilter->getValues();
            $identity = $this->get('api-identity')->getAuthenticationIdentity();
            $data['user'] = $identity['user_id'];
            $data['time'] = time();
            $generated_id = $data['id'] = $this->get('QuestionsTable')->save($data);
            if(!$data['id']) throw new \Application\Exception\Exception($this->translate("Question not saved, unknown error"), 1);
            if($data['tags']) {
                $tags = explode(',', $data['tags']);
                $tagsTable = $this->get('TagMapTable');
                $tagsTable->addTags(\Application\Model\NewsTable::SECTION_QUESTIONS, $data['id'], $tags);
            }
            $detail = $this->translate("Question added!");
        } catch (\Exception $e) {
            $detail = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
            return new ApiProblemResponse(new ApiProblem(406, $detail));
        }

        return new ViewModel(array(
            'detail' => $detail,
            'generated_id' => $generated_id,
            ));
    }
}
