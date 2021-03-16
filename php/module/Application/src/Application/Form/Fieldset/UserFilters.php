<?php
namespace Application\Form\Fieldset;

class UserFilters extends EmptyFieldset
{

	public function setup($filters = [], $options = [])
	{
		$userTable = $this->sl->get('UserTable');
		$options['more_than'] = (isset($options['min_nationality']))? $options['min_nationality'] : 30 ;
		$nationalities = $userTable->getFieldCountForSelect('nationality', $filters, $options);
		$options['more_than'] = (isset($options['min_home_country']))? $options['min_home_country'] : 0 ;
		$countries_list = $userTable->getFieldCountForSelect('home_country', $filters, $options);
		$options['more_than'] = (isset($options['min_home_city']))? $options['min_home_city'] : 0 ;
		$city_list = $userTable->getFieldCountForSelect('home_city', $filters, $options);
		$options['more_than'] = (isset($options['min_desired_rank']))? $options['min_desired_rank'] : 0 ;
		$desired_ranks = $userTable->getFieldCountForSelect('desired_rank', $filters, $options);
		$options['more_than'] = 0 ;
		$all_ranks = $userTable->getFieldCountForSelect('all_ranks', $filters, $options);
		$last_ranks = $userTable->getFieldCountForSelect('last_rank', $filters, $options);
		$ship_types_list = $this->sl->get('ExperienceTable')->getFieldCountForSelect('ship_type', $filters, $options);
		
		$last_ship_type = $this->sl->get('ExperienceTable')->getFieldCountForSelect('last_ship_type', $filters, $options);
		$info_source_list = $userTable->getFieldCountForSelect('info_source', $filters);

		$companies_list = $userTable->getUsersList(null, [], ['user_type' => ['company', 'owner'], '_user_fields' => ['id', 'company_name'], '_limit' => 0, 'use_cache' => 1]);
		$companies_list = $userTable->resultToFormSelect($companies_list, 'id', null, null, 'company_name');

		if(isset($filters['type_company']) && $filters['type_company'] == 1) 
			$roles = $this->sl->get('Access')->getAccessList()->getCompanyRoles();
		else $roles = $this->sl->get('Access')->getAccessList()->getUserRoles();
		for ($i=0; $i < count($roles); $i++) { 
			$roles_list[$roles[$i]] = $roles[$i];
		}

		// if($this->has('nationality')) $this->get('nationality')->setValueOptions($nationalities);
		if($this->has('home_country')) $this->get('home_country')->setValueOptions($countries_list);
		if($this->has('home_city')) $this->get('home_city')->setValueOptions($city_list);
		if($this->has('role')) $this->get('role')->setValueOptions($roles_list);
		if($this->has('info_source')) $this->get('info_source')->setValueOptions($info_source_list);
		if($this->has('desired_rank')) $this->get('desired_rank')->setValueOptions($desired_ranks);
		if($this->has('worked_in_psn')) $this->get('worked_in_psn')->setValueOptions($all_ranks);
		if($this->has('worked_for')) $this->get('worked_for')->setValueOptions($companies_list);
		if($this->has('real_last_rank')) $this->get('real_last_rank')->setValueOptions($last_ranks);
		if($this->has('last_ship_type')) $this->get('last_ship_type')->setValueOptions($last_ship_type);
		if($this->has('ship_type')) $this->get('ship_type')->setValueOptions($ship_types_list);


	}

	public function __construct()
	{
		parent::__construct('filters');
		$nationalities = [];
		$countries_list = [];
		$city_list = [];
		$desired_ranks = [];
		$all_ranks = [];
		$last_ranks = [];
		$ship_types_list = [];
		$info_source_list = [];
		$roles_list = [];


		$this->add(array(
			'name' => 'name',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Name'),
				'required' => false,
				'filters' => [],
				'validators' => [],
				),
			'attributes' => array(
				// 'placeholder' => $this->translate('Sergey Ivanov'),
				),
			));


