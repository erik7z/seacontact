<?php
namespace Application\Filter;

use Zend\Filter\FilterInterface;

class ComaStringToArray implements FilterInterface 
{

	public function filter($value)
	{
		if(!$value) return $value;
		$value = trim(str_replace(' ', '', $value),',');
		if(strpos($value, ',') !== false) $value = explode(',', $value);
		else $value = [$value];
		return $value;
	}

}

