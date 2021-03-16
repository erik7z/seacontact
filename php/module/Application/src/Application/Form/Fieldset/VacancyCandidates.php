<?php
namespace Application\Form\Fieldset;

class VacancyCandidates extends EmptyFieldset
{
	public function __construct()
	{
		parent::__construct('vacancy_candidates');

		$this->add(array(
			'name' => 'vacancy',
			'type' => 'Zend\Form\Element\Radio',
			'options' => array(
				'label' => $this->translate('Active Vacancies'),
				'value_options' => [],
				'required' => true,
				),
			'attributes' => array(
				'required' => true,
				),
			));	

		$this->add(array(
			'name' => 'comments',
			'type' => 'Zend\Form\Element\Textarea',
			'options' => array(
				'label' => $this->translate('Comments'),
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					),
				),
			'attributes' => array(
				'required' => true,
				'placeholder' => $this->translate('Any comments regarding assignment'),
				'rows' => 4,
				),
			));

		$this->add(array(
			'name' => 'status',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Assignment Status'),
				'options' => \Application\Model\VacancyCandidatesTable::getCandidateStatusList(),
				'required' => true,
				),
			));	


	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}

}