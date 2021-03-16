<?php

namespace Application\Validator;

use Zend\Validator\Db\RecordExists;
use Zend\Validator\Exception;

/**
 * Confirms a record exists in a table.
 */
class DbRecordExists extends RecordExists
{
    public function __construct($options = null)
    {
        $translator = \Application\Translator\StaticTranslator::getTranslator();
        $options['adapter'] = \Application\Model\zAbstractTable::getAdapter();
        parent::__construct($options);
    }

    public function isValid($value)
    {
        return parent::isValid($value);
    }
}
