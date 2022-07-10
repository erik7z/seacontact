<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class AuthForm extends EmptyForm 
{

	public function __construct()
	{
		parent::__construct('auth');

		$sl = \ServiceLocatorFactory\ServiceLocatorFactory::getInstance();

		$userFields = $sl->get('UserFields')->remainFields(array('login_or_email', 'password'));
		if($sl->get('ErrorLog')->count > 2) $this->add($sl->get('CaptchaField'));
		$this->add($userFields)->addSubmit($this->translate('Enter'));
	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}


}