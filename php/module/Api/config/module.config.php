<?php
return array(
    'controllers' => array(
        'invokables' => array(),
        'factories' => array(
            'Api\\V1\\Rpc\\UsersGet\\Controller' => 'Api\\V1\\Rpc\\UsersGet\\UsersGetControllerFactory',
            'Api\\V1\\Rpc\\NewsGet\\Controller' => 'Api\\V1\\Rpc\\NewsGet\\NewsGetControllerFactory',
            'Api\\V1\\Rpc\\QuestionsGet\\Controller' => 'Api\\V1\\Rpc\\QuestionsGet\\QuestionsGetControllerFactory',
            'Api\\V1\\Rpc\\QuestionsAdd\\Controller' => 'Api\\V1\\Rpc\\QuestionsAdd\\QuestionsAddControllerFactory',
            'Api\\V1\\Index' => 'ZF\\Apigility\\Documentation\\ControllerFactory',
            'Api\\V1\\Rpc\\LogbooksGet\\Controller' => 'Api\\V1\\Rpc\\LogbooksGet\\LogbooksGetControllerFactory',
            'Api\\V1\\Rpc\\LogbooksAdd\\Controller' => 'Api\\V1\\Rpc\\LogbooksAdd\\LogbooksAddControllerFactory',
            'Api\\V1\\Rpc\\VacanciesGet\\Controller' => 'Api\\V1\\Rpc\\VacanciesGet\\VacanciesGetControllerFactory',
            'Api\\V1\\Rpc\\VacanciesAdd\\Controller' => 'Api\\V1\\Rpc\\VacanciesAdd\\VacanciesAddControllerFactory',
            'Api\\V1\\Rpc\\QanswersGet\\Controller' => 'Api\\V1\\Rpc\\QanswersGet\\QanswersGetControllerFactory',
            'Api\\V1\\Rpc\\QanswersAdd\\Controller' => 'Api\\V1\\Rpc\\QanswersAdd\\QanswersAddControllerFactory',
            'Api\\V1\\Rpc\\CommentsGet\\Controller' => 'Api\\V1\\Rpc\\CommentsGet\\CommentsGetControllerFactory',
            'Api\\V1\\Rpc\\CommentsAdd\\Controller' => 'Api\\V1\\Rpc\\CommentsAdd\\CommentsAddControllerFactory',
            'Api\\V1\\Rpc\\ContactsGet\\Controller' => 'Api\\V1\\Rpc\\ContactsGet\\ContactsGetControllerFactory',
            'Api\\V1\\Rpc\\ContactsAdd\\Controller' => 'Api\\V1\\Rpc\\ContactsAdd\\ContactsAddControllerFactory',
            'Api\\V1\\Rpc\\ContactsRemove\\Controller' => 'Api\\V1\\Rpc\\ContactsRemove\\ContactsRemoveControllerFactory',
            'Api\\V1\\Rpc\\LikesGet\\Controller' => 'Api\\V1\\Rpc\\LikesGet\\LikesGetControllerFactory',
            'Api\\V1\\Rpc\\LikesAdd\\Controller' => 'Api\\V1\\Rpc\\LikesAdd\\LikesAddControllerFactory',
            'Api\\V1\\Rpc\\LikesRemove\\Controller' => 'Api\\V1\\Rpc\\LikesRemove\\LikesRemoveControllerFactory',
            'Api\\V1\\Rpc\\LikesIsLiked\\Controller' => 'Api\\V1\\Rpc\\LikesIsLiked\\LikesIsLikedControllerFactory',
            'Api\\V1\\Rpc\\UserExperienceGet\\Controller' => 'Api\\V1\\Rpc\\UserExperienceGet\\UserExperienceGetControllerFactory',
            'Api\\V1\\Rpc\\LogbooksRemove\\Controller' => 'Api\\V1\\Rpc\\LogbooksRemove\\LogbooksRemoveControllerFactory',
            'Api\\V1\\Rpc\\VacanciesRemove\\Controller' => 'Api\\V1\\Rpc\\VacanciesRemove\\VacanciesRemoveControllerFactory',
            'Api\\V1\\Rpc\\QuestionsRemove\\Controller' => 'Api\\V1\\Rpc\\QuestionsRemove\\QuestionsRemoveControllerFactory',
            'Api\\V1\\Rpc\\QanswersRemove\\Controller' => 'Api\\V1\\Rpc\\QanswersRemove\\QanswersRemoveControllerFactory',
            'Api\\V1\\Rpc\\CommentsRemove\\Controller' => 'Api\\V1\\Rpc\\CommentsRemove\\CommentsRemoveControllerFactory',
            'Api\\V1\\Rpc\\LinksRemove\\Controller' => 'Api\\V1\\Rpc\\LinksRemove\\LinksRemoveControllerFactory',
            'Api\\V1\\Rpc\\VideosRemove\\Controller' => 'Api\\V1\\Rpc\\VideosRemove\\VideosRemoveControllerFactory',
            'Api\\V1\\Rpc\\UserDocsGet\\Controller' => 'Api\\V1\\Rpc\\UserDocsGet\\UserDocsGetControllerFactory',
            'Api\\V1\\Rpc\\NotificationsGet\\Controller' => 'Api\\V1\\Rpc\\NotificationsGet\\NotificationsGetControllerFactory',
            'Api\\V1\\Rpc\\SiteInfoGet\\Controller' => 'Api\\V1\\Rpc\\SiteInfoGet\\SiteInfoGetControllerFactory',
            'Api\\V1\\Rpc\\UserRatingGet\\Controller' => 'Api\\V1\\Rpc\\UserRatingGet\\UserRatingGetControllerFactory',
            'Api\\V1\\Rpc\\TagsGet\\Controller' => 'Api\\V1\\Rpc\\TagsGet\\TagsGetControllerFactory',
            'Api\\V1\\Rpc\\ChatsGetList\\Controller' => 'Api\\V1\\Rpc\\ChatsGetList\\ChatsGetListControllerFactory',
            'Api\\V1\\Rpc\\ChatsGetChat\\Controller' => 'Api\\V1\\Rpc\\ChatsGetChat\\ChatsGetChatControllerFactory',
            'Api\\V1\\Rpc\\ChatsSendMessage\\Controller' => 'Api\\V1\\Rpc\\ChatsSendMessage\\ChatsSendMessageControllerFactory',
            'Api\\V1\\Rpc\\ChatsDeleteMessage\\Controller' => 'Api\\V1\\Rpc\\ChatsDeleteMessage\\ChatsDeleteMessageControllerFactory',
            'Api\\V1\\Rpc\\QuestionsEdit\\Controller' => 'Api\\V1\\Rpc\\QuestionsEdit\\QuestionsEditControllerFactory',
            'Api\\V1\\Rpc\\CommentsEdit\\Controller' => 'Api\\V1\\Rpc\\CommentsEdit\\CommentsEditControllerFactory',
            'Api\\V1\\Rpc\\LogbooksEdit\\Controller' => 'Api\\V1\\Rpc\\LogbooksEdit\\LogbooksEditControllerFactory',
            'Api\\V1\\Rpc\\VacanciesEdit\\Controller' => 'Api\\V1\\Rpc\\VacanciesEdit\\VacanciesEditControllerFactory',
            'Api\\V1\\Rpc\\QanswersEdit\\Controller' => 'Api\\V1\\Rpc\\QanswersEdit\\QanswersEditControllerFactory',
            'Api\\V1\\Rpc\\QuestionsChangeRating\\Controller' => 'Api\\V1\\Rpc\\QuestionsChangeRating\\QuestionsChangeRatingControllerFactory',
            'Api\\V1\\Rpc\\QanswersChangeRating\\Controller' => 'Api\\V1\\Rpc\\QanswersChangeRating\\QanswersChangeRatingControllerFactory',
            'Api\\V1\\Rpc\\CommentsChangeRating\\Controller' => 'Api\\V1\\Rpc\\CommentsChangeRating\\CommentsChangeRatingControllerFactory',
            'Api\\V1\\Rpc\\VacanciesToggleSubscribe\\Controller' => 'Api\\V1\\Rpc\\VacanciesToggleSubscribe\\VacanciesToggleSubscribeControllerFactory',
            'Api\\V1\\Rpc\\VacanciesToggleReport\\Controller' => 'Api\\V1\\Rpc\\VacanciesToggleReport\\VacanciesToggleReportControllerFactory',
            'Api\\V1\\Rpc\\QuestionsToggleSubscribe\\Controller' => 'Api\\V1\\Rpc\\QuestionsToggleSubscribe\\QuestionsToggleSubscribeControllerFactory',
            'Api\\V1\\Rpc\\UsersInfoUnlock\\Controller' => 'Api\\V1\\Rpc\\UsersInfoUnlock\\UsersInfoUnlockControllerFactory',
            'Api\\V1\\Rpc\\ProfileGet\\Controller' => 'Api\\V1\\Rpc\\ProfileGet\\ProfileGetControllerFactory',
            'Api\\V1\\Rpc\\ProfileEdit\\Controller' => 'Api\\V1\\Rpc\\ProfileEdit\\ProfileEditControllerFactory',
            'Api\\V1\\Rpc\\ListCountriesGet\\Controller' => 'Api\\V1\\Rpc\\ListCountriesGet\\ListCountriesGetControllerFactory',
            'Api\\V1\\Rpc\\ListRanksGet\\Controller' => 'Api\\V1\\Rpc\\ListRanksGet\\ListRanksGetControllerFactory',
            'Api\\V1\\Rpc\\ListShipTypesGet\\Controller' => 'Api\\V1\\Rpc\\ListShipTypesGet\\ListShipTypesGetControllerFactory',
            'Api\\V1\\Rpc\\ProfileRegStart\\Controller' => 'Api\\V1\\Rpc\\ProfileRegStart\\ProfileRegStartControllerFactory',
            'Api\\V1\\Rpc\\ProfileRegComplete\\Controller' => 'Api\\V1\\Rpc\\ProfileRegComplete\\ProfileRegCompleteControllerFactory',
            'Api\\V1\\Rpc\\ProfileCvAvatarUpload\\Controller' => 'Api\\V1\\Rpc\\ProfileCvAvatarUpload\\ProfileCvAvatarUploadControllerFactory',
            'Api\\V1\\Rpc\\ProfileAvatarUpload\\Controller' => 'Api\\V1\\Rpc\\ProfileAvatarUpload\\ProfileAvatarUploadControllerFactory',
            'Api\\V1\\Rpc\\ProfileAvatarRemove\\Controller' => 'Api\\V1\\Rpc\\ProfileAvatarRemove\\ProfileAvatarRemoveControllerFactory',
            'Api\\V1\\Rpc\\ProfileCvAvatarRemove\\Controller' => 'Api\\V1\\Rpc\\ProfileCvAvatarRemove\\ProfileCvAvatarRemoveControllerFactory',
            'Api\\V1\\Rpc\\PicsUpload\\Controller' => 'Api\\V1\\Rpc\\PicsUpload\\PicsUploadControllerFactory',
            'Api\\V1\\Rpc\\PicsArticleAttach\\Controller' => 'Api\\V1\\Rpc\\PicsArticleAttach\\PicsArticleAttachControllerFactory',
            'Api\\V1\\Rpc\\PicsArticleRemove\\Controller' => 'Api\\V1\\Rpc\\PicsArticleRemove\\PicsArticleRemoveControllerFactory',
            'Api\\V1\\Rpc\\PicsGet\\Controller' => 'Api\\V1\\Rpc\\PicsGet\\PicsGetControllerFactory',
            'Api\\V1\\Rpc\\ProfileMenuGet\\Controller' => 'Api\\V1\\Rpc\\ProfileMenuGet\\ProfileMenuGetControllerFactory',
            'Api\\V1\\Rpc\\ProfileDocsAdd\\Controller' => 'Api\\V1\\Rpc\\ProfileDocsAdd\\ProfileDocsAddControllerFactory',
            'Api\\V1\\Rpc\\ProfileDocsEdit\\Controller' => 'Api\\V1\\Rpc\\ProfileDocsEdit\\ProfileDocsEditControllerFactory',
            'Api\\V1\\Rpc\\ProfileDocsRemove\\Controller' => 'Api\\V1\\Rpc\\ProfileDocsRemove\\ProfileDocsRemoveControllerFactory',
            'Api\\V1\\Rpc\\ProfileExperienceAdd\\Controller' => 'Api\\V1\\Rpc\\ProfileExperienceAdd\\ProfileExperienceAddControllerFactory',
            'Api\\V1\\Rpc\\ProfileExperienceRemove\\Controller' => 'Api\\V1\\Rpc\\ProfileExperienceRemove\\ProfileExperienceRemoveControllerFactory',
            'Api\\V1\\Rpc\\ProfileExperienceEdit\\Controller' => 'Api\\V1\\Rpc\\ProfileExperienceEdit\\ProfileExperienceEditControllerFactory',
            'Api\\V1\\Rpc\\ProfilePassForgot\\Controller' => 'Api\\V1\\Rpc\\ProfilePassForgot\\ProfilePassForgotControllerFactory',
            'Api\\V1\\Rpc\\ProfilePassReset\\Controller' => 'Api\\V1\\Rpc\\ProfilePassReset\\ProfilePassResetControllerFactory',
            'Api\\V1\\Rpc\\QanswersToggleAccept\\Controller' => 'Api\\V1\\Rpc\\QanswersToggleAccept\\QanswersToggleAcceptControllerFactory',
            'Api\\V1\\Rpc\\VideosAdd\\Controller' => 'Api\\V1\\Rpc\\VideosAdd\\VideosAddControllerFactory',
            'Api\\V1\\Rpc\\LinksAdd\\Controller' => 'Api\\V1\\Rpc\\LinksAdd\\LinksAddControllerFactory',
            'Api\\V1\\Rpc\\VideosGet\\Controller' => 'Api\\V1\\Rpc\\VideosGet\\VideosGetControllerFactory',
            'Api\\V1\\Rpc\\LinksGet\\Controller' => 'Api\\V1\\Rpc\\LinksGet\\LinksGetControllerFactory',
            'Api\\V1\\Rpc\\VacanciesGetSubscribers\\Controller' => 'Api\\V1\\Rpc\\VacanciesGetSubscribers\\VacanciesGetSubscribersControllerFactory',
        ),
        'aliases' => array(),
    ),
    'router' => array(
        'routes' => array(
            'api' => array(
                'type' => 'Hostname',
                'options' => array(
                    'route' => 'api.'.getenv("SEA_DOMAIN"),
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Index',
                        'action' => 'show',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'api_home' => array(
                        'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
                        'options' => array(
                            'route' => '/',
                        ),
                        'may_terminate' => true,
                    ),
                    'zf-apigility' => array(
                        'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
                        'options' => array(
                            'route' => '/apigility',
                        ),
                        'may_terminate' => false,
                    ),
                ),
            ),
            'api.rpc.users-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/users.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\UsersGet\\Controller',
                        'action' => 'usersGet',
                    ),
                ),
            ),
            'api.rpc.questions-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/questions.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\QuestionsGet\\Controller',
                        'action' => 'questionsGet',
                    ),
                ),
            ),
            'api.rpc.questions-add' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/questions.add',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\QuestionsAdd\\Controller',
                        'action' => 'questionsAdd',
                    ),
                ),
            ),
            'api.rpc.logbooks-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/logbooks.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\LogbooksGet\\Controller',
                        'action' => 'logbooksGet',
                    ),
                ),
            ),
            'api.rpc.logbooks-add' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/logbooks.add',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\LogbooksAdd\\Controller',
                        'action' => 'logbooksAdd',
                    ),
                ),
            ),
            'api.rpc.vacancies-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/vacancies.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\VacanciesGet\\Controller',
                        'action' => 'vacanciesGet',
                    ),
                ),
            ),
            'api.rpc.vacancies-add' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/vacancies.add',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\VacanciesAdd\\Controller',
                        'action' => 'vacanciesAdd',
                    ),
                ),
            ),
            'api.rpc.news-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/news.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\NewsGet\\Controller',
                        'action' => 'newsGet',
                    ),
                ),
            ),
            'api.rpc.qanswers-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/qanswers.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\QanswersGet\\Controller',
                        'action' => 'qanswersGet',
                    ),
                ),
            ),
            'api.rpc.qanswers-add' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/qanswers.add',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\QanswersAdd\\Controller',
                        'action' => 'qanswersAdd',
                    ),
                ),
            ),
            'api.rpc.comments-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/comments.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\CommentsGet\\Controller',
                        'action' => 'commentsGet',
                    ),
                ),
            ),
            'api.rpc.comments-add' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/comments.add',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\CommentsAdd\\Controller',
                        'action' => 'commentsAdd',
                    ),
                ),
            ),
            'api.rpc.contacts-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/contacts.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ContactsGet\\Controller',
                        'action' => 'contactsGet',
                    ),
                ),
            ),
            'api.rpc.contacts-add' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/contacts.add',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ContactsAdd\\Controller',
                        'action' => 'contactsAdd',
                    ),
                ),
            ),
            'api.rpc.contacts-remove' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/contacts.remove',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ContactsRemove\\Controller',
                        'action' => 'contactsRemove',
                    ),
                ),
            ),
            'api.rpc.likes-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/likes.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\LikesGet\\Controller',
                        'action' => 'likesGet',
                    ),
                ),
            ),
            'api.rpc.likes-add' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/likes.add',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\LikesAdd\\Controller',
                        'action' => 'likesAdd',
                    ),
                ),
            ),
            'api.rpc.likes-remove' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/likes.remove',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\LikesRemove\\Controller',
                        'action' => 'likesRemove',
                    ),
                ),
            ),
            'api.rpc.likes-is-liked' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/likes.is-liked',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\LikesIsLiked\\Controller',
                        'action' => 'likesIsLiked',
                    ),
                ),
            ),
            'api.rpc.user-experience-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/user-experience.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\UserExperienceGet\\Controller',
                        'action' => 'userExperienceGet',
                    ),
                ),
            ),
            'api.rpc.logbook-remove' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/logbooks.remove',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\LogbooksRemove\\Controller',
                        'action' => 'LogbooksRemove',
                    ),
                ),
            ),
            'api.rpc.vacancies-remove' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/vacancies.remove',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\VacanciesRemove\\Controller',
                        'action' => 'vacanciesRemove',
                    ),
                ),
            ),
            'api.rpc.questions-remove' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/questions.remove',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\QuestionsRemove\\Controller',
                        'action' => 'questionsRemove',
                    ),
                ),
            ),
            'api.rpc.qanswers-remove' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/qanswers.remove',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\QanswersRemove\\Controller',
                        'action' => 'qanswersRemove',
                    ),
                ),
            ),
            'api.rpc.comments-remove' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/comments.remove',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\CommentsRemove\\Controller',
                        'action' => 'commentsRemove',
                    ),
                ),
            ),
            'api.rpc.links-remove' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/links.remove',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\LinksRemove\\Controller',
                        'action' => 'linksRemove',
                    ),
                ),
            ),
            'api.rpc.videos-remove' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/videos.remove',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\VideosRemove\\Controller',
                        'action' => 'videosRemove',
                    ),
                ),
            ),
            'api.rpc.user-docs-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/user-docs.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\UserDocsGet\\Controller',
                        'action' => 'userDocsGet',
                    ),
                ),
            ),
            'api.rpc.notifications-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/notifications.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\NotificationsGet\\Controller',
                        'action' => 'notificationsGet',
                    ),
                ),
            ),
            'api.rpc.site-info-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/site-info.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\SiteInfoGet\\Controller',
                        'action' => 'siteInfoGet',
                    ),
                ),
            ),
            'api.rpc.user-rating-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/user-rating.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\UserRatingGet\\Controller',
                        'action' => 'userRatingGet',
                    ),
                ),
            ),
            'api.rpc.tags-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/tags.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\TagsGet\\Controller',
                        'action' => 'tagsGet',
                    ),
                ),
            ),
            'api.rpc.chats-get-list' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/chats.get-list',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ChatsGetList\\Controller',
                        'action' => 'chatsGetList',
                    ),
                ),
            ),
            'api.rpc.chats-get-chat' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/chats.get-chat',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ChatsGetChat\\Controller',
                        'action' => 'chatsGetChat',
                    ),
                ),
            ),
            'api.rpc.chats-send-message' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/chats.send-message',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ChatsSendMessage\\Controller',
                        'action' => 'chatsSendMessage',
                    ),
                ),
            ),
            'api.rpc.chats-delete-message' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/chats.delete-message',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ChatsDeleteMessage\\Controller',
                        'action' => 'chatsDeleteMessage',
                    ),
                ),
            ),
            'api.rpc.questions-edit' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/questions.edit',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\QuestionsEdit\\Controller',
                        'action' => 'questionsEdit',
                    ),
                ),
            ),
            'api.rpc.comments-edit' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/comments.edit',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\CommentsEdit\\Controller',
                        'action' => 'commentsEdit',
                    ),
                ),
            ),
            'api.rpc.logbooks-edit' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/logbooks.edit',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\LogbooksEdit\\Controller',
                        'action' => 'logbooksEdit',
                    ),
                ),
            ),
            'api.rpc.vacancies-edit' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/vacancies.edit',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\VacanciesEdit\\Controller',
                        'action' => 'vacanciesEdit',
                    ),
                ),
            ),
            'api.rpc.qanswers-edit' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/qanswers.edit',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\QanswersEdit\\Controller',
                        'action' => 'qanswersEdit',
                    ),
                ),
            ),
            'api.rpc.questions-change-rating' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/questions.change-rating',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\QuestionsChangeRating\\Controller',
                        'action' => 'questionsChangeRating',
                    ),
                ),
            ),
            'api.rpc.qanswers-change-rating' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/qanswers.change-rating',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\QanswersChangeRating\\Controller',
                        'action' => 'qanswersChangeRating',
                    ),
                ),
            ),
            'api.rpc.comments-change-rating' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/comments.change-rating',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\CommentsChangeRating\\Controller',
                        'action' => 'commentsChangeRating',
                    ),
                ),
            ),
            'api.rpc.vacancies-toggle-subscribe' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/vacancies.toggle-subscribe',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\VacanciesToggleSubscribe\\Controller',
                        'action' => 'vacanciesToggleSubscribe',
                    ),
                ),
            ),
            'api.rpc.vacancies-toggle-report' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/vacancies.toggle-report',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\VacanciesToggleReport\\Controller',
                        'action' => 'vacanciesToggleReport',
                    ),
                ),
            ),
            'api.rpc.questions-toggle-subscribe' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/questions.toggle-subscribe',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\QuestionsToggleSubscribe\\Controller',
                        'action' => 'questionsToggleSubscribe',
                    ),
                ),
            ),
            'api.rpc.users-info-unlock' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/users.info-unlock',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\UsersInfoUnlock\\Controller',
                        'action' => 'usersInfoUnlock',
                    ),
                ),
            ),
            'api.rpc.profile-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ProfileGet\\Controller',
                        'action' => 'profileGet',
                    ),
                ),
            ),
            'api.rpc.profile-edit' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile.edit',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ProfileEdit\\Controller',
                        'action' => 'profileEdit',
                    ),
                ),
            ),
            'api.rpc.list-countries-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/list-countries.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ListCountriesGet\\Controller',
                        'action' => 'listCountriesGet',
                    ),
                ),
            ),
            'api.rpc.list-ranks-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/list-ranks.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ListRanksGet\\Controller',
                        'action' => 'listRanksGet',
                    ),
                ),
            ),
            'api.rpc.list-ship-types-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/list-ship-types.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ListShipTypesGet\\Controller',
                        'action' => 'listShipTypesGet',
                    ),
                ),
            ),
            'api.rpc.profile-reg-start' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile.reg-start',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ProfileRegStart\\Controller',
                        'action' => 'profileRegStart',
                    ),
                ),
            ),
            'api.rpc.profile-reg-complete' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile.reg-complete',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ProfileRegComplete\\Controller',
                        'action' => 'profileRegComplete',
                    ),
                ),
            ),
            'api.rpc.profile-cv-avatar-upload' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile.cv-avatar-upload',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ProfileCvAvatarUpload\\Controller',
                        'action' => 'profileCvAvatarUpload',
                    ),
                ),
            ),
            'api.rpc.profile-avatar-upload' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile.avatar-upload',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ProfileAvatarUpload\\Controller',
                        'action' => 'profileAvatarUpload',
                    ),
                ),
            ),
            'api.rpc.profile-avatar-remove' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile.avatar-remove',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ProfileAvatarRemove\\Controller',
                        'action' => 'profileAvatarRemove',
                    ),
                ),
            ),
            'api.rpc.profile-cv-avatar-remove' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile.cv-avatar-remove',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ProfileCvAvatarRemove\\Controller',
                        'action' => 'profileCvAvatarRemove',
                    ),
                ),
            ),
            'api.rpc.pics-upload' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/pics.upload',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\PicsUpload\\Controller',
                        'action' => 'picsUpload',
                    ),
                ),
            ),
            'api.rpc.pics-article-attach' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/pics.article-attach',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\PicsArticleAttach\\Controller',
                        'action' => 'picsArticleAttach',
                    ),
                ),
            ),
            'api.rpc.pics-article-remove' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/pics.article-remove',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\PicsArticleRemove\\Controller',
                        'action' => 'picsArticleRemove',
                    ),
                ),
            ),
            'api.rpc.pics-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/pics.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\PicsGet\\Controller',
                        'action' => 'picsGet',
                    ),
                ),
            ),
            'api.rpc.profile-menu-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile.menu-get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ProfileMenuGet\\Controller',
                        'action' => 'profileMenuGet',
                    ),
                ),
            ),
            'api.rpc.profile-docs-add' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile.docs-add',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ProfileDocsAdd\\Controller',
                        'action' => 'profileDocsAdd',
                    ),
                ),
            ),
            'api.rpc.profile-docs-edit' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile.docs-edit',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ProfileDocsEdit\\Controller',
                        'action' => 'profileDocsEdit',
                    ),
                ),
            ),
            'api.rpc.profile-docs-remove' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile.docs-remove',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ProfileDocsRemove\\Controller',
                        'action' => 'profileDocsRemove',
                    ),
                ),
            ),
            'api.rpc.profile-experience-add' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile.experience-add',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ProfileExperienceAdd\\Controller',
                        'action' => 'profileExperienceAdd',
                    ),
                ),
            ),
            'api.rpc.profile-experience-remove' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile.experience-remove',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ProfileExperienceRemove\\Controller',
                        'action' => 'profileExperienceRemove',
                    ),
                ),
            ),
            'api.rpc.profile-experience-edit' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile.experience-edit',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ProfileExperienceEdit\\Controller',
                        'action' => 'profileExperienceEdit',
                    ),
                ),
            ),
            'api.rpc.profile-pass-forgot' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile.pass-forgot',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ProfilePassForgot\\Controller',
                        'action' => 'profilePassForgot',
                    ),
                ),
            ),
            'api.rpc.profile-pass-reset' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile.pass-reset',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\ProfilePassReset\\Controller',
                        'action' => 'profilePassReset',
                    ),
                ),
            ),
            'api.rpc.qanswers-toggle-accept' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/qanswers.toggle-accept',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\QanswersToggleAccept\\Controller',
                        'action' => 'qanswersToggleAccept',
                    ),
                ),
            ),
            'api.rpc.videos-add' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/videos.add',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\VideosAdd\\Controller',
                        'action' => 'videosAdd',
                    ),
                ),
            ),
            'api.rpc.links-add' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/links.add',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\LinksAdd\\Controller',
                        'action' => 'linksAdd',
                    ),
                ),
            ),
            'api.rpc.videos-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/videos.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\VideosGet\\Controller',
                        'action' => 'videosGet',
                    ),
                ),
            ),
            'api.rpc.links-get' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/links.get',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\LinksGet\\Controller',
                        'action' => 'linksGet',
                    ),
                ),
            ),
            'api.rpc.vacancies-get-subscribers' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/vacancies.get-subscribers',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\VacanciesGetSubscribers\\Controller',
                        'action' => 'vacanciesGetSubscribers',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'api.rpc.questions-get',
            1 => 'api.rpc.users-get',
            2 => 'api.rpc.questions-add',
            3 => 'api.rpc.logbooks-get',
            4 => 'api.rpc.logbooks-add',
            5 => 'api.rpc.vacancies-get',
            6 => 'api.rpc.vacancies-add',
            7 => 'api.rpc.news-get',
            8 => 'api.rpc.qanswers-get',
            9 => 'api.rpc.qanswers-add',
            10 => 'api.rpc.comments-get',
            11 => 'api.rpc.comments-add',
            12 => 'api.rpc.contacts-get',
            13 => 'api.rpc.contacts-add',
            14 => 'api.rpc.contacts-remove',
            15 => 'api.rpc.likes-get',
            16 => 'api.rpc.likes-add',
            17 => 'api.rpc.likes-remove',
            18 => 'api.rpc.likes-is-liked',
            19 => 'api.rpc.user-experience-get',
            22 => 'api.rpc.logbook-remove',
            23 => 'api.rpc.vacancies-remove',
            24 => 'api.rpc.questions-remove',
            25 => 'api.rpc.qanswers-remove',
            26 => 'api.rpc.comments-remove',
            28 => 'api.rpc.links-remove',
            29 => 'api.rpc.videos-remove',
            31 => 'api.rpc.user-docs-get',
            32 => 'api.rpc.notifications-get',
            33 => 'api.rpc.site-info-get',
            34 => 'api.rpc.user-rating-get',
            35 => 'api.rpc.tags-get',
            36 => 'api.rpc.chats-get-list',
            37 => 'api.rpc.chats-get-chat',
            38 => 'api.rpc.chats-send-message',
            39 => 'api.rpc.chats-delete-message',
            40 => 'api.rpc.questions-edit',
            41 => 'api.rpc.comments-edit',
            42 => 'api.rpc.logbooks-edit',
            43 => 'api.rpc.vacancies-edit',
            44 => 'api.rpc.qanswers-edit',
            48 => 'api.rpc.questions-change-rating',
            49 => 'api.rpc.qanswers-change-rating',
            50 => 'api.rpc.comments-change-rating',
            51 => 'api.rpc.vacancies-toggle-subscribe',
            52 => 'api.rpc.vacancies-toggle-report',
            53 => 'api.rpc.questions-toggle-subscribe',
            54 => 'api.rpc.users-info-unlock',
            55 => 'api.rpc.profile-get',
            56 => 'api.rpc.profile-edit',
            58 => 'api.rpc.list-countries-get',
            59 => 'api.rpc.list-ranks-get',
            60 => 'api.rpc.list-ship-types-get',
            61 => 'api.rpc.profile-reg-start',
            62 => 'api.rpc.profile-reg-complete',
            67 => 'api.rpc.profile-cv-avatar-upload',
            68 => 'api.rpc.profile-avatar-upload',
            69 => 'api.rpc.profile-avatar-remove',
            70 => 'api.rpc.profile-cv-avatar-remove',
            71 => 'api.rpc.pics-upload',
            73 => 'api.rpc.pics-article-attach',
            74 => 'api.rpc.pics-article-remove',
            75 => 'api.rpc.pics-get',
            76 => 'api.rpc.profile-menu-get',
            77 => 'api.rpc.profile-docs-add',
            78 => 'api.rpc.profile-docs-edit',
            79 => 'api.rpc.profile-docs-remove',
            80 => 'api.rpc.profile-experience-add',
            81 => 'api.rpc.profile-experience-remove',
            82 => 'api.rpc.profile-experience-edit',
            83 => 'api.rpc.profile-pass-forgot',
            84 => 'api.rpc.profile-pass-reset',
            86 => 'api.rpc.qanswers-toggle-accept',
            87 => 'api.rpc.videos-add',
            88 => 'api.rpc.links-add',
            89 => 'api.rpc.videos-get',
            90 => 'api.rpc.links-get',
            91 => 'api.rpc.vacancies-get-subscribers',
        ),
    ),
    'zf-rpc' => array(
        'Api\\V1\\Rpc\\UsersGet\\Controller' => array(
            'service_name' => 'UsersGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.users-get',
        ),
        'Api\\V1\\Rpc\\NewsGet\\Controller' => array(
            'service_name' => 'NewsGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.news-get',
        ),
        'Api\\V1\\Rpc\\QuestionsGet\\Controller' => array(
            'service_name' => 'QuestionsGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.questions-get',
        ),
        'Api\\V1\\Rpc\\QuestionsAdd\\Controller' => array(
            'service_name' => 'QuestionsAdd',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.questions-add',
        ),
        'Api\\V1\\Rpc\\QuestionsRemove\\Controller' => array(
            'service_name' => 'QuestionsRemove',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.questions-remove',
        ),
        'Api\\V1\\Rpc\\LogbooksGet\\Controller' => array(
            'service_name' => 'LogbooksGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.logbooks-get',
        ),
        'Api\\V1\\Rpc\\LogbooksAdd\\Controller' => array(
            'service_name' => 'LogbooksAdd',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.logbooks-add',
        ),
        'Api\\V1\\Rpc\\LogbooksRemove\\Controller' => array(
            'service_name' => 'LogbooksRemove',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.logbook-remove',
        ),
        'Api\\V1\\Rpc\\VacanciesGet\\Controller' => array(
            'service_name' => 'VacanciesGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.vacancies-get',
        ),
        'Api\\V1\\Rpc\\VacanciesAdd\\Controller' => array(
            'service_name' => 'VacanciesAdd',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.vacancies-add',
        ),
        'Api\\V1\\Rpc\\VacanciesRemove\\Controller' => array(
            'service_name' => 'VacanciesRemove',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.vacancies-remove',
        ),
        'Api\\V1\\Rpc\\QanswersGet\\Controller' => array(
            'service_name' => 'QanswersGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.qanswers-get',
        ),
        'Api\\V1\\Rpc\\QanswersAdd\\Controller' => array(
            'service_name' => 'QanswersAdd',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.qanswers-add',
        ),
        'Api\\V1\\Rpc\\QanswersRemove\\Controller' => array(
            'service_name' => 'QanswersRemove',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.qanswers-remove',
        ),
        'Api\\V1\\Rpc\\CommentsGet\\Controller' => array(
            'service_name' => 'CommentsGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.comments-get',
        ),
        'Api\\V1\\Rpc\\CommentsAdd\\Controller' => array(
            'service_name' => 'CommentsAdd',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.comments-add',
        ),
        'Api\\V1\\Rpc\\CommentsRemove\\Controller' => array(
            'service_name' => 'CommentsRemove',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.comments-remove',
        ),
        'Api\\V1\\Rpc\\ContactsGet\\Controller' => array(
            'service_name' => 'ContactsGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.contacts-get',
        ),
        'Api\\V1\\Rpc\\ContactsAdd\\Controller' => array(
            'service_name' => 'ContactsAdd',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.contacts-add',
        ),
        'Api\\V1\\Rpc\\ContactsRemove\\Controller' => array(
            'service_name' => 'ContactsRemove',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.contacts-remove',
        ),
        'Api\\V1\\Rpc\\LikesGet\\Controller' => array(
            'service_name' => 'LikesGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.likes-get',
        ),
        'Api\\V1\\Rpc\\LikesAdd\\Controller' => array(
            'service_name' => 'LikesAdd',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.likes-add',
        ),
        'Api\\V1\\Rpc\\LikesRemove\\Controller' => array(
            'service_name' => 'LikesRemove',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.likes-remove',
        ),
        'Api\\V1\\Rpc\\LikesIsLiked\\Controller' => array(
            'service_name' => 'LikesIsLiked',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.likes-is-liked',
        ),
        'Api\\V1\\Rpc\\UserExperienceGet\\Controller' => array(
            'service_name' => 'UserExperienceGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.user-experience-get',
        ),
        'Api\\V1\\Rpc\\UserDocsGet\\Controller' => array(
            'service_name' => 'UserDocsGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.user-docs-get',
        ),
        'Api\\V1\\Rpc\\NotificationsGet\\Controller' => array(
            'service_name' => 'NotificationsGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.notifications-get',
        ),
        'Api\\V1\\Rpc\\LinksRemove\\Controller' => array(
            'service_name' => 'LinksRemove',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.links-remove',
        ),
        'Api\\V1\\Rpc\\VideosRemove\\Controller' => array(
            'service_name' => 'VideosRemove',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.videos-remove',
        ),
        'Api\\V1\\Rpc\\SiteInfoGet\\Controller' => array(
            'service_name' => 'SiteInfoGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.site-info-get',
        ),
        'Api\\V1\\Rpc\\UserRatingGet\\Controller' => array(
            'service_name' => 'UserRatingGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.user-rating-get',
        ),
        'Api\\V1\\Rpc\\TagsGet\\Controller' => array(
            'service_name' => 'TagsGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.tags-get',
        ),
        'Api\\V1\\Rpc\\ChatsGetList\\Controller' => array(
            'service_name' => 'ChatsGetList',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.chats-get-list',
        ),
        'Api\\V1\\Rpc\\ChatsGetChat\\Controller' => array(
            'service_name' => 'ChatsGetChat',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.chats-get-chat',
        ),
        'Api\\V1\\Rpc\\ChatsSendMessage\\Controller' => array(
            'service_name' => 'ChatsSendMessage',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.chats-send-message',
        ),
        'Api\\V1\\Rpc\\ChatsDeleteMessage\\Controller' => array(
            'service_name' => 'ChatsDeleteMessage',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.chats-delete-message',
        ),
        'Api\\V1\\Rpc\\QuestionsEdit\\Controller' => array(
            'service_name' => 'QuestionsEdit',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.questions-edit',
        ),
        'Api\\V1\\Rpc\\CommentsEdit\\Controller' => array(
            'service_name' => 'CommentsEdit',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.comments-edit',
        ),
        'Api\\V1\\Rpc\\LogbooksEdit\\Controller' => array(
            'service_name' => 'LogbooksEdit',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.logbooks-edit',
        ),
        'Api\\V1\\Rpc\\VacanciesEdit\\Controller' => array(
            'service_name' => 'VacanciesEdit',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.vacancies-edit',
        ),
        'Api\\V1\\Rpc\\QanswersEdit\\Controller' => array(
            'service_name' => 'QanswersEdit',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.qanswers-edit',
        ),
        'Api\\V1\\Rpc\\QuestionsChangeRating\\Controller' => array(
            'service_name' => 'QuestionsChangeRating',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.questions-change-rating',
        ),
        'Api\\V1\\Rpc\\QanswersChangeRating\\Controller' => array(
            'service_name' => 'QanswersChangeRating',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.qanswers-change-rating',
        ),
        'Api\\V1\\Rpc\\CommentsChangeRating\\Controller' => array(
            'service_name' => 'CommentsChangeRating',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.comments-change-rating',
        ),
        'Api\\V1\\Rpc\\VacanciesToggleSubscribe\\Controller' => array(
            'service_name' => 'VacanciesToggleSubscribe',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.vacancies-toggle-subscribe',
        ),
        'Api\\V1\\Rpc\\VacanciesToggleReport\\Controller' => array(
            'service_name' => 'VacanciesToggleReport',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.vacancies-toggle-report',
        ),
        'Api\\V1\\Rpc\\QuestionsToggleSubscribe\\Controller' => array(
            'service_name' => 'QuestionsToggleSubscribe',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.questions-toggle-subscribe',
        ),
        'Api\\V1\\Rpc\\UsersInfoUnlock\\Controller' => array(
            'service_name' => 'UsersInfoUnlock',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.users-info-unlock',
        ),
        'Api\\V1\\Rpc\\ProfileGet\\Controller' => array(
            'service_name' => 'ProfileGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.profile-get',
        ),
        'Api\\V1\\Rpc\\ProfileEdit\\Controller' => array(
            'service_name' => 'ProfileEdit',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.profile-edit',
        ),
        'Api\\V1\\Rpc\\ListCountriesGet\\Controller' => array(
            'service_name' => 'ListCountriesGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.list-countries-get',
        ),
        'Api\\V1\\Rpc\\ListRanksGet\\Controller' => array(
            'service_name' => 'ListRanksGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.list-ranks-get',
        ),
        'Api\\V1\\Rpc\\ListShipTypesGet\\Controller' => array(
            'service_name' => 'ListShipTypesGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.list-ship-types-get',
        ),
        'Api\\V1\\Rpc\\ProfileRegStart\\Controller' => array(
            'service_name' => 'ProfileRegStart',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.profile-reg-start',
        ),
        'Api\\V1\\Rpc\\ProfileRegComplete\\Controller' => array(
            'service_name' => 'ProfileRegComplete',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.profile-reg-complete',
        ),
        'Api\\V1\\Rpc\\ProfileCvAvatarUpload\\Controller' => array(
            'service_name' => 'ProfileCvAvatarUpload',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.profile-cv-avatar-upload',
        ),
        'Api\\V1\\Rpc\\ProfileAvatarUpload\\Controller' => array(
            'service_name' => 'ProfileAvatarUpload',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.profile-avatar-upload',
        ),
        'Api\\V1\\Rpc\\ProfileAvatarRemove\\Controller' => array(
            'service_name' => 'ProfileAvatarRemove',
            'http_methods' => array(
                0 => 'POST',
                1 => 'DELETE',
            ),
            'route_name' => 'api.rpc.profile-avatar-remove',
        ),
        'Api\\V1\\Rpc\\ProfileCvAvatarRemove\\Controller' => array(
            'service_name' => 'ProfileCvAvatarRemove',
            'http_methods' => array(
                0 => 'POST',
                1 => 'DELETE',
            ),
            'route_name' => 'api.rpc.profile-cv-avatar-remove',
        ),
        'Api\\V1\\Rpc\\PicsUpload\\Controller' => array(
            'service_name' => 'PicsUpload',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.pics-upload',
        ),
        'Api\\V1\\Rpc\\PicsArticleAttach\\Controller' => array(
            'service_name' => 'PicsArticleAttach',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.pics-article-attach',
        ),
        'Api\\V1\\Rpc\\PicsArticleRemove\\Controller' => array(
            'service_name' => 'PicsArticleRemove',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.pics-article-remove',
        ),
        'Api\\V1\\Rpc\\PicsGet\\Controller' => array(
            'service_name' => 'PicsGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.pics-get',
        ),
        'Api\\V1\\Rpc\\ProfileMenuGet\\Controller' => array(
            'service_name' => 'ProfileMenuGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.profile-menu-get',
        ),
        'Api\\V1\\Rpc\\ProfileDocsAdd\\Controller' => array(
            'service_name' => 'ProfileDocsAdd',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.profile-docs-add',
        ),
        'Api\\V1\\Rpc\\ProfileDocsEdit\\Controller' => array(
            'service_name' => 'ProfileDocsEdit',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.profile-docs-edit',
        ),
        'Api\\V1\\Rpc\\ProfileDocsRemove\\Controller' => array(
            'service_name' => 'ProfileDocsRemove',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.profile-docs-remove',
        ),
        'Api\\V1\\Rpc\\ProfileExperienceAdd\\Controller' => array(
            'service_name' => 'ProfileExperienceAdd',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.profile-experience-add',
        ),
        'Api\\V1\\Rpc\\ProfileExperienceRemove\\Controller' => array(
            'service_name' => 'ProfileExperienceRemove',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.profile-experience-remove',
        ),
        'Api\\V1\\Rpc\\ProfileExperienceEdit\\Controller' => array(
            'service_name' => 'ProfileExperienceEdit',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.profile-experience-edit',
        ),
        'Api\\V1\\Rpc\\ProfilePassForgot\\Controller' => array(
            'service_name' => 'ProfilePassForgot',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.profile-pass-forgot',
        ),
        'Api\\V1\\Rpc\\ProfilePassReset\\Controller' => array(
            'service_name' => 'ProfilePassReset',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.profile-pass-reset',
        ),
        'Api\\V1\\Rpc\\QanswersToggleAccept\\Controller' => array(
            'service_name' => 'QanswersToggleAccept',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.qanswers-toggle-accept',
        ),
        'Api\\V1\\Rpc\\VideosAdd\\Controller' => array(
            'service_name' => 'VideosAdd',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.videos-add',
        ),
        'Api\\V1\\Rpc\\LinksAdd\\Controller' => array(
            'service_name' => 'LinksAdd',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.links-add',
        ),
        'Api\\V1\\Rpc\\VideosGet\\Controller' => array(
            'service_name' => 'VideosGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.videos-get',
        ),
        'Api\\V1\\Rpc\\LinksGet\\Controller' => array(
            'service_name' => 'LinksGet',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.links-get',
        ),
        'Api\\V1\\Rpc\\VacanciesGetSubscribers\\Controller' => array(
            'service_name' => 'VacanciesGetSubscribers',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'api.rpc.vacancies-get-subscribers',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Api\\V1\\Rpc\\UsersGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\QuestionsGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\QuestionsAdd\\Controller' => 'Json',
            'Api\\V1\\Rpc\\LogbooksGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\LogbooksAdd\\Controller' => 'Json',
            'Api\\V1\\Rpc\\VacanciesGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\VacanciesAdd\\Controller' => 'Json',
            'Api\\V1\\Rpc\\NewsGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\QanswersGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\QanswersAdd\\Controller' => 'Json',
            'Api\\V1\\Rpc\\CommentsGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\CommentsAdd\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ContactsGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ContactsAdd\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ContactsRemove\\Controller' => 'Json',
            'Api\\V1\\Rpc\\LikesGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\LikesAdd\\Controller' => 'Json',
            'Api\\V1\\Rpc\\LikesRemove\\Controller' => 'Json',
            'Api\\V1\\Rpc\\LikesIsLiked\\Controller' => 'Json',
            'Api\\V1\\Rpc\\UserExperienceGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\LogbooksRemove\\Controller' => 'Json',
            'Api\\V1\\Rpc\\VacanciesRemove\\Controller' => 'Json',
            'Api\\V1\\Rpc\\QuestionsRemove\\Controller' => 'Json',
            'Api\\V1\\Rpc\\QanswersRemove\\Controller' => 'Json',
            'Api\\V1\\Rpc\\CommentsRemove\\Controller' => 'Json',
            'Api\\V1\\Rpc\\LinksRemove\\Controller' => 'Json',
            'Api\\V1\\Rpc\\VideosRemove\\Controller' => 'Json',
            'Api\\V1\\Rpc\\UserDocsGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\NotificationsGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\SiteInfoGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\UserRatingGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\TagsGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ChatsGetList\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ChatsGetChat\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ChatsSendMessage\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ChatsDeleteMessage\\Controller' => 'Json',
            'Api\\V1\\Rpc\\QuestionsEdit\\Controller' => 'Json',
            'Api\\V1\\Rpc\\CommentsEdit\\Controller' => 'Json',
            'Api\\V1\\Rpc\\LogbooksEdit\\Controller' => 'Json',
            'Api\\V1\\Rpc\\VacanciesEdit\\Controller' => 'Json',
            'Api\\V1\\Rpc\\QanswersEdit\\Controller' => 'Json',
            'Api\\V1\\Rpc\\QuestionsChangeRating\\Controller' => 'Json',
            'Api\\V1\\Rpc\\QanswersChangeRating\\Controller' => 'Json',
            'Api\\V1\\Rpc\\CommentsChangeRating\\Controller' => 'Json',
            'Api\\V1\\Rpc\\VacanciesToggleSubscribe\\Controller' => 'Json',
            'Api\\V1\\Rpc\\VacanciesToggleReport\\Controller' => 'Json',
            'Api\\V1\\Rpc\\QuestionsToggleSubscribe\\Controller' => 'Json',
            'Api\\V1\\Rpc\\UsersInfoUnlock\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ProfileGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ProfileEdit\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ListCountriesGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ListRanksGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ListShipTypesGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ProfileRegStart\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ProfileRegComplete\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ProfileCvAvatarUpload\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ProfileAvatarUpload\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ProfileAvatarRemove\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ProfileCvAvatarRemove\\Controller' => 'Json',
            'Api\\V1\\Rpc\\PicsUpload\\Controller' => 'Json',
            'Api\\V1\\Rpc\\PicsArticleAttach\\Controller' => 'Json',
            'Api\\V1\\Rpc\\PicsArticleRemove\\Controller' => 'Json',
            'Api\\V1\\Rpc\\PicsGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ProfileMenuGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ProfileDocsAdd\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ProfileDocsEdit\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ProfileDocsRemove\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ProfileExperienceAdd\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ProfileExperienceRemove\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ProfileExperienceEdit\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ProfilePassForgot\\Controller' => 'Json',
            'Api\\V1\\Rpc\\ProfilePassReset\\Controller' => 'Json',
            'Api\\V1\\Rpc\\QanswersToggleAccept\\Controller' => 'Json',
            'Api\\V1\\Rpc\\VideosAdd\\Controller' => 'Json',
            'Api\\V1\\Rpc\\LinksAdd\\Controller' => 'Json',
            'Api\\V1\\Rpc\\VideosGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\LinksGet\\Controller' => 'Json',
            'Api\\V1\\Rpc\\VacanciesGetSubscribers\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'Api\\V1\\Rpc\\UsersGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\QuestionsGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\QuestionsAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\LogbooksGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\LogbooksAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\VacanciesGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\VacanciesAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\NewsGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\QanswersGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\QanswersAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\CommentsGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\CommentsAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ContactsGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ContactsAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ContactsRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\LikesGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\LikesAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\LikesRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\LikesIsLiked\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\UserExperienceGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\LogbooksRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\VacanciesRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\QuestionsRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\QanswersRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\CommentsRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\LinksRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\VideosRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\UserDocsGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\NotificationsGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\SiteInfoGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\UserRatingGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\TagsGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ChatsGetList\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ChatsGetChat\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ChatsSendMessage\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ChatsDeleteMessage\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\QuestionsEdit\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\CommentsEdit\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\LogbooksEdit\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\VacanciesEdit\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\QanswersEdit\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\QuestionsChangeRating\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\QanswersChangeRating\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\CommentsChangeRating\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\VacanciesToggleSubscribe\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\VacanciesToggleReport\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\QuestionsToggleSubscribe\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\UsersInfoUnlock\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ProfileGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ProfileEdit\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ListCountriesGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ListRanksGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ListShipTypesGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ProfileRegStart\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ProfileRegComplete\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ProfileCvAvatarUpload\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ProfileAvatarUpload\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ProfileAvatarRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ProfileCvAvatarRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\PicsUpload\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
                3 => 'multipart/form-data',
                4 => 'multipart/mixed',
            ),
            'Api\\V1\\Rpc\\PicsArticleAttach\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\PicsArticleRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\PicsGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ProfileMenuGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ProfileDocsAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ProfileDocsEdit\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ProfileDocsRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ProfileExperienceAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ProfileExperienceRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ProfileExperienceEdit\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ProfilePassForgot\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\ProfilePassReset\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\QanswersToggleAccept\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\VideosAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\LinksAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\VideosGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\LinksGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
            'Api\\V1\\Rpc\\VacanciesGetSubscribers\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
        ),
        'content_type_whitelist' => array(
            'Api\\V1\\Rpc\\UsersGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\QuestionsGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\QuestionsAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\LogbooksGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\LogbooksAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\VacanciesGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\VacanciesAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\NewsGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\QanswersGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\QanswersAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\CommentsGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\CommentsAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ContactsGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ContactsAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ContactsRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\LikesGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\LikesAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\LikesRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\LikesIsLiked\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\UserExperienceGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\LogbooksRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\VacanciesRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\QuestionsRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\QanswersRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\CommentsRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\LinksRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\VideosRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\UserDocsGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\NotificationsGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\SiteInfoGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\UserRatingGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\TagsGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ChatsGetList\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ChatsGetChat\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ChatsSendMessage\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ChatsDeleteMessage\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\QuestionsEdit\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\CommentsEdit\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\LogbooksEdit\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\VacanciesEdit\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\QanswersEdit\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\QuestionsChangeRating\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\QanswersChangeRating\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\CommentsChangeRating\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\VacanciesToggleSubscribe\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\VacanciesToggleReport\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\QuestionsToggleSubscribe\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\UsersInfoUnlock\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ProfileGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ProfileEdit\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ListCountriesGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ListRanksGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ListShipTypesGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ProfileRegStart\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ProfileRegComplete\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ProfileCvAvatarUpload\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'multipart/form-data',
            ),
            'Api\\V1\\Rpc\\ProfileAvatarUpload\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'multipart/form-data',
            ),
            'Api\\V1\\Rpc\\ProfileAvatarRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ProfileCvAvatarRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\PicsUpload\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'multipart/form-data',
                3 => 'multipart/mixed',
            ),
            'Api\\V1\\Rpc\\PicsArticleAttach\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\PicsArticleRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\PicsGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ProfileMenuGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ProfileDocsAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ProfileDocsEdit\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ProfileDocsRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ProfileExperienceAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ProfileExperienceRemove\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ProfileExperienceEdit\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ProfilePassForgot\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\ProfilePassReset\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\QanswersToggleAccept\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\VideosAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\LinksAdd\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\VideosGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\LinksGet\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
            'Api\\V1\\Rpc\\VacanciesGetSubscribers\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-content-validation' => array(
        'Api\\V1\\Rpc\\UsersGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\UsersGet\\Validator',
        ),
        'Api\\V1\\Rpc\\QuestionsGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\QuestionsGet\\Validator',
        ),
        'Api\\V1\\Rpc\\QuestionsAdd\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\QuestionsAdd\\Validator',
        ),
        'Api\\V1\\Rpc\\LogbooksGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\LogbooksGet\\Validator',
        ),
        'Api\\V1\\Rpc\\LogbooksAdd\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\LogbooksAdd\\Validator',
        ),
        'Api\\V1\\Rpc\\VacanciesGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\VacanciesGet\\Validator',
        ),
        'Api\\V1\\Rpc\\VacanciesAdd\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\VacanciesAdd\\Validator',
        ),
        'Api\\V1\\Rpc\\NewsGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\NewsGet\\Validator',
        ),
        'Api\\V1\\Rpc\\QanswersGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\QanswersGet\\Validator',
        ),
        'Api\\V1\\Rpc\\QanswersAdd\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\QanswersAdd\\Validator',
        ),
        'Api\\V1\\Rpc\\CommentsGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\CommentsGet\\Validator',
        ),
        'Api\\V1\\Rpc\\CommentsAdd\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\CommentsAdd\\Validator',
        ),
        'Api\\V1\\Rpc\\ContactsGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ContactsGet\\Validator',
        ),
        'Api\\V1\\Rpc\\ContactsAdd\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ContactsAdd\\Validator',
        ),
        'Api\\V1\\Rpc\\ContactsRemove\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ContactsRemove\\Validator',
        ),
        'Api\\V1\\Rpc\\LikesGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\LikesGet\\Validator',
        ),
        'Api\\V1\\Rpc\\LikesAdd\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\LikesAdd\\Validator',
        ),
        'Api\\V1\\Rpc\\LikesRemove\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\LikesRemove\\Validator',
        ),
        'Api\\V1\\Rpc\\LikesIsLiked\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\LikesIsLiked\\Validator',
        ),
        'Api\\V1\\Rpc\\UserExperienceGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\UserExperienceGet\\Validator',
        ),
        'Api\\V1\\Rpc\\LogbooksRemove\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\LogbooksRemove\\Validator',
        ),
        'Api\\V1\\Rpc\\VacanciesRemove\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\VacanciesRemove\\Validator',
        ),
        'Api\\V1\\Rpc\\QuestionsRemove\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\QuestionsRemove\\Validator',
        ),
        'Api\\V1\\Rpc\\QanswersRemove\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\QanswersRemove\\Validator',
        ),
        'Api\\V1\\Rpc\\CommentsRemove\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\CommentsRemove\\Validator',
        ),
        'Api\\V1\\Rpc\\LinksRemove\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\LinksRemove\\Validator',
        ),
        'Api\\V1\\Rpc\\VideosRemove\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\VideosRemove\\Validator',
        ),
        'Api\\V1\\Rpc\\UserDocsGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\UserDocsGet\\Validator',
        ),
        'Api\\V1\\Rpc\\NotificationsGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\NotificationsGet\\Validator',
        ),
        'Api\\V1\\Rpc\\SiteInfoGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\SiteInfoGet\\Validator',
        ),
        'Api\\V1\\Rpc\\UserRatingGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\UserRatingGet\\Validator',
        ),
        'Api\\V1\\Rpc\\TagsGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\TagsGet\\Validator',
        ),
        'Api\\V1\\Rpc\\ChatsGetList\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ChatsGetList\\Validator',
        ),
        'Api\\V1\\Rpc\\ChatsGetChat\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ChatsGetChat\\Validator',
        ),
        'Api\\V1\\Rpc\\ChatsSendMessage\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ChatsSendMessage\\Validator',
        ),
        'Api\\V1\\Rpc\\ChatsDeleteMessage\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ChatsDeleteMessage\\Validator',
        ),
        'Api\\V1\\Rpc\\CommentsEdit\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\CommentsEdit\\Validator',
        ),
        'Api\\V1\\Rpc\\QuestionsEdit\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\QuestionsEdit\\Validator',
        ),
        'Api\\V1\\Rpc\\LogbooksEdit\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\LogbooksEdit\\Validator',
        ),
        'Api\\V1\\Rpc\\VacanciesEdit\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\VacanciesEdit\\Validator',
        ),
        'Api\\V1\\Rpc\\QanswersEdit\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\QanswersEdit\\Validator',
        ),
        'Api\\V1\\Rpc\\QuestionsChangeRating\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\QuestionsChangeRating\\Validator',
        ),
        'Api\\V1\\Rpc\\QanswersChangeRating\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\QanswersChangeRating\\Validator',
        ),
        'Api\\V1\\Rpc\\CommentsChangeRating\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\CommentsChangeRating\\Validator',
        ),
        'Api\\V1\\Rpc\\VacanciesToggleSubscribe\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\VacanciesToggleSubscribe\\Validator',
        ),
        'Api\\V1\\Rpc\\VacanciesToggleReport\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\VacanciesToggleReport\\Validator',
        ),
        'Api\\V1\\Rpc\\QuestionsToggleSubscribe\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\QuestionsToggleSubscribe\\Validator',
        ),
        'Api\\V1\\Rpc\\UsersInfoUnlock\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\UsersInfoUnlock\\Validator',
        ),
        'Api\\V1\\Rpc\\ProfileGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ProfileGet\\Validator',
        ),
        'Api\\V1\\Rpc\\ProfileEdit\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ProfileEdit\\Validator',
        ),
        'Api\\V1\\Rpc\\ListCountriesGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ListCountriesGet\\Validator',
        ),
        'Api\\V1\\Rpc\\ListRanksGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ListRanksGet\\Validator',
        ),
        'Api\\V1\\Rpc\\ListShipTypesGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ListShipTypesGet\\Validator',
        ),
        'Api\\V1\\Rpc\\ProfileRegStart\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ProfileRegStart\\Validator',
        ),
        'Api\\V1\\Rpc\\ProfileRegComplete\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ProfileRegComplete\\Validator',
        ),
        'Api\\V1\\Rpc\\ProfileCvAvatarUpload\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ProfileCvAvatarUpload\\Validator',
        ),
        'Api\\V1\\Rpc\\ProfileAvatarUpload\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ProfileAvatarUpload\\Validator',
        ),
        'Api\\V1\\Rpc\\PicsUpload\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\PicsUpload\\Validator',
        ),
        'Api\\V1\\Rpc\\PicsArticleAttach\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\PicsArticleAttach\\Validator',
        ),
        'Api\\V1\\Rpc\\PicsArticleRemove\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\PicsArticleRemove\\Validator',
        ),
        'Api\\V1\\Rpc\\PicsGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\PicsGet\\Validator',
        ),
        'Api\\V1\\Rpc\\ProfileDocsAdd\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ProfileDocsAdd\\Validator',
        ),
        'Api\\V1\\Rpc\\ProfileDocsEdit\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ProfileDocsEdit\\Validator',
        ),
        'Api\\V1\\Rpc\\ProfileDocsRemove\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ProfileDocsRemove\\Validator',
        ),
        'Api\\V1\\Rpc\\ProfileExperienceAdd\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ProfileExperienceAdd\\Validator',
        ),
        'Api\\V1\\Rpc\\ProfileExperienceRemove\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ProfileExperienceRemove\\Validator',
        ),
        'Api\\V1\\Rpc\\ProfileExperienceEdit\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ProfileExperienceEdit\\Validator',
        ),
        'Api\\V1\\Rpc\\ProfilePassForgot\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ProfilePassForgot\\Validator',
        ),
        'Api\\V1\\Rpc\\ProfilePassReset\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\ProfilePassReset\\Validator',
        ),
        'Api\\V1\\Rpc\\QanswersToggleAccept\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\QanswersToggleAccept\\Validator',
        ),
        'Api\\V1\\Rpc\\VideosAdd\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\VideosAdd\\Validator',
        ),
        'Api\\V1\\Rpc\\LinksAdd\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\LinksAdd\\Validator',
        ),
        'Api\\V1\\Rpc\\VideosGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\VideosGet\\Validator',
        ),
        'Api\\V1\\Rpc\\LinksGet\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\LinksGet\\Validator',
        ),
        'Api\\V1\\Rpc\\VacanciesGetSubscribers\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\VacanciesGetSubscribers\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'Api\\V1\\Rpc\\UsersGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_user_fields',
                'description' => 'Coma separated name of fields returned by the request. Acceptable fields are:
                    refer to _user_fields object',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                    Limit for the results quantity per page',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int) 
                    Filter by User id',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/^([a-z][a-z0-9_]+)$/i',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'login',
                'description' => '(string) 
                    Filter by User login',
            ),
            5 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'type',
                'description' => '(string)
                    Filter results by User type (company or user)',
            ),
            6 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'name',
                'description' => '(string) 
                    Filter results by Name of user',
            ),
            7 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'desired_rank',
                'description' => '(string)
                    Filter results by User desired rank, one of the ranks from ranks list',
            ),
            8 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'visa_usa',
                'description' => '(int), 1
                filter users only with usa visa',
            ),
            9 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'visa_shenghen',
                'description' => '(bool),
                    filter users only with shenghen visa',
            ),
            10 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'nationality',
                'description' => '(string)
                    Filter results by User nationality',
            ),
            11 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'home_city',
                'description' => '(string)
                    Filter results by home_city',
            ),
            12 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'real_last_rank',
                'description' => '(string)
                    Filter users with latest rank (last contract) as required by this field',
            ),
            13 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'last_ship_type',
                'description' => '(string)
                    Filter users with latest ship type (last contract) as required by this field',
            ),
            14 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'last_dwt_from',
                'description' => '(int)
                    Filter users with latest DWT (last contract) more than this field',
            ),
            15 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'last_dwt_to',
                'description' => '(int)
                    Filter users with latest (last contract) DWT less than this field',
            ),
            16 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'ship_type',
                'description' => '(string)
                    Filter users who ever worked on such ship type',
            ),
            17 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'worked_in_psn',
                'description' => '(string)
                    Filter users whose ever worked in that position',
            ),
            18 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'ship_name',
                'description' => '(string)
                    Filter users who ever worked on the ship with name',
            ),
            19 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'dwt_from',
                'description' => '(int)
                    Filter users who ever worked on ships with DWT more than this',
            ),
            20 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'dwt_to',
                'description' => '(int)
                    Filter users who ever worked on ships with DWT less than this',
            ),
            21 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'english_from',
                'description' => '(int)
                    FIlter users with english knowledge more than this',
            ),
            22 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'english_to',
                'description' => '(int)
                    Filter users with english level less than this',
            ),
            23 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'minimum_salary_from',
                'description' => '(int)
                    Filter users with min salary more than this',
            ),
            24 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'minimum_salary_to',
                'description' => '(int)
                    Filter users with required min salary less than this',
            ),
            25 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'age_from',
                'description' => '(int)
                    Filter users with age more than this',
            ),
            26 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'age_to',
                'description' => '(int)
                    Filter users with age less than this',
            ),
            27 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'readiness_date_from',
                'description' => '(int) unix timestamp
                    Filter users with readiness date more than this',
            ),
            28 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'readiness_date_to',
                'description' => '(int) unix timestamp
                    Filter users with readiness date less than this',
            ),
            29 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'cv_last_update_from',
                'description' => '(int), unix timestamp
                    Filter users who update their cv after this date',
            ),
            30 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'cv_last_update_to',
                'description' => '(int), unix timestamp
                    Filter users who update their cv before this date',
            ),
            31 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'registered',
                'description' => '(bool), 1
                    Filter users who was registered on site',
            ),
            32 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/^(online|offline)$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'online',
                'description' => '(string) \'online\' | \'offline\'
                    Filter users who are online or offline',
            ),
            33 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/^(have_notes|no_notes)$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'notes',
                'description' => '(string) \'have_notes\' | \'no_notes\'
                    Filter users who have any crewmanager notes to their cv\'s or not',
            ),
            34 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'unlocked_users',
                'description' => '(flag) (1) 
                    Show only users whose contacts and personal information unlocked (who are in company database) ',
                'allow_empty' => false,
            ),
        ),
        'Api\\V1\\Rpc\\QuestionsGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
            Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_user_fields',
                'description' => 'Coma separated names of user fields for question Author.',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_question_fields',
                'description' => 'Comma separated field names for question item;
                    refer to _question_fields object
                ',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_stats_fields',
                'description' => 'Comma separated list of statistic fields:
                    refer to _stats_fields object',
            ),
            5 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int) 
                    Filter by Question id',
            ),
            6 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'user_id',
                'description' => '(int) 
                    Filter questions by User id',
            ),
            7 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'unanswered',
                'description' => '(flag) (1) 
                    Show only questions without answers',
                'allow_empty' => false,
            ),
            8 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'answered',
                'description' => '(flag) (1)
                    Show only questions which have answers',
                'allow_empty' => false,
            ),
            9 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'completed',
                'description' => '(flag), (1)
                    Show only questions with right answer',
            ),
            10 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'query',
                'description' => '(string) 
                    Search questions which title or tag contains this value',
            ),
        ),
        'Api\\V1\\Rpc\\QuestionsAdd\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'title',
                'description' => '(string), max 255 chars,
            Question title',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Filter\\HashTag',
                    ),
                ),
                'name' => 'tags',
                'description' => '(string), max 255
            Comma separated list of tags for the question',
            ),
            2 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'text',
                'description' => '(text),
            Text of the question',
            ),
            3 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'anonym',
                'description' => '(bool),
            Does the author want to hide his name',
                'allow_empty' => true,
            ),
        ),
        'Api\\V1\\Rpc\\LogbooksGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                        Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_user_fields',
                'description' => 'Coma separated names of user fields for question Author.',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_logbook_fields',
                'description' => 'Comma separated field names for logbook item;',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_stats_fields',
                'description' => 'Comma separated list of statistic fields',
            ),
            5 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int) 
            Filter by logbook id',
            ),
            6 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'owner_id',
                'description' => '(int) 
            Filter by Owner id',
            ),
            7 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'query',
                'description' => '(string) 
            Search items which title or tag contains this value',
            ),
        ),
        'Api\\V1\\Rpc\\LogbooksAdd\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Filter\\HashTag',
                    ),
                ),
                'name' => 'tags',
                'description' => '(string), max 255
            Comma separated list of tags for the logbook',
            ),
            1 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Filter\\UrlToLinks',
                    ),
                ),
                'name' => 'text',
                'description' => '(text),
            Text of the logbook entry',
            ),
        ),
        'Api\\V1\\Rpc\\VacanciesGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                        Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_company_fields',
                'description' => 'Coma separated names of vacancy author fields.',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_vacancy_fields',
                'description' => 'Comma separated field names for vacancy item;
            (refer to _vacancy_fields object) ',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_stats_fields',
                'description' => 'Comma separated list of statistic fields (refer to stats_fields object)',
            ),
            5 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int) 
            Filter by Vacancy id',
            ),
            6 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'company_id',
                'description' => '(int) 
            Filter vacancies by Company id',
            ),
            7 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'ship_type',
                'description' => 'varchar (32) 
            Filter vacancies by ship type',
            ),
            8 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'rank',
                'description' => 'varchar (32) 
            Filter vacancies by rank',
            ),
            9 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'minimum_salary',
                'description' => 'int (5) 
            Filter vacancies by minimum salary',
            ),
            10 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'only_new',
                'description' => 'bool (1) 
            Filter only new vacancies',
            ),
            11 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'max_contract',
                'description' => 'int (2) months 
            Filter vacancies with contract length less than value',
            ),
        ),
        'Api\\V1\\Rpc\\VacanciesAdd\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'title',
                'description' => '(string), max 255
            Vacancy Title',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'time',
                'description' => '(int), Unix Time Stamp
            Postpone publication',
            ),
            2 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Filter\\UrlToLinks',
                    ),
                ),
                'name' => 'text',
                'description' => '(text),
            Vacancy description',
            ),
            3 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'rank',
                'description' => '(varchar), max 32
            Rank',
            ),
            4 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'salary',
                'description' => '(int), max 6
            Salary',
            ),
            5 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/^(USD|EUR|GBP)$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'salary_unit',
                'description' => '(varchar option), USD/EUR/GBP
            Salary currency',
            ),
            6 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'ship_name',
                'description' => '(varchar), 32
            Ship Name',
            ),
            7 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'ship_type',
                'description' => '(varchar), 32
            Ship Type',
            ),
            8 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Digits',
                        'options' => array(
                            'min' => 1900,
                            'max' => 2020,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'ship_built',
                'description' => '(digits), year 1900 - 2020
            Ship Built',
            ),
            9 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'ship_dwt',
                'description' => '(int), 10
            Ship Size / Dwt',
            ),
            10 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'date_join',
                'description' => '(int), Unix Time
            Date Join',
            ),
            11 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Digits',
                        'options' => array(
                            'min' => 1,
                            'max' => 999,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'contract_length',
                'description' => '(digits), days 1 - 999
            Contract length',
            ),
            12 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'crew_nationality',
                'description' => '(varchar), 32
            Crew nationality',
            ),
            13 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Digits',
                        'options' => array(
                            'min' => 1,
                            'max' => 5,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'english',
                'description' => '(digits), 1 - 5
            Minimum English Level',
            ),
            14 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'comments',
                'description' => '(varchar), 255
            Any personal comments (visible only to vacancy author)',
            ),
            15 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'urgent',
                'description' => '(bool), 0/1
                 is vacancy urgent?',
            ),
        ),
        'Api\\V1\\Rpc\\NewsGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                        Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_user_fields',
                'description' => 'Coma separated names of article author fields.',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_stats_fields',
                'description' => 'Comma separated list of statistic fields (refer to stats_fields object)',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'owner_id',
                'description' => '(int) 
            Filter news by Owner id',
            ),
            5 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'tag',
                'description' => '(string) 
                    Search news which tag contains this value',
            ),
            6 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'only_later',
                'description' => '(bool) 
                    Show only postponed news',
            ),
            7 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'only_current',
                'description' => '(bool) 
                    Show only current news',
                'allow_empty' => true,
                'continue_if_empty' => true,
            ),
        ),
        'Api\\V1\\Rpc\\QanswersGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                        Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_user_fields',
                'description' => 'Coma separated names of article author fields.',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_answer_fields',
                'description' => 'Comma separated list of answer object fields (refer to _answer_fields object)',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_stats_fields',
                'description' => 'Comma separated list of statistic fields (refer to stats_fields object)',
            ),
            5 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'question_id',
                'description' => '(int) 
            Filter answers by Question id',
            ),
            6 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'answer_id',
                'description' => '(int) 
            Filter answer id',
            ),
            7 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'owner_id',
                'description' => '(int) 
            Filter answers by Owner id',
            ),
        ),
        'Api\\V1\\Rpc\\QanswersAdd\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'questions',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'question_id',
                'description' => '(int) 
                Question id on which you answering',
            ),
            1 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'text',
                'description' => '(text),
            Text of the answer',
            ),
            2 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'anonym',
                'description' => '(bool), [default=0]
            Does the author want to hide his name',
                'allow_empty' => true,
            ),
        ),
        'Api\\V1\\Rpc\\CommentsGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                        Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_user_fields',
                'description' => 'Coma separated names of comment author fields.',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_comments_fields',
                'description' => 'Comma separated list of object fields (refer to _comments_fields object)',
            ),
            4 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\SectionNameValidator',
                    ),
                ),
                'filters' => array(),
                'name' => 'section',
                'description' => '(varchar) 
                Article section, refer to the _sections_object ',
            ),
            5 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'section_id',
                'description' => '(int) 
                Article id',
            ),
            6 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'comment_id',
                'description' => '(int) 
            Filter results by comment id',
            ),
            7 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'user_id',
                'description' => '(int) 
            Filter results by comment owner id',
            ),
        ),
        'Api\\V1\\Rpc\\CommentsAdd\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\SectionNameValidator',
                    ),
                ),
                'filters' => array(),
                'name' => 'section',
                'description' => '(varchar) 
                Article section, refer to the _sections_object ',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'section_id',
                'description' => '(int) 
                Article id',
            ),
            2 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'comment',
                'description' => '(text),
            Comment text',
            ),
        ),
        'Api\\V1\\Rpc\\ContactsGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                        Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_contacts_fields',
                'description' => 'Contacts related information, (refer to _contacts_fields object).',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_user_fields',
                'description' => 'Coma separated names of partner fields.',
            ),
            4 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/^(collegues|friends|reqrcvd|reqsent|follower|following)$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'relations',
                'description' => '(string)[option] collegues|friends|reqrcvd|reqsent|follower|following
                    Filter contacts by relations',
            ),
            5 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'owner_id',
                'description' => '(int),
                    Show contacts of that user. If not provided, idenitity of current user would be taken ',
            ),
            6 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'user_id',
                'description' => '(int),
                    Filter contacts by user_id ',
            ),
            7 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'ship_type',
                'description' => 'varchar (32) 
            Filter contacts by ship type',
            ),
            8 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'ship_name',
                'description' => 'varchar (32) 
            Filter contacts by ship name',
            ),
            9 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'worked_in_psn',
                'description' => 'varchar (32) 
            Filter contacts by partner position (ever worked)',
            ),
            10 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'desired_rank',
                'description' => 'varchar (32) 
            Filter contacts by partner position (desired rank)',
            ),
            11 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'nationality',
                'description' => 'varchar (32) 
            Filter contacts by partner nationality',
            ),
            12 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'home_city',
                'description' => 'varchar (32) 
            Filter contacts by partner home town',
            ),
            13 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'online',
                'description' => '(bool),
                    filter only online contacts',
            ),
        ),
        'Api\\V1\\Rpc\\ContactsAdd\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'user_id',
                'description' => '(int),
                    User to which friendship request will be sent ',
            ),
        ),
        'Api\\V1\\Rpc\\ContactsRemove\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'user_id',
                'description' => '(int),
                    User with whom friendship would be denied',
            ),
        ),
        'Api\\V1\\Rpc\\LikesGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                        Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_user_fields',
                'description' => 'Coma separated names of like author fields.',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_likes_fields',
                'description' => 'Comma separated list of object fields (refer to _likes_fields object)',
            ),
            4 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\SectionNameValidator',
                    ),
                ),
                'filters' => array(),
                'name' => 'section',
                'description' => '(varchar) 
                Article section, refer to the _sections_object ',
            ),
            5 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'section_id',
                'description' => '(int) 
                Article id',
            ),
        ),
        'Api\\V1\\Rpc\\LikesAdd\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\SectionNameValidator',
                    ),
                ),
                'filters' => array(),
                'name' => 'section',
                'description' => '(varchar) 
                Article section, refer to the _sections_object ',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'section_id',
                'description' => '(int) 
                Article id',
            ),
        ),
        'Api\\V1\\Rpc\\LikesRemove\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\SectionNameValidator',
                    ),
                ),
                'filters' => array(),
                'name' => 'section',
                'description' => '(varchar) 
                Article section, refer to the _sections_object ',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'section_id',
                'description' => '(int) 
                Article id',
            ),
        ),
        'Api\\V1\\Rpc\\LikesIsLiked\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\SectionNameValidator',
                    ),
                ),
                'filters' => array(),
                'name' => 'section',
                'description' => '(varchar) 
                Article section, refer to the _sections_object ',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'section_id',
                'description' => '(int) 
                Article id',
            ),
        ),
        'Api\\V1\\Rpc\\UserExperienceGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                        Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_experience_fields',
                'description' => 'Experience related information, (refer to _experience_fields object).',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_user_fields',
                'description' => 'Coma separated names of user fields.',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'owner_id',
                'description' => '(int),
                    Show experience of that user. If not provided, idenitity of current user would be taken ',
            ),
        ),
        'Api\\V1\\Rpc\\LogbooksRemove\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user_logbook',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Logbook record id',
            ),
        ),
        'Api\\V1\\Rpc\\VacanciesRemove\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'vacancies',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Vacancy record id',
            ),
        ),
        'Api\\V1\\Rpc\\QuestionsRemove\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'questions',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Question record id',
            ),
        ),
        'Api\\V1\\Rpc\\QanswersRemove\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'question_answers',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Answer record id',
            ),
        ),
        'Api\\V1\\Rpc\\CommentsRemove\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'comments',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Comment id',
            ),
        ),
        'Api\\V1\\Rpc\\UserDocsRemove\\Validator' => array(),
        'Api\\V1\\Rpc\\LinksRemove\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'article_links',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Link id',
            ),
        ),
        'Api\\V1\\Rpc\\VideosRemove\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'article_videos',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Video id',
            ),
        ),
        'Api\\V1\\Rpc\\MessagesRemove\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user_messages',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Message id',
            ),
        ),
        'Api\\V1\\Rpc\\UserDocsGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                        Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_docs_fields',
                'description' => 'Documents related information, (refer to _docs_fields object).',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_user_fields',
                'description' => 'Coma separated names of user fields.',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'owner_id',
                'description' => '(int),
                    Show documents of that user. If not provided, idenitity of current user would be taken ',
            ),
        ),
        'Api\\V1\\Rpc\\NotificationsGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                        Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_notif_fields',
                'description' => 'Notifications related information, (refer to _notif_fields object).',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_user_fields',
                'description' => 'Coma separated names of user fields.',
            ),
        ),
        'Api\\V1\\Rpc\\SiteInfoGet\\Validator' => array(),
        'Api\\V1\\Rpc\\UserRatingGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'user_id',
                'description' => '(int),
                    user for which rating would be returned, defaults to current user ',
            ),
        ),
        'Api\\V1\\Rpc\\TagsGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                        Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\SectionNameValidator',
                    ),
                ),
                'filters' => array(),
                'name' => 'section',
                'description' => '(varchar) 
                Article section, for which tags are provided. Refer to the _sections_object ',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'section_id',
                'description' => '(int) 
                Filter results by section id',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'query',
                'description' => '(varchar) 
            Show tags contains this value',
            ),
        ),
        'Api\\V1\\Rpc\\ChatsGetList\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                        Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
        ),
        'Api\\V1\\Rpc\\ChatsGetChat\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                        Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_message_fields',
                'description' => 'Coma separated name of fields returned by the request. Acceptable fields are:
                    refer to _message_fields object',
            ),
            3 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user_messages',
                            'field' => 'chat_id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int) Chat id',
            ),
        ),
        'Api\\V1\\Rpc\\ChatsSendMessage\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user_messages',
                            'field' => 'chat_id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'chat_id',
                'description' => '(int) 
                Chat id',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'user_id',
                'description' => '(int) 
                User id',
            ),
            2 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'message',
                'description' => '(text),
            Message text',
            ),
        ),
        'Api\\V1\\Rpc\\ChatsDeleteMessage\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user_messages',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Message id',
            ),
        ),
        'Api\\V1\\Rpc\\CommentsEdit\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'comments',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Comment id',
            ),
            1 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'comment',
                'description' => '(text),
            Comment text',
            ),
        ),
        'Api\\V1\\Rpc\\QuestionsEdit\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'questions',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Question id',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'title',
                'description' => '(string), max 255 chars,
            Question title',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Filter\\HashTag',
                    ),
                ),
                'name' => 'tags',
                'description' => '(string), max 255
            Comma separated list of tags for the question',
            ),
            3 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'text',
                'description' => '(text),
            Text of the question',
            ),
            4 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'anonym',
                'description' => '(bool),
            Does the author want to hide his name',
                'allow_empty' => true,
                'continue_if_empty' => true,
            ),
        ),
        'Api\\V1\\Rpc\\LogbooksEdit\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user_logbook',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Logbook id',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Filter\\HashTag',
                    ),
                ),
                'name' => 'tags',
                'description' => '(string), max 255
            Comma separated list of tags for the logbook',
            ),
            2 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Filter\\UrlToLinks',
                    ),
                ),
                'name' => 'text',
                'description' => '(text),
            Text of the logbook',
            ),
        ),
        'Api\\V1\\Rpc\\VacanciesEdit\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'vacancies',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Vacancy id',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'title',
                'description' => '(string), max 255
            Vacancy Title',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'time',
                'description' => '(int), Unix Time Stamp
            Postpone publication',
            ),
            3 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Filter\\UrlToLinks',
                    ),
                ),
                'name' => 'text',
                'description' => '(text),
            Vacancy description',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'rank',
                'description' => '(varchar), max 32
            Rank',
            ),
            5 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'salary',
                'description' => '(int), max 6
            Salary',
            ),
            6 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/^(USD|EUR|GBP)$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'salary_unit',
                'description' => '(varchar option), USD/EUR/GBP
            Salary currency',
            ),
            7 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'ship_name',
                'description' => '(varchar), 32
            Ship Name',
            ),
            8 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'ship_type',
                'description' => '(varchar), 32
            Ship Type',
            ),
            9 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Digits',
                        'options' => array(
                            'min' => 1900,
                            'max' => 2020,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'ship_built',
                'description' => '(digits), year 1900 - 2020
            Ship Built',
            ),
            10 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'ship_dwt',
                'description' => '(int), 10
            Ship Size / Dwt',
            ),
            11 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'date_join',
                'description' => '(int), Unix Time
            Date Join',
            ),
            12 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Digits',
                        'options' => array(
                            'min' => 1,
                            'max' => 999,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'contract_length',
                'description' => '(digits), days 1 - 999
            Contract length',
            ),
            13 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'crew_nationality',
                'description' => '(varchar), 32
            Crew nationality',
            ),
            14 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Digits',
                        'options' => array(
                            'min' => 1,
                            'max' => 5,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'english',
                'description' => '(digits), 1 - 5
            Minimum English Level',
            ),
            15 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'comments',
                'description' => '(varchar), 255
            Any personal comments (visible only to vacancy author)',
            ),
            16 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'urgent',
                'description' => '(bool), 0/1
                 is vacancy urgent?',
            ),
        ),
        'Api\\V1\\Rpc\\QanswersEdit\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'question_answers',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Answer id',
            ),
            1 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'text',
                'description' => '(text),
            Text of the answer',
            ),
            2 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'anonym',
                'description' => '(bool), [default=0]
            Does the author want to hide his name',
                'allow_empty' => true,
            ),
        ),
        'Api\\V1\\Rpc\\QuestionsChangeRating\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'questions',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Question id',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/^(up|down)$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'rating',
                'description' => '(string) \'up\' | \'down\'
                    Rate question up or down',
            ),
        ),
        'Api\\V1\\Rpc\\QanswersChangeRating\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'question_answers',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Answer id',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/^(up|down)$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'rating',
                'description' => '(string) \'up\' | \'down\'
                    Rate answer up or down',
            ),
        ),
        'Api\\V1\\Rpc\\CommentsChangeRating\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'comments',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Comment id',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/^(up|down)$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'rating',
                'description' => '(string) \'up\' | \'down\'
                    Rate comment up or down',
            ),
        ),
        'Api\\V1\\Rpc\\VacanciesToggleSubscribe\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'vacancies',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Vacancy record id',
            ),
        ),
        'Api\\V1\\Rpc\\VacanciesToggleReport\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'vacancies',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Vacancy record id',
            ),
        ),
        'Api\\V1\\Rpc\\QuestionsToggleSubscribe\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'questions',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Question record id',
            ),
        ),
        'Api\\V1\\Rpc\\UsersInfoUnlock\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    User id',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_user_fields',
                'description' => 'Coma separated name of fields returned by the request. Acceptable fields are:
                    refer to _user_fields object',
            ),
        ),
        'Api\\V1\\Rpc\\ProfileGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_user_fields',
                'description' => 'Coma separated name of fields returned by the request. Acceptable fields are:
                    refer to _user_fields object',
            ),
        ),
        'Api\\V1\\Rpc\\ProfileEdit\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 4,
                            'max' => 20,
                            'messages' => array(
                                'stringLengthInvalid' => 'Login length should be from 4 to 20 chars',
                            ),
                        ),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\UserLoginValidator',
                    ),
                    2 => array(
                        'name' => '\\Application\\Validator\\DbNoRecordExists',
                        'options' => array(
                            'table' => 'user',
                            'field' => 'login',
                            'messages' => array(
                                'recordFound' => 'This login is already used in the system',
                            ),
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'login',
                'description' => '(string) (max 20) User login',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 5,
                            'max' => 32,
                            'messages' => array(
                                'stringLengthInvalid' => 'Password length should be from 5 to 32 chars',
                            ),
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StringTrim',
                    ),
                    1 => array(
                        'name' => '\\Application\\Filter\\HashPassword',
                    ),
                ),
                'name' => 'password',
                'description' => '(string) (max 32) Change user password',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user_experience',
                            'field' => 'rank',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'desired_rank',
                'description' => '(string) User rank. Should be one of the acceptable ranks',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Digits',
                        'options' => array(
                            'min' => 100,
                            'max' => 99999,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'minimum_salary',
                'description' => '(int) (100 - 99999) Minimum salary, USD.',
            ),
            4 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'visa_usa',
                'description' => '(bool) does user have USA visa',
            ),
            5 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'visa_usa_exp',
                'description' => '(int), Unix Time
            USA Visa expiry date',
            ),
            6 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\Boolean',
                        'options' => array(),
                    ),
                ),
                'name' => 'visa_shenghen',
                'description' => '(bool) does user have Shenghen visa',
            ),
            7 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'visa_shenghen_exp',
                'description' => '(int), Unix Time
            Shenghen Visa expiry date',
            ),
            8 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 32,
                            'messages' => array(
                                'stringLengthInvalid' => 'Name length should be from 3 to 32 chars',
                            ),
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'name',
                'description' => '(string) (max 32) User name',
            ),
            9 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 32,
                            'messages' => array(
                                'stringLengthInvalid' => 'Name length should be from 3 to 32 chars',
                            ),
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'surname',
                'description' => '(string) (max 32) User surname',
            ),
            10 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/^(male|female)$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'sex',
                'description' => '(varchar option), male/female
            Gender',
            ),
            11 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'dob',
                'description' => '(int), Unix Time
            Date of birth',
            ),
            12 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'list-countries',
                            'field' => 'country_name',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'home_country',
                'description' => '(string) User country of living. Should be one of the acceptable countries',
            ),
            13 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'list-countries',
                            'field' => 'country_name',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'nationality',
                'description' => '(string) User nationality. Should be one of the acceptable nationalities',
            ),
            14 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user',
                            'field' => 'home_city',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'home_city',
                'description' => '(string) User city. Should be one of the acceptable cities',
            ),
            15 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'home_address',
                'description' => '(string) (max 255) User home address',
            ),
            16 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                    1 => array(
                        'name' => 'EmailAddress',
                        'options' => array(
                            'domain' => true,
                            'messages' => array(
                                'emailAddressInvalidFormat' => 'Please check your e-mail address',
                            ),
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'contact_email',
                'description' => '(string) (max 255) User additional contact email',
            ),
            17 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'contact_mobile',
                'description' => '(string) (max 255) User contact mobile',
            ),
            18 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'contact_phone',
                'description' => '(string) (max 255) User contact phone',
            ),
            19 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Uri',
                        'options' => array(),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'info_website',
                'description' => '(string) (max 255) User web site',
            ),
            20 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Digits',
                        'options' => array(
                            'min' => 1,
                            'max' => 5,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'english_knowledge',
                'description' => '(int) (1 - 5) English Knowledge level',
            ),
            21 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/^(sea|home)$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'current_location',
                'description' => '(string) \'sea\' | \'home\'
                    Current location',
            ),
            22 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'readiness_date',
                'description' => '(int), Unix Time
            Readiness date',
            ),
            23 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 4,
                            'max' => 64,
                            'messages' => array(
                                'stringLengthInvalid' => 'Company Name length should be from 4 to 64 chars',
                            ),
                        ),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbNoRecordExists',
                        'options' => array(
                            'table' => 'user',
                            'field' => 'company_name',
                            'messages' => array(
                                'recordFound' => 'This Company Name is already used in the system',
                            ),
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'company_name',
                'description' => '(string) (max 64) Company name,
                to be used if user type is "Company" ',
            ),
            24 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                ),
                'name' => 'company_description',
                'description' => '(text) Company description,
                to be used if user type is "Company" ',
            ),
            25 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '24',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'company_license',
                'description' => '(string) (max 24) Company license number,
                to be used if user type is "Company" 
                ',
            ),
        ),
        'Api\\V1\\Rpc\\CountriesGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_fields',
                'description' => 'Coma separated name of fields returned by the request. Acceptable fields are:
                    id, country_code, country_name',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '300',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                    Limit for the results quantity per page',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
        ),
        'Api\\V1\\Rpc\\ListCountriesGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_fields',
                'description' => 'Coma separated name of fields returned by the request. Acceptable fields are:
                    id, country_code, country_name',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '999',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                    Limit for the results quantity per page',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
        ),
        'Api\\V1\\Rpc\\ListRanksGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_fields',
                'description' => 'Coma separated name of fields returned by the request. Acceptable fields are:
                    id, rank',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '999',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                    Limit for the results quantity per page',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
        ),
        'Api\\V1\\Rpc\\ListShipTypesGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '999',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                    Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
        ),
        'Api\\V1\\Rpc\\ProfileRegister\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '64',
                        ),
                    ),
                    1 => array(
                        'name' => 'EmailAddress',
                        'options' => array(
                            'domain' => true,
                            'messages' => array(
                                'emailAddressInvalidFormat' => 'Please check your e-mail address',
                            ),
                        ),
                    ),
                    2 => array(
                        'name' => '\\Application\\Validator\\DbNoRecordExists',
                        'options' => array(
                            'table' => 'user',
                            'field' => 'email',
                            'messages' => array(
                                'recordFound' => 'User with such E-mail already found in database. Please login.',
                            ),
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'email',
                'description' => '(string) (max 255) User email',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 32,
                            'messages' => array(
                                'stringLengthInvalid' => 'Name length should be from 3 to 32 chars',
                            ),
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'name',
                'description' => '(string) (max 32) User name',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 32,
                            'messages' => array(
                                'stringLengthInvalid' => 'Name length should be from 3 to 32 chars',
                            ),
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'surname',
                'description' => '(string) (max 32) User surname',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'desired_rank',
                'description' => '(string) 
User desired rank, one of the ranks from ranks list.',
            ),
        ),
        'Api\\V1\\Rpc\\ProfileRegStart\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '64',
                        ),
                    ),
                    1 => array(
                        'name' => 'EmailAddress',
                        'options' => array(
                            'domain' => true,
                            'messages' => array(
                                'emailAddressInvalidFormat' => 'Please check your e-mail address',
                            ),
                        ),
                    ),
                    2 => array(
                        'name' => '\\Application\\Validator\\DbNoRecordExists',
                        'options' => array(
                            'table' => 'user',
                            'field' => 'email',
                            'messages' => array(
                                'recordFound' => 'User with such E-mail already found in database. Please login.',
                            ),
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'email',
                'description' => '(string) (max 255) User email',
            ),
        ),
        'Api\\V1\\Rpc\\ProfileRegComplete\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user',
                            'field' => 'email_confirmation_key',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'email_confirmation_key',
                'description' => '(varchar) 
                Confirmation key sent to user email during start of registration ',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 5,
                            'max' => 32,
                            'messages' => array(
                                'stringLengthInvalid' => 'Password length should be from 5 to 32 chars',
                            ),
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'password',
                'description' => '(string) (max 32) Change user password',
            ),
            2 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/^(user|company)$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'type',
                'description' => '(string) \'user\' | \'company\'
                    User type seamen or company ',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 4,
                            'max' => 20,
                            'messages' => array(
                                'stringLengthInvalid' => 'Login length should be from 4 to 20 chars',
                            ),
                        ),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\UserLoginValidator',
                    ),
                    2 => array(
                        'name' => '\\Application\\Validator\\DbNoRecordExists',
                        'options' => array(
                            'table' => 'user',
                            'field' => 'login',
                            'messages' => array(
                                'recordFound' => 'This login is already used in the system',
                            ),
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'login',
                'description' => '(string) (max 20) User login',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 32,
                            'messages' => array(
                                'stringLengthInvalid' => 'Name length should be from 3 to 32 chars',
                            ),
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'name',
                'description' => '(string) (max 32) User name',
            ),
            5 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 32,
                            'messages' => array(
                                'stringLengthInvalid' => 'Name length should be from 3 to 32 chars',
                            ),
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'surname',
                'description' => '(string) (max 32) User surname',
            ),
            6 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\HtmlEntities',
                        'options' => array(),
                    ),
                ),
                'name' => 'desired_rank',
                'description' => '(string)
                    User desired rank, one of the ranks from ranks list',
            ),
            7 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Digits',
                        'options' => array(
                            'min' => 100,
                            'max' => 99999,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'minimum_salary',
                'description' => '(int) (100 - 99999) Minimum salary, USD.',
            ),
            8 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'readiness_date',
                'description' => '(int), Unix Time
            Readiness date',
            ),
        ),
        'Api\\V1\\Rpc\\ProfileCvAvatarUpload\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\File\\IsImage',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\File\\MimeType',
                        'options' => array(
                            'mimeType' => 'image/jpeg,image/jpg,image/pjpeg,image/png',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'cv_avatar',
                'type' => 'Zend\\InputFilter\\FileInput',
                'description' => 'User Application CV avatar image file. Accepts file mimetypes: image/jpeg,image/jpg,image/pjpeg,image/png',
            ),
        ),
        'Api\\V1\\Rpc\\ProfileAvatarUpload\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\File\\IsImage',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\File\\MimeType',
                        'options' => array(
                            'mimeType' => 'image/jpeg,image/jpg,image/pjpeg,image/png',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'avatar',
                'type' => 'Zend\\InputFilter\\FileInput',
                'description' => 'User Main avatar image file. Accepts file mimetypes: image/jpeg,image/jpg,image/pjpeg,image/png',
            ),
        ),
        'Api\\V1\\Rpc\\PicsUpload\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\File\\IsImage',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\File\\MimeType',
                        'options' => array(
                            'mimeType' => 'image/jpeg,image/jpg,image/pjpeg,image/png',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'pic',
                'type' => 'Zend\\InputFilter\\FileInput',
                'description' => 'image file. Accepts file mimetypes: image/jpeg,image/jpg,image/pjpeg,image/png',
            ),
        ),
        'Api\\V1\\Rpc\\PicsArticleAttach\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[0-9][,]?[0-9]+$/',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StringTrim',
                        'options' => array(
                            'charlist' => ',',
                        ),
                    ),
                ),
                'name' => 'pics_ids',
                'description' => 'Coma separated ids of the pics that have to be attached to post. Maximum 10 pics can be attached',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\SectionNameValidator',
                    ),
                ),
                'filters' => array(),
                'name' => 'section',
                'description' => '(varchar) 
                Article section, refer to the _sections_object ',
            ),
            2 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'section_id',
                'description' => '(int) 
                Article id',
            ),
        ),
        'Api\\V1\\Rpc\\PicsArticleRemove\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[0-9][,]?[0-9]+$/',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StringTrim',
                        'options' => array(
                            'charlist' => ',',
                        ),
                    ),
                ),
                'name' => 'pics_ids',
                'description' => 'Coma separated ids of the pics that have to be removed from post. If no ids sent all pics would be removed.',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\SectionNameValidator',
                    ),
                ),
                'filters' => array(),
                'name' => 'section',
                'description' => '(varchar) 
                Article section, refer to the _sections_object ',
            ),
            2 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'section_id',
                'description' => '(int) 
                Article id',
            ),
        ),
        'Api\\V1\\Rpc\\PicsGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                        Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_pics_fields',
                'description' => 'Coma separated names of pics fields.',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int) 
            Filter by pic id',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[0-9][,]?[0-9]+$/',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StringTrim',
                        'options' => array(
                            'charlist' => ',',
                        ),
                    ),
                ),
                'name' => 'pics_ids',
                'description' => '(varchar) 
                Filter results by the coma separated list of picture ids. Only pics with current ids would be shown',
            ),
            5 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'user_id',
                'description' => '(int) 
            Filter by owner id',
            ),
            6 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\SectionNameValidator',
                    ),
                ),
                'filters' => array(),
                'name' => 'section',
                'description' => '(varchar) 
                Filter results by Article section, refer to the _sections_object ',
            ),
            7 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'section_id',
                'description' => '(int) 
                Filter results by Article id. If article id is used for filtering, `section` must also be provided ',
            ),
        ),
        'Api\\V1\\Rpc\\ProfileDocsAdd\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'title',
                'description' => '(string), max 255 chars,
            Document title',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'number',
                'description' => '(string), max 32 chars,
            Document number',
            ),
            2 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Digits',
                        'options' => array(
                            'min' => 1,
                            'max' => 2,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'type',
                'description' => '(digits), 1 - 2
            Document type: 1 - Passport, 2 - Certificate',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '64',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'grade',
                'description' => '(string), max 64 chars,
            Document grade',
            ),
            4 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'issue_date',
                'description' => '(int), Unix Time
            Document issue date',
            ),
            5 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'expiry_date',
                'description' => '(int), Unix Time
            Document expiry date',
            ),
            6 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '30',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'issue_place',
                'description' => '(string), max 30 chars,
            Place of issue',
            ),
        ),
        'Api\\V1\\Rpc\\ProfileDocsEdit\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user_documents',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Document record id',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'title',
                'description' => '(string), max 255 chars,
            Document title',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'number',
                'description' => '(string), max 32 chars,
            Document number',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Digits',
                        'options' => array(
                            'min' => 1,
                            'max' => 2,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'type',
                'description' => '(digits), 1 - 2
            Document type: 1 - Passport, 2 - Certificate',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '64',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'grade',
                'description' => '(string), max 64 chars,
            Document grade',
            ),
            5 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'issue_date',
                'description' => '(int), Unix Time
            Document issue date',
            ),
            6 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'expiry_date',
                'description' => '(int), Unix Time
            Document expiry date',
            ),
            7 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '30',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'issue_place',
                'description' => '(string), max 30 chars,
            Place of issue',
            ),
        ),
        'Api\\V1\\Rpc\\ProfileDocsRemove\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user_documents',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Document id',
            ),
        ),
        'Api\\V1\\Rpc\\ProfileExperienceAdd\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                    1 => array(
                        'name' => 'Alnum',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'ship_name',
                'description' => '(string), max 32
            Ship Name',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'date_from',
                'description' => '(int) unix timestamp
                    Date when contract begin',
            ),
            2 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'date_to',
                'description' => '(int) unix timestamp
                    Date when contract completed',
            ),
            3 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                    1 => array(
                        'name' => 'Alnum',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'rank',
                'description' => '(string), max 32
            Rank',
            ),
            4 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                    1 => array(
                        'name' => 'Alnum',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'flag',
                'description' => '(string), max 32
            Ship Flag',
            ),
            5 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                    1 => array(
                        'name' => 'Alnum',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'type',
                'description' => '(string), max 32
            Ship Type',
            ),
            6 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Digits',
                        'options' => array(
                            'min' => 1900,
                            'max' => 2020,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'ship_built',
                'description' => '(digits), year 1900 - 2020
            Ship Built',
            ),
            7 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'dwt',
                'description' => '(int), 10
            Ship Size / Dwt',
            ),
            8 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'grt',
                'description' => '(int), 10
            Ship Size / Gross Tonnage',
            ),
            9 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'bhp',
                'description' => '(int), 10
            Engine Horse Powers (Kilowats)',
            ),
            10 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                    1 => array(
                        'name' => 'Alnum',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'company',
                'description' => '(string), max 255
            Company/Owner/Crewing agency',
            ),
            11 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '1000',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'trading_area',
                'description' => '(string), max 1000 chars
            Trading area, ports visited during contract',
            ),
            12 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '1000',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'text',
                'description' => '(string), max 1000 chars
            Any additional information about this contract',
            ),
        ),
        'Api\\V1\\Rpc\\ProfileExperienceRemove\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user_experience',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Experience record id',
            ),
        ),
        'Api\\V1\\Rpc\\ProfileExperienceEdit\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user_experience',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Experience record id',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                    1 => array(
                        'name' => 'Alnum',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'ship_name',
                'description' => '(string), max 32
            Ship Name',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'date_from',
                'description' => '(int) unix timestamp
                    Date when contract begin',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'date_to',
                'description' => '(int) unix timestamp
                    Date when contract completed',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                    1 => array(
                        'name' => 'Alnum',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'rank',
                'description' => '(string), max 32
            Rank',
            ),
            5 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                    1 => array(
                        'name' => 'Alnum',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'flag',
                'description' => '(string), max 32
            Ship Flag',
            ),
            6 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '32',
                        ),
                    ),
                    1 => array(
                        'name' => 'Alnum',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'type',
                'description' => '(string), max 32
            Ship Type',
            ),
            7 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Digits',
                        'options' => array(
                            'min' => 1900,
                            'max' => 2020,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'ship_built',
                'description' => '(digits), year 1900 - 2020
            Ship Built',
            ),
            8 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'dwt',
                'description' => '(int), 10
            Ship Size / Dwt',
            ),
            9 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'grt',
                'description' => '(int), 10
            Ship Size / Gross Tonnage',
            ),
            10 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'bhp',
                'description' => '(int), 10
            Engine Horse Powers (Kilowats)',
            ),
            11 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                    1 => array(
                        'name' => 'Alnum',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'company',
                'description' => '(string), max 255
            Company/Owner/Crewing agency',
            ),
            12 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '1000',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'trading_area',
                'description' => '(string), max 1000 chars
            Trading area, ports visited during contract',
            ),
            13 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '1000',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'text',
                'description' => '(string), max 1000 chars
            Any additional information about this contract',
            ),
        ),
        'Api\\V1\\Rpc\\ProfilePassForgot\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '64',
                        ),
                    ),
                    1 => array(
                        'name' => 'EmailAddress',
                        'options' => array(
                            'domain' => true,
                            'messages' => array(
                                'emailAddressInvalidFormat' => 'Please check your e-mail address',
                            ),
                        ),
                    ),
                    2 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user',
                            'field' => 'email',
                            'messages' => array(
                                'recordFound' => 'User with such E-mail already not found in database.',
                            ),
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StripTags',
                    ),
                    1 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'email',
                'description' => '(string) (max 255) User email',
            ),
        ),
        'Api\\V1\\Rpc\\ProfilePassReset\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'user',
                            'field' => 'password_reset_key',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'password_reset_key',
                'description' => '(varchar) 
                Confirmation key sent to user email during /profile.forgot-pass action ',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 5,
                            'max' => 32,
                            'messages' => array(
                                'stringLengthInvalid' => 'Password length should be from 5 to 32 chars',
                            ),
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'StringTrim',
                    ),
                ),
                'name' => 'password',
                'description' => '(string) (max 32) Change user password',
            ),
        ),
        'Api\\V1\\Rpc\\QanswersToggleAccept\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'question_answers',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Answer id',
            ),
        ),
        'Api\\V1\\Rpc\\VideosAdd\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\SectionNameValidator',
                    ),
                ),
                'filters' => array(),
                'name' => 'section',
                'description' => '(varchar) 
                Article section, refer to the _sections_object ',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'section_id',
                'description' => '(int) 
                Article id',
            ),
            2 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Application\\Validator\\YoutubeIdValidator',
                    ),
                ),
                'filters' => array(
                    1 => array(
                        'name' => '\\Application\\Filter\\ParseYoutubeLink',
                        'options' => array(
                            'format' => 'link',
                        ),
                    ),
                ),
                'name' => 'url',
                'description' => '(string), max 255 chars,
            Youtube video link',
                'allow_empty' => true,
                'continue_if_empty' => true,
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'title',
                'description' => '(string), max 255 chars,
            Video title',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '3600',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'description',
                'description' => '(text), max 3600 chars,
            Video description',
            ),
        ),
        'Api\\V1\\Rpc\\LinksAdd\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\SectionNameValidator',
                    ),
                ),
                'filters' => array(),
                'name' => 'section',
                'description' => '(varchar) 
                Article section, refer to the _sections_object ',
            ),
            1 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'section_id',
                'description' => '(int) 
                Article id',
            ),
            2 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Application\\Validator\\UrlValidator',
                        'options' => array(
                            'strict' => 0,
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Application\\Filter\\UrlFilter',
                    ),
                ),
                'name' => 'url',
                'description' => '(string), max 255 chars,
            Url',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'max' => '255',
                        ),
                    ),
                ),
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                        'options' => array(),
                    ),
                ),
                'name' => 'title',
                'description' => '(string), max 255 chars,
            Link title',
            ),
        ),
        'Api\\V1\\Rpc\\VideosGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                        Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_videos_fields',
                'description' => 'Coma separated names of videos fields.',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int) 
            Filter by video id',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'user_id',
                'description' => '(int) 
            Filter by owner id',
            ),
            5 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\SectionNameValidator',
                    ),
                ),
                'filters' => array(),
                'name' => 'section',
                'description' => '(varchar) 
                Filter results by Article section, refer to the _sections_object ',
            ),
            6 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'section_id',
                'description' => '(int) 
                Filter results by Article id. If article id is used for filtering, `section` must also be provided ',
            ),
        ),
        'Api\\V1\\Rpc\\LinksGet\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                        Limit for the results quantity per page',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_links_fields',
                'description' => 'Coma separated names of links fields.',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int) 
            Filter by link id',
            ),
            4 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'user_id',
                'description' => '(int) 
            Filter by Owner id',
            ),
            5 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => '\\Application\\Validator\\SectionNameValidator',
                    ),
                ),
                'filters' => array(),
                'name' => 'section',
                'description' => '(varchar) 
                Filter results by Article section, refer to the _sections_object ',
            ),
            6 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => 'section_id',
                'description' => '(int) 
                Filter results by Article id. If article id is used for filtering, `section` must also be provided ',
            ),
        ),
        'Api\\V1\\Rpc\\VacanciesGetSubscribers\\Validator' => array(
            0 => array(
                'required' => true,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => '\\Application\\Validator\\DbRecordExists',
                        'options' => array(
                            'table' => 'vacancies',
                            'field' => 'id',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => 'id',
                'description' => '(int),
                    Vacancy id',
            ),
            1 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-z][,]?[a-z]+$/',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_user_fields',
                'description' => 'Coma separated name of fields returned by the request. Acceptable fields are:
                    refer to _user_fields object',
            ),
            2 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                    1 => array(
                        'name' => 'Zend\\Validator\\LessThan',
                        'options' => array(
                            'max' => '101',
                        ),
                    ),
                ),
                'filters' => array(),
                'name' => '_limit',
                'description' => '(int),
                    Limit for the results quantity per page',
            ),
            3 => array(
                'required' => false,
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\IsInt',
                        'options' => array(),
                    ),
                ),
                'filters' => array(),
                'name' => '_page',
                'description' => '(int) Page of the collection.',
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'template_map' => array(
            'layout/api' => __DIR__ . '/../view/layout/api.phtml',
            'layout/api-doc' => __DIR__ . '/../view/layout/api-doc.phtml',
            'api/error/404' => __DIR__ . '/../view/error/404.phtml',
            'api/error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            'api' => __DIR__ . '/../view',
        ),
    ),
    'zf-rest' => array(),
    'zf-hal' => array(
        'metadata_map' => array(),
    ),
    'zf-mvc-auth' => array(
        'authorization' => array(
            'Api\\V1\\Rpc\\QuestionsAdd\\Controller' => array(
                'actions' => array(
                    'QuestionsAdd' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\QuestionsGet\\Controller' => array(
                'actions' => array(
                    'QuestionsGet' => array(
                        'GET' => false,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\UsersGet\\Controller' => array(
                'actions' => array(
                    'UsersGet' => array(
                        'GET' => false,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\LogbooksGet\\Controller' => array(
                'actions' => array(
                    'LogbooksGet' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\LogbooksAdd\\Controller' => array(
                'actions' => array(
                    'LogbooksAdd' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\VacanciesGet\\Controller' => array(
                'actions' => array(
                    'VacanciesGet' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\VacanciesAdd\\Controller' => array(
                'actions' => array(
                    'VacanciesAdd' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\NewsGet\\Controller' => array(
                'actions' => array(
                    'NewsGet' => array(
                        'GET' => false,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\QanswersGet\\Controller' => array(
                'actions' => array(
                    'QanswersGet' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\QanswersAdd\\Controller' => array(
                'actions' => array(
                    'QanswersAdd' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\CommentsGet\\Controller' => array(
                'actions' => array(
                    'CommentsGet' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\CommentsAdd\\Controller' => array(
                'actions' => array(
                    'CommentsAdd' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ContactsGet\\Controller' => array(
                'actions' => array(
                    'ContactsGet' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ContactsAdd\\Controller' => array(
                'actions' => array(
                    'ContactsAdd' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ContactsRemove\\Controller' => array(
                'actions' => array(
                    'ContactsRemove' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\LikesGet\\Controller' => array(
                'actions' => array(
                    'LikesGet' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\LikesAdd\\Controller' => array(
                'actions' => array(
                    'LikesAdd' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\LikesRemove\\Controller' => array(
                'actions' => array(
                    'LikesRemove' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\LikesIsLiked\\Controller' => array(
                'actions' => array(
                    'LikesIsLiked' => array(
                        'GET' => true,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\UserExperienceGet\\Controller' => array(
                'actions' => array(
                    'UserExperienceGet' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\LogbooksRemove\\Controller' => array(
                'actions' => array(
                    'LogbooksRemove' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\VacanciesRemove\\Controller' => array(
                'actions' => array(
                    'VacanciesRemove' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\QuestionsRemove\\Controller' => array(
                'actions' => array(
                    'QuestionsRemove' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\QanswersRemove\\Controller' => array(
                'actions' => array(
                    'QanswersRemove' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\CommentsRemove\\Controller' => array(
                'actions' => array(
                    'CommentsRemove' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\LinksRemove\\Controller' => array(
                'actions' => array(
                    'LinksRemove' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\VideosRemove\\Controller' => array(
                'actions' => array(
                    'VideosRemove' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\UserDocsGet\\Controller' => array(
                'actions' => array(
                    'UserDocsGet' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\NotificationsGet\\Controller' => array(
                'actions' => array(
                    'NotificationsGet' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\SiteInfoGet\\Controller' => array(
                'actions' => array(
                    'SiteInfoGet' => array(
                        'GET' => false,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\UserRatingGet\\Controller' => array(
                'actions' => array(
                    'UserRatingGet' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\TagsGet\\Controller' => array(
                'actions' => array(
                    'TagsGet' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ChatsGetList\\Controller' => array(
                'actions' => array(
                    'ChatsGetList' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ChatsGetChat\\Controller' => array(
                'actions' => array(
                    'ChatsGetChat' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ChatsSendMessage\\Controller' => array(
                'actions' => array(
                    'ChatsSendMessage' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ChatsDeleteMessage\\Controller' => array(
                'actions' => array(
                    'ChatsDeleteMessage' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\CommentsEdit\\Controller' => array(
                'actions' => array(
                    'CommentsEdit' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\QuestionsEdit\\Controller' => array(
                'actions' => array(
                    'QuestionsEdit' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\LogbooksEdit\\Controller' => array(
                'actions' => array(
                    'LogbooksEdit' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\VacanciesEdit\\Controller' => array(
                'actions' => array(
                    'VacanciesEdit' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\QanswersEdit\\Controller' => array(
                'actions' => array(
                    'QanswersEdit' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\QuestionsChangeRating\\Controller' => array(
                'actions' => array(
                    'QuestionsChangeRating' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\QanswersChangeRating\\Controller' => array(
                'actions' => array(
                    'QanswersChangeRating' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\CommentsChangeRating\\Controller' => array(
                'actions' => array(
                    'CommentsChangeRating' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\VacanciesToggleSubscribe\\Controller' => array(
                'actions' => array(
                    'VacanciesToggleSubscribe' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\VacanciesToggleReport\\Controller' => array(
                'actions' => array(
                    'VacanciesToggleReport' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\QuestionsToggleSubscribe\\Controller' => array(
                'actions' => array(
                    'QuestionsToggleSubscribe' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\UsersInfoUnlock\\Controller' => array(
                'actions' => array(
                    'UsersInfoUnlock' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ProfileGet\\Controller' => array(
                'actions' => array(
                    'ProfileGet' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ProfileEdit\\Controller' => array(
                'actions' => array(
                    'ProfileEdit' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ProfileAvatarRemove\\Controller' => array(
                'actions' => array(
                    'ProfileAvatarRemove' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => true,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ProfileAvatarUpload\\Controller' => array(
                'actions' => array(
                    'ProfileAvatarUpload' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ProfileCvAvatarUpload\\Controller' => array(
                'actions' => array(
                    'ProfileCvAvatarUpload' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ProfileCvAvatarRemove\\Controller' => array(
                'actions' => array(
                    'ProfileCvAvatarRemove' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => true,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\PicsUpload\\Controller' => array(
                'actions' => array(
                    'PicsUpload' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\PicsArticleAttach\\Controller' => array(
                'actions' => array(
                    'PicsArticleAttach' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\PicsArticleRemove\\Controller' => array(
                'actions' => array(
                    'PicsArticleRemove' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\PicsGet\\Controller' => array(
                'actions' => array(
                    'PicsGet' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ProfileMenuGet\\Controller' => array(
                'actions' => array(
                    'ProfileMenuGet' => array(
                        'GET' => false,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ProfileDocsAdd\\Controller' => array(
                'actions' => array(
                    'ProfileDocsAdd' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ProfileDocsEdit\\Controller' => array(
                'actions' => array(
                    'ProfileDocsEdit' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ProfileDocsRemove\\Controller' => array(
                'actions' => array(
                    'ProfileDocsRemove' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ProfileExperienceAdd\\Controller' => array(
                'actions' => array(
                    'ProfileExperienceAdd' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ProfileExperienceRemove\\Controller' => array(
                'actions' => array(
                    'ProfileExperienceRemove' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\ProfileExperienceEdit\\Controller' => array(
                'actions' => array(
                    'ProfileExperienceEdit' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\QanswersToggleAccept\\Controller' => array(
                'actions' => array(
                    'QanswersToggleAccept' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\VideosAdd\\Controller' => array(
                'actions' => array(
                    'VideosAdd' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\LinksAdd\\Controller' => array(
                'actions' => array(
                    'LinksAdd' => array(
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\VideosGet\\Controller' => array(
                'actions' => array(
                    'VideosGet' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\LinksGet\\Controller' => array(
                'actions' => array(
                    'LinksGet' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
            'Api\\V1\\Rpc\\VacanciesGetSubscribers\\Controller' => array(
                'actions' => array(
                    'VacanciesGetSubscribers' => array(
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ),
                ),
            ),
        ),
    ),
);
