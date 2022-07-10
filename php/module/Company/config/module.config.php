<?php

return array(

    'controllers' => array(
        'invokables' => array(
            'Company\Controller\Index' => 'Company\Controller\IndexController',
            'Company\Controller\Profile' => 'Company\Controller\ProfileController',
            ),
        ),
    'router' => array(
        'routes' => array(
            ),
        ),

     
    'view_manager' => array(
            'template_path_stack' => array(
                'company' => __DIR__ . '/../view',
            ),
            'template_map' => array(
            ),
        ),    
    );