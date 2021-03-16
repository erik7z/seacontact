<?php
$translator = \Application\Translator\StaticTranslator::getTranslator();

return array(
    'controllers' => array(
        'invokables' => array(
            'UserInfo\Controller\Index' => 'UserInfo\Controller\IndexController',
            'UserInfo\Controller\Cv' => 'UserInfo\Controller\CvController',
            'UserInfo\Controller\Contacts' => 'UserInfo\Controller\ContactsController',
            'UserInfo\Controller\Logbook' => 'UserInfo\Controller\LogbookController',
            'UserInfo\Controller\Pics' => 'UserInfo\Controller\PicsController',
            'UserInfo\Controller\Vacancies' => 'UserInfo\Controller\VacanciesController',
            ),
        ),
    'router' => array(
        'routes' => array(
            ),
        ),


    'navigation' => array(
        'user_navigation' => array(
            array(
                'label' => $translator->translate('USER INFO SECTION'),
                'route' => 'sc/userinfo',
                'resource' => 'userinfo\controller',
                'visible' => false,
                ),
            array(
                'label' => $translator->translate('LogBook'),
                'route' => 'sc/userinfo',
                'controller' => 'index',
                'resource' => 'userinfo\controller\index',
                'icon' => 'fa fa-user',
                'useRouteMatch' => true,
                'visible' => true,
                ),
            array(
                'label' => $translator->translate('Application CV'),
                'route' => 'sc/userinfo',
                'controller' => 'cv',
                'resource' => 'userinfo\controller\cv',
                'icon' => 'fa fa-file-text-o ',
                'useRouteMatch' => true,
                'visible' => true
                ),
            array(
                'label' => $translator->translate('Contacts'),
                'route' => 'sc/userinfo',
                'controller' => 'contacts',
                'resource' => 'userinfo\controller\contacts',
                'icon' => 'fa fa-users ',
                'useRouteMatch' => true,
                'visible' => true
                ),
            array(
                'label' => $translator->translate('User Pics'),
                'route' => 'sc/userinfo',
                'controller' => 'pics',
                'resource' => 'userinfo\controller\pics',
                'icon' => 'fa fa-sticky-note-o ',
                'useRouteMatch' => true,
                'visible' => false
                ),
            ),
        ),

    'service_manager' => array(
        'factories' => array(
            'user_navigation' => 'UserInfo\Navigation\Service\UsersNavigationFactory',
        ),
    ),


    'view_manager' => array(
            'template_path_stack' => array(
                'userinfo' => __DIR__ . '/../view',
            ),
            'template_map' => array(
                'userinfo/profile' => __DIR__.'/../view/user-info/partial/profile.phtml',
            ),
        ),
    );