		$this->add(array(
			'name' => 'login',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Login'),
				'required' => false,
				'filters' => [],
				'validators' => [],
				),
			'attributes' => array(
				'placeholder' => $this->translate('Nickname'),
				),
			));

		$this->add(array(
			'name' => 'email',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'E-mail',
				'required' => false,
				'filters' => [],
				'validators' => [],
				),
			'attributes' => array(
				'placeholder' => 'somename@gmail.com',
				),
			));


		$this->add(array(
			'name' => 'desired_rank',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Position Applied For'),
				'empty_option' => '--Select--',
				'options' => $desired_ranks,
				'required' => false,
				'filters' => [],
				'validators' => [],
				),
			'attributes' => [
				'multiple' => true,
				'id' => 'desired_rank'
			]
			));	


		$this->add(array(
			'name' => 'nationality',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Nationality'),
				'required' => false,
				'filters' => [],
				'validators' => [],
				),
			'attributes' => array(
				'placeholder' => 'Ukraine',
				),
			));

		$this->add(array(
			'name' => 'company',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Company'),
				'required' => false,
				'filters' => [],
				'validators' => [],
				),
			'attributes' => array(
				'placeholder' => '',
				),
			));

		$this->add(array(
		'name' => 'worked_for',
		'type' => 'Zend\Form\Element\Select',
		'options' => array(
			'label' => $this->translate('Source or Company'),
			'required' => false,
			'empty_option' => '--Select--',
			'options' => [],
			),
		'attributes' => array(
			'id' => 'worked_for',
			)
		));
		// $this->add(array(
		// 	'name' => 'nationality',
		// 	'type' => 'Zend\Form\Element\Select',
		// 	'options' => array(
		// 		'label' => $this->translate('Nationality'),
		// 		'empty_option' => '--Select--',
		// 		'options' => $nationalities,
		// 		'required' => false,
		// 		'filters' => [],
		// 		'validators' => [],
		// 		),
		// 	'attributes' => array(
		// 		'multiple' => true,
		// 		'placeholder' => 'Ukraine',
		// 		),
		// 	));


		$this->add(array(
			'name' => 'home_address',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Address'),
				'required' => false,
				'filters' => [],
				'validators' => [],
				),
			'attributes' => array(
				'placeholder' => '1, Deribasovskaya str., Odessa, Ukraine'
				),
			));

		$this->add(array(
			'name' => 'home_city',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Home City'),
				'empty_option' => '--Select--',
				'options' => $city_list,
				),
			'attributes' => [
				'multiple' => true,
				'id' => 'home_city'
			]
			));

		$this->add(array(
			'name' => 'home_country',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Country'),
				'empty_option' => '--Select country--',
				'options' => $countries_list,
				'required' => false,
				),
			'attributes' => [
				'multiple' => true,
				'id' => 'home_country'
			]
			));

		$this->add(array(
			'name' => 'contact_mobile',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Mobile phone'),
				'required' => false,
				'filters' => [],
				'validators' => [],
				),
			'attributes' => array(
				'placeholder' => '+380.........',
				),			
			));

		$this->add(array(
			'name' => 'english_from',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('English From'),
				'empty_option' => '--Select--',
				'value_options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					),
				'required' => false,
				'filters' => [],
				'validators' => [],
				),
			));

		$this->add(array(
			'name' => 'english_to',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('English To'),
				'empty_option' => '--Select--',
				'value_options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					),
				),
			));

		$this->add(array(
				'name' => 'age_from',
				'type' => 'Zend\Form\Element\Number',
				'options' => array(
					'label' => $this->translate('Age From'),
					),
				'attributes' => array(
					'min' => 18,
					'max' => 70,
					),
				));

		$this->add(array(
				'name' => 'age_to',
				'type' => 'Zend\Form\Element\Number',
				'options' => array(
					'label' => $this->translate('Age To'),
					),
				'attributes' => array(
					'min' => 18,
					'max' => 70,
					),
				));


		
		$this->add(array(
			'name' => 'real_last_rank',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Latest Rank'),
				'empty_option' => '--Select--',
				'options' => $last_ranks,
				),
			'attributes' => [
				'multiple' => true,
				'id' => 'real_last_rank'
			]
			));	

		$this->add(array(
			'name' => 'worked_in_psn',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Ever Worked In Position'),
				'empty_option' => '--Select--',
				'options' => $all_ranks,
				),
			'attributes' => [
				'multiple' => true,
				'id' => 'worked_in_psn'
			]
			));	

		$this->add(array(
			'name' => 'ship_name',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Ship Name'),
				'required' => false,
				'filters' => [],
				'validators' => [],
				),
			'attributes' => array(
				'placeholder' => 'Rose Gas',
				),
			));


		$this->add(array(
			'name' => 'last_ship_type',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Last Ship Type'),
				'empty_option' => '--Select--',
				'options' => $ship_types_list,
				),
			'attributes' => [
				'multiple' => true,
				'id' => 'last_ship_type'
			]
			));	

		
		$this->add(array(
			'name' => 'ship_type',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Ever Worked On Ship'),
				'empty_option' => '--Select--',
				'options' => $ship_types_list,
				),
			'attributes' => [
				'multiple' => true,
				'id' => 'ship_type'
			]
			));	


		$this->add(array(
				'name' => 'readiness_date_from',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
					'label' => $this->translate('Readiness Date From'),
					'required' => false,
					'filters' => [
							['name' => '\Application\Filter\FormDateToUnix'],
						],
					),
				'attributes' => array(
					'type' => 'date',
					)
				));

		$this->add(array(
				'name' => 'readiness_date_to',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
					'label' => $this->translate('Readiness Date To'),
					'required' => false,
					'filters' => [
							['name' => '\Application\Filter\FormDateToUnix'],
						],
					),
				'attributes' => array(
					'type' => 'date',
					)
				));



		$this->add(array(
				'name' => 'cv_last_view_from',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
					'label' => $this->translate('CV Last Opened From'),
					'required' => false,
					'filters' => [
							['name' => '\Application\Filter\FormDateToUnix'],
						],
					),
				'attributes' => array(
					'type' => 'date',
					)
				));

		$this->add(array(
				'name' => 'in_db_from',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
					'label' => $this->translate('Cv Added To Database From'),
					'required' => false,
					'filters' => [
							['name' => '\Application\Filter\FormDateToUnix'],
						],
					),
				'attributes' => array(
					'type' => 'date',
					)
				));

		$this->add(array(
				'name' => 'in_db_to',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
					'label' => $this->translate('Cv Added To Database To'),
					'required' => false,
					'filters' => [
							['name' => '\Application\Filter\FormDateToUnix'],
						],
					),
				'attributes' => array(
					'type' => 'date',
					)
				));

		$this->add(array(
			'name' => 'registered',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Registered'),
				'empty_option' => '--Select--',
				'value_options' => array(
					'yes' => $this->translate('Yes'),
					'no' => $this->translate('No'),
					),
				),
			));


		$this->add(array(
			'name' => 'visa_usa',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Usa Visa'),
				'empty_option' => '--Select--',
				'value_options' => array(
					1 => 'Have USA visa',
					),
				),
			));

		$this->add(array(
			'name' => 'visa_shenghen',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Shenghen Visa'),
				'empty_option' => '--Select--',
				'value_options' => array(
					1 => 'Have Shenghen visa',
					),
				),
			));


		$this->add(array(
				'name' => 'dwt_from',
				'type' => 'Zend\Form\Element\Number',
				'options' => array(
					'label' => $this->translate('Any ship DWT From'),
					),
				'attributes' => array(
					'type' => 'number',
					)
				));

		$this->add(array(
				'name' => 'dwt_to',
				'type' => 'Zend\Form\Element\Number',
				'options' => array(
					'label' => $this->translate('Any ship DWT to'),
					),
				'attributes' => array(
					'type' => 'number',
					)
				));

		$this->add(array(
				'name' => 'last_dwt_from',
				'type' => 'Zend\Form\Element\Number',
				'options' => array(
					'label' => $this->translate('Last ship DWT From'),
					),
				'attributes' => array(
					'type' => 'number',
					)
				));

		$this->add(array(
				'name' => 'last_dwt_to',
				'type' => 'Zend\Form\Element\Number',
				'options' => array(
					'label' => $this->translate('Last ship DWT to'),
					),
				'attributes' => array(
					'type' => 'number',
					)
				));

		$this->add(array(
			'name' => 'online',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Online'),
				'empty_option' => '--Select--',
				'value_options' => array(
					'online' => $this->translate('Online'),
					'offline' => $this->translate('Offline'),
					),
				),
			));

		$this->add(array(
			'name' => 'notes',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Any notes'),
				'empty_option' => '--Select--',
				'value_options' => array(
					'have_notes' => $this->translate('Have notes'),
					'no_notes' => $this->translate('No notes'),
					),
				),
			));

		$this->add(array(
			'name' => 'notes_text',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Note Text'),
				'required' => false,
				'filters' => [],
				'validators' => [],
				),
			'attributes' => array(
				'placeholder' => $this->translate('Any text in notes'),
				),
			));

		$this->add(array(
				'name' => 'minimum_salary_from',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
					'label' => $this->translate('Salary From'),
					),
				'attributes' => array(
					'type' => 'number',
					)
				));

		$this->add(array(
				'name' => 'minimum_salary_to',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
					'label' => $this->translate('Salary To'),
					),
				'attributes' => array(
					'type' => 'number',
					)
				));


		$this->add(array(
				'name' => 'cv_last_view_to',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
					'label' => $this->translate('CV Last Opened To'),
					'required' => false,
					'filters' => [
							['name' => '\Application\Filter\FormDateToUnix'],
						],
					),
				'attributes' => array(
					'type' => 'date',
					)
				));

		$this->add(array(
				'name' => 'cv_last_call_from',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
					'label' => $this->translate('CV Last Called From'),
					'required' => false,
					'filters' => [
							['name' => '\Application\Filter\FormDateToUnix'],
						],
					),
				'attributes' => array(
					'type' => 'date',
					)
				));

		$this->add(array(
				'name' => 'cv_last_call_to',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
					'label' => $this->translate('CV Last Called To'),
					'required' => false,
					'filters' => [
							['name' => '\Application\Filter\FormDateToUnix'],
						],
					),
				'attributes' => array(
					'type' => 'date',
					)
				));

		$this->add(array(
				'name' => 'cv_last_update_from',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
					'label' => $this->translate('CV Updated From'),
					'required' => false,
					'filters' => [
							['name' => '\Application\Filter\FormDateToUnix'],
						],
					),
				'attributes' => array(
					'type' => 'date',
					)
				));

		$this->add(array(
				'name' => 'cv_last_update_to',
				'type' => 'Zend\Form\Element\Text',
				'options' => array(
					'label' => $this->translate('CV Updated To'),
					'required' => false,
					'filters' => [
							['name' => '\Application\Filter\FormDateToUnix'],
						],
					),
				'attributes' => array(
					'type' => 'date',
					)
				));



		$this->add(array(
			'name' => 'info_source',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Information Source'),
				'empty_option' => '--Select--',
				'options' => $info_source_list,
				),
			));	

		$this->add(array(
			'name' => 'role',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Role'),
				'empty_option' => '--Select--',
				'options' => $roles_list,
				),
			));	

	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}
}