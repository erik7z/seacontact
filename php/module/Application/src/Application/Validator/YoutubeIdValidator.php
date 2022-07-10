<?php
namespace Application\Validator;

use Zend\Validator\AbstractValidator;



class YoutubeIdValidator extends \Zend\Validator\AbstractValidator
{
    const INVALID      = 'idInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::INVALID      => "Youtube id is invalid",
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
        if (!$value) {
            $this->error(self::INVALID);
            return false;
        }

        // Some youtube api logic can be here
        // 
        // 

        return true;
    }
}
