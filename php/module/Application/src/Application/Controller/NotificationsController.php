<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class NotificationsController extends AbstractController
{
    public function indexAction()
    {
        $success = 1;
        $message = '';
        try {
            $order_by = $this->params()->fromQuery('order_by', 'time');
            $page = (int) $this->params()->fromQuery('_page', 1);
            $limit = (int) $this->params()->fromQuery('_limit', 20);
            $filters = $this->params()->fromQuery('filters') ? array_filter($this->params()->fromQuery('filters')) : [];
            if (!$this->identity()) throw new \Application\Exception\Exception("Authorisation Required", 401);
            $user_id = $this->identity()->id;

            $options = [
                '_limit' => $limit,
                '_page' => $page,
                '_order' => $order_by,
            ];
            $notifications = $this->get('UserNotificationsTable')->getNotifications($user_id, $filters, $options);
            $options['count'] = 1;
            $total_results = $this->get('UserNotificationsTable')->getNotifications($user_id, $filters, $options)->current()->count;
            $this->get('UserNotificationsTable')->updateLastView($user_id);
            // $this->get('UserTable')->refreshUserNotificationsLastView($user_id);

        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $viewModel = 0;
        if(isset($notifications)) 
            $viewModel = new ViewModel(array(
                'notifications' => $notifications,
                'filters' => $filters,
                'order_by' => $order_by,
                '_page' => $page,
                '_limit' => $limit,
                'total_results' => $total_results,
                )); 

        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel, 
            'template' => 'application/notifications/index',
            'exception' => (isset($e)) ? $e : null
            ]);
    }
    

}
