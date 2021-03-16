<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class AnswerForm extends EmptyForm 
{

	public function __construct()
	{
		parent::__construct('answer');
	}

	public function setup($question_fields, $options = [])
	{
		$this->setAttribute('name' , 'question_answers_form');
		$submit_text = (isset($options['submit_text'])) ? $options['submit_text'] : $this->translate('Answer');
		$form_data = (isset($options['form_data'])) ? $options['form_data'] : null;

		if($form_data) {
			$form_data['time'] = date("d.m.Y H:i", $form_data['time']);
			$form_data['text'] = htmlspecialchars_decode($form_data['text']);

			if($form_data['pics']) $this->addOldPictureFields(json_decode($form_data['pics']));
		}

		$this->add($question_fields);
		$this->addPicsField();
		$this->addSubmit($submit_text);

		if($form_data) $this->setData(array('question_answers' => $form_data));
		return $this;
	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}


}