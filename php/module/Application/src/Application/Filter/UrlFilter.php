<?php
namespace Application\Filter;

use Zend\Filter\FilterInterface;

class UrlFilter implements FilterInterface 
{

	public function filter($value)
	{	
		// if link text contain http in middle ??
		$value = ltrim(str_replace(['//', 'http', 'https', ':'], '', $value), '/');
		$value = 'http://'.$value;
		return $value;
	}

}

