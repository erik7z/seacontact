<?php
namespace Api;

use ZF\Apigility\Provider\ApigilityProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use ZF\MvcAuth\MvcAuthEvent;
use Api\V1\Access\Authorization;

class Module implements ApigilityProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'ZF\Apigility\Autoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
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
            $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'setLayout' ), 100);
            $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'handleError' ), 100);
            $eventManager->attach(MvcAuthEvent::EVENT_AUTHENTICATION_POST, new Authorization, 100);
        }

    }

    public function setLayout($e)
    {
        if($e->getRouteMatch()) {
            $controller = $e->getRouteMatch()->getParam('controller');
            if (strpos($controller, 'Api\V1\Index') !== false || strpos($controller, 'ZF\Apigility\Documentation') !== false) {
                $sm  = $e->getApplication()->getServiceManager();
                $viewModel = $sm->get('viewManager')->getViewModel();
                $viewModel->setTemplate('layout/api-doc');
            }

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

        // custom layout
        $sm  = $e->getApplication()->getServiceManager();
        $viewModel = $sm->get('viewManager')->getViewModel();
        $viewModel->setTemplate('layout/api');

        // change inner template:
        $exceptionstrategy = $sm->get('HttpExceptionStrategy');
        $notfoundstrategy = $sm->get('HttpRouteNotFoundStrategy');
        $exceptionstrategy->setExceptionTemplate('api/error/index');
        $notfoundstrategy->setNotFoundTemplate('api/error/404');
    }

}
