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
use Application\Access\AccessList;

class SeamansdbController extends AbstractController
{
    public function indexAction()
    {
        $success = 1;
        $message ='';
        try {

            $viewer_id = ($this->identity()) ? $this->identity()->id : null;
            $without_exp = $this->params()->fromQuery('without_exp');
            $min_contracts = ($without_exp)? -1 : 0;
            $q_options = $this->setDefaultOptions([
                '_limit' => 10,
                'layout' => 'thumb',
                'ship_type_contracts' => true,
                'min_contracts' => $min_contracts,
                'company_id' => $viewer_id,
                '_user_fields' => ['id', 'name', 'surname', 'full_name', 'login', 'avatar', 'cv_avatar', 'nationality','desired_rank', 'cv_last_update', 'user_notes'],
                'show_notes' => 1,
                'only_auto_notes' => ($this->identity()) ? 0 : 1,
                'notes_company_private' => true,
                'notes_admin_all' => 0,
                'notes_admin_private' => 0,
                'notes_author_id' => $viewer_id
                ]);

            $q_options['ff_filters'] = [];
            $q_options['ff_options'] = ['min_home_city' => 50];
            if($this->params()->fromRoute('dispatch')) {
                $q_options = array_merge($q_options, (array) $this->params()->fromRoute('options'));
                $q_options['filters'] = array_merge($q_options['filters'], (array) $this->params()->fromRoute('filters'));
                $q_options['ff_filters'] = array_merge($q_options['ff_filters'], (array) $this->params()->fromRoute('ff_filters'));
                $q_options['ff_options'] = array_merge($q_options['ff_options'], (array) $this->params()->fromRoute('ff_options'));
            }

            $filter_fields = array('name','nationality','home_city','english_from','english_to','age_from','age_to','real_last_rank',
                'worked_in_psn','desired_rank','ship_name','last_ship_type','ship_type','visa_usa','visa_shenghen','dwt_from','dwt_to',
                'cv_last_update_from','cv_last_update_to','readiness_date_from', 'readiness_date_to','minimum_salary_from','minimum_salary_to','online'
                );

            if ($this->identity()) {
               $filter_fields[] = 'notes';
               $filter_fields[] = 'notes_text';
            }

            $filterFields = $this->get('UserFilterFields');
            $filterFields->remainFields($filter_fields);
            $filterFields->setup($q_options['ff_filters'], $q_options['ff_options']);

            $form = $this->get('EmptyForm');
            $form->add($filterFields);
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

            $form->addSubmit($this->translate('Filter'));
            $form->setAttribute('method', 'get');
            $show_form = false;
            $data = array_merge((array) $q_options, (array) $this->request->getQuery());
            $form->setData($data);
            if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 100);
            $data = $form->getData();
            $q_options['filters'] = array_filter($data['filters']);

        } catch (\Exception $e) {
            $message = ($e->getCode() == 100)? unserialize($e->getMessage()) : $e->getMessage();
            $show_form = true;
            $success = 0;
        }

        $users = $this->get('UserTable')->getUsersList($viewer_id, $q_options['filters'], $q_options);

        $q_options['count'] = 1;
        $total_results = $this->get('UserTable')->getUsersList($viewer_id, $q_options['filters'], $q_options)->current()->count;

