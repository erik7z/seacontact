<?php
namespace Application\Model;

class SocialPublicsTable extends zEmptyTable
{
	CONST PUBLISH_FROM_ME = 'me';
	CONST PUBLISH_FROM_AUTHOR = 'author';

	public function __construct()
	{
		$this->init('social_publics');
	}

}
