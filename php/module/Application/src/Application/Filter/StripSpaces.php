<?php
namespace Application\Filter;

use Zend\Filter\FilterInterface;

class StripSpaces implements FilterInterface 
{

	public function filter($text)
	{	
		return str_replace(' ', '', $text);
	}

}

