<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class QuestionForm extends EmptyForm 
{

	public function __construct()
	{
		parent::__construct('question');
	}

	public function setup($question_fields, $options = [])
	{
		$submit_text = (isset($options['submit_text'])) ? $options['submit_text'] : $this->translate('Ask Question');
		$form_data = (isset($options['form_data'])) ? $options['form_data'] : null;

		if($form_data) {
			$form_data['time'] = date("d.m.Y H:i", $form_data['time']);
			$form_data['text'] = htmlspecialchars_decode($form_data['text']);
			if($form_data['post_vk_time'] && $question_fields->has('post_vk')) {
				$question_fields->get('post_vk')
						->setValueOptions(array(0 => $this->translate('Last ').zformatDateTime($form_data['post_vk_time']), 1 => $this->translate('Post Again')));
			}

			if($form_data['pics']) $this->addOldPictureFields(json_decode($form_data['pics']));
		}

		$this->add($question_fields);
		$this->addPicsField();
		$this->addSubmit($submit_text);

		if($form_data) $this->setData(array('questions' => $form_data));
		if($form_data['post_vk_time'] && $question_fields->has('post_vk')) $this->get('questions')->get('post_vk')->setValue(0);
		return $this;
	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}


}