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

class VacanciesController extends AbstractController
{
    public function indexAction()
    {
        $success = 1;
        $message ='';
        try {
            $q_options = $this->setDefaultOptions(['_limit' => 50, 'layout' => 'thumb']);
                
            if($q_options['layout'] == 'thumb') {
                if($q_options['_limit'] > 10) $q_options['_limit'] = 10;
            }
            else if($q_options['_limit'] < 50) $q_options['_limit'] = 50;
            
            $forward_filters = $this->params()->fromRoute('filters');
            if($forward_filters) $q_options['filters'] = array_merge($q_options['filters'], $forward_filters);
            $viewer_id = ($this->identity())? $this->identity()->id : 0;
           
            $q_options['show_hidden'] = 0;
            $data_list = $this->get('VacanciesTable')->getAllVacancies($viewer_id, $q_options['filters'], $q_options);
            $q_options['count'] = 1;
            $total_results = $this->get('VacanciesTable')->getAllVacancies($viewer_id, $q_options['filters'], $q_options)->current()->count;
            if(!$q_options['_offset']) $q_options['_offset'] = ($q_options['_limit'] * $q_options['_page']) - $q_options['_limit'];


            $form = $this->get('EmptyForm');
            $form->setAttribute('method', 'get');
            $fieldset = $this->get('EmptyFieldset')->setName('filters');

            $ship_type = $this->get('VacanciesTable')->getFieldCountForSelect('ship_type', ['only_active' => 1], ['use_cache' => 0]);
            $rank_type = $this->get('VacanciesTable')->getFieldCountForSelect('rank', ['only_active' => 1], ['use_cache' => 0]);
    
            $fieldset->add(array(
                'name' => 'ship_type',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => $this->translate('Fleet'),
                    'empty_option' => '--'.$this->translate('Fleet').'--',
                    'options' => $ship_type,
                    ),
                )); 

            $fieldset->add(array(
                'name' => 'minimum_salary',
                'type' => 'Zend\Form\Element\Number',
                'options' => array(
                    'label' => $this->translate('Minimum Salary'),
                    ),
                'attributes' => array(
                    'min' => 0,
                    'max' => 99999,
                    ),
                ));

            $fieldset->add(array(
                'name' => 'max_contract',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => $this->translate('Contract'),
                    'empty_option' => '--'.$this->translate('Contract').'--',
                    'options' => [
                        30 => '< 1 '.$this->translate('Month'),
                        90 => '< 3 '.$this->translate('Month\'s'),
                        180 => '< 6 '.$this->translate('Month\'s'),
                        240 => '< 9 '.$this->translate('Month\'s'),
                    ],
                    ),
                ));

