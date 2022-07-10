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

class LogbookController extends AbstractController
{

    public function indexAction()
    {
        $success = 1;
        $message = '';
        try {
            $q_options = $this->setDefaultOptions(['_limit' => 5]);
           
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

            $viewer_id = ($this->identity())? $this->identity()->id : 0;
            $q_options['only_current'] = 1;
            $data_list = $this->get('LogBookTable')->getAllLogbooks($viewer_id, $q_options['filters'], $q_options);
            $q_options['count'] = 1;
            $total_results = $this->get('LogBookTable')->getAllLogbooks($viewer_id, $q_options['filters'], $q_options)->current()->count;
            if(!$q_options['_offset']) $q_options['_offset'] = ($q_options['_limit'] * $q_options['_page']) - $q_options['_limit'];

        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $viewModel = 0;
        if(isset($form)) 
            $viewModel = new ViewModel(array(
                'form' => $form,
                'tags_map' => $this->get('TagMapTable')->getTagsMap(null, ['section' => \Application\Model\NewsTable::SECTION_LOGBOOK]),
                'data_list' => $data_list,
                'q_options' => $q_options,
                'total_results' => $total_results,
                )); 

        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel, 
            'template' => 'application/logbook/index',
            'exception' => (isset($e)) ? $e : null
            ]);
    }
    

    public function viewAction()
    {
        $logbook_id = $this->params()->fromRoute('id');
        $viewer_id = ($this->identity()) ? $this->identity()->id : 0;
        try {
            if(!$logbook_id) throw new \Application\Exception\Exception('Logbook id not provided', 404);
            $log_entry = $this->get('LogBookTable')->getAllLogbooks($viewer_id, ['logbook_id' => $logbook_id])->current();
            if(!$log_entry) throw new \Application\Exception\Exception('Logbook with such id not found', 404);

            $sim_logs = [];
            if($log_entry->tags) {
                $sim_logs = $this->get('LogBookTable')->getAllLogbooks($viewer_id, ['tags' => explode(',', $log_entry->tags), 'exclude' => $logbook_id], ['_limit' => 5]);
            }

            $comm_opt['viewer_id'] = $viewer_id;
            $comm_opt['_offset'] = ($log_entry->total_comments > _VIEW_COMMENT_LIMIT_)? $log_entry->total_comments - _VIEW_COMMENT_LIMIT_ : 0;
            $log_entry->comments_list = json_encode($this->get('CommentsTable')->getComments(\Application\Model\NewsTable::SECTION_LOGBOOK, $logbook_id, [], $comm_opt)->toArray());

            $this->get('ActivityViewsTable')->addView(\Application\Model\NewsTable::SECTION_LOGBOOK, $logbook_id, $viewer_id);
            $success = 1;
            $message = '';
        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $viewModel = 0;
        if(isset($log_entry)) 
            $viewModel = new ViewModel(array(
                'log_entry' => $log_entry,
                'sim_logs' => $sim_logs,
                'request' => 'all',
                ));

        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel, 
            'template' => 'application/logbook/view',
            'exception' => (isset($e)) ? $e : null
            ]);
    }

    public function addAction()
    {
        $success = 1;
        $message = '';
        $redirect = 0;

        try {
            $form = $this->get('LogBookRecordForm');
            $form->setAttribute('action', $this->url()->fromRoute('sc/logbook/actions', ['action'=>'add']));
            if($this->isPermitted('_info_\social\post_vk')) $form->addPostToVk();

            if($this->request->isPost()){
                $data = array_merge_recursive(
                    $this->request->getPost()->toArray(),
                    $this->request->getFiles()->toArray()
                    );
                $form->setData($data);
                if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
                $info = 0;
                $data = $form->getData();
                $user = $this->identity();
                $data['user'] = $user->id;
                $data['time'] = $this->getPublicationTime($data['time']);

                if(isset($data['pics']) && $data['pics'][0]['error'] == 0) $info++;
                if($data['text'] != '') $info++;
                if($info == 0) throw new \Application\Exception\Exception($this->translate('You cannot add empty entry to logbook'), 1);

                $data['id'] = $this->get('LogBookTable')->save(array_filter($data));
                if(!$data['id']) throw new \Application\Exception\Exception($this->translate("LogBook Record not saved, some error"), 1);
                if($data['tags']) {
                    $tags = explode(',', $data['tags']);
                    $tagsTable = $this->get('TagMapTable');
                    $tagsTable->addTags(\Application\Model\NewsTable::SECTION_LOGBOOK, $data['id'], $tags);
                }

                if($data['link']) {
                    $this->get('LinksTable')->addLink($this->identity()->id, 'logbook', $data['id'], $data['link'], $data['link_title'], $data['link_description'], $data['link_img']);
                }

                $uploaded_pics = null;
                if(isset($data['pics']) && !empty($data['pics'])) {
                    $uploaded_pics = $this->get('PicsTable')->saveArticlePics($data, 'logbook', $data['id'], $this->identity()->id);
                }

                if(isset($data['post_vk']) && $data['post_vk']){
                    try {
                        $data['post_vk_id'] = $this->postLogEntryToVk($data, $this->identity(), $uploaded_pics);
                        $data['post_vk_time'] = time();
                    } catch (\Exception $e) {
                        $data['post_vk'] = 0;
                        $this->fm()->addErrorMessage($e->getMessage());
                    }
                }
                $this->get('LogBookTable')->save($data);
                $success = 1;
                $message = $this->translate('Log Book Entry Added!');
                $redirect = $this->url()->fromRoute('sc/logbook/actions', ['action' => 'view', 'id' => $data['id']]);
            }

        } catch (\Exception $e) {
            $success = 0;
            $message = ($e->getCode() == 100)? unserialize($e->getMessage()) : $e->getMessage(); 
        }

        $viewModel = 0;
        if(isset($form)) 
            $viewModel = new ViewModel(array(
                'form' => $form,
                'add_mode' => 1,
                'tags_map' => $this->get('TagMapTable')->getTagsMap(null, [], ['_limit' => 0, '_order' => 'name', 'up' => 1, 'more_than' => 1]),
                ));


        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel, 
            'template' => 'application/logbook/add',
            'exception' => (isset($e)) ? $e : null,
            'redirect' => (isset($redirect))? $redirect : $this->url()->fromRoute('sc/home'),
            'force_redirect' => ($redirect)? 1 : 0
            ]);
    }


    public function editAction()
    {
        $logbook_id = $this->params()->fromRoute('id');
        try {
            if(!$logbook_id) throw new \Application\Exception\Exception($this->translate('Logbook entry not found'), 1);
            
            $logbookTable = $this->get('LogBookTable');
            $entry = $logbookTable->getAllLogbooks(null, ['logbook_id' => $logbook_id])->current();
            $user_id = $entry->user;

            $form = $this->get('LogBookRecordForm');
            
            $form->remove('time');
            $entry['pics'] = json_decode($entry['pics']);
            $entry['links'] = json_decode($entry['links']);
            $entry['videos'] = json_decode($entry['videos']);
            $entry['text'] = htmlspecialchars_decode($entry['text']);
            $form->addOldPictureFields($entry['pics']);
            $form->addOldLinkFields($entry['links']);
            $form->addOldVideoFields($entry['videos']);
            $form->get('pics')->setLabel($this->translate('Add more images').':');
            $form->get('submit')->setLabel($this->translate('Save Record'))->setAttribute('data-ajax', true);
            $form->setData($entry);
            $form->setAttribute('action', $this->url()->fromRoute('sc/logbook/actions', ['action' => 'edit', 'id' => $logbook_id]));

            if($this->isPermitted('_info_\social\post_vk')) {
                $form->addPostToVk();
                if($entry->post_vk_time) {
                    $form->get('post_vk')
                        ->setValueOptions(array(0 => $this->translate('Last Post ').zformatDateTime($entry->post_vk_time), 1 => $this->translate('Post Again')));
                    $form->get('post_vk')->setValue(0);
                }
            }
            $success = 1;
            $message = '';
            $refresh = 0;
            if($this->request->isPost()){
                $data = array_merge_recursive(
                    $this->request->getPost()->toArray(),
                    $this->request->getFiles()->toArray()
                    );

                $form->setData($data);
                if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
                $data = $form->getData();
                $data['user'] = $user_id;
                $data['time'] = ($data['submit'] == 'publish_now')? time() : $entry->time;
                if(isset($data['pics']) && !empty($data['pics'])) {
                    $this->get('PicsTable')->saveArticlePics($data, 'logbook', $logbook_id, $user_id);
                }
                $this->get('LinksTable')->saveArticleLinks($data, 'logbook', $logbook_id, $user_id);
                $this->get('VideosTable')->saveArticleVideos($data, 'logbook', $logbook_id, $user_id);

                if(isset($data['post_vk']) && $data['post_vk'] == 1){
                    $log_pics = $this->get('PicsTable')->getArticlePics('logbook', $logbook_id);
                    $author_info = $this->get('UserTable')->getUserById($user_id, 0, ['id','login', 'name', 'surname', 'full_name', 'company_name', 'type']);
                    try {
                        $data['post_vk_id'] = $this->postLogEntryToVk($data, $author_info, $log_pics);
                        $data['post_vk_time'] = time();
                    } catch (\Exception $e) {
                        $data['post_vk'] = 0;
                        $this->fm()->addErrorMessage($e->getMessage());
                    }
                }
                
                $this->get('LogBookTable')->save($data);
                $tagsTable = $this->get('TagMapTable');
                if($data['tags']) {
                    $tags = explode(',', $data['tags']);
                    $tagsTable->addTags(\Application\Model\NewsTable::SECTION_LOGBOOK, $logbook_id, $tags, 1);
                } else $this->get('TagMapTable')->deleteArticleTags(\Application\Model\NewsTable::SECTION_LOGBOOK, $logbook_id);
                $success = 1;
                $message = $this->translate('Log Book Entry Saved');
                $redirect = $this->url()->fromRoute('sc/logbook/actions', ['action' => 'view', 'id' => $logbook_id]);
            }
        } catch (\Exception $e) {
            $success = 0;
            $message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
        }
    
        $viewModel = 0;
        if(isset($form)) 
            $viewModel = new ViewModel(array(
                'form' => $form,
                'publish_time' => $entry->time,
                'edit_mode' => 1,
                'tags_map' => $this->get('TagMapTable')->getTagsMap(null, [], ['_limit' => 0, '_order' => 'name', 'up' => 1, 'more_than' => 1]),
                ));

        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel, 
            'template' => 'application/logbook/edit',
            'redirect' => (isset($redirect))? $redirect : null,
            'force_redirect' => (isset($redirect))? 1 : 0,
            'exception' => (isset($e)) ? $e : null
            ]);
    }


    public function deleteAction()
    {
        $log_id = $this->params()->fromRoute('id');
        try {
            if(!$log_id) throw new \Application\Exception\Exception("Log book record id not provided", 1);
            $this->get('LogBookTable')->save(['id' => $log_id, 'active' => 0]);
            $success = 1;
            $message = $this->translate('Logbook Entry Deleted');
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

    protected function postLogEntryToVk($data, $author_info, $pics = null)
    {
        $tags = explode(',', $data['tags']);
        $tags = array_merge([zstripForHashTag($author_info->login), zstripForHashTag(zgetUserName($author_info))],$tags, ['seacontact', 'seacontact.com']);
        $page_url = $this->url()->fromRoute('sc/logbook/actions', 
                array('action' => 'view', 'id' => $data['id'])
            );
        $post_text = "\n".strip_tags(html_entity_decode($data['text']));
        $post_pics = ($pics) ? array_map(function($i){return _PICSROOT_.$i['img'];}, $pics) : null;
        $post_time = ($data['time'] > time()) ? $data['time'] : null;
        try {
            $post_vk_id = $this->get('vk_wall_post')->postToPublic(
                _VK_GROUP_, 
                $post_text, 
                $post_pics,
                $tags,
                $page_url,
                $post_time
            );
            $this->fm()->addSuccessMessage($this->translate('LogBook Entry Posted To Vkontakte'));
        } catch (\Exception $e) {
            $this->fm()->addErrorMessage($e->getMessage());
        }

        
        return $post_vk_id;
    }
    
}
