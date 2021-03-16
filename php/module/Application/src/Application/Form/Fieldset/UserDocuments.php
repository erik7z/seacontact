<?php
namespace Application\Form\Fieldset;

class UserDocuments extends EmptyFieldset
{
	public function __construct()
	{
		parent::__construct('user_documents');

		$this->add(array(
			'name' => 'id',
			'type' => 'Zend\Form\Element\Hidden',
			'options' => [
				'required' => false,
			]
			));

		$this->add(array(
			'name' => 'title',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Title'),
				'required' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				),
			));

		$this->add(array(
			'name' => 'number',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Document Number'),
				'required' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					),
				),
			));

		$this->add(array(
			'name' => 'type',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Document Type'),
				'empty_option' => '--Select--',
				'options' => array(
					1 => 'Passport',
					2 => 'Certificate',
					),
				'required' => true,
				),
			));

		$this->add(array(
			'name' => 'grade',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Grade / Class'),
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				),
			));

		$this->add(array(
			'name' => 'issue_date',
			'type' => 'Zend\Form\Element\Date',
			'options' => array(
				'label' => $this->translate('Issue Date').' (YYYY-MM-DD)',
				'required' => false,
				'validators' => array(
					array(
						'name'    => 'Date',
						'options' => array(
							'format' => 'Y-m-d',
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				'placeholder' => $this->translate('2017-12-31'),
				),
			));

		$this->add(array(
			'name' => 'expiry_date',
			'type' => 'Zend\Form\Element\Date',
			'options' => array(
				'label' => $this->translate('Expiry Date').' (YYYY-MM-DD)',
				'min_year' => 2015,
				'max_year' => 2030,
				'required' => false,
				'validators' => array(
					array(
						'name'    => 'Date',
						'options' => array(
							'format' => 'Y-m-d',
							),
						),
					),
				),
			'attributes' => array(
				'required' => 'required',
				),
			));


		$this->add(array(
			'name' => 'issue_place',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Place of issue'),
				'required' => false,
				),
			));
	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}


}