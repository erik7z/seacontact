<?php

$translator = \Application\Translator\StaticTranslator::getTranslator();

return array(
    'controllers' => array(
        'invokables' => array(
            'CompanyInfo\Controller\Index' => 'CompanyInfo\Controller\IndexController',
            'CompanyInfo\Controller\Vacancies' => 'CompanyInfo\Controller\VacanciesController',
            ),
        ),

    'navigation' => array(
        'company_navigation' => array(                                                     
            array(
                'label' => $translator->translate('COMPANY INFO SECTION'),
                'route' => 'sc/companyinfo',
                'resource' => 'companyinfo\controller',
                'visible' => false,
                ),
            array(
                'label' => $translator->translate('COMPANY INFO PAGE'),
                'route' => 'sc/companyinfo',
                'resource' => 'companyinfo\controller\index',
                'useRouteMatch' => true,
                'visible' => false,
                ),                                                         
            array(
                'label' => $translator->translate('COMPANY VACANCIES'),
                'route' => 'sc/companyinfo',
                'controller' => 'vacancies', 
                'action' => 'index',
                // 'resource' => 'companyinfo\controller\index.vacancies',
                'useRouteMatch' => true,
                'visible' => true,
                ),  
            ),
        ),

    'service_manager' => array(
        'factories' => array(
            'company_navigation' => 'CompanyInfo\Navigation\Service\CompanyNavigationFactory',
        ),
    ),
    
    'view_manager' => array(
            'template_path_stack' => array(
                'companyinfo' => __DIR__ . '/../view',
            ),
        ),
    );