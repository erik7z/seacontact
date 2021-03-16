<?php
namespace Application\Form\Fieldset;

class UserExperience extends EmptyFieldset
{
	public function __construct()
	{
		parent::__construct('user_experience');

		$all_ranks = $this->sl->get('UserTable')->getFieldCountForSelect('all_ranks', [], ['more_than' => 1]);
		$ship_types_list = $this->sl->get('ExperienceTable')->getFieldCountForSelect('ship_type', [], ['more_than' => 1]);


		$this->add(array(
			'name' => 'id',
			'type' => 'Zend\Form\Element\Hidden',
			'options' => [
				'required' => false,
				'validators' => array(				
					),
			]
			));

		$this->add(array(
			'name' => 'ship_name',
			'type' => 'text',
			'options' => array(
				'label' => $this->translate('Ship Name'),
				'required' => true,
				'filters' => [],
				'validators' => [
					[
						'name'    => 'Alnum',
						'options' => array(
							'allowWhiteSpace' => true,
							)
					],
				]
				),
			'attributes' => array(
				'label' => 'nowrap',
				'col' => 'col-md-3',
				'required' => false,
				),
			));



		$this->add(array(
			'name' => 'date_from',
			'type' => 'Zend\Form\Element\Date',
			'options' => array(
				'label' => $this->translate('Date from').' (YYYY-MM-DD)',
				'required' => true,
				'filters' => array(
					array('name' => '\Application\Filter\FormDateToUnix')
					),
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
				'label' => 'nowrap',
				'col' => 'col-md-3',
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'date_to',
			'type' => 'Zend\Form\Element\Date',
			'options' => array(
				'label' => $this->translate('Date to').' (YYYY-MM-DD)',
				'required' => true,
				'filters' => array(
					array('name' => '\Application\Filter\FormDateToUnix')
					),
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
				'label' => 'nowrap',
				'col' => 'col-md-3',
				'required' => false,
				),
			));


		$this->add(array(
			'name' => 'rank',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Rank'),
				'empty_option' => '--Select--',
				'options' => $all_ranks,
				'required' => true,
				'validators' => array(
					),
				),
			'attributes' => array(
				'label' => 'nowrap',
				'col' => 'col-md-3',
				),
			));

		$this->add(array(
			'name' => 'rank_text',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Rank Free Text'),
				'required' => false,
				'validators' => array(
					),
				),
			'attributes' => array(
				'label' => 'nowrap',
				'col' => 'col-md-3',
				),
			));

		$this->add(array(
			'name' => 'flag',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Flag'),
				'required' => false,
				'validators' => array(
					),
				),
			'attributes' => array(
				'label' => 'nowrap',
				'col' => 'col-md-3',
				),
			));

		$this->add(array(
			'name' => 'ship_type',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Ship Type'),
				'empty_option' => '--Select--',
				'options' => $ship_types_list,
				'required' => true,
				'validators' => array(				
					),
				),
			));	

		$this->add(array(
			'name' => 'ship_built',
			'type' => 'Zend\Form\Element\Number',
			'options' => array(
				'label' => $this->translate('Ship Built'),
				'required' => false,
				'filters' => array(
					),
				'validators' => array(			
					),
				),
			'attributes' => array(
				'label' => 'nowrap',
				'col' => 'col-md-4',
				'min' => 1950,
				'max' => 2020,
				),
			));

		$this->add(array(
			'name' => 'dwt',
			'type' => 'Zend\Form\Element\Number',
			'options' => array(
				'label' => $this->translate('Deadweight'),
				'required' => false,
				'validators' => array(				
					),
				),
			'attributes' => array(
				'label' => 'nowrap',
				'col' => 'col-md-2',
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'grt',
			'type' => 'Zend\Form\Element\Number',
			'options' => array(
				'label' => $this->translate('Gross Tonnage'),
				'required' => false,
				'validators' => array(				
					),
				),
			'attributes' => array(
				'label' => 'nowrap',
				'col' => 'col-md-2',
				'required' => false,
				),
			));


		$this->add(array(
			'name' => 'bhp',
			'type' => 'Zend\Form\Element\Number',
			'options' => array(
				'label' => $this->translate('BHP (KW)'),
				'required' => false,
				'validators' => array(				
					),
				),
			'attributes' => array(
				'label' => 'nowrap',
				'col' => 'col-md-2',
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'engine',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Engine'),
				'required' => false,
				'validators' => array(				
					),
				),
			'attributes' => array(
				'label' => 'nowrap',
				'col' => 'col-md-2',
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'company',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Company'),
				'required' => false,
				'validators' => array(				
					),
				),
			'attributes' => array(
				'label' => 'nowrap',
				'col' => 'col-md-6',
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'trading_area',
			'type' => 'Zend\Form\Element\text',
			'options' => array(
				'label' => $this->translate('Trading areas'),
				'required' => false,
				'validators' => array(
					),
				),
			'attributes' => array(
				'label' => 'nowrap',
				'col' => 'col-md-6',
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'text',
			'type' => 'textarea',
			'options' => array(
				'label' => $this->translate('Something about this contract'),
				'required' => false,
				'validators' => array(
					),
				),
			'attributes' => array(
				'label' => 'nowrap',
				'col' => 'col-md-12',
				'required' => false,
				),
			));


	}

	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}


}