<?php

namespace Admin\Navigation\Service;



class AdminNavigationFactory extends \Zend\Navigation\Service\AbstractNavigationFactory
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'admin_nav';
    }
}
