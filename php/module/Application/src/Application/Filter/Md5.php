<?php
namespace Application\Filter;

use Zend\Filter\FilterInterface;

class Md5 implements FilterInterface 
{

	public function filter($string)
	{	
		if($string) $string = md5($string);
		return $string;
	}

}

