<?php
namespace Application\Adapter;

use Zend\Db\Adapter\Adapter as zAdapter;

class Adapter extends zAdapter
{
	public function __construct()
	{
		$config = include __DIR__ .'/../../../../../config/autoload/db.local.php';
		parent::__construct($config['db']);
	}
}