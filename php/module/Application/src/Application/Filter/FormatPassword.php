<?php
namespace Application\Filter;

use Zend\Filter\FilterInterface;

class FormatPassword implements FilterInterface 
{
	public function filter($string)
	{	
		if($string) {
			$sl = \ServiceLocatorFactory\ServiceLocatorFactory::getInstance();
			$string = $sl->get('salt')->format($string);
		}
		return $string;
	}

}

