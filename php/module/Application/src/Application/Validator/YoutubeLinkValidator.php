<?php
namespace Application\Validator;

use Zend\Validator\AbstractValidator;



class YoutubeLinkValidator extends \Zend\Validator\AbstractValidator
{
    const INVALID      = 'linkInvalid';
    const STRING_EMPTY = 'StringEmpty';

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
        self::INVALID      => "Youtube link is invalid",
        self::STRING_EMPTY => "The input is empty",
    );


    /**
     * Sets default option values for this instance
     *
     * @param bool
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

        if(!preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/i' , $value)) {
            $this->error(self::INVALID);
            return false;
        }

        return true;
    }
}
