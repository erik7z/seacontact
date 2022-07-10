<?php
namespace Application\Validator;

use Zend\Validator\AbstractValidator;



class UrlValidator extends \Zend\Validator\AbstractValidator
{
    const INVALID      = 'urlInvalid';
    const NOTRESPONDING      = 'urlNotResponding';
    const STRING_EMPTY = 'StringEmpty';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::INVALID      => "Url probably invalid",
        self::NOTRESPONDING  => "Website with such url not found or not responding",
        self::STRING_EMPTY => "The input is empty",
    );

    /**
     * @var array
     */
    protected $options = [
        'strict' => false,
    ];

    /**
     * Sets default option values for this instance
     *
     * @param array|Traversable|bool|null $strict
     */
    public function __construct($strict = false)
    {
        if ($strict !== null) {
            if (is_array($strict)) {
                parent::__construct($strict);
            } else {
                $this->setStrict($strict);
                parent::__construct();
            }
        }
    }

    /**
     * Sets  option
     *
     * @param  bool $flag
     * @return  Provides a fluent interface
     */
    public function setStrict($strict = false)
    {
        $this->options['strict'] = $strict;
        return $this;
    }

    /**
     * 
     *
     * @return bool
     */
    public function getStrict()
    {
        return $this->options['strict'];
    }

    /**
     * Returns true if and only if url correct
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
        // $urlReg = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        // $urlReg = "$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i";
        // $urlReg = "#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i"; //not bad
        // $urlReg = "/((http|https)\:\/\/)?[a-zA-Z0-9\.\/\?\:@\-_=#]+\.([a-zA-Z0-9\&\.\/\?:@\-_=#])*/"; // best
        // #((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i
        $urlReg = "/((http|https)\:\/\/)?[a-zA-Z0-9\.\/\?\:@\-_=#]+\.[a-zA-Z]{2,3}(\/\S*)?/"; // best2
        if(!preg_match($urlReg , $value)) {
            $this->error(self::INVALID);
            return false;
        }

        if($this->options['strict']) {
             if (@!get_headers($value, 1)) {
                $this->error(self::NOTRESPONDING);
                return false;
            }
        }
        return true;
    }
}
