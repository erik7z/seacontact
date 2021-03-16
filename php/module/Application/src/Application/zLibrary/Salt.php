<?php
namespace Application\zLibrary;

class Salt {

	protected $secret_word = 'zzzloyyy';

	public function __construct()
	{
		return $this;
	}

	public function getWord()
	{
		return $this->secret_word;
	}

	public function format($value)
	{
		return $this->secret_word.$value.$this->secret_word;
	}

	public function hash($value) {
		return md5($this->format($value));
	}

	public function salt($chars, $uppercase = false, $name = '') {
		for($i = 0; $i < $chars; $i++){
			$o = round((mt_rand(1,3)));
			if($o == 1) $char = chr(mt_rand(65, 90));
			if($o == 2) $char = mt_rand(1, 9);
			if($o == 3) $char = chr(mt_rand(97, 122));
			$name .= $char;
		}
		if($uppercase) $name = strtoupper($name);
	return $name;
	}

	public function regKey($chars, $uppercase = false, $name = '')
	{
		$validator = new \Zend\Validator\Db\NoRecordExists(
			 array(
			 'table' => 'user',
			 'field' => 'email_confirmation_key',
			 'adapter' => \Application\Model\zAbstractTable::getAdapter()
			 )
		 );
		do {
			$key = $this->salt($chars, $uppercase, $name);
		} while (!$validator->isValid($key));
		return $key;
	}

	public function resetKey($chars, $uppercase = false, $name = '')
	{
		$validator = new \Zend\Validator\Db\NoRecordExists(
			 array(
			 'table' => 'user',
			 'field' => 'password_reset_key',
			 'adapter' => \Application\Model\zAbstractTable::getAdapter()
			 )
		 );
		do {
			$key = $this->salt($chars, $uppercase, $name);
		} while (!$validator->isValid($key));
		return $key;
	}

	public function mail_id()
	{
		return (int)'9'.ceil(time()/4).mt_rand(0,9);
	}

}