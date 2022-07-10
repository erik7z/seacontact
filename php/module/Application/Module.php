<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource;

use Zend\EventManager\EventInterface;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container;

use Zend\Db\TableGateway\TableGateway;
use Zend\Session\SaveHandler\DbTableGateway;
use Zend\Session\SaveHandler\DbTableGatewayOptions;

use Application\Service\ErrorHandling as ErrorHandlingService;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream as LogWriterStream;

class Module
    implements ConsoleUsageProviderInterface
{

    public function onBootstrap(MvcEvent $e)
    {
       $eventManager        = $e->getApplication()->getEventManager();
       $moduleRouteListener = new ModuleRouteListener();
       $moduleRouteListener->attach($eventManager);

       if($e->getRequest() instanceof \Zend\Console\Request) {}
        else {
            $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'setLocale'));
            //  Logging zend exceptions
            // $eventManager->attach('dispatch.error', function($event){
            //             $exception = $event->getResult()->exception;
            //             if ($exception) {
            //                 $sm = $event->getApplication()->getServiceManager();
            //                 $service = $sm->get('Application\Service\ErrorHandling');
            //                 $service->logException($exception);
            //             }
            //         });
            $sharedManager = $eventManager->getSharedManager();
            $sharedManager->attach('Zend\Mvc\Controller\AbstractController','dispatch', array($this, 'initRoute' ), 101);
            // $sharedManager->attach('Zend\Mvc\Controller\AbstractActionController','dispatch', array($this, 'handleControllerCannotDispatchRequest' ), 101);
            // $eventManager->attach('dispatch.error', array($this, 'handleControllerNotFoundAndControllerInvalidAndRouteNotFound'), 100);

        }
    }

    public function initRoute($e)
    {
        $sm  = $e->getApplication()->getServiceManager();
        $redirect = $sm->get('ControllerPluginManager')->get('redirect');
        $url = $sm->get('ControllerPluginManager')->get('url');
        $translator = $sm->get('translator');


        $identity = $sm->get('AuthService')->getIdentity();
        $routeMatch = $e->getTarget()->getEvent()->getRouteMatch();

        try {
            $access = $this->accessRoute($e);
            $user = $routeMatch->getParam('user');
            // if($identity &&  ($identity->login == $user || $identity->id == str_replace('id', '', $user))) {
            //     $controller = strtolower($routeMatch->getParam('__CONTROLLER__'));
            //    if($controller == 'index') {
            //         $route = ($identity->type == \Application\Model\UserTable::TYPE_COMPANY) ? 'sc/my_company' : 'sc/my';
            //         return $redirect->toRoute($route);
            //    }
            // }
        } catch (\Exception $exc) {
            if ($e->getRequest() instanceof \Zend\Console\Request){
                echo 'Your request did not pass authorisation, check access settings';
              return false;
            }
            if (strtolower($routeMatch->getParam('__NAMESPACE__')) == 'admin\controller') {
                if (!$identity) {
                    $query_params = ($_SERVER['REQUEST_URI'] != '/') ?  ['query' => ['redirect' => _ADMINADRESS_.$_SERVER['REQUEST_URI']]] : null;
                    return $this->accessFail($e, $url->fromRoute('admin/actions', ['controller' => 'auth'],$query_params), false, $exc);
                }
                else return $this->accessFail($e, null, false, $exc, null, 'layout/admin/error');
            }
            else if (!$identity) {
                $query_params = ($_SERVER['REQUEST_URI'] != '/')? '?'.http_build_query(['query' => ['redirect' => _ADDRESS_NO_SLASH_.$_SERVER['REQUEST_URI']]]) : null;
                return $this->accessFail($e, _ADDRESS_NO_SLASH_.'/auth'.$query_params, false, $exc);
            }
            else return $this->accessFail($e, null, false, $exc);
        }
    }


    public function accessFail($e, $redirect_to_url = null, $success, $exception, $data = null, $layout = 'layout/layout', $error_code = null)
    {
        $message = $exception->getMessage();
        $code = $exception->getCode();
        $sm  = $e->getApplication()->getServiceManager();
        $fm = $sm->get('ControllerPluginManager')->get('fm');
        $params = $sm->get('ControllerPluginManager')->get('params');
        $redirect = $sm->get('ControllerPluginManager')->get('redirect');

        if($e->getRequest()->isXmlHttpRequest()) {
            $response['success'] = $success;
            $response['message'] = $message;
            $response['code'] = $code;
            $response['data'] = ($data) ? $data : $message;
            $redirect->toRoute('sc/json', ['controller' => 'index', 'action' => 'json'], ['query' => $response]);
        } else {
            if(!$success) $fm->addErrorMessage($message);
            else $fm->addSuccessMessage($message);
            if($redirect_to_url) return $redirect->toUrl($redirect_to_url);
            else return $this->handleError($e, $message, false, null, $layout);

        }
    }


    public function handleError($e, $message = null, $notfound = false, $exception = null, $layout = 'layout/layout')
    {
        $model = new \Zend\View\Model\ViewModel(array(
            'message' => $message,
            'reason' => $e->getError(),
            'exception' => $exception,
        ));
        $model->setTemplate('error/index');
        $e->getViewModel()->addChild($model)->setTemplate($layout);
        // // custom status code
        // $response = $e->getaccessFail();
        // $response->setStatusCode(404);
        $e->stopPropagation();
        return $model;

        /*redirect*/
           // $response = $e->getaccessFail();
           // $response->setStatusCode(404);
           // $response->getHeaders()->addHeaderLine('Location', '/notfound');
           // $response->send();
           // exit(0);
        // custom layout
        // $sm  = $e->getApplication()->getServiceManager();
        // $viewModel = $sm->get('viewManager')->getViewModel();
        // $viewModel->setTemplate('layout/admin/error');
    }

    public function accessRoute($e)
    {
        $access = $e->getApplication()->getServiceManager()->get('Access');
        $access->initOnLoad($e->getTarget()->getEvent()->getRouteMatch());
        return $access->accessRoute();
    }


    public function setLocale($e)
    {
        $host = $e->getRequest()->getUri()->getHost();
        $translator = $e->getApplication()->getServiceManager()->get('translator');

        if(strpos($host, 'en.') !== false) {
            $translator->setLocale('en_US');
        } else if(strpos($host, 'ru.') !== false) {
            $translator->setLocale('ru_RU');
        } else if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            if(strpos($lang, 'ru') !== false) $translator->setLocale('ru_RU');
            else $translator->setLocale('en_US');
        }

    }

    // public function error($e)
    // {
    //     // dispatch errors could be triggered only before dispatch
    //     $e->setError('some new error');
    //     $e->getTarget()->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $e);
    // }

    public function getConsoleUsage(Console $console)
    {
        return array(
            'application cronstart ' => 'start cron jobs',
            array( '<job_name>', '(optional) run specific job' ),
            );
    }

    public function getControllerPluginConfig()
    {
        return array(
             'factories' => array(
                    'get' => function($plugin_manager) {
                        return new \Application\Controller\Plugin\Get($plugin_manager->getServiceLocator());
                    },
                    'routeMatch' => function($plugin_manager) {
                        return new \Application\Controller\Plugin\routeMatch($plugin_manager->getServiceLocator());
                    },
                    'isPermitted' => function($plugin_manager) {
                        return new \Application\Controller\Plugin\isPermitted($plugin_manager->getServiceLocator());
                    },
                    'qOptions' => function($plugin_manager) {
                        return new \Application\Controller\Plugin\qOptions($plugin_manager->getServiceLocator());
                    },
                    'apiQoptions' => function($plugin_manager) {
                        return new \Application\Controller\Plugin\ApiQoptions($plugin_manager->getServiceLocator());
                    },
                    'apiValidateResponse' => function($plugin_manager) {
                        return new \Application\Controller\Plugin\ApiValidateResponse($plugin_manager->getServiceLocator());
                    },
                    'nav' => function($plugin_manager) {
                        return new \Application\Controller\Plugin\Nav($plugin_manager->getServiceLocator());
                    },
                    'fm' => function($plugin_manager) {
                        return $plugin_manager->get('FlashMessenger');
                    },
                    'translate' => function($plugin_manager) {
                        return new \Application\Controller\Plugin\Translate($plugin_manager->getServiceLocator());
                    },
                    'url' => function ($hpm) {
                        $url = new \Application\Controller\Plugin\ScUrl;
                        return $url;
                    },
                ),
             'invokables' => array(
                'LogExc' => '\Application\Controller\Plugin\LogExc',
                ),
            );
    }

    public function getValidatorConfig()
    {
        return array(
            'factories' => array(
               ),
            'invokables' => array(
                // 'UserLoginValidator' => '\Application\Validator\UserLoginValidator',
                )
            );
    }


    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'basePath' => function($hpm) {
                    $basePath = $hpm->get('Zend\View\Helper\BasePath');
                    $basePath->setBasePath('/');
                    return $basePath;
                },
                'navigation' => function ($hpm) {
                    $acl = $hpm->getServiceLocator()->get('Access')->getAccessList();
                    $navigation = $hpm->get('Zend\View\Helper\Navigation');
                    $navigation->setAcl($acl);
                    return $navigation;
                },
                'currentPageLabel' => function($hpm) {
                    return new \Application\View\Helper\CurrentPageLabel($hpm);
                    },
                'msgs' => function($hpm) {
                    return new \Application\View\Helper\CustomFlashMessenger($hpm);
                    },
                'routeMatch' => function ($hpm) {
                    return new \Application\View\Helper\RouteMatch($hpm);
                },
                'isPermitted' => function ($hpm) {
                    return new \Application\View\Helper\isPermitted($hpm);
                },

                'notifications' => function ($hpm) {
                    return new \Application\View\Helper\Notifications($hpm);
                },

                'siteInfo' => function ($hpm) {
                    return new \Application\View\Helper\SiteInfo($hpm);
                },
                'userInfo' => function ($hpm) {
                    return new \UserInfo\View\Helper\UserInfo($hpm);
                },
                'formelementerrors' => function($vhm) {
                            $form_errors = new \Zend\Form\View\Helper\FormElementErrors();
                            $form_errors->setAttributes([
                                'class' => 'text-danger'
                            ]);
                            return $form_errors;
                },
                'url' => function ($hpm) {
                    $url = new \Application\View\Helper\ScUrl;
                    $sl = $hpm->getServiceLocator();

                    // $url->query = $sl->get('request')->getQuery(null, null)->toArray();
                    $url->setRouter($sl->get('router'));
                    $url->setRouteMatch($sl->get('Application')->getMvcEvent()->getRouteMatch());
                    return $url;
                },

                // 'config' => function($hpm) {
                //     return  new \Application\View\Helper\Config($hpm);
                // },

                ),
            'invokables' => array(
                'customRow' => 'Application\View\Helper\CustomRow',
                'customCollection' => 'Application\View\Helper\CustomCollection',
            ),
        );
    }


    public function getServiceConfig()
    {
        return array(
            'abstract_factories' => array(),
            'aliases' => array(
                'Zend\Authentication\AuthenticationService' => 'AuthService',
                ),
            'invokables' =>array(
        // DB Tables
                'PicsTable' => '\Application\Model\PicsTable',
                'LinksTable' => '\Application\Model\ArticleLinksTable',
                'VideosTable' => '\Application\Model\ArticleVideosTable',
                'NewsTable' => '\Application\Model\NewsTable',
                'ShipsTable' => '\Application\Model\ShipsTable',
                'ReviewsTable' => '\Application\Model\ShipsReviewsTable',

                'UserTable' => '\Application\Model\UserTable',
                'ContactsTable' => '\Application\Model\UserContactsTable',
                'ExperienceTable' => '\Application\Model\UserExperienceTable',
                'MessageTable' => '\Application\Model\UserMessagesTable',
                'AvatarTable' => '\Application\Model\UserAvatarTable',
                'LogbookTable' => '\Application\Model\UserLogbookTable',
                'QuestionsTable' => '\Application\Model\QuestionsTable',
                'QuestionAnswersTable' => '\Application\Model\QuestionAnswersTable',
                'DocumentsTable' => '\Application\Model\UserDocumentsTable',
                'EducationTable' => '\Application\Model\UserEducationTable',
                'VacanciesTable' => '\Application\Model\VacanciesTable',
                'VacancySubsTable' => '\Application\Model\VacancySubscribersTable',
                'VacancyRepsTable' => '\Application\Model\VacancyReportsTable',
                'QuestionSubsTable' => '\Application\Model\QuestionSubscribersTable',
                'VacancyCandTable' => '\Application\Model\VacancyCandidatesTable',
                'UserCvNotesTable' => '\Application\Model\UserCvNotesTable',
                'AdminMailTemplatesTable' => '\Application\Model\AdminMailTemplatesTable',
                'MailAccountsTable' => '\Application\Model\AdminMailAccountsTable',
                'MailBoxTable' => '\Application\Model\AdminMailBoxTable',
                'MailBoxFoldersTable' => '\Application\Model\AdminMailBoxFoldersTable',
                'MailQueryTable' => '\Application\Model\AdminMailQueryTable',
                'UserDataAccessTable' => '\Application\Model\UserDataAccessTable',
                'AdminUserSettingsTable' => '\Application\Model\AdminUserSettingsTable',
                'AdminUserFavoritesTable' => '\Application\Model\AdminUserFavoritesTable',
                'AdminActivityTable' => '\Application\Model\AdminActivityTable',
                'FUsersActivityTable' => '\Application\Model\FUsersActivityTable',
                'FUsersOnlineTable' => '\Application\Model\FUsersOnlineTable',
                'ActivityViewsTable' => '\Application\Model\ActivityViewsTable',
                'ActivityVotesTable' => '\Application\Model\ActivityVotesTable',
                'AdminNotifTable' => '\Application\Model\AdminNotifTable',
                'AdminNotifReadedTable' => '\Application\Model\AdminNotifReadedTable',
                'UserNotificationsTable' => '\Application\Model\UserNotificationsTable',
                'RefreshTable' => '\Application\Model\RefreshTable',
                'UploadsTable' => '\Application\Model\UserUploadsTable',
                'CompanyUsersTable' => '\Application\Model\CompanyUsersTable',
                'BanListTable' => '\Application\Model\BanListTable',
                'InfoTable' => '\Application\Model\InfoTable',
                'LikesTable' => '\Application\Model\LikesTable',
                'CommentsTable' => '\Application\Model\CommentsTable',
                'SocialCommentsTable' => '\Application\Model\SocialCommentsTable',
                'SocialAnswersTable' => '\Application\Model\SocialAnswersTable',
                'SocialUsersTable' => '\Application\Model\SocialUsersTable',
                'SocialLikesTable' => '\Application\Model\SocialLikesTable',
                'SocialPublicsTable' => '\Application\Model\SocialPublicsTable',
                'TasksTable' => '\Application\Model\AdminTasksTable',
                'TagMapTable' => '\Application\Model\TagMapTable',
                'TagsTable' => '\Application\Model\TagsTable',
                'WorkedForTable' => '\Application\Model\UserWorkedForTable',
                'ExceptionsTable' => '\Application\Model\ExceptionsTable',

        // Forms
                'EmptyForm' => '\Application\Form\EmptyForm',
                'EmptyFieldset' => '\Application\Form\Fieldset\EmptyFieldset',
                'LogbookRecordForm' => '\Application\Form\LogbookRecordForm',
                'VacancyForm' => '\Application\Form\VacancyForm',
                'QuestionForm' => '\Application\Form\QuestionForm',
                'AnswerForm' => '\Application\Form\AnswerForm',
                'MailForm' => '\Application\Form\MailForm',
                'MailAccountsForm' => '\Application\Form\MailAccountsForm',
                'ContactsMessagesForm' => '\Application\Form\ContactsMessagesForm',
                'SettingsForm' => '\Application\Form\SettingsForm',

        // Fieldsets
                'UserFields' => '\Application\Form\Fieldset\User',
                'ShipsFields' => '\Application\Form\Fieldset\Ships',
                'ContractFields' => '\Application\Form\Fieldset\UserExperience',
                'DocsFields' => '\Application\Form\Fieldset\UserDocuments',
                'ReviewFields' => '\Application\Form\Fieldset\ShipsReviews',
                'PicsFields' => '\Application\Form\Fieldset\Pics',
                'VacancyFields' => '\Application\Form\Fieldset\Vacancies',
                'QuestionFields' => '\Application\Form\Fieldset\Questions',
                'QuestionAnswersFields' => '\Application\Form\Fieldset\QuestionAnswers',
                'VacancyCandFields' => '\Application\Form\Fieldset\VacancyCandidates',
                'UserContactsFilter' => '\Application\Form\Fieldset\UserContactsFilter',
                'UserFilterFields' => '\Application\Form\Fieldset\UserFilters',
                'FilterOptionsFields' => '\Application\Form\Fieldset\FilterOptions',
                'MailFields' => '\Application\Form\Fieldset\AdminMail',
                'AdminRoles' => '\Application\Form\Fieldset\AdminRoles',
                'CvNotesFields' => '\Application\Form\Fieldset\UserCvNotes',
                'CommentsFields' => '\Application\Form\Fieldset\Comments',
                'TaskFields' => '\Application\Form\Fieldset\AdminTasks',
                'ParsingOptions' => '\Application\Form\Fieldset\ParsingOptions',

        // Assertions
                'PartnersAssertion' => 'Application\Access\Assertion\PartnersAssertion',
                'FriendsAssertion' => 'Application\Access\Assertion\FriendsAssertion',
                'FriendsOrPartnersAssertion' => 'Application\Access\Assertion\FriendsOrPartnersAssertion',
                'CompanyDbAssertion' => 'Application\Access\Assertion\CompanyDbAssertion',
                'MyPropertyAssertion' => 'Application\Access\Assertion\MyPropertyAssertion',
                'NotMyPropertyAssertion' => 'Application\Access\Assertion\NotMyPropertyAssertion',
                'CommentOrResourceAuthorAssertion' => 'Application\Access\Assertion\CommentOrResourceAuthorAssertion',
                'ParentResourceAuthorAssertion' => 'Application\Access\Assertion\ParentResourceAuthorAssertion',

        //Libraries
                'UploadImage' => '\Application\zLibrary\uploadImageCrop',
                'UploadCv' => '\Application\zLibrary\UploadCv',
                'UploadMailAttachment' => '\Application\zLibrary\UploadMailAttachment',
                'Dump' => '\Application\zLibrary\RedisDump',
                'salt' => '\Application\zLibrary\Salt',
                'MailParser' => '\Application\zLibrary\MailParser',
                ),

            'factories' => array(
                'Application\Service\ErrorHandling' =>  function($sm) {
                                    $logger = $sm->get('Zend\Log');
                                    $service = new ErrorHandlingService($logger);
                                    return $service;
                                },
                'Zend\Log' => function ($sm) {
                    $filename = 'log_' . date('F') . '.txt';
                    $log = new Logger();
                    $writer = new LogWriterStream('./data/logs/' . $filename);
                    $log->addWriter($writer);

                    return $log;
                },
                'headTitle' => function($sm) {
                    return $sm->get('ViewHelperManager')->get('HeadTitle');
                },
                'imageUploadDir' => function($sm) {
                    $config = $sm->get('config');
                    return $config['module_config']['image_upload_location'];
                },
                'Access' => function($sm) {
                    return new \Application\Access\Access($sm);
                },
        // Authentication
                'AuthService' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter,'user','email','password','MD5(?)');
                    $authService = new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);
                    $session = new \Zend\Authentication\Storage\Session(null, null, $sm->get('session_manager'));
                    $authService->setStorage($session);

                    return $authService;
                },
                'session_manager' => function ($sm) {
                    $config = $sm->get('Configuration');

                    $sessionTableGateway = new TableGateway('session', \Application\Model\zAbstractTable::getAdapter());
                    $saveHandler = new DbTableGateway($sessionTableGateway, new DbTableGatewayOptions());
                    $sessionConfig = new SessionConfig();
                    $sessionConfig->setOptions($config['session']);
                    $sessionManager = new SessionManager($sessionConfig, NULL, $saveHandler);
                    $sessionManager->start();
                    return $sessionManager;
                },
                'RefreshUserId' => function ($sm) {
                    $authService = $sm->get('AuthService');
                    $userTable = $sm->get('UserTable');
                    $user = $authService->getStorage()->read();
                    $refreshed_user = $userTable->getUserById($user->id);
                    $authService->getStorage()->clear();
                    $authService->getStorage()->write($refreshed_user);
                    return true;
                },
        //Static Db Adapter
                'DB' => function($sm) {
                    return \Application\Model\zAbstractTable::getAdapter();
                },
        //Session Error Log
                'ErrorLog' => function() {
                    return new \Zend\Session\Container('ErrorLog');
                },

        //   Функции
                'validation-md5' => function() {
                    $validatorChain = new \Zend\Validator\ValidatorChain();
                    $validatorChain->attach(new \Zend\Validator\StringLength(array('min' => 32, 'max' => 32)))
                        ->attach(new \Zend\I18n\Validator\Alnum());
                        return $validatorChain;
                },

                'validation-secr_code' => function() {
                    $validatorChain = new \Zend\Validator\ValidatorChain();
                    $validatorChain
                            ->attach(new \Zend\Validator\StringLength(array('min' => 6, 'max' => 6)))
                            ->attach(new \Zend\I18n\Validator\Alnum());
                    return $validatorChain;
                },

                'Captcha' => function ($sm) {
                    $captcha = new \Zend\Captcha\Image(array(
                        'name' => 'captcha',
                        'wordLen' => 6,
                        'timeout' => 300,
                        ));
                    $captcha->setImgDir(_PUBLICROOT_.'img/captcha/');
                    $captcha->setImgUrl('/img/captcha/');
                    $captcha->setFont(_PUBLICROOT_.'css/fonts/verdana.ttf');
                    return $captcha;
                },
                'CaptchaField' => function ($sm) {
                    $captcha = $sm->get('Captcha');
                    return array(
                    'name' => 'captcha',
                    'type' => 'Zend\Form\Element\Captcha',
                    'options' => array(
                        'label' => 'Confirm Humanity',
                        'captcha' => $captcha,
                        ),
                    'attributes' => array (
                        'id' => 'captcha',
                        )
                    );
                },
                'Mail' => function ($sm) {
                    return new \Application\Mail\Mail($sm);
                },
                'api_in' => function ($sm) {
                    return new \Application\zLibrary\LinkedIn(['api_key' => _IN_APP_ID_, 'api_secret' => _IN_APP_SECRET_, 'callback_url' => 'http://'._SITENAME_.'/in-oauth']);
                },
                'api_fb' => function ($sm) {
                    return new \Facebook\Facebook(['app_id' => _FB_APP_ID_, 'app_secret' => _FB_APP_SECRET_, 'default_graph_version' => 'v2.5']);
                },
                'api_vk' => function ($sm) {
                    return new \Application\zLibrary\Vkontakte(['app_id' => _VK_APP_ID_, 'app_secret' => _VK_APP_SECR_KEY_, 'scopes' => ['email', 'offline', 'wall', 'nohttps', 'groups', 'photos', 'video','audio'], 'redirect_uri' => 'http://'._SITENAME_.'/vk-oauth']);
                },
                'vk_wall_post' => function ($sm) {
                    return new \Application\zLibrary\Vkontakte(['user_token'  => _VK_USER_TOKEN_, 'user_secret' => _VK_USER_SECRET_]);
                },
                'vk_wall_parsing' => function ($sm) {
                    return new \Application\Service\VkWallParsingService($sm);
                },
                'user_reg' => function ($sm) {
                    return new \Application\Service\UserRegisterService($sm);
                },
                'user_rating' => '\Application\Service\UserRatingServiceFactory',

            ),
            'shared' => array(),
        );
    }


    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }


    public function handleControllerCannotDispatchRequest(MvcEvent $e)
       {
           $action = $e->getRouteMatch()->getParam('action');
           $controller = get_class($e->getTarget());
            throw new \Application\Exception\Exception("Error Processing Request", 1);

           // error-controller-cannot-dispatch
           if (! method_exists($e->getTarget(), $action.'Action')) {
               // $logText = 'The requested controller '.$controller.' was unable to dispatch the request : '.$action.'Action';
           }
       }

    public function handleControllerNotFoundAndControllerInvalidAndRouteNotFound(MvcEvent $e)
       {
           $error  = $e->getError();

           if ($error == Application::ERROR_CONTROLLER_NOT_FOUND) {
               //there is no controller named $e->getRouteMatch()->getParam('controller')
               // $logText =  'The requested controller '.$e->getRouteMatch()->getParam('controller'). '  could not be mapped to an existing controller class2.';
           }

           if ($error == Application::ERROR_CONTROLLER_INVALID) {
               //the controller doesn't extends AbstractActionController
               // $logText =  'The requested controller '.$e->getRouteMatch()->getParam('controller'). ' is not dispatchable';
           }

           if ($error == Application::ERROR_ROUTER_NO_MATCH) {
               // the url doesn't match route, for example, there is no /foo literal of route
               // $logText =  'The requested URL could not be matched by routing.';
           }
       }


}
