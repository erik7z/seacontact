<?php
namespace Application\Filter;

use Zend\Filter\FilterInterface;

class FormDateToUnix implements FilterInterface 
{

	public function filter($date)
	{
		if(is_array($date)) {
			$day = (isset($date['day']))? $date['day'] : '01';
			$month = (isset($date['month']))? $date['month'] : '01';
			return strtotime($day.'-'.$month.'-'.$date['year']);
		} else {
			if(strpos($date, '-')) return strtotime($date);
			else return $date; 
		}
	}

}

