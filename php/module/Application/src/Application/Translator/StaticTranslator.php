<?php
namespace Application\Translator;

use \Zend\I18n\Translator\Translator;


abstract class StaticTranslator
{

	public static $translator;



	public static function getTranslator() {
		if(!self::$translator) {
			self::$translator = new Translator;
		}
		return self::$translator;
	}

	public static function translate($string)
	{
		return self::getTranslator()->translate($string);
	}
}



