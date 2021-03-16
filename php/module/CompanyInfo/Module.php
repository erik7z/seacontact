<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CompanyInfo;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;



class Module implements AutoloaderProviderInterface
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
                ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                    ),
                ),
            );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $sharedEventManager = $eventManager->getSharedManager();
                
        $sharedEventManager->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, function($e){

                // $acl = new \Users\Access\Access();
                // $acl->initOnLoad($e);

                // if(!$acl->checkUserPage()) {
                    // $controller = $e->getTarget();
                    // return $controller->notFoundAction();
                    // throw new \Application\Exception\Exception("User Page Not Found", 1);
                // }

                // if(!$acl->getAccess()) {
                //  $error = $acl->getError();
                //     // $controller = $e->getTarget();
                //     // $controller->flashMessenger()->addErrorMessage($error);
                //     // $controller->redirect()->toRoute('sc/home');
                //  throw new \Application\Exception\Exception("You cannot access this page", 1);

                // }          

            });


    }

    public function getControllerPluginConfig()
    {
        return array(
             'factories' => array(
                
                ),
            );
    }


    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                ),
            );
    }

    public function getServiceConfig()
    {
        return array(
            'abstract_factories' => array(),
            'aliases' => array(

                ),
            'invokables' =>array(

                ),

            'factories' => array(

            ),
			'services' => array(

                ),
			'shared' => array(),
		);
	}


}
