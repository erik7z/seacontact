<?php

define('_PUBLICROOT_', $_SERVER['DOCUMENT_ROOT'].'/');
define('_ROOT_', __DIR__.'/../../');
define('_CRONDIR_', _ROOT_.'cron/');
define('_ADDRESS_', 'https://'.getenv('SEA_DOMAIN').'/');
define('_ADDRESS_NO_SLASH_', 'https://'.getenv('SEA_DOMAIN'));
define('_RUADDRESS_', 'http://www.ru.'.getenv('SEA_DOMAIN').'/');
define('_ENADDRESS_', 'http://www.en.'.getenv('SEA_DOMAIN').'/');
define('_ADMINADRESS_', 'http://admin.'.getenv('SEA_DOMAIN'));
define('_UPLOADROOT_', __DIR__.'/../../public_html/uploads/');
define('_IMGROOT_', __DIR__.'/../../public_html/img/');
define('_PICSROOT_',  __DIR__.'/../../public_html/uploads/pics/');
define('_FILESROOT_',  __DIR__.'/../../public_html/uploads/files/');
define('_MAILSROOT_',  __DIR__.'/../../public_html/uploads/mails/');

define('_AVABLANKUSER_', 'ava_blank.png');
define('_AVABLANKCOMPANY_', 'company_ava.jpg');
define('_ANONYM_AVA_', 'anonym_ava.png');
define('_IMGWWW_', 'img/');
define('_PICSWWW_', 'uploads/pics/');
define('_FILESWWW_', 'uploads/files/');
define('_MAILSWWW_', 'uploads/mails/');

define('_SITENAME_', 'www.'.getenv('SEA_DOMAIN'));
define('_RUSITENAME_', 'www.ru.'.getenv('SEA_DOMAIN'));
define('_ENSITENAME_', 'www.en.'.getenv('SEA_DOMAIN'));
define('_SITEMAIL_', 'admin@seacontact.ru');
define('_SITEADMIN_', 'Seacontact');

define('_COMPANY_ID_', 39296);
define('_COMPANY_ADDRESS_', 'Odessa, Ukraine');

define('_CREWING_ID_', 39296);


define('_SOCIAL_VK_', 'https://vk.com/club_seacontact');
define('_SOCIAL_FB_', 'https://www.facebook.com/groups/seacontact/');
define('_SOCIAL_IN_', 'https://www.linkedin.com/company/seacontact');

define('_REDIS_IP_', getenv('_REDIS_IP_'));
define('_REDIS_PORT_', getenv('_REDIS_PORT_'));

define('_MBOFFICE_', getenv('_MBOFFICE_'));
define('_MBOFFICEPWD_', getenv('_MBOFFICEPWD_'));

define('_MBCREW_', getenv('_MBCREW_'));
define('_MBCREWPWD_', getenv('_MBCREWPWD_'));

// OAUTH ID
define('_IN_APP_ID_', getenv('_IN_APP_ID_'));
define('_IN_APP_SECRET_', getenv('_IN_APP_SECRET_'));

define('_FB_APP_ID_', getenv('_FB_APP_ID_'));
define('_FB_APP_SECRET_', getenv('_FB_APP_SECRET_'));

define('_VK_APP_ID_', getenv('_VK_APP_ID_'));
define('_VK_APP_SECR_KEY_', getenv('_VK_APP_SECR_KEY_'));
define('_VK_APP_CODE_', getenv('_VK_APP_CODE_'));

define('_VK_2APP_ID_', getenv('_VK_2APP_ID_'));
define('_VK_2APPSECR_KEY_', getenv('_VK_2APPSECR_KEY_'));


// VK wall publication
define('_VK_USER_', getenv('_VK_USER_'));
define('_VK_USER_SECRET_', getenv('_VK_USER_SECRET_'));
define('_VK_USER_TOKEN_', getenv('_VK_USER_TOKEN_'));
define('_VK_GROUP_', getenv('_VK_GROUP_'));

define('_GMAPS_KEY_', getenv('_GMAPS_KEY_'));
define('_SCRIPTS_VER_', '?v=4.8');
define('_DUMP_CACHE_EXP_', 3000);

define('_SOCIAL_PARSE_KEYWORD_', 'seacontact.com/');
define('_ONLINE_TIME_', 900);

define('_MAX_F_USERS_ONLINE_', 5);
define('_F_USER_ACT_TOTAL_TIME_', 60800); // not more than 9 hours
define('_F_USER_ACT_PERIOD_', 604800); // for last 7 days
define('_F_POSTS_ACT_PERIOD_', 2592000); // Posts within last 30 days to be liked
define('_PERIOD_MAX_LIKES_', 0); // max likes for period
define('_PERIOD_MAX_VOTES_', 0); // max votes for period
define('_PERIOD_MAX_VIEWS_', 0); // max views for period

define('_MAX_F_COMP_ONLINE_', 2);
define('_F_COMP_ACT_TOTAL_TIME_', 20800); // not more than 6 hours per
define('_PERIOD_MAX_MAILS_', 0); // max mails for period



define('_VIEW_COMMENT_LIMIT_', 10); // how many comments select with every post
define('_PAGINATION_VIEW_LIMIT_', 10); // how many items select with every pagination
define('_VIEW_LIKERS_LIMIT_', 20); // how many likers visible with every post

define('_MAX_VK_POST_PARSING_', 30); //
define('_MAX_VK_USERS_PARSING_', 10); //
define('_MAX_VK_COMMENTS_PARSING_', 10); //
define('_MAX_VK_LIKES_PARSING_', 20); //


define('_PICS_MAX_ATTACH_', 10); //


return array();
