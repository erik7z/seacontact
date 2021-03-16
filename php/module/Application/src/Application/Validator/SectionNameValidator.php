<?php
namespace Application\Validator;

use Zend\Validator;
use Zend\Validator\AbstractValidator;
use Application\Model\NewsTable;

class SectionNameValidator extends \Zend\Validator\AbstractValidator
{
    const INVALID      = 'sectionInvalid';

    protected $messageTemplates = array(
        self::INVALID      => "Unknown section provided",
    );


    public function __construct($options = null)
    {
        $options = is_array($options) ? $options : null;
        parent::__construct($options);

    }

    /**
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        $sections = implode('|', NewsTable::getSections());
        if(!preg_match("%$sections%i", $value)){
            $this->error(self::INVALID);
            return false;
        }
        return true;
    }
}
