<?php
return array(
	'module_layouts' => array(
		'Application' => 'layout/layout',
		'Admin' => 'layout/admin',
		'Api' => 'layout/api',
		),
	'GMaps'=> array(
	    'api_key' => getenv('_GMAPS_KEY_'),
	),
	'service_manager' => array(
	    'factories' => array(
	    	'Api\\V1\\Access\\ScPdoAdapter' => 'Api\\V1\\Access\\ScPdoAdapterFactory',
	        'Zend\\Db\\Adapter\\Adapter' => 'Zend\\Db\\Adapter\\AdapterServiceFactory',
	        // 'Zend\\Db\\Adapter\\Adapter' => function ($serviceManager) {
	        // 	$adapterFactory = new Zend\Db\Adapter\AdapterServiceFactory();
	        // 	$adapter = $adapterFactory->createService($serviceManager);
	        // 	\Zend\Db\TableGateway\Feature\GlobalAdapterFeature::setStaticAdapter($adapter);
	        // 	return $adapter;
	        // },
	    ),
	    'aliases' => array(
	        // 'Zend\\Authentication\\AuthenticationService' => 'ZF\\MvcAuth\\Authentication',
	    ),
	    'delegators' => array(
	        'ZF\MvcAuth\Authentication\DefaultAuthenticationListener' => array(
	            'Api\\V1\\Access\\ScAuthenticationAdapterDelegatorFactory',
	        ),
	    ),
	),
	'router' => array(
	    'routes' => array(
	        'oauth' => array(
	            'options' => array(
	                'spec' => '%oauth%',
	                'regex' => '(?P<oauth>(/oauth))',
	            ),
	            'type' => 'regex',
	        ),
	    ),
	),
	'zf-mvc-auth' => array(
	    'authentication' => array(
	        'adapters' => array(
	            'ScAuth' => array(
	                'adapter' => 'Api\\V1\\Access\\ScOAuth2Adapter',
	                'storage' => array(
	                    'storage' => 'Api\\V1\\Access\\ScPdoAdapter',
	                ),
	            ),
	        ),
	    ),
	),
	'zf-oauth2' => array(
	    'db' => array(
	        'dsn' => 'mysql:dbname='.getenv('_MYSQL_DB_NAME_').';host='.getenv('_MYSQL_HOST_'),
					'username' => getenv('_MYSQL_USER_'),
	        'password' => getenv('_MYSQL_PASSWORD_'),
	    ),
	    'allow_implicit' => false,
	    'access_lifetime' => 9999999,
	    'enforce_state' => true,
	    'storage' => 'Api\\V1\\Access\\ScPdoAdapter',
	),
);
