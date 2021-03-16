<?php
namespace Application\Form\Fieldset;

class AdminTasks extends EmptyFieldset
{
	public function __construct()
	{
		parent::__construct('admin_tasks');

		$this->add(array(
			'name' => 'id',
			'type' => 'hidden',
			));

		$this->add(array(
			'name' => 'title',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Title'),
				'required' => true,
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 3,
							'max'      => 255,
							),
						),					
					),
				),
			'attributes' => array(
				'required' => 'required',
				'placeholder' => $this->translate('Job name'),
				),
			));

		$this->add(array(
			'name' => 'text',
			'type' => 'textarea',
			'options' => array(
				'label' => $this->translate('Job description'),
				'required' => false,
				'filters'  => array(
					array('name' => '\Application\Filter\NewLineToBr'),
					array('name' => '\Application\Filter\UrlToLinks'),
					array('name' => 'HtmlEntities'),
					),
				),
			'attributes' => array(
				'required' => false,
				'rows' => 5,
				),
			));


		$this->add(array(
			'name' => 'status',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Job Status'),
				'options' => \Application\Model\AdminTasksTable::getTasksStatusList(),
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'priority',
			'type' => 'Zend\Form\Element\Number',
			'options' => array(
				'label' => $this->translate('Priority'),
				'required' => false,
				),
			'attributes' => array(
				'min' => 0,
				'value' => 1,
				'max' => 10,
				),
			));

		$this->add(array(
			'name' => 'due_date',
			'type' => 'Zend\Form\Element\Date',
			'options' => array(
				'label' => $this->translate('Due Date').' (dd-mm-yyyy)',
				'required' => true,
				'filters' => array(
					array('name' => '\Application\Filter\FormDateToUnix')
					)
				),
			'attributes' => array(
				'required' => false,
				'placeholder' => '01.12.2016'
				),
			));



	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();	
	}

}