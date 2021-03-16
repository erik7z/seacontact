<?php
namespace Application\Form\Fieldset;

use Zend\Form\Fieldset;

class Vacancies extends EmptyFieldset 
{

	public function addFromCompany($companies_list)
	{
		$this->remove('user');
		$this->add(array(
			'name' => 'user',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Company'),
				'required' => 1,
				'empty_option' => '--Select--',
				'options' => $this->getListFromArray($companies_list, 'company_name'),
				),
			'attributes' => array(
				'id' => 'company_id',
				)
		));

		return $this;
	}

	public function addForCompany($companies_list)
	{
		$this->add(array(
			'name' => 'for_company',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('For Company'),
				'required' => 0,
				'empty_option' => '--Select--',
				'options' => $this->getListFromArray($companies_list, 'company_name'),
				),
			'attributes' => array(
				'id' => 'owner_id',
				)
		));

		return $this;
	}


	public function __construct()
	{
		parent::__construct('vacancies');

		$ranks_list = $this->sl->get('UserTable')->getFieldCountForSelect('all_ranks', [], ['more_than' => 1]);
		$ship_types_list = $this->sl->get('ExperienceTable')->getFieldCountForSelect('ship_type', [], ['more_than' => 1]);

		$this->add(array(
			'name' => 'id',
			'type' => 'hidden',
			));

		$this->add(array(
			'name' => 'user',
			'type' => 'hidden',
			));


		$this->add(array(
			'name' => 'title',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Vacancy Title'),
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					),
				),
			'attributes' => array(
				'placeholder' => $this->translate('ASAP Chief Engineer On Oil Tanker'),
				'required' => true,
				),
			));

		$this->add(array(
			'name' => 'time',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Publish Time'),
				),
			'attributes' => array(
				'required' => false,
				'placeholder' => $this->translate('Choose Time'),
				),
			));

		$this->add(array(
			'name' => 'text',
			'type' => 'Zend\Form\Element\Textarea',
			'options' => array(
				'label' => $this->translate('Description'),
				'required' => true,
				'filters'  => array(
					array('name' => '\Application\Filter\NewLineToBr'),
					array('name' => 'HtmlEntities'),
					),
				'validators' => array(
					),
				),
			'attributes' => array(
				'placeholder' => $this->translate('Vacancy Description'),

				),
			));

		$this->add(array(
			'name' => 'rank',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Rank'),
				'empty_option' => '--Select--',
				'options' => $ranks_list,
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					),
				),
			));

		$this->add(array(
			'name' => 'salary',
			'type' => 'Zend\Form\Element\Number',
			'options' => array(
				'label' => $this->translate('Salary'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					array(
						'name'    => 'Digits',
						'options' => array(
							'min'      => 0,
							'max'      => 99999,
							),
						),
					),
				),
			'attributes' => array(
				'min' => 0,
				'max' => 99999,
				),
			));


		$this->add(array(
			'name' => 'salary_unit',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Currency'),
				'options' => array(
					'USD' => 'USD',
					'EUR' => 'EUR',
					'GBP' => 'GBP'
					),
				),
			));

		$this->add(array(
			'name' => 'ship_name',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Ship Name'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					),
				),
			'attributes' => array(
				'placeholder' => $this->translate('Ship Name'),
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
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
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
				'filters'  => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					array(
						'name'    => 'Digits',
						'options' => array(
							'min'      => 1900,
							'max'      => 2020,
							),
						),
					),
				),
			'attributes' => array(
				'min' => 0,
				'max' => 2020,
				),
			));

		$this->add(array(
			'name' => 'ship_dwt',
			'type' => 'Zend\Form\Element\Number',
			'options' => array(
				'label' => $this->translate('Ship Size / Dwt'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(

					),
				),
			'attributes' => array(
				),
			));

		$this->add(array(
			'name' => 'date_join',
			'type' => 'Zend\Form\Element\Date',
			'options' => array(
				'label' => $this->translate('Joining Date'),
				),
			'attributes' => array(
				),
			));

		$this->add(array(
			'name' => 'contract_length',
			'type' => 'Zend\Form\Element\Number',
			'options' => array(
				'label' => $this->translate('Contract'),
				),
			'attributes' => array(
				'value' => 1,
				'min' => 1,
				'max' => 999,
				'step' => 0.1,
				),
			));

		$this->add(array(
			'name' => 'contract_unit',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Unit'),
				'options' => array(
					30 => 'M',
					1 => 'D',
					),
				),
			));


		$this->add(array(
			'name' => 'crew_nationality',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Nationality'),
				),
			'attributes' => array(
				'placeholder' => $this->translate('Filipino'),
				),
			));

		$this->add(array(
			'name' => 'english',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('English'),
				'value_options' => array(
					'5' => '5',
					'4' => '4',
					'3' => '3',
					'2' => '2',
					'1' => '1',
					),
				),
			));

		$this->add(array(
			'name' => 'comments',
			'type' => 'Zend\Form\Element\Textarea',
			'options' => array(
				'label' => $this->translate('Comments (Visible only for your company)'),
				'required' => false,
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 0,
							'max'      => 255,
							),
						),
					),
				),
			'attributes' => array(
				'placeholder' =>  $this->translate('Any personal notes visible only for your company'),
				),
			));

		$this->add(array(
			'name' => 'urgent',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Urgent'),
				'value_options' => array(
					0 => 'No',
					1 => 'Yes',
					),
				'required' => 1,
				),
			));

		$this->add(array(
			'name' => 'post_vk',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Post To VK'),
				'options' => array(
					0 => $this->translate('No'),
					1 => $this->translate('Yes'),
					),
				),
			));


		$this->add(array(
			'name' => 'active',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Vacancy Active'),
				'value_options' => array(
					1 => 'Yes',
					0 => 'No',
					),
				'required' => 1,
				),
			'attributes' => array(
				'required' => 'required',
				),
			));

	}
	

	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}

}