        $viewModel = 0;
        if(isset($form))
            $viewModel = new ViewModel(array(
                'form' => $form,
                'show_form' => $show_form,
                'users' => $users,
                'total_results' => $total_results,
                'q_options' => $q_options,
                'without_exp' => $without_exp
                ));

        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel,
            'template' => 'application/seamansdb/index',
            'exception' => (isset($e)) ? $e : null
            ]);

    }


    public function companyDbAction(){
        if(!$this->identity()) throw new \Application\Exception\Exception("You should authorize to view this content", 401);
        $viewer_id = $this->identity()->id;

        return $this->forward()->dispatch('Application\Controller\Seamansdb', array(
            'controller' => 'Application\Controller\Seamansdb',
            'action' => 'index',
            'dispatch' => true,
            'ff_filters' => ['company_db' => $viewer_id],
            'ff_options' => ['use_cache' => 0, 'min_home_city' => 0],
            'options' => [
                'ship_type_contracts' => true,
                'min_contracts' => 0,
                'unlocked_users' => true,
                'company_id' => $viewer_id,
                'show_notes' => 1,
                'only_auto_notes' => ($viewer_id) ? 0 : 1,
                'notes_company_private' => true,
                'notes_admin_all' => 0,
                'notes_admin_private' => 0,
                'notes_author_id' => $viewer_id
                ]
            ));
    }



    public function unlockUserInfoAction() {
        try {
            $user_id = (int)$this->params()->fromRoute('id');
            if($user_id == 0) throw new \Application\Exception\Exception($this->translate('User Not Found'), 1);
            if(!$this->identity()) throw new \Application\Exception\Exception($this->translate('Only registered companies can see contact information'), 1);
            if($this->identity()->type != \Application\Model\UserTable::TYPE_COMPANY) throw new \Application\Exception\Exception($this->translate('Only registered companies can see contact information'), 1);

            $companyUsersTable = $this->get('CompanyUsersTable');
            $stats = $companyUsersTable->getCompanyStats($this->identity()->id);
            $role_limits = $this->get('access')->getAccessList()->getRolesLimits($this->identity()->role);
            if($stats->user_add_day >= $role_limits['USERS_ADD_DAY'])
                throw new \Application\Exception\Exception(sprintf($this->translate('You cannot open more than %s CVs per day'), $role_limits['USERS_ADD_DAY']), 1);
            if($stats->user_add_week >= $role_limits['USERS_ADD_WEEK'])
                throw new \Application\Exception\Exception(sprintf($this->translate('You cannot open more than %s CVs per week'), $role_limits['USERS_ADD_WEEK']), 1);
            if($stats->user_add_month >= $role_limits['USERS_ADD_MONTH'])
                throw new \Application\Exception\Exception(sprintf($this->translate('You cannot open more than %s CVs per month'), $role_limits['USERS_ADD_MONTH']), 1);

            $companyUsersTable->addUser($this->identity()->id, $user_id);
            $user_info = $this->get('UserTable')->getUserById($user_id, false, ['name', 'surname', 'full_name','login', 'email', 'avatar', 'cv_avatar']);
            $userNotTable = $this->get('UserNotificationsTable');
            $this->addUserNotification($userNotTable::TYPE_UNLOCK_CV, \Application\Model\NewsTable::SECTION_USER, $this->identity()->id, $this->identity()->id, $user_id);
            $this->get('AdminNotifTable')->addCompanyUnlockedUserNotification($this->identity()->id, $user_id);
            $success = 1;
            $message = $this->translate('User added to Company Database');

        } catch (\Exception $e) {
            $success = 0;
            if($this->request->isXmlHttpRequest()) {
                $error_view = new ViewModel(array(
                    'role' => ($this->identity()) ? $this->identity()->role : null,
                    'user_type' => ($this->identity()) ? $this->identity()->type : null,
                    'stats' => (isset($stats)) ? $stats : null,
                    'role_limits' => (isset($role_limits)) ? $role_limits : null,
                    'message' => $e->getMessage()
                    ));
                $error_view->setTemplate('application/seamansdb/unlock-user-info-error');
                $error_view->setOption('has_parent', true);
                $error_view = $this->get('ViewManager')->getView()->render($error_view);

                $data = $error_view;
                $message = $e->getMessage();
            } else $message = $e->getMessage();
        }

        return $this->viewResponse($success, $message, [
            'view_data' => (isset($data)) ? $data : null,
            'extra_data' => (isset($user_info))? ['name' => zgetUserName($user_info)] : null,
            'redirect' => $this->url()->fromRoute('sc/userinfo', array('controller' => 'cv', 'user' => 'id'.$user_id)),
            'exception' => (isset($e)) ? $e : null
            ]);

    }


    public function deleteCvNoteAction()
    {
        $cv_note_id = $this->params()->fromRoute('id');
        try {
            if(!$cv_note_id) throw new \Application\Exception\Exception("CV note id should be provided", 1);
            $cv_note_table = $this->get('UserCvNotesTable');
            $cv_note = $cv_note_table->get($cv_note_id);
            if(!$cv_note) throw new \Application\Exception\Exception("CV note not found", 1);
            $user_id = $cv_note->user_id;
            $this->get('UserCvNotesTable')->delete($cv_note_id);
            $success = 1;
            $message = $this->translate('Cv Note Deleted');
        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $controller =  $this->routeMatch()->getParam('controller');
        if(strpos(strtolower($controller), 'seamansdb') !== false) {
            $route = 'sc/seamansdb/actions';
            $controller = 'seamansdb';
        } else {
            $route = 'admin/actions';
            $controller = 'userdb';
        }
        $redirect = $this->url()->fromRoute($route, ['controller' => $controller,'action' => 'user-cv-notes', 'id' => $user_id]);

        return $this->viewResponse($success, $message, [
            'redirect' => $redirect,
            'exception' => (isset($e)) ? $e : null
            ]);
    }

    public function userCvNotesAction()
    {
        $user_id = (int)$this->params()->fromRoute('id');
        try {
            if($user_id == 0) throw new \Application\Exception\Exception("User id not provided", 1);
            $q_options = $this->setDefaultOptions([
                '_limit' => 5,
                ]);
            $query = $this->params()->fromRoute('query', $this->params()->fromQuery());


            $form_action = (isset($query['form_action'])) ? $query['form_action'] : $this->url()->fromRoute('sc/seamansdb/actions', ['action' => 'user-cv-notes', 'id' => $user_id]);
            $user_cv_notes = [];
            $total_results = 0;


            $show_admin_notes = $this->params()->fromRoute('show_admin_notes', false);
            $show_admin_private_notes = $this->params()->fromRoute('show_admin_private_notes', false);

            $cvNotesTable = $this->get('UserCvNotesTable');
            $form = $this->get('EmptyForm');
            $noteFields = $this->get('CvNotesFields');
            if($show_admin_notes) {
                $visibility_options = [
                    $cvNotesTable::VIS_COMPANY_ALL => $this->translate('Any Company or Admin'),
                    $cvNotesTable::VIS_ADMIN_ALL => $this->translate('Any Admin'),
                    $cvNotesTable::VIS_ADMIN_PRIVATE => $this->translate('Only Me'),
                ];
            } else {
                $visibility_options = [
                    $cvNotesTable::VIS_COMPANY_ALL => $this->translate('Any Company'),
                    $cvNotesTable::VIS_COMPANY_PRIVATE => $this->translate('Only My Company'),
                ];
            }

            $noteFields->get('visibility')->setValueOptions($visibility_options);
            $form->add($noteFields);
            $form->setAttribute('action', $form_action);
            $form->addSubmit($this->translate('Add Note'));
            $form->get('submit')->setAttribute('data-ajax', true);

            $fast_form = clone $this->get('EmptyForm');
            $fast_form->add($this->get('UserFields')->remainFields(['desired_rank','minimum_salary']));

            $success = 1;
            $message = null;

            $q_options['show_admin_notes'] = $show_admin_notes;
            $q_options['show_admin_private_notes'] = $show_admin_private_notes;
            $q_options['show_company_private_notes'] = 1;

            $data_list = $cvNotesTable->getUserCvNotes($user_id, $q_options['filters'], $q_options,$this->identity()->id);
            $q_options['count'] = 1;
            $total_results = $cvNotesTable->getUserCvNotes($user_id, $q_options['filters'], $q_options, $this->identity()->id)->current()->count;

            if($this->request->isPost()){
                $data = $this->request->getPost()->toArray();
                $form->setData($data);
                if (!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
                $data = $form->getData();
                $data = $data['user_cv_notes'];
                $result = $cvNotesTable->saveUserCvNote($user_id, $this->identity()->id, $data['user_notes'], $data['visibility']);
                $notificationsTable = $this->get('AdminNotifTable');
                if($this->identity()->type == \Application\Model\UserTable::TYPE_COMPANY) {
                    $not_type = $notificationsTable::NOT_TYPE_COMPANY_NOTE;
                    $not_section = $notificationsTable::NOT_SECTION_COMPANY;
                } else {
                    $not_type = $notificationsTable::NOT_TYPE_ADMIN_NOTE;
                    $not_section = $notificationsTable::NOT_SECTION_CREWING;
                }

                $notificationsTable->addNotification($not_type, $not_section, $user_id, $this->identity()->id, zshorterText($data['user_notes'], 10));
                $success = 1;
                $message = 'User Cv Note Added';
            }


        } catch (\Exception $e) {
            $success = 0;
            $message = ($e->getCode() == 777) ? unserialize($e->getMessage()) : $e->getMessage();
        }

        $viewModel = 0;
        if(isset($form))
            $viewModel = new ViewModel(array(
                'user_id' => $user_id,
                'form' => $form,
                'fast_form' => $fast_form,
                'data_list' => $data_list,
                'total_results' => $total_results,
                'q_options' => $q_options,
                'show_admin_notes' => $show_admin_notes
                ));

        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel,
            'template' => 'application/seamansdb/user-cv-notes',
            'terminal' => 1,
            'exception' => (isset($e)) ? $e : null
            ]);
    }


    public function notesCallFailAction()
    {
        $user_id = $this->params()->fromRoute('id');
        $success = 1;
        $message = '';
        try {
            if(!$user_id) throw new \Application\Exception\Exception("user id should be provided", 1);
            $cvNotesTable = $this->get('UserCvNotesTable');
            $note_text = "Call not succeed (User didn't pick up the phone, or was out of range)";
            $result = $cvNotesTable->saveUserCvNote($user_id, $this->identity()->id, $note_text , $cvNotesTable::VIS_COMPANY_ALL);
            $notificationsTable = $this->get('AdminNotifTable');
            if($this->identity()->type == \Application\Model\UserTable::TYPE_COMPANY) {
                $not_type = $notificationsTable::NOT_TYPE_COMPANY_NOTE;
                $not_section = $notificationsTable::NOT_SECTION_COMPANY;
            } else {
                $not_type = $notificationsTable::NOT_TYPE_ADMIN_NOTE;
                $not_section = $notificationsTable::NOT_SECTION_CREWING;
            }

            $notificationsTable->addNotification($not_type, $not_section, $user_id, $this->identity()->id, zshorterText($note_text, 10));
            $success = 1;
            $message = $this->translate('Cv Note Added');
        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $controller =  $this->routeMatch()->getParam('controller');
        if(strpos(strtolower($controller), 'seamansdb') !== false) {
            $route = 'sc/seamansdb/actions';
            $controller = 'seamansdb';
        } else {
            $route = 'admin/actions';
            $controller = 'userdb';
        }
        $redirect = $this->url()->fromRoute($route, ['controller' => $controller,'action' => 'user-cv-notes', 'id' => $user_id]);

        return $this->viewResponse($success, $message, [
            'redirect' => $redirect,
            'exception' => (isset($e)) ? $e : null
            ]);
    }

    public function notesCvUpdatedAction()
    {
        $user_id = $this->params()->fromRoute('id');
        $success = 1;
        $message = '';
        try {
            if(!$user_id) throw new \Application\Exception\Exception("user id should be provided", 1);
            $this->get('UserTable')->save(['id' => $user_id, 'cv_last_update' => time()]);
            $cvNotesTable = $this->get('UserCvNotesTable');
            $note_text = "CV updated: ".zformatDateYear(time()).' (id'.$this->identity()->id.')';
            $result = $cvNotesTable->saveUserCvNote($user_id, $this->identity()->id, $note_text , $cvNotesTable::VIS_COMPANY_ALL);
            $notificationsTable = $this->get('AdminNotifTable');
            if($this->identity()->type == \Application\Model\UserTable::TYPE_COMPANY) {
                $not_type = $notificationsTable::NOT_TYPE_COMPANY_NOTE;
                $not_section = $notificationsTable::NOT_SECTION_COMPANY;
            } else {
                $not_type = $notificationsTable::NOT_TYPE_ADMIN_NOTE;
                $not_section = $notificationsTable::NOT_SECTION_CREWING;
            }

            $notificationsTable->addNotification($not_type, $not_section, $user_id, $this->identity()->id, zshorterText($note_text, 10));
            $success = 1;
            $message = $this->translate('Cv Note Added');
        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $controller =  $this->routeMatch()->getParam('controller');
        if(strpos(strtolower($controller), 'seamansdb') !== false) {
            $route = 'sc/seamansdb/actions';
            $controller = 'seamansdb';
        } else {
            $route = 'admin/actions';
            $controller = 'userdb';
        }
        $redirect = $this->url()->fromRoute($route, ['controller' => $controller,'action' => 'user-cv-notes', 'id' => $user_id]);

        return $this->viewResponse($success, $message, [
            'redirect' => $redirect,
            'exception' => (isset($e)) ? $e : null
            ]);
    }
    public function notesAskSalaryAction()
    {
        $user_id = $this->params()->fromRoute('id');
        $success = 1;
        $message = '';
        try {
            if(!$user_id) throw new \Application\Exception\Exception("user id should be provided", 1);
            $form = $this->get('EmptyForm');
            $form->add($this->get('UserFields')->remainFields(['minimum_salary']));
            $form->setData(['user' => $this->params()->fromQuery()]);
            if (!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
            $data = $form->getData();
            $data['user']['id'] = $user_id;
            $this->get('UserTable')->save($data['user']);
            $cvNotesTable = $this->get('UserCvNotesTable');
            $note_text = 'Asked for minimum salary : '.$data['user']['minimum_salary'].' $';
            $result = $cvNotesTable->saveUserCvNote($user_id, $this->identity()->id, $note_text , $cvNotesTable::VIS_COMPANY_ALL);
            $notificationsTable = $this->get('AdminNotifTable');
            if($this->identity()->type == \Application\Model\UserTable::TYPE_COMPANY) {
                $not_type = $notificationsTable::NOT_TYPE_COMPANY_NOTE;
                $not_section = $notificationsTable::NOT_SECTION_COMPANY;
            } else {
                $not_type = $notificationsTable::NOT_TYPE_ADMIN_NOTE;
                $not_section = $notificationsTable::NOT_SECTION_CREWING;
            }

            $notificationsTable->addNotification($not_type, $not_section, $user_id, $this->identity()->id, zshorterText($note_text, 10));
            $success = 1;
            $message = $this->translate('Cv Note Added');
        } catch (\Exception $e) {
            $success = 0;
            $message = ($e->getCode() == 777) ? unserialize($e->getMessage()) : $e->getMessage();
        }

        $controller =  $this->routeMatch()->getParam('controller');
        if(strpos(strtolower($controller), 'seamansdb') !== false) {
            $route = 'sc/seamansdb/actions';
            $controller = 'seamansdb';
        } else {
            $route = 'admin/actions';
            $controller = 'userdb';
        }
        $redirect = $this->url()->fromRoute($route, ['controller' => $controller,'action' => 'user-cv-notes', 'id' => $user_id]);

        return $this->viewResponse($success, $message, [
            'redirect' => $redirect,
            'exception' => (isset($e)) ? $e : null
            ]);
    }

    public function notesAskPositionAction()
    {
        $user_id = $this->params()->fromRoute('id');
        $success = 1;
        $message = '';
        try {
            if(!$user_id) throw new \Application\Exception\Exception("user id should be provided", 1);
            $form = $this->get('EmptyForm');
            $form->add($this->get('UserFields')->remainFields(['desired_rank']));
            $form->setData(['user' => $this->params()->fromQuery()]);
            if (!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
            $data = $form->getData();
            $data['user']['id'] = $user_id;
            $this->get('UserTable')->save($data['user']);
            $cvNotesTable = $this->get('UserCvNotesTable');
            $note_text = 'Asked for rank : '.$data['user']['desired_rank'];
            $result = $cvNotesTable->saveUserCvNote($user_id, $this->identity()->id, $note_text , $cvNotesTable::VIS_COMPANY_ALL);
            $notificationsTable = $this->get('AdminNotifTable');
            if($this->identity()->type == \Application\Model\UserTable::TYPE_COMPANY) {
                $not_type = $notificationsTable::NOT_TYPE_COMPANY_NOTE;
                $not_section = $notificationsTable::NOT_SECTION_COMPANY;
            } else {
                $not_type = $notificationsTable::NOT_TYPE_ADMIN_NOTE;
                $not_section = $notificationsTable::NOT_SECTION_CREWING;
            }

            $notificationsTable->addNotification($not_type, $not_section, $user_id, $this->identity()->id, zshorterText($note_text, 10));
            $success = 1;
            $message = $this->translate('Cv Note Added');
        } catch (\Exception $e) {
            $success = 0;
            $message = ($e->getCode() == 777) ? unserialize($e->getMessage()) : $e->getMessage();
        }

        $controller =  $this->routeMatch()->getParam('controller');
        if(strpos(strtolower($controller), 'seamansdb') !== false) {
            $route = 'sc/seamansdb/actions';
            $controller = 'seamansdb';
        } else {
            $route = 'admin/actions';
            $controller = 'userdb';
        }
        $redirect = $this->url()->fromRoute($route, ['controller' => $controller,'action' => 'user-cv-notes', 'id' => $user_id]);

        return $this->viewResponse($success, $message, [
            'redirect' => $redirect,
            'exception' => (isset($e)) ? $e : null
            ]);
    }

    public  function notesReadinessAction()
    {
        $user_id = $this->params()->fromRoute('id');
        $months = (int) $this->params()->fromQuery('months');
        $success = 1;
        $message = '';
        try {
            if(!$user_id) throw new \Application\Exception\Exception("user id should be provided", 1);
            if($months < 1 || $months > 8) throw new \Application\Exception\Exception("valid months value should be provided", 1);
            $readiness_date = time() + (2592000 * $months);
            $this->get('UserTable')->save(['id' => $user_id, 'readiness_date' => $readiness_date]);
            $cvNotesTable = $this->get('UserCvNotesTable');
            $note_text = 'Reported readiness : '.zformatDateYear($readiness_date);
            $result = $cvNotesTable->saveUserCvNote($user_id, $this->identity()->id, $note_text , $cvNotesTable::VIS_COMPANY_ALL);
            $notificationsTable = $this->get('AdminNotifTable');
            if($this->identity()->type == \Application\Model\UserTable::TYPE_COMPANY) {
                $not_type = $notificationsTable::NOT_TYPE_COMPANY_NOTE;
                $not_section = $notificationsTable::NOT_SECTION_COMPANY;
            } else {
                $not_type = $notificationsTable::NOT_TYPE_ADMIN_NOTE;
                $not_section = $notificationsTable::NOT_SECTION_CREWING;
            }

            $notificationsTable->addNotification($not_type, $not_section, $user_id, $this->identity()->id, zshorterText($note_text, 10));
            $success = 1;
            $message = $this->translate('Cv Note Added');
        } catch (\Exception $e) {
            $success = 0;
            $message = ($e->getCode() == 777) ? unserialize($e->getMessage()) : $e->getMessage();
        }

        $controller =  $this->routeMatch()->getParam('controller');
        if(strpos(strtolower($controller), 'seamansdb') !== false) {
            $route = 'sc/seamansdb/actions';
            $controller = 'seamansdb';
        } else {
            $route = 'admin/actions';
            $controller = 'userdb';
        }
        $redirect = $this->url()->fromRoute($route, ['controller' => $controller,'action' => 'user-cv-notes', 'id' => $user_id]);

        return $this->viewResponse($success, $message, [
            'redirect' => $redirect,
            'exception' => (isset($e)) ? $e : null
            ]);
    }
}
