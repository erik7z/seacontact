<?php
namespace Application\Form\Fieldset;

class Comments extends EmptyFieldset
{
	public function __construct()
	{
		parent::__construct('comments');

		$this->add(array(
			'name' => 'id',
			'type' => 'hidden',
			));

		$this->add(array(
			'name' => 'comment',
			'type' => 'textarea',
			'options' => array(
				'label' => $this->translate('Comment'),
				'required' => true,
				'filters' => array(
					array('name' => '\Application\Filter\NewLineToBr'),
					array('name' => 'HtmlEntities'),
					),
				),
			'attributes' => array(
				'placeholder' => $this->translate('What you think about this ?'),
				'rows' => 2,
				),
			));

		$this->add(array(
			'name' => 'reply_on',
			'type' => 'Zend\Form\Element\Number',
			'options' => array(
				'required' => 0,
				),
			'attributes' => array(
				'min' => 0,
				'max' => 99999999999,
				'hidden' => 1
				),
			));


	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();	
	}

}