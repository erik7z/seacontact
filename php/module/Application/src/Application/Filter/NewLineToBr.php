<?php
namespace Application\Filter;

use Zend\Filter\FilterInterface;

class NewLineToBr implements FilterInterface 
{

	public function filter($text)
	{	
		$text2 = strip_tags($text);
		// check if text contains html tags, if yes means text is not plain and no convertions needed
		if($text == $text2) {
			$text = str_replace(array("\n","\r"),'',nl2br($text)) ;
		}

		return $text;
	}

}

