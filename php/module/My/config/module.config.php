<?php

$translator = \Application\Translator\StaticTranslator::getTranslator();

return array(

    'controllers' => array(
        'invokables' => array(
            'My\Controller\Index' => 'My\Controller\IndexController',
            'My\Controller\Cv' => 'My\Controller\CvController',
            'My\Controller\Ships' => 'My\Controller\ShipsController',
            'My\Controller\Reviews' => 'My\Controller\ReviewsController',
            'My\Controller\Contacts' => 'My\Controller\ContactsController',
            'My\Controller\Messages' => 'My\Controller\MessagesController',
            'My\Controller\Pics' => 'My\Controller\PicsController',
            'My\Controller\Settings' => 'My\Controller\SettingsController',
            'My\Controller\Like' => 'My\Controller\LikeController',
            'My\Controller\Comments' => 'My\Controller\CommentsController',
            ),
        ),
    'view_manager' => array(
            'template_path_stack' => array(
                'my' => __DIR__ . '/../view',
            ),
            'template_map' => array(
                'my/profile' => __DIR__.'/../view/my/partial/profile.phtml',
                'my/profile-avatar' => __DIR__.'/../view/my/partial/profile-avatar.phtml',
                'my/contacts-list-item' => __DIR__.'/../view/my/partial/contacts-list-item.phtml',
                'my/contacts-list-filter' => __DIR__.'/../view/my/partial/contacts-list-filter.phtml',
                'my/reviews-review-form' => __DIR__.'/../view/my/partial/reviews-review-form.phtml',
            ),
        ),

    'assets_bundle' => array(
        'assets' => array(          
            'My' => array(
                'css' => array(
                ),
                'js' => array(
                   
                ),

            ), // Application

        ), //assets
        'production' => true,
        'recursiveSearch' => true
    ), //assets_bundle

    
    );