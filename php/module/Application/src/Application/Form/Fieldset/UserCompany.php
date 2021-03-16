<?php
namespace Application\Form\Fieldset;

class UserCompany extends EmptyFieldset
{
	public function __construct()
	{
		parent::__construct('user_company');

		$this->add(array(
			'name' => 'id',
			'type' => 'Zend\Form\Element\Hidden',
			));

		$this->add(array(
			'name' => 'company_name',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Company Name',
				),
			));


		$this->add(array(
			'name' => 'date_from',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Date From',
				),
			));


		$this->add(array(
			'name' => 'date_to',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Date To',
				),
			));

	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}


}