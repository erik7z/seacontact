<?php
namespace Application\Form\Fieldset;

use Zend\Form\Fieldset;

class QuestionAnswers extends EmptyFieldset 
{

	public function __construct()
	{
		parent::__construct('question_answers');

		$this->add(array(
			'name' => 'id',
			'type' => 'hidden',
			));

		$this->add(array(
			'name' => 'question_id',
			'type' => 'hidden',
			));

		$this->add(array(
			'name' => 'user',
			'type' => 'hidden',
			));

		$this->add(array(
			'name' => 'text',
			'type' => 'Zend\Form\Element\Textarea',
			'options' => array(
				'label' => $this->translate('Answer'),
				'required' => true,
				'filters'  => array(
					array('name' => '\Application\Filter\NewLineToBr'),
					array('name' => '\Application\Filter\UrlToLinks'),
					array('name' => 'HtmlEntities'),
					),
				'validators' => array(
					),
				),
			'attributes' => array(
				'placeholder' => $this->translate('Your answer'),

				),
			));
		
		$this->add(array(
			'name' => 'anonym',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Hide my name'),
				'options' => array(
					0 => $this->translate('No'),
					1 => $this->translate('Yes'),
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