<?php

$translator = \Application\Translator\StaticTranslator::getTranslator();


return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
            'Admin\Controller\Auth' => 'Admin\Controller\AuthController',
            'Admin\Controller\Userdb' => 'Admin\Controller\UserdbController',
            'Admin\Controller\Mailbox' => 'Admin\Controller\MailboxController',
            'Admin\Controller\Messages' => 'Admin\Controller\MessagesController',
            'Admin\Controller\Vacancies' => 'Admin\Controller\VacanciesController',
            'Admin\Controller\Content' => 'Admin\Controller\ContentController',
            'Admin\Controller\Tasks' => 'Admin\Controller\TasksController',
            'Admin\Controller\Moderation' => 'Admin\Controller\ModerationController',
            'Admin\Controller\Access' => 'Admin\Controller\AccessController',
            ),
        ),
    'router' => array(
        'routes' => array(           
            'admin' => array(
                   'type' => 'Hostname',
                   'options' => array(
                       'route' => 'admin.'.getenv("SEA_DOMAIN"),
                       'defaults' => array(
                           '__NAMESPACE__' => 'Admin\Controller',
                           'controller'    => 'Index',
                           'action'        => 'index',
                       ),
                   ),
                   'may_terminate' => true,
                   'child_routes' => array(
                       // This Segment route captures the requested controller
                       // and action from the URI and, through ModuleRouteListener,
                       // selects the correct controller class to use
                       'actions' => array(
                           'type'    => 'Segment',
                           'options' => array(
                               'route'    => '/[:controller[/:action][/:id][/:subaction]]',
                               'constraints' => array(
                                  'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                  'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                  'subaction'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                  'id'     => '[0-9]+',
                               ),
                               'defaults' => array(
                                   'controller' => 'Index',
                                   'action'     => 'index',
                               ),
                           ),
                       ),
                       'json' => array(
                           'type' => 'Zend\Mvc\Router\Http\Literal',
                           'options' => array(
                               'route'    => '/json',
                               'defaults' => array(
                                  '__NAMESPACE__' => 'Application\Controller',
                                   'controller' => 'Application\Controller\Index',
                                   'action'     => 'json',
                               ),
                           ),
                           'may_terminate' => true,
                       ),
                       'exit' => array(
                           'type' => 'Zend\Mvc\Router\Http\Literal',
                           'options' => array(
                               'route'    => '/exit',
                               'defaults' => array(
                                  '__NAMESPACE__' => 'Application\Controller',
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
    'admin_nav' => array(
      array(
          'label' => $translator->translate('ADMIN SECTION'),
          'route' => 'admin',
          'resource' => 'admin\controller',
          'visible' => false,
          ),
      array(
          'label' => $translator->translate('ADMIN DASHBOARD'),
          'route' => 'admin/actions',
          'controller' => 'index',
          'resource' => 'admin\controller\index',
          'visible' => false,
          ),
      array(
          'label' => 'ADMIN AUTH CONTROLLER',
          'route' => 'admin/actions',
          'controller' => 'auth',
          'resource' => 'admin\controller\auth',
          'visible' => false,
          'pages' => array(
            array(
                'label' => $translator->translate('ADMIN LOGIN PAGE'),
                'route' => 'admin/actions',
                'controller' => 'auth',
                'resource' => 'admin\controller\auth.index',
                'visible' => false,
                ),
          ),
          ),
        array(
            'label' => $translator->translate('Database'),
            'route' => 'admin/actions',
            'controller' => 'userdb',
            'icon' => 'fa fa-database',
            'pages' => array(
              array(
                  'label' => $translator->translate('Seamans Database'),
                  'route' => 'admin/actions',
                  'controller' => 'userdb',
                  'action' => 'index',
                  'resource' => 'admin\controller\userdb.index',
                  'icon' => 'fa fa-users',
                  'pages' => array(
                    array(
                        'label' => $translator->translate('Seaman Cv'),
                        'route' => 'admin/actions',
                        'controller' => 'userdb',
                        'action' => 'user',
                        'resource' => 'admin\controller\userdb.user',
                        ),
                    )
                  ),
              array(
                  'label' => $translator->translate('Registered Users'),
                  'route' => 'admin/actions',
                  'controller' => 'userdb',
                  'action' => 'registered-users',
                  'resource' => 'admin\controller\userdb.registered-users',
                  'icon' => 'fa fa-users',
                  ),
              array(
                  'label' => $translator->translate('Crewing Agencies'),
                  'route' => 'admin/actions',
                  'controller' => 'userdb',
                  'action' => 'companies',
                  'resource' => 'admin\controller\userdb.companies',
                  'icon' => 'fa fa-university',
                  'pages' => array(
                    array(
                        'label' => $translator->translate('Crewing Profile'),
                        'route' => 'admin/actions',
                        'controller' => 'userdb',
                        'action' => 'company',
                        'resource' => 'admin\controller\userdb.company',
                        ),
                    )
                  ),
              array(
                  'label' => $translator->translate('Ship Owners'),
                  'route' => 'admin/actions',
                  'controller' => 'userdb',
                  'action' => 'owners',
                  'resource' => 'admin\controller\userdb.owners',
                  'icon' => 'fa fa-anchor',
                  'pages' => array(
                    array(
                        'label' => $translator->translate('Ship Owner Profile'),
                        'route' => 'admin/actions',
                        'controller' => 'userdb',
                        'action' => 'owner',
                        'resource' => 'admin\controller\userdb.owner',
                        ),
                    )
                  ),
              array(
                  'label' => $translator->translate('Favorite Users'),
                  'route' => 'admin/actions',
                  'controller' => 'userdb',
                  'action' => 'favorite-users',
                  'resource' => 'admin\controller\userdb.favorite-users',
                  'icon' => 'fa fa-star',
                  ),
              array(
                  'label' => $translator->translate('Favorite Companies'),
                  'route' => 'admin/actions',
                  'controller' => 'userdb',
                  'action' => 'favorite-companies',
                  'resource' => 'admin\controller\userdb.favorite-companies',
                  'icon' => 'fa fa-star',
                  ),
              array(
                  'label' => $translator->translate('Add User'),
                  'route' => 'admin/actions',
                  'controller' => 'userdb',
                  'action' => 'add-user',
                  'resource' => 'admin\controller\userdb.add-user',
                  'icon' => 'fa fa-user-plus',
                  ),
              array(
                  'label' => $translator->translate('Add Company'),
                  'route' => 'admin/actions',
                  'controller' => 'userdb',
                  'action' => 'add-company',
                  'resource' => 'admin\controller\userdb.add-company',
                  'icon' => 'fa fa-university',
                  ),

              )
            ),
        array(
            'label' => 'Vacancies Database',
            'route' => 'admin/actions',
            'controller' => 'vacancies',
            'action' => 'index',
            'resource' => 'admin\controller\vacancies.index',
            'icon' => 'glyphicon glyphicon-fire',
            'pages' => array(
              array(
                  'label' => 'Vacancies',
                  'route' => 'admin/actions',
                  'controller' => 'vacancies',
                  'action' => 'index',
                  'resource' => 'admin\controller\vacancies.index',
                  'icon' => 'glyphicon glyphicon-fire',
                  ),
              array(
                  'label' => 'Vacancies Candidates',
                  'route' => 'admin/actions',
                  'controller' => 'vacancies',
                  'action' => 'vacancies-candidates',
                  'resource' => 'admin\controller\vacancies.index',
                  'icon' => 'fa fa-street-view',
                  ),
              )
            ),

        array(
            'label' => $translator->translate('Admin Messages'),
            'route' => 'admin/actions',
            'controller' => 'messages',
            'resource' => 'admin\controller\messages',
            'visible' => false
            ),
        array(
            'label' => $translator->translate('Mail'),
            'route' => 'admin/actions',
            'controller' => 'mailbox',
            'action' => 'index',
            'resource' => 'admin\controller\mailbox.index',
            'icon' => 'fa fa-envelope',
            'pages' => array(
              array(
                  'label' => $translator->translate('New Mail'),
                  'route' => 'admin/actions',
                  'controller' => 'mailbox',
                  'action' => 'new-mail',
                  'resource' => 'admin\controller\mailbox.new-mail',
                  'icon' => 'fa fa-edit',
                  ),
              array(
                  'label' => $translator->translate('Mail Box'),
                  'route' => 'admin/actions',
                  'controller' => 'mailbox',
                  'action' => 'index',
                  'resource' => 'admin\controller\mailbox.index',
                  'icon' => 'glyphicon glyphicon-inbox',
                  'pages' => array(
                    array(
                        'label' => $translator->translate('Read Mail'),
                        'route' => 'admin/actions',
                        'controller' => 'mailbox',
                        'action' => 'view',
                        'resource' => 'admin\controller\mailbox.view',
                        ),
                    )
                  ),
              array(
                  'label' => $translator->translate('Mail Query'),
                  'route' => 'admin/actions',
                  'controller' => 'mailbox',
                  'action' => 'query',
                  'resource' => 'admin\controller\mailbox.query',
                  'icon' => 'fa fa-cogs',
                  ),
              array(
                  'label' => $translator->translate('Mail Templates'),
                  'route' => 'admin/actions',
                  'controller' => 'mailbox',
                  'action' => 'templates',
                  'resource' => 'admin\controller\mailbox.templates',
                  'icon' => 'fa fa-edit',
                  ),
              array(
                  'label' => $translator->translate('Mail Signature'),
                  'route' => 'admin/actions',
                  'controller' => 'mailbox',
                  'action' => 'signature',
                  'resource' => 'admin\controller\mailbox.signature',
                  'icon' => 'fa fa-pencil',
                  ),
              array(
                  'label' => $translator->translate('Mail Accounts'),
                  'route' => 'admin/actions',
                  'controller' => 'mailbox',
                  'action' => 'accounts',
                  'resource' => 'admin\controller\mailbox.accounts',
                  'icon' => 'fa fa-users',
                  ),
              )
            ),
        array(
            'label' => 'Content Management',
            'route' => 'admin/actions',
            'controller' => 'content',
            'resource' => 'admin\controller\content',
            'icon' => 'fa fa-newspaper-o',
            'pages' => array(
              array(
                  'label' => $translator->translate('Sc Users Activity'),
                  'route' => 'admin/actions',
                  'controller' => 'content',
                  'action' => 'fusers-activity',
                  'resource' => 'admin\controller\content.fusers-activity',
                  'icon' => 'fa fa-users',
                  ),
              array(
                  'label' => $translator->translate('Comments'),
                  'route' => 'admin/actions',
                  'controller' => 'content',
                  'action' => 'comments',
                  'resource' => 'admin\controller\content.comments',
                  'icon' => 'fa fa-comments-o',
                  ),
              array(
                  'label' => $translator->translate('Logbooks'),
                  'route' => 'admin/actions',
                  'controller' => 'content',
                  'action' => 'logbooks',
                  'resource' => 'admin\controller\content.logbooks',
                  'icon' => 'fa fa-edit',
                  ),
              array(
                  'label' => $translator->translate('Questions'),
                  'route' => 'admin/actions',
                  'controller' => 'content',
                  'action' => 'questions',
                  'resource' => 'admin\controller\content.questions',
                  'icon' => 'fa fa-question',
                  ),
              array(
                  'label' => $translator->translate('Answers'),
                  'route' => 'admin/actions',
                  'controller' => 'content',
                  'action' => 'answers',
                  'resource' => 'admin\controller\content.answers',
                  'icon' => 'fa fa-bolt',
                  ),
              array(
                  'label' => $translator->translate('Tags'),
                  'route' => 'admin/actions',
                  'controller' => 'content',
                  'action' => 'tags',
                  'resource' => 'admin\controller\content.tags',
                  'icon' => 'fa fa-tag',
                  ),
              )
            ),
        array(
            'label' => $translator->translate('Task Management'),
            'route' => 'admin/actions',
            'controller' => 'tasks',
            'action' => 'index',
            'resource' => 'admin\controller\tasks.index',
            'icon' => 'fa fa-check-square-o',
            ),
        array(
            'label' => $translator->translate('Moderation'),
            'route' => 'admin/actions',
            'controller' => 'moderation',
            'action' => 'index',
            'resource' => 'admin\controller\moderation.index',
            'icon' => 'fa fa-cog',
            'pages' => array(
              array(
                  'label' => $translator->translate('Admins Activity'),
                  'route' => 'admin/actions',
                  'controller' => 'moderation',
                  'action' => 'admin-activity',
                  'resource' => 'admin\controller\moderation.admin-activity',
                  'icon' => 'fa fa-users',
                  ),
              array(
                  'label' => $translator->translate('Exceptions'),
                  'route' => 'admin/actions',
                  'controller' => 'moderation',
                  'action' => 'exceptions',
                  'resource' => 'admin\controller\moderation.exceptions',
                  'icon' => 'fa fa-warning',
                  ),
              array(
                  'label' => $translator->translate('Bugs'),
                  'route' => 'admin/actions',
                  'controller' => 'moderation',
                  'action' => 'bugs',
                  'resource' => 'admin\controller\moderation.bugs',
                  'icon' => 'fa fa-bug',
                  ),
              ),
            ),
        array(
            'label' => 'Access',
            'route' => 'admin/actions',
            'controller' => 'access',
            'resource' => 'admin\controller\access',
            'icon' => 'glyphicon glyphicon-lock',
            'pages' => array(
              array(
                  'label' => $translator->translate('Roles'),
                  'route' => 'admin/actions',
                  'controller' => 'access',
                  'action' => 'roles',
                  'resource' => 'admin\controller\access.roles',
                  'icon' => 'fa fa-users',
                  'pages' => array(
                    array(
                      'label' => $translator->translate('View Role'),
                      'route' => 'admin/actions',
                      'controller' => 'access',
                      'action' => 'role',
                      'resource' => 'admin\controller\access.role',
                      'icon' => 'fa fa-user',
                      ),
                    )
                  ),
              array(
                  'label' => $translator->translate('Resources'),
                  'route' => 'admin/actions',
                  'controller' => 'access',
                  'action' => 'resources',
                  'resource' => 'admin\controller\access.resources',
                  'icon' => 'fa fa-list-ul',
                  'pages' => array(
                    array(
                      'label' => $translator->translate('View Resource'),
                      'route' => 'admin/actions',
                      'controller' => 'access',
                      'action' => 'resource',
                      'resource' => 'admin\controller\access.resource',
                      'icon' => 'glyphicon glyphicon-apple',
                      ),
                    )
                  ),
              array(
                  'label' => $translator->translate('Ban List'),
                  'route' => 'admin/actions',
                  'controller' => 'access',
                  'action' => 'ban-list',
                  'resource' => 'admin\controller\access.ban-list',
                  'icon' => 'glyphicon glyphicon-ban-circle',
                  ),
              )
            ),
        ),

        
    ),

    'service_manager' => array(
        'factories' => array(
            'admin_nav' => 'Admin\Navigation\Service\AdminNavigationFactory',
        ),
    ),

    'view_manager' => array(
        'template_map' => array(
            'layout/admin'           => __DIR__ . '/../view/layout/admin.phtml',
            'layout/admin/error'           => __DIR__ . '/../view/layout/error.phtml',
        ),
        'template_path_stack' => array(
            'admin' => __DIR__ . '/../view',
        ),
    ),

);