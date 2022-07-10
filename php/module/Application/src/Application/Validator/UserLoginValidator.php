<?php
namespace Application\Validator;

use Zend\I18n\Filter\Alnum as AlnumFilter;
use Zend\Validator\AbstractValidator;



class UserLoginValidator extends \Zend\Validator\AbstractValidator
{
    const INVALID      = 'alnumInvalid';
    const STRING_EMPTY = 'alnumStringEmpty';

    /**
     * Alphanumeric filter used for validation
     *
     * @var AlnumFilter
     */
    protected static $filter = null;

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::INVALID      => "Invalid Characters only [A-Z, 0-9, _] are acceptable",
        self::STRING_EMPTY => "The input is empty",
    );


    /**
     * Sets default option values for this instance
     *
     * @param bool $allowWhiteSpace
     */
    public function __construct($options = null)
    {
        $options = is_array($options) ? $options : null;
        parent::__construct($options);

    }


    /**
     * Returns true if and only if $value contains only alphabetic and digit characters
     *
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->setValue($value);
        if ('' === $value) {
            $this->error(self::STRING_EMPTY);
            return false;
        }

        if(!preg_match('/^[a-z][a-z\d_]{2,20}$/i' , $value)) {
            $this->error(self::INVALID);
            return false;
        }

        return true;
    }
}
