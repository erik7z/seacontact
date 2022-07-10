<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

$translator = \Application\Translator\StaticTranslator::getTranslator();

return array(
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Vacancies' => 'Application\Controller\VacanciesController',
            'Application\Controller\Seamansdb' => 'Application\Controller\SeamansdbController',
            'Application\Controller\Companiesdb' => 'Application\Controller\CompaniesdbController',
            'Application\Controller\Questions' => 'Application\Controller\QuestionsController',
            'Application\Controller\Notifications' => 'Application\Controller\NotificationsController',
            'Application\Controller\Logbook' => 'Application\Controller\LogbookController',
            'Application\Controller\News' => 'Application\Controller\NewsController',
            'Application\Controller\Tags' => 'Application\Controller\TagsController',
            'Application\Controller\Packages' => 'Application\Controller\PackagesController',
            'Application\Controller\Console' => 'Application\Controller\ConsoleController',
            'Application\Controller\Social' => 'Application\Controller\SocialController',

        ),
    ),
    'router' => array(
        'routes' => array(
          'sc' => array(
              'type' => 'Hostname',
              'options' => array(
                  'route' => '[www.][:lang[.]]'.getenv("SEA_DOMAIN"),
                  'constraints' => array(
                      'lang' =>'en|ru',
                      ),
                  'defaults' => array(
                      'controller' => 'Application\Controller\Index',
                      'action'     => 'index',
                  ),
              ),
              'may_terminate' => true,
              'child_routes' => array(
                // route configuration for users module (do not move from this file)
                'userinfo' => array(
                    'type' => 'Segment',
                    'options' => array(
                        'route' =>'/[:user[/:controller[/:action[/:id]]]]',
                        'constraints' => array(
                            'user' =>'[a-zA-Z][a-zA-Z0-9\._-]*',
                            'controller' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                            'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                            'id' =>'[0-9]*',
                            ),
                        'defaults' => array(
                            '__NAMESPACE__' => 'UserInfo\Controller',
                            'controller' => 'Index',
                            'action' => 'index',
                            ),
                        ),
                    ),
                'companyinfo' => array(
                    'type' => 'Segment',
                    'options' => array(
                        'route' =>'/company/:user[/:controller[/:action[/:id]]]',
                        'constraints' => array(
                            'user' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                            'controller' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                            'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                            'id' =>'[0-9]*',
                            ),
                        'defaults' => array(
                            '__NAMESPACE__' => 'CompanyInfo\Controller',
                            'controller' => 'Index',
                            'action' => 'index',
                            'id' => 1,
                            ),
                        ),
                    ),

                'home' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'index',
                        ),
                    ),
                    'may_terminate' => true,
                ),

                'my' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/me',
                        'defaults' => array(
                            'controller' => 'My\Controller\Index',
                            'action'     => 'index',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        ),
                ),
                'my_company' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/my_company',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Company\Controller',
                            'controller' => 'Company\Controller\Index',
                            'action'     => 'index',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'actions' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' =>'/:controller[/:action[/:id]]',
                                'constraints' => array(
                                    'controller' =>'[a-zA-Z][a-zA-Z]*',
                                    'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                                    'id' =>'[0-9]*',
                                    ),
                                ),
                            ),
                        ),
                ),

                'cv' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/cv',
                        'defaults' => array(
                            'controller' => 'My\Controller\Cv',
                            'action'     => 'index',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'actions' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' =>'/:action[/:id]',
                                'constraints' => array(
                                    'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                                    'id' =>'[0-9]*',
                                    ),
                                ),
                            ),
                        ),
                ),
                'pics' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/pics',
                        'defaults' => array(
                            'controller' => 'My\Controller\Pics',
                            'action'     => 'index',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'actions' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' =>'/:action[/:id]',
                                'constraints' => array(
                                    'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                                    'id' =>'[0-9]*',
                                    ),
                                ),
                            ),
                        ),
                ),

                'contacts' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/contacts',
                        'defaults' => array(
                            'controller' => 'My\Controller\contacts',
                            'action'     => 'index',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'actions' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' =>'/:action[/:id]',
                                'constraints' => array(
                                    'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                                    'id' =>'[0-9]*',
                                    ),
                                ),
                            ),
                        ),
                ),

                'messages' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/messages',
                        'defaults' => array(
                            'controller' => 'My\Controller\messages',
                            'action'     => 'index',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'actions' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' =>'/:action[/:id]',
                                'constraints' => array(
                                    'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                                    'id' =>'[0-9]*',
                                    ),
                                ),
                            ),
                        ),
                ),

                'settings' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/settings',
                        'defaults' => array(
                            'controller' => 'My\Controller\settings',
                            'action'     => 'index',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'actions' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' =>'/:action[/:id]',
                                'constraints' => array(
                                    'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                                    'id' =>'[0-9]*',
                                    ),
                                ),
                            ),
                        ),
                ),
                'like' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/like',
                        'defaults' => array(
                            'controller' => 'My\Controller\Like',
                            'action'     => 'index',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'actions' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' =>'/:section[/:id]',
                                'constraints' => array(
                                    'section' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                                    'id' =>'[0-9]*',
                                    ),
                                ),
                            ),
                        ),
                ),
                'comments' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/comments',
                        'defaults' => array(
                            'controller' => 'My\Controller\Comments',
                            'action'     => 'get',
                            'menu' => 'nomenu'
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'actions' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' =>'/:action[/:section][/:id]',
                                'constraints' => array(
                                    'action' =>'[a-zA-Z_-]*',
                                    'section' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                                    'id' =>'[0-9]*',
                                    ),
                                'defaults' => array(
                                    'action' => 'get',
                                    'section' => '',
                                    'menu' => 'nomenu'
                                ),
                                ),
                            ),
                        ),
                ),
                'vacancies' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/vacancies',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Vacancies',
                            'action'     => 'index',
                            'menu'       => 'nomenu',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'actions' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' =>'/:action[/:id]',
                                'constraints' => array(
                                    'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                                    'id' =>'[0-9]*',
                                    ),
                                'defaults' => array(
                                    'controller' => 'Application\Controller\Vacancies',
                                    'action'     => 'index',
                                ),
                                ),
                            ),
                        ),
                    ),
                'questions' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/questions',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Questions',
                            'action'     => 'index',
                            'menu'       => 'nomenu',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'actions' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' =>'/:action[/:id]',
                                'constraints' => array(
                                    'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                                    'id' =>'[0-9]*',
                                    ),
                                'defaults' => array(
                                    'controller' => 'Application\Controller\Questions',
                                    'action'     => 'index',
                                ),
                                ),
                            ),
                        ),
                    ),
                'notifications' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/notifications',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Notifications',
                            'action'     => 'index',
                            'menu'       => 'nomenu',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'actions' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' =>'/:action[/:id]',
                                'constraints' => array(
                                    'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                                    'id' =>'[0-9]*',
                                    ),
                                'defaults' => array(
                                    'controller' => 'Application\Controller\Notifications',
                                    'action'     => 'index',
                                ),
                                ),
                            ),
                        ),
                    ),
                'seamansdb' => array(
                     'type' => 'Zend\Mvc\Router\Http\Literal',
                     'options' => array(
                         'route'    => '/seamansdb',
                         'defaults' => array(
                             'controller' => 'Application\Controller\Seamansdb',
                             'action'     => 'index',
                             'menu'       => 'nomenu',
                         ),
                     ),
                     'may_terminate' => true,
                     'child_routes' => array(
                         'actions' => array(
                             'type' => 'Segment',
                             'options' => array(
                                 'route' =>'/:action[/:id]',
                                 'constraints' => array(
                                     'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                                     'id' =>'[0-9]*',
                                     ),
                                 ),
                             ),
                         ),
                     ),
                'companiesdb' => array(
                 'type' => 'Zend\Mvc\Router\Http\Literal',
                 'options' => array(
                     'route'    => '/companiesdb',
                     'defaults' => array(
                         'controller' => 'Application\Controller\Companiesdb',
                         'action'     => 'index',
                         'menu'       => 'nomenu',
                         ),
                     ),
                 'may_terminate' => true,
                 'child_routes' => array(
                     'actions' => array(
                         'type' => 'Segment',
                         'options' => array(
                             'route' =>'/:action[/:id]',
                             'constraints' => array(
                                 'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                                 'id' =>'[0-9]*',
                                 ),
                             ),
                         ),
                     ),
                 ),
                'logbook' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/logbook',
                        'defaults' => array(
                            'controller' => 'Application\Controller\logbook',
                            'action'     => 'index',
                            'menu'       => 'nomenu',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'actions' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' =>'/:action[/:id]',
                                'constraints' => array(
                                    'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                                    'id' =>'[0-9]*',
                                    ),
                                ),
                            ),
                        ),
                ),
                'news' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/news',
                        'defaults' => array(
                            'controller' => 'Application\Controller\News',
                            'action'     => 'index',
                            'menu'       => 'nomenu',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'actions' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' =>'/:action[/:id]',
                                'constraints' => array(
                                    'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                                    'id' =>'[0-9]*',
                                    ),
                                ),
                            ),
                        ),
                ),
                'tags' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/tags',
                        'defaults' => array(
                            'controller' => 'Application\Controller\tags',
                            'action'     => 'index',
                            'menu'       => 'nomenu',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'actions' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' =>'/:action[/:id]',
                                'constraints' => array(
                                    'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                                    'id' =>'[0-9]*',
                                    ),
                                ),
                            ),
                        ),
                ),
                'packages' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/packages',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Packages',
                            'action'     => 'index',
                            'menu'       => 'nomenu',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'actions' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' =>'/:action[/:id]',
                                'constraints' => array(
                                    'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',
                                    'id' =>'[0-9]*',
                                    ),
                                ),
                            ),
                        ),
                ),
                'auth' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/auth',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'auth',
                        ),
                    ),
                    'may_terminate' => true,
                ),
                'social_auth' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/social-auth',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'social-auth',
                        ),
                    ),
                    'may_terminate' => true,
                ),
                'social_captcha' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/social-captcha',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'social-captcha',
                        ),
                    ),
                    'may_terminate' => true,
                ),
                'vk_oauth' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/vk-oauth',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'vk-oauth',
                        ),
                    ),
                    'may_terminate' => true,
                ),
                'fb_oauth' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/fb-oauth',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'fb-oauth',
                        ),
                    ),
                    'may_terminate' => true,
                ),
                'in_oauth' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/in-oauth',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'in-oauth',
                        ),
                    ),
                    'may_terminate' => true,
                ),
                'social' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/social',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Social',
                            'action'     => 'index',
                            'menu' => 'nomenu'
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'actions' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' =>'/:action[/:id]',
                                'constraints' => array(
                                    'action' =>'[a-zA-Z_-]*',
                                    'id' =>'[0-9]*',
                                    ),
                                'defaults' => array(
                                    'action' => 'index',
                                    'menu' => 'nomenu'
                                ),
                                ),
                            ),
                        ),
                ),
                'forgot-password' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/forgot-password',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'forgot-password',
                        ),
                    ),
                    'may_terminate' => true,
                ),
                'confirm-reset-password' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/confirm-reset-password',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'confirm-reset-password',
                        ),
                    ),
                    'may_terminate' => true,
                ),
                'set-new-password' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/set-new-password',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'set-new-password',
                        ),
                    ),
                    'may_terminate' => true,
                ),
                'confirm-email' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/confirm-email',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'confirm-email',
                        ),
                    ),
                    'may_terminate' => true,
                ),
                'fast-reg' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/fast-reg',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'fast-reg',
                        ),
                    ),
                    'may_terminate' => true,
                ),
                'complete-registration' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/complete-registration',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'complete-registration',
                        ),
                    ),
                    'may_terminate' => true,
                ),
                'json' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/json',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'json',
                        ),
                    ),
                    'may_terminate' => true,
                ),
                'notfound' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/notfound',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'notfound',
                        ),
                    ),
                    'may_terminate' => true,
                ),
                'exit' => array(
                    'type' => 'Zend\Mvc\Router\Http\Literal',
                    'options' => array(
                        'route'    => '/exit',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'exit',
                        ),
                    ),
                    'may_terminate' => true,
                ),

              ),
          ),
        ),
    ),

    'navigation' => array(
        'default' => array(
            array(
                'label' => $translator->translate('Seacontact'),
                'menu_id' => 'main_menu',
                'route' => 'sc',
                'visible' => true,
                'useRouteMatch' => true,
                'pages' => array(
                    array(
                        'label' => $translator->translate('Home'),
                        'menu_id' => 'home',
                        'route' => 'sc/home',
                        'resource' => 'application\controller\index',
                        'useRouteMatch' => true,
                        'icon' => 'fa fa-home',
                        ),
                    array(
                        'label' => $translator->translate('News'),
                        'route' => 'sc/news',
                        'resource' => 'application\controller\news',
                        'useRouteMatch' => true,
                        'icon' => 'fa fa-bullhorn',
                        ),
                    array(
                        'label' => $translator->translate('Contacts'),
                        'menu_id' => 'contacts',
                        'route' => 'sc/contacts/actions',
                        'action' => 'collegues',
                        'resource' => 'my\controller\contacts',
                        'useRouteMatch' => true,
                        'icon' => 'fa fa-share-alt',
                        // 'notifications' => '2',
                        'pages' => array(
                                array(
                                    'label' => $translator->translate('Search'),
                                    'route' => 'sc/contacts/actions',
                                    'action' => 'search',
                                    'resource' => 'my\controller\contacts.search',
                                    'useRouteMatch' => true,
                                    'icon' => 'fa fa-search',
                                    ),
                                array(
                                    'label' => $translator->translate('Collegues'),
                                    'route' => 'sc/contacts/actions',
                                    'action' => 'collegues',
                                    'resource' => 'my\controller\contacts.collegues',
                                    'useRouteMatch' => true,
                                    'icon' => 'fa fa-chain',
                                    ),
                                array(
                                    'label' => $translator->translate('Friends'),
                                    'route' => 'sc/contacts',
                                    'action' => 'index',
                                    'resource' => 'my\controller\contacts.index',
                                    'useRouteMatch' => true,
                                    'icon' => 'fa fa-users',
                                    ),
                                array(
                                    'label' => $translator->translate('Incoming Requests'),
                                    'menu_id' => 'contacts_incoming',
                                    'route' => 'sc/contacts/actions',
                                    'action' => 'rcvdreq',
                                    'resource' => 'my\controller\contacts.rcvdreq',
                                    'useRouteMatch' => true,
                                    'icon' => 'fa fa-reply-all',
                                    ),
                                array(
                                    'label' => $translator->translate('Sent Requests'),
                                    'route' => 'sc/contacts/actions',
                                    'action' => 'sentreq',
                                    'resource' => 'my\controller\contacts.sentreq',
                                    'useRouteMatch' => true,
                                    'icon' => 'fa fa-share',
                                    ),
                                array(
                                    'label' => $translator->translate('Subscribers'),
                                    'route' => 'sc/contacts/actions',
                                    'action' => 'subscribers',
                                    'resource' => 'my\controller\contacts.subscribers',
                                    'useRouteMatch' => true,
                                    'icon' => 'fa fa-child',
                                    ),
                            )
                        ),
                    array(
                        'label' => $translator->translate('Messages'),
                        'menu_id' => 'messages',
                        'route' => 'sc/messages',
                        'resource' => 'my\controller\messages',
                        'useRouteMatch' => true,
                        'icon' => 'fa fa-comments-o',
                        // 'notifications' => '3',
                        ),

                    array(
                        'label' => $translator->translate('Vacancies'),
                        'route' => 'sc/vacancies',
                        'visible' => true,
                        'resource' => 'application\controller\vacancies.index',
                        'useRouteMatch' => true,
                        'icon' => 'li_fire',
                        'pages' => array(
                                array(
                                    'label' => $translator->translate('View Vacancy'),
                                    'route' => 'sc/vacancies/actions',
                                    'action' => 'view',
                                    'resource' => 'application\controller\vacancies.view',
                                    'useRouteMatch' => true,
                                    'visible' => true,
                                    'icon' => '',
                                    ),
                            )
                        ),
                    array(
                        'label' => $translator->translate('Seamans DB'),
                        'route' => 'sc/seamansdb',
                        'resource' => 'application\controller\seamansdb.index',
                        'useRouteMatch' => true,
                        'icon' => 'fa fa-users',
                        ),
                    array(
                        'label' => $translator->translate('Companies DB'),
                        'route' => 'sc/companiesdb',
                        'resource' => 'application\controller\companiesdb.index',
                        'useRouteMatch' => true,
                        'icon' => 'fa fa-university',
                        ),
                    array(
                        'label' => $translator->translate('Questions'),
                        'route' => 'sc/questions',
                        'action' => 'index',
                        'resource' => 'application\controller\questions.index',
                        'useRouteMatch' => true,
                        'icon' => 'fa fa-bolt pl05',
                        'pages' => array(
                                array(
                                    'label' => $translator->translate('Ask Question'),
                                    'route' => 'sc/questions/actions',
                                    'action' => 'ask',
                                    'resource' => 'application\controller\questions.ask',
                                    'useRouteMatch' => true,
                                    'visible' => true,
                                    'icon' => '',
                                    ),
                                array(
                                    'label' => $translator->translate('Answer On Question'),
                                    'route' => 'sc/questions/actions',
                                    'action' => 'view',
                                    'resource' => 'application\controller\questions.view',
                                    'useRouteMatch' => true,
                                    'visible' => false,
                                    'icon' => '',
                                    ),
                                array(
                                    'label' => $translator->translate('Edit Question'),
                                    'route' => 'sc/questions/actions',
                                    'action' => 'edit',
                                    'resource' => 'application\controller\questions.edit',
                                    'useRouteMatch' => true,
                                    'visible' => true,
                                    'icon' => '',
                                    ),
                            )
                        ),
                    array(
                        'label' => $translator->translate('Logbook'),
                        'route' => 'sc/logbook',
                        'resource' => 'application\controller\logbook.index',
                        'useRouteMatch' => true,
                        'action' => 'index',
                        'icon' => 'fa fa-edit',
                        'pages' => array(
                                array(
                                    'label' => $translator->translate('Read Logbook Entry'),
                                    'route' => 'sc/logbook/actions',
                                    'action' => 'view',
                                    'resource' => 'application\controller\logbook.view',
                                    'useRouteMatch' => true,
                                    'visible' => true,
                                    'icon' => '',
                                    ),
                                array(
                                    'label' => $translator->translate('Edit Logbook Entry'),
                                    'route' => 'sc/logbook/actions',
                                    'action' => 'edit',
                                    'resource' => 'application\controller\logbook.edit',
                                    'useRouteMatch' => true,
                                    'visible' => true,
                                    'icon' => '',
                                    ),
                                array(
                                    'label' => $translator->translate('Add Logbook Entry'),
                                    'route' => 'sc/logbook/actions',
                                    'action' => 'add',
                                    'resource' => 'application\controller\logbook.add',
                                    'useRouteMatch' => true,
                                    'visible' => true,
                                    'icon' => '',
                                    ),
                            )
                        ),
                    array(
                        'label' => $translator->translate('Notifications'),
                        'menu_id' => 'notifications',
                        'route' => 'sc/notifications',
                        'action' => 'index',
                        'resource' => 'application\controller\notifications.index',
                        'useRouteMatch' => true,
                        'icon' => 'fa fa-bell-o',
                        ),
                    )
                ),
            array(
                'label' => $translator->translate('MY'),
                'route' => 'sc/home',
                'visible' => true,
                'icon' => '',
                'menu_id' => 'my_menu',
                'useRouteMatch' => true,
                'pages' => array(
                    array(
                        'label' => $translator->translate('CV Application'),
                        'route' => 'sc/cv',
                        'icon' => 'li_note icon-md',
                        'resource' => 'my\controller\cv',
                        'useRouteMatch' => true,
                        // 'visible' => true
                        ),
                    array(
                         'label' => $translator->translate('CV Page'),
                         'route' => 'sc/cv/actions',
                         'action' => 'index',
                         'resource' => 'my\controller\cv.index',
                         'useRouteMatch' => true,
                         'visible' => false,
                         ),

                    array(
                        'label' => $translator->translate('Personal Information'),
                        'route' => 'sc/cv/actions',
                        'action' => 'personal',
                        'id' => null,
                        'icon' => 'li_user icon-md',
                        'resource' => 'my\controller\cv.personal',
                        'useRouteMatch' => true,
                        ),
                    array(
                        'label' => $translator->translate('Experience'),
                        'route' => 'sc/cv/actions',
                        'action' => 'experience',
                        'icon' => 'glyphicon glyphicon-signal icon-md',
                        'resource' => 'my\controller\cv.experience',
                        'useRouteMatch' => true,
                        'visible' => 1,
                        'pages' => [
                            [
                                'label' => $translator->translate('Add Contract'),
                                'route' => 'sc/cv/actions',
                                'action' => 'experience-add',
                                'icon' => 'fa fa-plus',
                                'resource' => 'my\controller\cv.experience-add',
                                'useRouteMatch' => true,
                            ],
                            [
                                'label' => $translator->translate('Edit Contract'),
                                'route' => 'sc/cv/actions',
                                'action' => 'experience-edit',
                                'icon' => 'fa fa-edit',
                                'resource' => 'my\controller\cv.experience-add',
                                'useRouteMatch' => true,
                            ]
                        ]
                        ),
                    array(
                        'label' => $translator->translate('Documents'),
                        'route' => 'sc/cv/actions',
                        'action' => 'docs',
                        'icon' => 'li_note icon-md',
                        'resource' => 'my\controller\cv.docs',
                        'useRouteMatch' => true,
                        ),
                    array(
                        'label' => $translator->translate('Delete CV'),
                        'route' => 'sc/cv/actions',
                        'action' => 'delete',
                        'resource' => 'my\controller\cv.delete',
                        'useRouteMatch' => true,
                        'visible' => false,
                        ),
                    array(
                        'label' => $translator->translate('Set Avatar'),
                        'route' => 'sc/cv/actions',
                        'action' => 'set-avatar',
                        'resource' => 'my\controller\cv.set-avatar',
                        'useRouteMatch' => true,
                        'visible' => false,
                        ),
                    array(
                        'label' => $translator->translate('Delete Avatar'),
                        'route' => 'sc/cv/actions',
                        'action' => 'delete-avatar',
                        'resource' => 'my\controller\cv.delete-avatar',
                        'useRouteMatch' => true,
                        'visible' => false,
                        ),
                    array(
                        'label' => $translator->translate('Download CV'),
                        'route' => 'sc/cv/actions',
                        'action' => 'download',
                        'resource' => 'my\controller\cv.download',
                        'useRouteMatch' => true,
                        'visible' => false,
                        ),
                    array(
                        'label' => $translator->translate('My Company Section'),
                        'route' => 'sc/my_company',
                        'resource' => 'company\controller',
                        'useRouteMatch' => true,
                        'visible' => false,
                        ),
                     array(
                         'label' => $translator->translate('My Company Page'),
                         'route' => 'sc/my_company',
                         'resource' => 'company\controller\index',
                         'useRouteMatch' => true,
                         'visible' => false
                         ),
                     array(
                         'label' => $translator->translate('Company Vacancies'),
                         'route' => 'sc/vacancies/actions',
                         'action' => 'company',
                         'resource' => 'application\controller\vacancies.company',
                         'useRouteMatch' => true,
                         'visible' => true,
                         'icon' => 'li_fire icon-md',
                         'pages' => array(
                                 array(
                                     'label' => $translator->translate('Add Vacancy'),
                                     'route' => 'sc/vacancies/actions',
                                     'action' => 'add',
                                     'icon' => 'li_fire icon-md',
                                     'resource' => 'application\controller\vacancies.add',
                                     'useRouteMatch' => true,
                                     'visible' => true
                                     ),
                                 array(
                                     'label' => $translator->translate('Edit Vacancy'),
                                     'route' => 'sc/vacancies/actions',
                                     'action' => 'edit',
                                     'icon' => 'li_fire icon-md',
                                     'resource' => 'application\controller\vacancies.edit',
                                     'useRouteMatch' => true,
                                     'visible' => false
                                     ),
                             )
                         ),


                    array(
                        'label' => $translator->translate('Company Seamens Database'),
                        'route' => 'sc/seamansdb/actions',
                        'action' => 'company-db',
                        'resource' => 'application\controller\seamansdb.company-db',
                        'useRouteMatch' => true,
                        'icon' => 'fa fa-users icon-md',
                        ),
                     array(
                         'label' => $translator->translate('Company Profile'),
                         'route' => 'sc/my_company/actions',
                         'controller' => 'profile',
                         'action' => 'index',
                         'resource' => 'company\controller\profile',
                         'useRouteMatch' => true,
                         'visible' => true,
                         'icon' => 'fa fa-university icon-md',
                         ),
                     array(
                         'label' => $translator->translate('Settings'),
                         'route' => 'sc/settings',
                         'icon' => 'li_settings icon-md',
                         'resource' => 'my\controller\settings',
                         'useRouteMatch' => true,
                         'visible' => true,
                         ),


                    )
                ),

            ),

            array(
                'label' => $translator->translate('logout'),
                'route' => 'sc/exit',
                'resource' => 'application\controller\index.exit',
                'useRouteMatch' => true,
                'visible' => false
                ),
        ),

    'validators' => array(
            'invokables' => array(
                // 'UserLoginValidator' => '\Application\Validator\UserLoginValidator',
                )
        ),

    'module_config' => array(
        'upload_location' => $_SERVER['DOCUMENT_ROOT'].'/uploads',
        'image_upload_location' => $_SERVER['DOCUMENT_ROOT'].'/uploads/images',
        'search_index' => __DIR__.'/../data/search_index'
        ),


    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'ru_RU',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),

    'session' => array(
            // 'cookie_domain' => '.seacontact.com',
            // 'cookie_secure' => TRUE,
            'cache_expire' => 525949,
            'cookie_lifetime' => 31536000,
            'cookie_path' => '/',
            'gc_maxlifetime' => 31536000,
            'remember_me_seconds' => 31536000,
            'use_cookies' => TRUE,
        ),

    'view_manager' => array(
        'display_not_found_reason' => false,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'application/modal' => __DIR__.'/../view/partial/modal.phtml',
            'application/image-gallery' => __DIR__.'/../view/partial/image-gallery.phtml',
            'application/js/file_upload' => __DIR__.'/../view/partial/js/file_upload.phtml',
            'application/js/unlock_user_info' => __DIR__.'/../view/partial/js/unlock_user_info.phtml',
            'paginator_slide' => __DIR__ . '/../view/partial/paginator_slide.phtml',
            'landing-menu' => __DIR__.'/../view/partial/landing-menu.phtml',
            'landing-social' => __DIR__.'/../view/partial/landing-social.phtml',

        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'dkim' => array (
        'params' => array(
            'd'    => getenv('_DKIM_DOMAIN_'), // domain
            'h'    => 'from:to:subject', // headers to sign
            's'    => 'dkim', // domain key selector
        ),

        'private_key' => getenv('_DKIM_KEY_') // add this in your local.php
    ),

    'cronModule' => [
        'phpPath'    => 'php',
        'scriptPath' => __DIR__.'/../../../public_html/',
        // 'scriptPath' => '/path/to/application/public/folder/',
        'jobs'       => [
            [
                'command'  => 'index.php application update-fusers',
                'schedule' => '*/15 * * * *'
            ]
        ]
    ],


    'console' => array(
        'router' => array(
            'routes' => array(
                'test-cron' => array(
                    'options' => array(
                        'route' => 'application test',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Console',
                            'action' => 'test',
                        )
                    )
                ),
                'start-cron' => array(
                    'options' => array(
                        'route' => 'application cronstart',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Console',
                            'action' => 'cronstart',
                        )
                    )
                ),
                'update-fusers' => array(
                    'options' => array(
                        'route' => 'application update-fusers',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Console',
                            'action' => 'update-fusers',
                        )
                    )
                ),
                'update-mail-box' => array(
                    'options' => array(
                        'route' => 'application update-mail-box',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Console',
                            'action' => 'update-mail-box',
                        )
                    )
                ),
               'admin-mail-query' => array(
                    'options' => array(
                        'route' => 'application admin-mail-query',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Console',
                            'action' => 'admin-mail-query',
                        )
                    )
                )
            )
        )
    )
);
