<?php

namespace UserInfo\Navigation\Service;



class UsersNavigationFactory extends \Zend\Navigation\Service\AbstractNavigationFactory
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'user_navigation';
    }
}
