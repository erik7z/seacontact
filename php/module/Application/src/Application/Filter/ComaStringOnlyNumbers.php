<?php
namespace Application\Filter;

use Zend\Filter\FilterInterface;
use Zend\Filter\Exception;

class ComaStringOnlyNumbers implements FilterInterface 
{
	public function filter($value)
	{
		if(preg_match('/[0-9][,]?[0-9]+$/', $value)) return $value;
		return false;
	}

}

