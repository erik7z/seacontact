<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;


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
        if($e->getRequest() instanceof \Zend\Console\Request) {} 
        else {
            $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'handleError' ), 100);
        }

    }


    public function handleError($e)
    {
        if($e->getRouteMatch()) {
            $controller = $e->getRouteMatch()->getParam('controller');
            if (0 !== strpos($controller, __NAMESPACE__, 0)) {
                //if not this module
                return;
            }
        }


        //examples :

            // custom message and variables for inner template
            // $model = new \Zend\View\Model\ViewModel(array(
            //     'message' => 'some message',
            //     'reason' => $e->getError(),
            //     'exception' => $e->getError(),
            // ));
            // $model->setTemplate('error/404');
            // $e->getViewModel()->addChild($model)->setTemplate('layout/admin/error');
            // // custom status code
            // // $response = $e->getResponse();
            // // $response->setStatusCode(404);
            // $e->stopPropagation();
            // return $model;

            /*redirect*/
               // $response = $e->getResponse();
               // $response->setStatusCode(404);
               // $response->getHeaders()->addHeaderLine('Location', '/notfound');
               // $response->send();
               // exit(0);

            // custom layout
            $sm  = $e->getApplication()->getServiceManager();
            $viewModel = $sm->get('viewManager')->getViewModel();
            $viewModel->setTemplate('layout/admin/error');

            // change inner template:
            // $exceptionstrategy = $sm->get('HttpExceptionStrategy');
            // $notfoundstrategy = $sm->get('HttpRouteNotFoundStrategy');
            // $exceptionstrategy->setExceptionTemplate('admin/error/index');
            // $notfoundstrategy->setNotFoundTemplate('admin/error/404');
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
            'invokables' => array(
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
                // Forms
                    'UserFilterForm' => '\Admin\Form\UserFilterForm',
                ),

            'factories' => array(
            ),
			'services' => array(

                ),
			'shared' => array(),
		);
	}


}
