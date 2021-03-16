<?php
namespace Application\Filter;

use Zend\Filter\FilterInterface;

class StripHashTag implements FilterInterface 
{

	public function filter($text)
	{	
		return str_replace('#', '', $text);
	}

}

