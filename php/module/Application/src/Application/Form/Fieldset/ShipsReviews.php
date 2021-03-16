<?php
namespace Application\Form\Fieldset;

class ShipsReviews extends EmptyFieldset
{
	public function __construct()
	{
		parent::__construct('ships-reviews');

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

		// $this->add(array(
		// 	'name' => 'ship_id',
		// 	'type' => 'Zend\Form\Element\Hidden',
		// 	));


		$this->add(array(
			'name' => 'ship_id',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => 'Ship',
				'empty_option' => '--Select--',
				'options' => $this->getListFromDb('ships', 'ship_name'),
				'required' => false,
				'filters' => array(
					array('name' => 'Digits'),
					),
				'validators' => array(
					array('name' => 'Digits'),

					array(
						'name'    => 'DbRecordExists',
						'options' => array(
							'table' => 'ships',
							'field' => 'id',
							'adapter' => \Application\Model\zAbstractTable::getAdapter(),
							'messages' => array (
								\Zend\Validator\Db\RecordExists::ERROR_NO_RECORD_FOUND => 'Ship ID not found in Data Base',
								),
							),
						),	

					),
				),
			'attributes' => array(
				'required' => false,
				),
			));


		$this->add(array(
			'name' => 'title',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Review Title',
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
			'name' => 'link',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Review link',
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
			'name' => 'text',
			'type' => 'Zend\Form\Element\TextArea',
			'options' => array(
				'label' => 'Review Text',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				),
			'attributes' => array(
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'rate',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => 'Rate this Ship',
				'empty_option' => '--Select--',
				'options' => array(
						1 => 1,
						2 => 2,
						3 => 3,
						4 => 4,
						5 => 5,
					)
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					array(
						'name'    => 'Between',
						'options' => array(
							'min' => 1,
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
			'name' => 'date_from',
			'type' => 'Zend\Form\Element\MonthSelect',
			'options' => array(
				'label' => 'From',
				'min_year' => 1950,
				'max_year' => date('Y', time()),
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					),
				),
			'attributes' => array(
				'required' => 'required',
				),
			));



		$this->add(array(
			'name' => 'date_to',
			'type' => 'Zend\Form\Element\MonthSelect',
			'options' => array(
				'label' => 'To',
				'min_year' => 1950,
				'max_year' => date('Y', time()),
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
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