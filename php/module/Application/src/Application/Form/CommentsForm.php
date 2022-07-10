<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class CommentsForm extends EmptyForm 
{

	public function __construct()
	{
		parent::__construct('auth');

		$sl = \ServiceLocatorFactory\ServiceLocatorFactory::getInstance();

		$this->add($sl->get('CommentsFields'));
		$this->addSubmit($this->translate('Add Comment'));
	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}


}