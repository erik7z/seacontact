<?php
namespace Application\Form\Fieldset;

class UserEducation extends EmptyFieldset
{
	public function __construct()
	{
		parent::__construct('user_education');

		$this->add(array(
			'name' => 'id',
			'type' => 'Zend\Form\Element\Hidden',
			'options' => [
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					),
			]
			));

		$this->add(array(
			'name' => 'name',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Academy/University'),
				),
			'attributes' => array(
				'label' => 'nowrap',
				'col' => 'col-md-8',
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'country',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Country'),
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					),
				),
			'attributes' => array(
				'label' => 'nowrap',
				'col' => 'col-md-4',
				'required' => false,
				),
			));

		$this->add(array(
			'name' => 'from',
			'type' => 'Zend\Form\Element\Number',
			'options' => array(
				'label' => $this->translate('Year From'),
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					array(
						'name'    => 'Between',
						'options' => array(
							'min' => 1950,
							'max' => date("Y", time())
							),
						),
					),
				),
			'attributes' => array(
				'label' => 'nowrap',
				'col' => 'col-md-4',
				'min' => 1950,
				'max' => date("Y", time()),
				),
			));


		$this->add(array(
			'name' => 'to',
			'type' => 'Zend\Form\Element\Number',
			'options' => array(
				'label' => $this->translate('Year To'),
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					array(
						'name'    => 'Between',
						'options' => array(
							'min' => 1950,
							'max' => date("Y", time())
							),
						),
					),
				),
			'attributes' => array(
				'label' => 'nowrap',
				'col' => 'col-md-4',
				'min' => 1950,
				'max' => date("Y", time()),
				),
			));

		$this->add(array(
			'name' => 'diploma',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Diploma Number'),
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					),
				),
			'attributes' => array(
				'label' => 'nowrap',
				'col' => 'col-md-4',
				'required' => false,
				),

			));

	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}


}