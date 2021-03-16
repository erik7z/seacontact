<?php
namespace CompanyInfo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class VacanciesController extends AbstractController
{

    
    /**
     * Index action of some controller
     * @return [type]
     */
    public function indexAction()
    {
        $company_login = $this->params()->fromRoute('user'); 
        $company = $this->get('UserTable')->getUserByLogin($company_login);
        if(!$company) throw new \Application\Exception\Exception($this->translate('Company with such login not found !', 404));

        return $this->forward()->dispatch('Application\Controller\Vacancies', array(
            'controller' => 'Application\Controller\Vacancies', 
            'action' => 'index',
            'dispatch' => true,
            'filters' => array(
                'company_id' => $company->id
                )
            )
        );
    }



}