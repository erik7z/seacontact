<?php
namespace Application\Validator;

use Zend\Validator;
use Zend\Validator\AbstractValidator;



class EmailFieldValidator extends \Zend\Validator\AbstractValidator
{
    const INVALID      = 'emailInvalid';

    protected $messageTemplates = array(
        self::INVALID      => "Email address invalid",
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
        $validator = new Validator\EmailAddress();
        if(!is_array($value)) {
            $value = rtrim(str_replace(' ', '', $value),',');
            if(strpos($value, ',') !== false) $value = explode(',', $value);
        } 

        if(is_array($value)) {
            for ($i=0; $i < count($value); $i++) { 
                if(!$validator->isValid($value[$i])){
                    $this->error(self::INVALID);
                   return false;
                }
            }
        } else {
            if(!$validator->isValid($value)){
                $this->error(self::INVALID);
               return false;
            }
        }
        return true;
    }
}
