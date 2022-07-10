<?php
namespace Application\Model;

class TagsTable extends zAbstractTable
{

	public function __construct()
	{
		$this->init('tags');
	}

	// returns some class CONSTANT
	protected function con($const)
	{
		return constant($const);
	}


}