            $fieldset->add(array(
                'name' => 'rank',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => $this->translate('Rank'),
                    'empty_option' => '--'.$this->translate('Rank').'--',
                    'options' => $rank_type,
                    ),
                ));

            $form->add($fieldset);
            $form->add([
                'name' => 'layout',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => $this->translate('Layout'),
                    'options' => array(
                            'thumb' => $this->translate('Thumbs'),
                            'list' => $this->translate('List')
                        ),
                    ),
                ]);
            $form->add([
                'name' => '_limit',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => $this->translate('Results on page'),
                    'options' => array(
                            10 => 10,
                            50 => 50,
                            100 => 100,
                            500 => 500
                        ),
                    ),
                ]);
            $form->addSubmit('Filter');
            $form->setData($this->request->getQuery());
        } catch (\Exception $e) {
            $message = ($e->getCode() == 100)? unserialize($e->getMessage()) : $e->getMessage();
            $success = 0;
        }

        $viewModel = 0;
        if(isset($form)) 
            $viewModel = new ViewModel(array(
                'form' => $form,
                'data_list' => $data_list,
                'total_results' => $total_results,
                'q_options' => $q_options,
                )); 

        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel, 
            'template' => 'application/vacancies/index',
            'exception' => (isset($e)) ? $e : null
            ]);
    }




    public function viewAction()
    {

    	$vacancy_id = $this->params()->fromRoute('id');
        $viewer_id = ($this->identity())? $this->identity()->id : null;
        $vacancy = $this->get('VacanciesTable')->getAllVacancies($viewer_id, ['id' => $vacancy_id])->current();
       
    	if(!$vacancy) throw new \Application\Exception\Exception($this->translate('Vacancy with such id not found', 404));
        $this->get('ActivityViewsTable')->addView('vacancy', $vacancy_id, $viewer_id);
        
        $comm_opt['viewer_id'] = $viewer_id;
        $comm_opt['_offset'] = ($vacancy->total_comments > _VIEW_COMMENT_LIMIT_)? $vacancy->total_comments - _VIEW_COMMENT_LIMIT_ : 0;
        $vacancy->comments_list = json_encode($this->get('CommentsTable')->getComments(\Application\Model\NewsTable::SECTION_VACANCY, $vacancy_id, [], $comm_opt)->toArray());

        $vacancy->subscribers_list = $this->get('VacancySubsTable')->getVacancySubscribers($viewer_id, ['id' => $vacancy_id], ['include_notes' => 1]);


        $viewModel = new ViewModel(array(
        	'vacancy' => $vacancy,
            )); 
        return $viewModel;
    }


    public function addAction()
    {
        $success = 1;
        $message = '';
        $redirect = null;

        $admin_form = $this->params()->fromRoute('admin_form', 0);
        $dispatch = $this->params()->fromRoute('dispatch', 0);
        $full_form = ($admin_form)? 1 : (int) $this->params()->fromQuery('full_form');

        try {
            $form = $this->get('VacancyForm');
            $vacancy_fields = $this->get('VacancyFields');
            if(!$full_form) $vacancy_fields = $vacancy_fields->remainFields(['id', 'post_vk', 'rank', 'ship_type', 'salary', 'salary_unit', 'text', 'contract_length', 'contract_unit']);
            $form->setup($vacancy_fields, $this->identity()->id, ['admin_form' => $admin_form, 'post_vk' => $this->isPermitted('_info_\social\post_vk'), 'pics_field' => $full_form]);
            $form->setAttribute('action', $this->getRequest()->getRequestUri());
            if($this->request->isPost()){
               
                $data = array_merge_recursive(
                    $this->request->getPost()->toArray(),
                    $this->request->getFiles()->toArray()
                    );
                $form->setData($data);
                if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
                $data = $form->getData();
                $owner_id = ($admin_form)? $data['vacancies']['user'] : null;
                $new_vacancy_id = $this->saveVacancyData($data, null, null, $owner_id);
                $this->get('AdminNotifTable')->addNewVacancyNotification($new_vacancy_id, $this->identity()->id);
                $redirect = $this->url()->fromRoute('sc/vacancies/actions', ['action' => 'view', 'id' => $new_vacancy_id]);
            }
        } catch (\Exception $e) {
            $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
            $success = 0;
        }

        if($dispatch) {
            return (object) [
                'success' => $success,
                'message' => $message,
                'form' => $form,
                'data' => $data
            ];
        }


        $viewModel = 0;
        if(isset($form)) 
            $viewModel = new ViewModel(array(
                'form' => $form,
                'full_form' => $full_form
                ));

        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel, 
            'template' => 'application/vacancies/add',
            'exception' => (isset($e)) ? $e : null,
            'redirect' => (!$dispatch)? $redirect : null,
            'force_redirect' => (!$dispatch)? 1 : 0,
            ]);
    }

    public function editAction()
    {
        $success = 1;
        $message = '';

        $vacancy_id = $this->params()->fromRoute('id');

        $admin_form = $this->params()->fromRoute('admin_form', 0);
        $dispatch = $this->params()->fromRoute('dispatch', 0);
        try {
            $vacancy = $this->get('VacanciesTable')->getAllVacancies(null, ['id' => $vacancy_id])->current();
            if(!$vacancy) throw new \Application\Exception\Exception($this->translate('Vacancy with such id not found', 404));

            $form = $this->get('VacancyForm');
            $form->setup($this->get('VacancyFields'), $this->identity()->id, ['admin_form' => $admin_form, 'vacancy_data' => $vacancy, 'submit_text' => $this->translate('Save Vacancy'), 'post_vk' => $this->isPermitted('_info_\social\post_vk')]);
            
            $original_time = $vacancy['time'];
            $owner_id = $vacancy['user'];

            if($this->request->isPost()){
                $data = array_merge_recursive(
                    $this->request->getPost()->toArray(),
                    $this->request->getFiles()->toArray()
                    );
                $old_pics = (isset($data['old_pics']))? $data['old_pics'] :null;
                $form->setData($data);
                if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
                    $data = $form->getData();
                    if($admin_form) $owner_id = $data['vacancies']['user'];
                    $vacancy_id = $this->saveVacancyData($data, $old_pics, $original_time, $owner_id);
            }
        } catch (\Exception $e) {
            $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
            $success = 0;
        }

        if($dispatch) {
            return (object) [
                'success' => $success,
                'message' => $message,
                'form' => $form,
                'data' => $data
            ];
        }

        $view = new ViewModel(array(
            'form' => $form,
            ));
        return $view;

    }


    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        try {
            if(!$id) throw new \Application\Exception\Exception("Article id not provided", 1);
            if(!$this->isPermitted('_admin_\delete\vacancy')) throw new \Application\Exception\Exception($this->translate("You cannot delete vacancies"), 1);
            $this->get('VacanciesTable')->save(['id' => $id, 'active' => 0]);
            $success = 1;
            $message = $this->translate('Vacancy Deleted');
        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        return $this->viewResponse($success, $message, [
            'redirect' => $this->url()->fromRoute('sc/home'), 
            'force_redirect' => 1,
            'exception' => (isset($e)) ? $e : null
            ]);     
    }


    public function companyAction()
    {

        $success = 1;
        $message ='';
        try {
            $q_options = $this->setDefaultOptions(['_limit' => 100, 'layout' => 'list']);
            
            $vacanciesTable = $this->get('VacanciesTable');
            $user_id = $this->identity()->id;

            $q_options['count'] = 1;
            $q_options['only_current'] = 0;
            $q_options['show_hidden'] = 1;
            $total_results = $this->get('NewsTable')->getNews($user_id, ['section' => 'vacancies', 'owner_id' => $user_id], $q_options)->current()->count;
            $q_options['count'] = 0;
            $q_options['only_current'] = 0;
            $q_options['show_hidden'] = 1;
            $data_list = $this->get('NewsTable')->getNews($user_id, ['section' => 'vacancies', 'owner_id' => $user_id], $q_options);
            $later_vacancies = array();
            $curr_vacancies = array();
              foreach ($data_list as $v_item) {
              if($v_item['time'] > time()) $later_vacancies[] = $v_item;
              else $curr_vacancies[] = $v_item;
            }
       } catch (\Exception $e) {
            $message = ($e->getCode() == 100)? unserialize($e->getMessage()) : $e->getMessage();
            $success = 0;
        }


        $viewModel = 0;
        if(isset($data_list)) 
            $viewModel = new ViewModel(array(
                'later_vacancies' => $later_vacancies,
                'curr_vacancies' => $curr_vacancies,
                'next_pub_time' => $vacanciesTable->getNextPubTime($user_id),
                'data_list' => $data_list,
                'total_results' => $total_results,
                'q_options' => $q_options,
                )); 

        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel, 
            'template' => 'application/vacancies/company',
            'exception' => (isset($e)) ? $e : null
            ]);
    }



    public function toggleActiveAction() {
        try {
            $vacancy_id = (int)$this->params()->fromRoute('id', null);
            if(!$vacancy_id) throw new Exception("Vacancy id not set", 1);
            $vacancy_status = $this->params()->fromQuery('status', 0);

            $this->get('VacanciesTable')->setFieldOnID($vacancy_id, 'active', $vacancy_status);
            $success = 1;
            $message = $this->translate('Vacancy status changed');
            $this->get('AdminActivityTable')->addActivity($this->identity()->id, 
                                                            array(
                                                                'module' => 'Admin',
                                                                'controller' => 'vacancies',
                                                                'action' => $this->routeMatch()->getParam('action'),
                                                                'id' => $vacancy_id
                                                                ) ,
                                                            $message 
                                                        );
        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        return $this->viewResponse($success, $message, [
            'redirect' => $this->url()->fromRoute('sc/vacancies'), 
            'exception' => (isset($e)) ? $e : null
            ]);
    }


    public function toggleSubscribeAction() {
        $vacancy_id = (int)$this->params()->fromRoute('id');
        $redirect = $this->params()->fromQuery('redirect');
        $subs_count = null;
        $status = '';
        try {
            if($vacancy_id == 0) throw new \Application\Exception\Exception($this->translate('Vacancy not found'), 404);
            if(!$this->identity()) {
                $redirect = $this->url()->fromRoute('sc/fast-reg', array(), array('query' => array('subscribe_vacancy' => $vacancy_id)));
                throw new \Application\Exception\Exception($this->translate("Authorisation Required"), 401);
            }
            $subsTable = $this->get('VacancySubsTable');
            if($subsTable->isSubscribed($this->identity()->id, $vacancy_id)) {
                $this->get('VacancySubsTable')->unsubscribe($this->identity()->id, $vacancy_id);
                $message = $this->translate('You are un-subscribed from this vacancy, company will be informed by email');
                $status = 'unsubscribed';
            } else {
                $this->get('VacancySubsTable')->subscribe($this->identity()->id, $vacancy_id);
                $this->get('AdminNotifTable')->addVacancySubNotification($vacancy_id, $this->identity()->id);
                $vacancy = $this->get('VacanciesTable')->getAllVacancies(null, ['id' => $vacancy_id])->current();
                $this->get('Mail')->sendSubToVacancyUserMail($this->identity()->email, zgetUserName($this->identity()), $vacancy_id, $vacancy->title);
                if(isset($vacancy->email) && $vacancy->email) {
                    $experience_info = $this->get('ExperienceTable')->getUserExperience($this->identity()->id);
                    $this->get('Mail')->sendSubToVacancyCompanyMail($vacancy, $this->identity(),$experience_info);
                }
                $message = $this->translate('You are subscribed to this vacancy, your CV sent to Company');
                $status = 'subscribed';
            }
            
            $subs_count = $subsTable->getVacancySubscribers($this->identity()->id, ['id' => $vacancy_id], ['count' => 1])->current()->count;
            $success = 1;
            
        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();

        }

        return $this->viewResponse($success, $message, [
            'redirect' => ($redirect)? $redirect : $this->url()->fromRoute('sc/vacancies'),
            'force_redirect' => ($this->params()->fromQuery('redirect'))? 1 : 0,
            'exception' => (isset($e)) ? $e : null,
            'extra_data' => ['status' => $status, 'count' => $subs_count]
            ]);
    }


    public function toggleReportAction() {
        $vacancy_id = (int)$this->params()->fromRoute('id');
        $redirect = $this->params()->fromQuery('redirect');
        $count = null;
        $status = '';
        try {
            if($vacancy_id == 0) throw new \Application\Exception\Exception($this->translate('Vacancy not found'), 404);
            if(!$this->identity()) {
                $redirect = $this->url()->fromRoute('sc/fast-reg', array(), array('query' => array('subscribe_vacancy' => $vacancy_id)));
                throw new \Application\Exception\Exception($this->translate("Authorisation Required"), 401);
            }
            $repTable = $this->get('VacancyRepsTable');
            if($repTable->isReported($this->identity()->id, $vacancy_id)) {
                $this->get('VacancyRepsTable')->unreport($this->identity()->id, $vacancy_id);
                $message = $this->translate('You removed your report');
                $status = 'unsubscribed';
            } else {
                $this->get('VacancyRepsTable')->report($this->identity()->id, $vacancy_id);
                $message = $this->translate('You are reported for this vacancy, company will be informed by email');
                $status = 'subscribed';
            }
            $count = $repTable->getVacancyReports($vacancy_id, null, ['id'])->count();
            $success = 1;
            
        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();

        }
        return $this->viewResponse($success, $message, [
            'redirect' => ($redirect)? $redirect : $this->url()->fromRoute('sc/vacancies'),
            'force_redirect' => ($this->params()->fromQuery('redirect'))? 1 : 0,
            'exception' => (isset($e)) ? $e : null,
            'extra_data' => ['status' => $status, 'count' => $count]
            ]);
    }

    protected function saveVacancyData($data, $old_pics = null, $original_time = null, $owner_id = null)
    {

        if(!$owner_id) $owner_id = $this->identity()->id;
        $vacancy_data = $data['vacancies'];
        $vacanciesTable = $this->get('VacanciesTable');
        $vacancy_data['time'] = ($data['submit'] == 'publish_now') ? time() : $this->getPublicationTime($vacancy_data['time'], $original_time);
        if($data['submit'] == 'publish_now') $vacancy_data['active'] = 1;
        $vacancy_data['active'] = (isset($vacancy_data['active']))? $vacancy_data['active'] : 1;
        $vacancy_data['title'] = (isset($vacancy_data['title']))? $vacancy_data['title'] : $vacancy_data['rank'].'/ '.$vacancy_data['ship_type'].'/ '.$vacancy_data['salary'].$vacancy_data['salary_unit'];
        $vacancy_data['tags'] = $vacanciesTable->getTagString($vacancy_data);
        
        $vacancy_id = $vacanciesTable->saveVacancy($owner_id, $vacancy_data);

        $tags = explode(',', $vacancy_data['tags']);
        $tagsTable = $this->get('TagMapTable');
        $tagsTable->addTags(\Application\Model\NewsTable::SECTION_VACANCY, $vacancy_id, $tags);

        $data['old_pics'] = $old_pics;

        $vacancy_pics = null;
        if(isset($data['pics']) && !empty($data['pics'])) {
            $vacancy_pics = $this->get('PicsTable')->saveArticlePics($data, 'vacancy', $vacancy_id, $owner_id);
        }

        if(isset($vacancy_data['post_vk']) && $vacancy_data['post_vk']){
            try {
                $company_info = $this->get('UserTable')->getUserById($owner_id, 0, ['id', 'company_name', 'contact_email', 'contact_phone', 'contact_mobile', 'contact_mobile_2', 'contact_phone_2']);
                $vacancy_data['post_vk_id'] = $this->postVacancyToVk($vacancy_data, $company_info, $vacancy_pics);
                $vacancy_data['post_vk_time'] = time();
                $vacanciesTable->save(array('id' => $vacancy_data['id'], 'post_vk_id' => $vacancy_data['post_vk_id'], 'post_vk' => $vacancy_data['post_vk']));
            } catch (\Exception $e) {
                $vacancy_data['post_vk'] = 0;
                $this->fm()->addErrorMessage($e->getMessage());
            }
        }

        if($data['submit'] == 'publish_now') $this->fm()->addSuccessMessage($this->translate('Vacancy Published!'));
        else $this->fm()->addSuccessMessage($this->translate('Vacancy Saved!'));
        return $vacancy_id;
    }

    protected function postVacancyToVk($data, $company_info = null, $vacancy_pics = null)
    {
        $company_info_string = '';
        if($company_info) $company_info_string .= $this->translate('Contacts').':'.
                                    "\n".$company_info->contact_email.
                                    "\n".$company_info->contact_phone.
                                    "\n".$company_info->contact_mobile.
                                    "\n".$company_info->contact_mobile_2.
                                    "\n".$company_info->contact_phone_2;


        $page_url = $this->url()->fromRoute('sc/vacancies/actions', 
                array('controller' => 'vacancy', 'action' => 'view', 'id' => $data['id'])
            );
        $urgent = ($data['urgent'])? ' !!! URGENT !!! ' : '';
        $post_text = "\n".$data['title'].$urgent.
                     "\n".$data['text'].
                     "\n".$company_info_string;
        $post_pics = ($vacancy_pics) ? array_map(function($i){return _PICSROOT_.$i['img'];}, $vacancy_pics) : null;
        $tags = explode(',', $data['tags']);
        $tags = array_merge([zstripForHashTag($company_info->company_name)], $tags, ['seacontact.com', 'vacancies', 'job']);
        $post_time = ($data['time'] > time()) ? $data['time'] : null;

        $post_vk_id = null;
        try {
            $post_vk_id = $this->get('vk_wall_post')->postToPublic(
                _VK_GROUP_, 
                $post_text, 
                $post_pics,
                $tags,
                $page_url,
                $post_time
            );
            $this->fm()->addSuccessMessage($this->translate('Vacancy Posted To Vkontakte'));
        } catch (\Exception $e) {
            $this->fm()->addErrorMessage($e->getMessage());
        }
        return $post_vk_id;
    }




    
}
