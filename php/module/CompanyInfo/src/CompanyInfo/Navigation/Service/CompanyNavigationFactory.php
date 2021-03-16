<?php

namespace CompanyInfo\Navigation\Service;



class CompanyNavigationFactory extends \Zend\Navigation\Service\AbstractNavigationFactory
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'company_navigation';
    }
}
