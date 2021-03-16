<?php
namespace Application\Form\Fieldset;

class Ships extends EmptyFieldset
{
	public function __construct()
	{
		parent::__construct('ships');

		$this->add(array(
			'name' => 'id',
			'type' => 'Zend\Form\Element\Hidden',
			'options' => [
				'required' => false,
				'filters' => array(
					array('name' => 'Digits'),
					),
				'validators' => array(
					array('name' => 'Digits'),
					),
			]
			));

		$this->add(array(
			'name' => 'ship_name',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Ship Name',
				'required' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 64
							),
						),
					),
				),
			'attributes' => array(
				'required' => true,
				),
			));

		$this->add(array(
			'name' => 'port_of_registry',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Port Of Registry',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 50
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'ship_type',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => 'Type of the Vessel',
				'empty_option' => '--Select--',
				'options' => [],
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 50
							),
						),
					),
				),
			));


		$this->add(array(
			'name' => 'mmsi',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'MMSI Number',
				'required' => false,
				'filters' => array(
					array('name' => 'Digits'),
					),
				'validators' => array(
					array('name' => 'Digits'),
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 20
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'imo',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'IMO Number',
				'required' => false,
				'filters' => array(
					array('name' => 'Digits'),
					),
				'validators' => array(
					array('name' => 'Digits'),
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 20
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'callsign',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Call Sign',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 7
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				'placeholder' => 'C6WM4',
				),
			));


		$this->add(array(
			'name' => 'official_number',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Official Number',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 20
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				),
			));


		$this->add(array(
			'name' => 'built',
			'type' => 'Zend\Form\Element\MonthSelect',
			'options' => array(
				'label' => 'Built',
				'min_year' => 1940,
				'max_year' => date('Y', time()),
				'required' => false,
				'filters' => array(
					array('name' => 'Digits'),
					),
				'validators' => array(
					array('name' => 'Digits'),
					array(
						'name' => 'StringLength',
						'options' => array(
							'min' => 4,
							'max' => 4
							),
						),
					),
				),
			));

		$this->add(array(
			'name' => 'dwt',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Deadweight',
				'required' => false,
				'filters' => array(
					array('name' => 'Digits'),
					),
				'validators' => array(
					array('name' => 'Digits'),
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 10
							),
						),
					),
				),
			'attributes' => array(
				'required' => true,
				),
			));

		$this->add(array(
			'name' => 'grt',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Gross Tonnage',
				'required' => false,
				'filters' => array(
					array('name' => 'Digits'),
					),
				'validators' => array(
					array('name' => 'Digits'),
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 10
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'flag',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => 'Flag',
				'empty_option' => '--Select--',
				'options' => $this->getListFromDb('list-flag', 'flag','flag'),
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 30
							),
						),
					),
				),
			));

		$this->add(array(
			'name' => 'classification_society',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Classification Society',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 100
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'classification_type',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Classification Type Of Ship',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 255
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'length',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Length Over All',
				'required' => false,
				'filters' => array(
					array('name' => 'Digits'),
					),
				'validators' => array(
					array('name' => 'Digits'),
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 5
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'breadth',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Breadth Moulded',
				'required' => false,
				'filters' => array(
					array('name' => 'Digits'),
					),
				'validators' => array(
					array('name' => 'Digits'),
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 5
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'depth',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Depth Moulded',
				'required' => false,
				'filters' => array(
					array('name' => 'Digits'),
					),
				'validators' => array(
					array('name' => 'Digits'),
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 5
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'draft',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Maximum Draft',
				'required' => false,
				'filters' => array(
					array('name' => 'Digits'),
					),
				'validators' => array(
					array('name' => 'Digits'),
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 5
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				),
			));



		$this->add(array(
			'name' => 'engine',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Engine',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 255
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				'placeholder' => 'Sulzer',
				),
			));


		$this->add(array(
			'name' => 'speed',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Service Speed',
				'required' => false,
				'filters' => array(
					array('name' => 'Digits'),
					),
				'validators' => array(
					array('name' => 'Digits'),
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 5
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				),
			));


		$this->add(array(
			'name' => 'email',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Ships Email',
				'required' => false,
				'filters' => array(
					),
				'validators' => array(
					array('name' => 'Email'),
					),
				),
			'attributes' => array(
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'inmarsat',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Inmarsat Number',
				'required' => false,
				'filters' => array(
					array('name' => 'Digits'),
					),
				'validators' => array(
					array('name' => 'Digits'),
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 30
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'inmarsat_b',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Inmarsat 1',
				'required' => false,
				'filters' => array(
					array('name' => 'Digits'),
					),
				'validators' => array(
					array('name' => 'Digits'),
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 30
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'inmarsat_c',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Inmarsat 2',
				'required' => false,
				'filters' => array(
					array('name' => 'Digits'),
					),
				'validators' => array(
					array('name' => 'Digits'),
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 30
							),
						),
					),
				),
			'attributes' => array(
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'mobile',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Ships Mobile',
				'required' => false,
				'filters' => array(
					array('name' => 'Digits'),
					),
				'validators' => array(
					array('name' => 'Digits'),
					array(
						'name' => 'StringLength',
						'options' => array(
							'max' => 30
							),
						),
					),
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