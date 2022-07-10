<?php
namespace Application\Form\Fieldset;

class FilterOptions extends EmptyFieldset
{
	public function __construct()
	{
		parent::__construct('filter_options');


		$this->add(array(
			'name' => '_limit',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Maximum Result Quantity'),
	 			'required' => false,
	 			'filters' => [],
	 			'validators' => [
		 			array(
		 				'name'    => 'StringLength',
		 				'options' => array(
		 					'encoding' => 'UTF-8',
		 					'min'      => 1,
		 					'max'      => 1,
		 					),
		 				),
	 			],
				),
			// 'attributes' => array(
			// 	'min' => 1,
			// 	'value' => 50,
			// 	'max' => 100000,
			// 	),
			));

		$this->add(array(
			'name' => 'order_by',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Order By'),
				'options' => [
					'id' => 'id',
					'required' => false,
					'filters' => [],
					'validators' => [],
				],
			),
			'attributes' => array(
				'required' => false,
				),
			));

		$this->add(array(
			'name' => '_order',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Order'),
				'options' => [
					'ASC' => 'Ascending',
					'DESC' => 'Descending',
				],
				'required' => false,
				'filters' => [],
				'validators' => [],
				),
			'attributes' => array(
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'show_notes',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Show CV Notes'),
				'options' => [
					1 => $this->translate('Yes'),
					0 => $this->translate('No'),
				],
				'required' => false,
				'filters' => [],
				'validators' => [],
				),
			'attributes' => array(
				'required' => false,
				),
			));


	}


	public function getInputFilterSpecification()
	 {
	 	return $this->getFiltersFromFieldsOptions();
	}

}