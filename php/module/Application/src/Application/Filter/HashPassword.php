<?php
namespace Application\Filter;

use Zend\Filter\FilterInterface;

class HashPassword implements FilterInterface 
{
	public function filter($string)
	{	
		if($string) {
			$sl = \ServiceLocatorFactory\ServiceLocatorFactory::getInstance();
			$string = $sl->get('salt')->hash($string);
		}
		return $string;
	}

}

