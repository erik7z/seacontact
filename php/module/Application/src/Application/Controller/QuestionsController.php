<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class QuestionsController extends AbstractController
{
    public function indexAction()
    {
        $success = 1;
        $message = '';
        try {
            $q_options = $this->setDefaultOptions(['_limit' => 20, 'layout' => 'list']);
            
            $form = $this->get('EmptyForm');
            $form->add([
                'name' => 'q',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' => $this->translate('Search'),
                    'required' => true,
                    'filters' => [],
                    'validators' => [],
                    ),
                'attributes' => array(
                    'placeholder' => $this->translate('Question or #tag'),
                    ),
            ]);
            $form->addSubmit($this->translate('Search'));
            $form->setAttribute('method', 'get');
            $form->setData($this->request->getQuery());
            $q_options['q'] = str_replace('#', '', $q_options['q']);
            if($q_options['q']) $q_options['filters']['query'] = $q_options['q'];



            $user_id = ($this->identity())? $this->identity()->id : 0;

            $data_list = $this->get('QuestionsTable')->getQuestions($user_id, $q_options['filters'], $q_options);
            $q_options['count'] = 1;
            $total_results = $this->get('QuestionsTable')->getQuestions($user_id, $q_options['filters'], $q_options)->current()->count;
            if(!$q_options['_offset']) $q_options['_offset'] = ($q_options['_limit'] * $q_options['_page']) - $q_options['_limit'];

        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $viewModel = 0;
        if(isset($data_list)) 
            $viewModel = new ViewModel(array(
                'form' => $form,
                'tags_map' => $this->get('TagMapTable')->getTagsMap(null, ['section' => \Application\Model\NewsTable::SECTION_QUESTIONS]),
                'data_list' => $data_list,
                'q_options' => $q_options,
                'total_results' => $total_results,
                )); 

        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel, 
            'template' => 'application/questions/index',
            'exception' => (isset($e)) ? $e : null
            ]);
    }
    
    public function viewAction()
    {
        $success = 1;
        $message = '';
        $comments_list = null;
        $data_list = null;
        $q_options = $this->setDefaultOptions(['_limit' => 5, '_order' => 'total_rating', 'up' => 0]);

        $question_id = $this->params()->fromRoute('id');
        try {
            if(!$question_id) throw new \Application\Exception\Exception('Question id should be provided', 404);
            $viewer_id = ($this->identity())? $this->identity()->id : null;
            $question = $this->get('QuestionsTable')->getQuestions($viewer_id, ['question_id' => $question_id])->current();
            if(!$question) throw new \Application\Exception\Exception('Question with such id not found', 404);
            $this->get('ActivityViewsTable')->addView(\Application\Model\NewsTable::SECTION_QUESTIONS, $question_id, $viewer_id);
            
            $sim_questions = [];
            if($question->tags) {
                $sim_questions = $this->get('QuestionsTable')->getQuestions($viewer_id, ['tags' => explode(',', $question->tags), 'exclude' => $question_id], ['_limit' => 3]);
            }
            

            $comm_opt['viewer_id'] = $viewer_id;
            $comm_opt['_offset'] = ($question->total_comments > _VIEW_COMMENT_LIMIT_)? $question->total_comments - _VIEW_COMMENT_LIMIT_ : 0;
            $question->comments_list = json_encode($this->get('CommentsTable')->getComments(\Application\Model\NewsTable::SECTION_QUESTIONS, $question_id, [], $comm_opt)->toArray());
            
            // d($q_options);
            $data_list = $this->get('QuestionAnswersTable')->getAnswers($viewer_id, ['question_id' => $question_id], $q_options);
            $q_options['count'] = 1;
            $total_results = $this->get('QuestionAnswersTable')->getAnswers($viewer_id,['question_id' => $question_id], $q_options)->current()->count;
            if(!$q_options['_offset']) $q_options['_offset'] = ($q_options['_limit'] * $q_options['_page']) - $q_options['_limit'];


            $form = $this->get('AnswerForm')->setup($this->get('QuestionAnswersFields'));
            $form->setAttribute('action' , $this->url()->fromRoute('sc/questions/actions', ['action' => 'view', 'id' => $question_id]));

            // answer on question
            if($this->request->isPost()){
                if(!$this->isPermitted('application\controller\questions.answer-submit')) throw new \Application\Exception\Exception($this->translate('Please Authenticate'), 401);
                
                $data = array_merge_recursive(
                    $this->request->getPost()->toArray(),
                    $this->request->getFiles()->toArray()
                    );
                $form->setData($data);
                if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
                $data = $form->getData();
                $data['question_answers']['question_id'] = $question_id;
                $a_id = $this->saveAnswerData($data);

                $success = 1;
                $message = $this->translate('Answer Added!');

                // sending answer notification
                $subsTable = $this->get('QuestionSubsTable');
                $subscribers = $subsTable->getSubscribers($question_id)->toArray();
                $userNotTable = $this->get('UserNotificationsTable');
                $author = $this->get('UserTable')->getUserById($question->user, 0, ['id' => 'user_id', 'email', 'name', 'surname', 'full_name'], 0);
                if ($author) array_push($subscribers, (array) $author);
                foreach ($subscribers as $subscriber) {
                    if($subscriber['user_id'] != $this->identity()->id) {
                        $answer_author = $this->identity();
                        if ($data['question_answers']['anonym']) {
                           $answer_author->name = $answer_author->surname =  $answer_author->full_name = $answer_author->company_name = '*****';
                           $answer_author->avatar = _ANONYM_AVA_;
                        }
                        $this->addUserNotification(
                            $userNotTable::TYPE_ANSWER, 
                            \Application\Model\NewsTable::SECTION_QUESTIONS, 
                            $question_id, 
                            $this->identity()->id, 
                            $subscriber['user_id'], 
                            [   
                                'q_text' => html_entity_decode($question->text),
                                'a_text' => html_entity_decode($data['question_answers']['text']),
                                'text' => $data['question_answers']['text'],
                                'post_url' => $this->url()->fromRoute('sc/questions/actions', ['action' => 'view', 'id' => $question_id]),
                                'answer_author' => $answer_author
                            ] 
                        );
                    }
                }
            } 

        } catch (\Exception $e) {
            $success = 0;
            $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
        }
                  

        $viewModel = 0;
        if($question) 
            $viewModel = new ViewModel(array(
                'form' => $form,
                'question' => $question,
                'data_list' => $data_list,
                'total_results' => $total_results,
                'q_options' => $q_options,
                'subscribers' => $this->get('QuestionSubsTable')->getSubscribers($question_id),
                'sim_questions' => $sim_questions
                )); 

        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel, 
            'template' => 'application/questions/view',
            'exception' => (isset($e)) ? $e : null
            ]);
    }

    public function askAction()
    {
        try {
            $qFields = $this->get('QuestionFields');
            // if($this->isPermitted('_info_\social\post_vk')) $qFields->addPostToVk();
            $form = $this->get('QuestionForm');
            $form->setup($qFields);
           
            $success = 1;
            $message = '';
            if($this->request->isPost()){
                if(!$this->isPermitted('application\controller\questions.ask-submit')) throw new \Application\Exception\Exception($this->translate('Please Authenticate'), 401);
                $data = array_merge_recursive(
                    $this->request->getPost()->toArray(),
                    $this->request->getFiles()->toArray()
                    );
                $form->setData($data);
                if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
                $data = $form->getData();
                $q_id = $this->saveQuestionData($data);
                $redirect = $this->url()->fromRoute('sc/questions/actions', ['action' => 'view', 'id' => $q_id]);
                $success = 1;
                $message = $this->translate('Question Added!');
            }

        } catch (\Exception $e) {
            $success = 0;
            $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage(); 
        }
           
        $viewModel = 0;
        if(isset($form)) 
            $viewModel = new ViewModel(array(
                'form' => $form,
                'tags_map' => $this->get('TagMapTable')->getTagsMap(null, [], ['_limit' => 0, '_order' => 'name', 'up' => 1, 'more_than' => 1]),
                ));
        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel, 
            'template' => 'application/questions/ask',
            'exception' => (isset($e)) ? $e : null,
            'redirect' => (isset($redirect))? $redirect : null,
            'force_redirect' => (isset($redirect))? 1:0,
            ]);
    }

    public function askSubmitAction()
    {
        return $this->redirect()->toRoute('sc/questions',[],[], true);
    }

    public function editAction()
    {
        $success = 1;
        $message = '';

        $question_id = $this->params()->fromRoute('id');
        try {
            if(!$question_id) throw new \Application\Exception\Exception($this->translate('Question id should be provided', 404));
            $viewer_id = $this->identity()->id;
            $question = $this->get('QuestionsTable')->getQuestions($viewer_id, ['question_id' => $question_id]);
            $question = $question->current();
            $original_time = $question->time;
            if(!$question) throw new \Application\Exception\Exception('Question with such id not found', 404);
            $qFields = $this->get('QuestionFields');
            // if($this->isPermitted('_info_\social\post_vk')) $qFields->addPostToVk();
            $form = $this->get('QuestionForm');
            $form->setup($qFields, ['form_data' => $question, 'submit_text' => $this->translate('Save')]);

            if($this->request->isPost()){
                $data = array_merge_recursive(
                    $this->request->getPost()->toArray(),
                    $this->request->getFiles()->toArray()
                    );
                $old_pics = (isset($data['old_pics']))? $data['old_pics'] :null;
                $form->setData($data);
                if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
                $data = $form->getData();
                $q_id = $this->saveQuestionData($data, $old_pics, $question->user, $original_time);
                $redirect = $this->url()->fromRoute('sc/questions/actions', ['action' => 'view', 'id' => $q_id]);
                $success = 1;
                $message = $this->translate('Question Saved!');
            }


        } catch (\Exception $e) {
            $success = 0;
            $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage(); 
        }
           
        $viewModel = 0;
        if(isset($form)) 
            $viewModel = new ViewModel(array(
                'form' => $form,
                'tags_map' => $this->get('TagMapTable')->getTagsMap(null, [], ['_limit' => 0, '_order' => 'name', 'up' => 1, 'more_than' => 1]),
                ));
        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel, 
            'template' => 'application/questions/edit',
            'exception' => (isset($e)) ? $e : null,
            'redirect' => (isset($redirect))? $redirect : null,
            ]);
    }

    // check view action for the post of the answer
    public function answerAction()
    {
       $success = 1;
       $message = '';
       $id = $this->params()->fromRoute('id');
       $viewer_id = ($this->identity())? $this->identity()->id : null;
       try {
           if(!$id) throw new \Application\Exception\Exception($this->translate('Answer id should be provided', 404));
           $answer = $this->get('QuestionAnswersTable')->getAnswers($viewer_id, ['answer_id' => $id])->current();
           if(!$answer) throw new \Application\Exception\Exception($this->translate('Answer with such id not found', 404));
           $this->get('ActivityViewsTable')->addView(\Application\Model\NewsTable::SECTION_ANSWERS, $id, $viewer_id);
           $comm_opt['viewer_id'] = $viewer_id;
           $comm_opt['_offset'] = ($answer->total_comments > _VIEW_COMMENT_LIMIT_)? $answer->total_comments - _VIEW_COMMENT_LIMIT_ : 0;
           $answer->comments_list = json_encode($this->get('CommentsTable')->getComments(\Application\Model\NewsTable::SECTION_ANSWERS, $id, [], $comm_opt)->toArray());


       } catch (\Exception $e) {
           $success = 0;
           $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
       }

       $viewModel = 0;
       if(isset($answer)) 
           $viewModel = new ViewModel(array(
               'answer' => $answer,
               )); 

       return $this->viewResponse($success, $message, [
           'view_data' => $viewModel, 
           'template' => 'application/questions/answer',
           'exception' => (isset($e)) ? $e : null
           ]);
    }

    // only for access routeing
    public function answerSubmitAction()
    {
        return $this->redirect()->toRoute('sc/questions',[],[], true);
    }

    public function answerEditAction()
    {
        $success = 1;
        $message = '';

        $a_id = $this->params()->fromRoute('id');
        try {
            if(!$a_id) throw new \Application\Exception\Exception($this->translate('Answer id should be provided', 404));
            $answer = $this->get('QuestionAnswersTable')->getAnswers($this->identity()->id, ['answer_id' => $a_id]);
            $answer = $answer->current();
            $original_time = $answer->time;
            if(!$answer) throw new \Application\Exception\Exception($this->translate('Answer with such id not found', 404));

            $form = $this->get('AnswerForm')->setup($this->get('QuestionAnswersFields'), ['form_data' => $answer, 'submit_text' => $this->translate('Save')]);
            $form->setAttribute('action' , $this->url()->fromRoute('sc/questions/actions', ['action' => 'answer-edit', 'id' => $a_id]));

            if($this->request->isPost()){
                $data = array_merge_recursive(
                    $this->request->getPost()->toArray(),
                    $this->request->getFiles()->toArray()
                    );
                $old_pics = (isset($data['old_pics']))? $data['old_pics'] :null;
                $form->setData($data);
                if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
                $data = $form->getData();
                $data['question_answers']['question_id'] = $answer->question_id;
                $this->saveAnswerData($data, $old_pics, $answer->user, $original_time);
                $redirect = $this->url()->fromRoute('sc/questions/actions', ['action' => 'view', 'id' => $answer->question_id]);
                $success = 1;
                $message = $this->translate('Answer Saved!');
            }


        } catch (\Exception $e) {
            $success = 0;
            $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage(); 
        }
           
        $viewModel = 0;
        if(isset($form)) 
            $viewModel = new ViewModel(array(
                'form' => $form,
                ));
        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel, 
            'template' => 'application/questions/answer-edit',
            'exception' => (isset($e)) ? $e : null,
            'redirect' => (isset($redirect))? $redirect : null,
            ]);
    }


    public function changeRatingAction()
    {
        try {
            $success = 1;
            $message = '';
            $id = (int)$this->params()->fromRoute('id');
            $rating = $this->params()->fromQuery('rating');
            if($id == 0) throw new \Application\Exception\Exception("Question id Not provided", 1);
            if(!$rating) throw new \Application\Exception\Exception("rating Not provided", 1);
            if(!in_array($rating, ['up', 'down'])) throw new \Application\Exception\Exception("rating unknown", 1);
            $question = $this->get('QuestionsTable')->get($id);
            if(!$question) throw new \Application\Exception\Exception('Question with such id not found', 404);

            $votesTable = $this->get('ActivityVotesTable');

            $result = $votesTable->addVote($rating, \Application\Model\NewsTable::SECTION_QUESTIONS, $id, $this->identity()->id);
            $rating = $votesTable->getRating(\Application\Model\NewsTable::SECTION_QUESTIONS, $id, $this->identity()->id)->current();
            $userNotTable = $this->get('UserNotificationsTable');
            $this->addUserNotification($userNotTable::TYPE_VOTE, \Application\Model\NewsTable::SECTION_QUESTIONS, $id, $this->identity()->id, $question->user);

     
        } catch (\Exception $e) {
            $success = 0;
            $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
        }
        return $this->viewResponse($success, $message, [
            'extra_data' => $rating,
            'redirect' => $this->url()->fromRoute('sc/questions'), 
            'exception' => (isset($e)) ? $e : null
            ]);
    }

    public function answerChangeRatingAction()
    {
        try {
            $success = 1;
            $message = '';
            $id = (int)$this->params()->fromRoute('id');
            $rating = $this->params()->fromQuery('rating');
            if($id == 0) throw new \Application\Exception\Exception("Answer id Not provided", 1);
            if(!$rating) throw new \Application\Exception\Exception("rating Not provided", 1);
            if(!in_array($rating, ['up', 'down'])) throw new \Application\Exception\Exception("rating unknown", 1);
            $answer = $this->get('QuestionAnswersTable')->get($id);
            if(!$answer) throw new \Application\Exception\Exception($this->translate('Answer with such id not found', 1));

            $votesTable = $this->get('ActivityVotesTable');

            $result = $votesTable->addVote($rating, \Application\Model\NewsTable::SECTION_ANSWERS, $id, $this->identity()->id);
        
        } catch (\Exception $e) {
            $success = 0;
            $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
        }
        
        return $this->viewResponse($success, $message, [
            'redirect' => $this->url()->fromRoute('sc/questions', ['action' => 'view', 'id' => $answer->question_id]), 
            'exception' => (isset($e)) ? $e : null
            ]);
    }


    public function toggleAcceptAction()
    {
        try {
            $success = 1;
            $message = '';
            $id = (int)$this->params()->fromRoute('id');
            if($id == 0) throw new \Application\Exception\Exception("Answer id Not provided", 1);
            $answer = $this->get('QuestionAnswersTable')->get($id);
            if(!$answer) throw new \Application\Exception\Exception($this->translate('Answer with such id not found', 1));

            $answerTable = $this->get('QuestionAnswersTable');
            if($answer->correct)  $answerTable->removeAccept($answer->question_id);
            else $answerTable->accept($id, $answer->question_id);
        
        } catch (\Exception $e) {
            $success = 0;
            $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
        }
        
        return $this->viewResponse($success, $message, [
            'redirect' => $this->url()->fromRoute('sc/questions', ['action' => 'view', 'id' => $answer->question_id]),
            'exception' => (isset($e)) ? $e : null
            ]);
    }

    public function deleteAction()
    { 
        // CHECK FROM OWNER PROPERTY
        $id = $this->params()->fromRoute('id');
        try {
            if(!$id) throw new \Application\Exception\Exception("id not provided", 1);
            // $this->get('QuestionsTable')->delete($id, $this->identity()->id);
            $this->get('QuestionsTable')->save(['id' => $id, 'active' => 0]);
            $success = 1;
            $message = $this->translate('Question Deleted');
        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $default_route = $this->url()->fromRoute('sc/questions');
        $previous_url = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : $default_route; 
        $redirect = (strpos($previous_url, 'view/'.$id) !== false) ? $default_route : $previous_url;


        return $this->viewResponse($success, $message, [
            'redirect' => $redirect, 
            'force_redirect' => 1,
            'exception' => (isset($e)) ? $e : null
            ]); 
    }

    public function answerDeleteAction()
    {
        // CHECK FROM OWNER PROPERTY
        $id = $this->params()->fromRoute('id');
        try {
            if(!$id) throw new \Application\Exception\Exception("id not provided", 1);
            // $this->get('QuestionAnswersTable')->delete($id, $this->identity()->id);
            $this->get('QuestionAnswersTable')->save(['id' => $id, 'active' => 0]);
            $success = 1;
            $message = $this->translate('Answer Deleted');
        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        $default_route = $this->url()->fromRoute('sc/questions');
        $previous_url = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : $default_route; 
        $redirect = (strpos($previous_url, 'view/'.$id) !== false) ? $default_route : $previous_url;

        return $this->viewResponse($success, $message, [
            'redirect' => $redirect, 
            'force_redirect' => 1,
            'exception' => (isset($e)) ? $e : null
            ]); 
    }


    public function toggleSubscribeAction() {
        $question_id = (int)$this->params()->fromRoute('id');
        $redirect = $this->params()->fromQuery('redirect');
        $subs_count = null;
        $status = '';
        try {
            if($question_id == 0) throw new \Application\Exception\Exception('Question with such id not found', 404);
            if(!$this->identity()) {
                $redirect = $this->url()->fromRoute('sc/fast-reg', array(), array('query' => array('subscribe_question' => $question_id)));
                throw new \Application\Exception\Exception($this->translate("Authorisation Required"), 401);
            }
            $subsTable = $this->get('QuestionSubsTable');
            if($subsTable->isSubscribed($this->identity()->id, $question_id)) {
                $subsTable->unsubscribe($this->identity()->id, $question_id);
                $message = $this->translate('You are un-subscribed to this question, no more notifications for this question would be received');
                $status = 'unsubscribed';
            } else {
                $subsTable->subscribe($this->identity()->id, $question_id);
                $notTable = $this->get('AdminNotifTable');
                $notTable->addNotification($notTable::NOT_TYPE_NEW_SUBSCRIBER, $notTable::NOT_SECTION_QUESTION, $question_id, $this->identity()->id, 'New user subscribed for question');
                $userNotTable = $this->get('UserNotificationsTable');
                $to_id = $this->getPostAuthorBySectionId(\Application\Model\NewsTable::SECTION_QUESTIONS, $question_id);
                if($to_id) $this->addUserNotification($userNotTable::TYPE_SUBSCRIBER, \Application\Model\NewsTable::SECTION_QUESTIONS, $question_id, $this->identity()->id, $to_id, ['text' => 'New user subscribed for question']);
                $message = $this->translate('You are subscribed to this question, you will be informed when answers found');
                $status = 'subscribed';
            }
            $subs_count = $subsTable->getSubscribers($question_id, ['id'])->count();
            $success = 1;
            
        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();

        }
        return $this->viewResponse($success, $message, [
            'redirect' => ($redirect)? $redirect : $this->url()->fromRoute('sc/questions'),
            'force_redirect' => ($redirect)? 1 : 0,
            'exception' => (isset($e)) ? $e : null,
            'extra_data' => ['status' => $status, 'count' => $subs_count]
            ]);
    }

    protected function saveQuestionData($data, $old_pics = null, $owner_id = null, $original_time = null)
    {
        if(!$owner_id) $owner_id = $this->identity()->id;
        $q_data = $data['questions'];
        $q_data['user'] = $owner_id;
        $q_data['time'] = ($original_time)? $original_time : time();
        $q_data['id'] = $this->get('QuestionsTable')->save($q_data);
        if(!$q_data['id']) throw new \Application\Exception\Exception($this->translate("Question not saved, some error"), 1);
        if($q_data['tags']) {
            $tags = explode(',', $q_data['tags']);
            $tagsTable = $this->get('TagMapTable');
            $tagsTable->addTags(\Application\Model\NewsTable::SECTION_QUESTIONS, $q_data['id'], $tags);
        }

        $data['old_pics'] = $old_pics;

        $uploaded_pics = null;
        if(isset($data['pics']) && !empty($data['pics'])) {
            $uploaded_pics = $this->get('PicsTable')->saveArticlePics($data, 'questions', $q_data['id'], $owner_id);
        }

        if(isset($q_data['post_vk'])){
            try {
                $q_data['post_vk_id'] = $this->postLogEntryToVk($q_data, $owner_id, $uploaded_pics);
                $q_data['post_vk_time'] = time();
            } catch (\Exception $e) {
                $q_data['post_vk'] = 0;
                $this->fm()->addErrorMessage($e->getMessage());
            }
            $this->get('QuestionsTable')->save(array(
                'id' => $q_data['id'], 
                'post_vk_id' => $q_data['post_vk_id'], 
                'post_vk' => $q_data['post_vk'],
                'post_vk_time' => $q_data['post_vk_time'],
                ));
        }

        return $q_data['id'];
    }

    protected function saveAnswerData($data, $old_pics = null, $owner_id = null, $original_time = null)
    {
        if(!$owner_id) $owner_id = $this->identity()->id;

        $a_data = $data['question_answers'];
        $a_data['user'] = $owner_id;
        $a_data['time'] = ($original_time)? $original_time : time();
        $a_data['id'] = $this->get('QuestionAnswersTable')->save($a_data);

        if(!$a_data['id']) throw new \Application\Exception\Exception($this->translate("Question not saved, some error"), 1);
        $data['old_pics'] = $old_pics;

        $uploaded_pics = null;
        if(isset($data['pics']) && !empty($data['pics'])) {
            $uploaded_pics = $this->get('PicsTable')->saveArticlePics($data, 'answers', $a_data['id'], $owner_id);
        }
        if(!$a_data['id']) throw new \Application\Exception\Exception($this->translate("Answer not saved, unknown error"), 1);

        return $a_data['id'];
    }






}
