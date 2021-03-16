<?php
namespace Application\Form\Fieldset;

class UserCvNotes extends EmptyFieldset
{
	public function __construct()
	{
		parent::__construct('user_cv_notes');

		$this->add(array(
			'name' => 'user_notes',
			'type' => 'Zend\Form\Element\Textarea',
			'options' => array(
				'label' => $this->translate('Any notes regarding user'),
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					array(
						'name'    => 'NotEmpty',
						),				
					),
				),
			'attributes' => array(
				'placeholder' => $this->translate('Call results, user readiness etc...'),
				'rows' => 5,
				),
			));		

		$this->add(array(
			'name' => 'visibility',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Visibility'),
				'empty_option' => $this->translate('--Visible To--'),
				'options' => array(),
				'required' => true,
				),
			));	


	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}


}