<?php
namespace Application\Validator;

use Zend\I18n\Filter\Alnum as AlnumFilter;
use Zend\Validator\AbstractValidator;



class FormDateInFuture extends \Zend\Validator\AbstractValidator
{
    const CONVERT_TO_UNIX      = 'convertToUnix';
    const ONLY_FUTURE      = 'onlyFuture';

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
        self::CONVERT_TO_UNIX      => "Only Unix Format Date Is acceptable use FormDateToUnix Filter",
        self::ONLY_FUTURE      => "Dates Only In Future Are Acceptable",
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
        if(is_array($value)) {
             $this->error(self::CONVERT_TO_UNIX);
             return false;
        }

        if (time() > $value) {
            $this->error(self::ONLY_FUTURE);
            return false;
        }


        return true;
    }
}
