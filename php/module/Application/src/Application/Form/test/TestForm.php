<?php
namespace Application\Form;
use Zend\Form\Form;

class TestForm extends Form
{


	public function __construct($name = null)
	{
		parent::__construct($name);
		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype', 'multipart/form-data');

		$this->add(array(
			'name' => 'ship_name',
			'type' => 'Application\Form\EmptyElement',
			'options' => array(
				'label' => 'Ship Name',
				'required' => true,
				'baseElement' => '\Zend\Form\Element',
	            'validators' => array(
					array(
						'name' => 'Alpha',
						'options' => array(
							),
						),		            
					),
	            'filters' => array(
	                array('name' => 'Zend\Filter\StringTrim'),
	            	),				
				),

			));

		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type' => 'submit',
				'value' => 'Submit',
				),
			'options' => array(
				'label' => 'Submit',
				),
			));

	}


}