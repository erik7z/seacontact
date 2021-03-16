<?php
namespace Application\Filter;

use Zend\Filter\FilterInterface;

class HashTag implements FilterInterface 
{

	public function filter($text)
	{	
		$array = explode(',', $text);
		for ($i=0; $i < count($array); $i++) { 
			$array[$i] = str_replace('_', 'underscoresymbol', $array[$i]);
			$array[$i] = trim(preg_replace('/[^\p{L}\p{N}\s,]/u', '', $array[$i]));
			$array[$i] = str_replace('underscoresymbol', '_', $array[$i]);
			$array[$i] = str_replace(' ', '_', $array[$i]);
			// if($i > 0) $array[$i] = ' '.$array[$i];
		}
		return implode(',' , $array);
	}

}

