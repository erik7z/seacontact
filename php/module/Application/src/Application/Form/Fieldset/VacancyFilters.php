<?php
namespace Application\Form\Fieldset;

class VacancyFilters extends EmptyFieldset
{

	public function __construct()
	{
		parent::__construct('filters');

		$userTable = $this->sl->get('UserTable');
		$countries_list = $userTable->getFieldCountForSelect('home_country', [], ['more_than' => 1]);
		$city_list = $userTable->getFieldCountForSelect('home_city', [], ['more_than' => 100]);
		$desired_ranks = $userTable->getFieldCountForSelect('desired_rank', [], ['more_than' => 1]);
		$all_ranks = $userTable->getFieldCountForSelect('all_ranks', [], ['more_than' => 1]);
		$last_ranks = $userTable->getFieldCountForSelect('all_ranks', [], ['more_than' => 1]);
		$ship_types_list = $this->sl->get('ExperienceTable')->getFieldCountForSelect('ship_type', [], ['more_than' => 1]);
		$info_source_list = $userTable->getFieldCountForSelect('info_source', []);

		$roles = $this->sl->get('Access')->getAccessList()->getUserRoles();
		for ($i=0; $i < count($roles); $i++) { 
			$roles_list[$roles[$i]] = $roles[$i];
		}

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
				'placeholder' => $this->translate('Sergey Ivanov'),
				),
			));



	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}
}