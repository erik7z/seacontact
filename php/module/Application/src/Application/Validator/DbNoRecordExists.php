<?php

namespace Application\Validator;

use Zend\Validator\Db\NoRecordExists;
use Zend\Validator\Exception;

/**
 * Confirms a record exists in a table.
 */
class DbNoRecordExists extends NoRecordExists
{
    public function __construct($options = null)
    {
        $options['adapter'] = \Application\Model\zAbstractTable::getAdapter();
        parent::__construct($options);
    }

    public function isValid($value)
    {
        return parent::isValid($value);
    }
}
