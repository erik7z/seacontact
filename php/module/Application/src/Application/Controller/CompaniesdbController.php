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
use Application\Access\AccessList;

class CompaniesdbController extends AbstractController
{
    public function indexAction()
    {

        $success = 1;
        $message ='';
        try {

            $user_id = ($this->identity()) ? $this->identity()->id : 0;

            $q_options = $this->setDefaultOptions(['_limit' => 50, 'layout' => 'thumb']);

            $q_options['user_type'] = 'company';
            $q_options['show_not_confirmed'] = 0;
            $q_options['show_stats'] = 1;
            $q_options['_user_fields'] = ['id', 'id' => 'company_id', 'company_name','company_description', 'login','role', 'avatar', 'home_country', 'home_address', 'home_city', 'contact_phone', 'contact_mobile', 'email', 'contact_email', 'reg_date', 'last_activity'];
            $q_options['form_fields'] = ['name', 'home_city', 'home_country', 'home_address', 'online'];


            $filterFields = $this->get('UserFilterFields');
            $filterFields->remainFields($q_options['form_fields']);
            $filterFields->setup(['type_company' => 1]);
            $filterFields->get('name')->setLabel($this->translate('Company Name'))->setAttribute('placeholder', 'Marlow Navigation');
            $form = $this->get('EmptyForm');
            $form->add($filterFields);
            $form->add([
                'name' => 'layout',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => $this->translate('Layout'),
                    'options' => array(
                            'thumb' => $this->translate('Thumbs'),
                            'list' => $this->translate('List')
                        ),
                    ),
                ]);
            $form->add([
                'name' => '_limit',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => $this->translate('Results on page'),
                    'options' => array(
                            10 => 10,
                            50 => 50,
                            100 => 100,
                            500 => 500
                        ),
                    ),
                ]);
            $form->addSubmit($this->translate('Filter'));
            $form->setAttribute('method', 'get');
            $show_form = false;
            $form->addSubmit($this->translate('Filter'));
            $form->setAttribute('method', 'get');
            $show_form = false;
            $data = array_merge((array) $q_options, (array) $this->request->getQuery());
            $form->setData($data);
            if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 100);
            $data = $form->getData();
            $q_options['filters'] = array_filter($data['filters']);

        } catch (\Exception $e) {
            $message = ($e->getCode() == 100)? unserialize($e->getMessage()) : $e->getMessage();
            $show_form = true;
        }
        $userTable = $this->get('UserTable');
        $data_list = $userTable->getUsersList($user_id, $q_options['filters'], $q_options);
        $q_options['count'] = 1;
        $total_results = $userTable->getUsersList($user_id, $q_options['filters'], $q_options)->current()->count;

        $viewModel = 0;
        if(isset($form)) 
            $viewModel = new ViewModel(array(
                'form' => $form,
                'show_form' => $show_form,
                'data_list' => $data_list,
                'total_results' => $total_results,
                'q_options' => $q_options
                )); 

        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel, 
            'template' => 'application/companiesdb/index',
            'exception' => (isset($e)) ? $e : null
            ]);
    }

    
}
