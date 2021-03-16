<?php
namespace Application\Form\Fieldset;

use Zend\Form\Fieldset;

class Questions extends EmptyFieldset 
{


	public function addPostToVk()
	{
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
			'attributes' => array(
				'required' => false,
				),
			));
		return $this;
	}

	public function __construct()
	{
		parent::__construct('questions');

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
				'label' => $this->translate('Question Title'),
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					),
				),
			'attributes' => array(
				'placeholder' => $this->translate('How to prepare for CDI inspection'),
				'required' => true,
				),
			));

		$this->add(array(
			'name' => 'tags',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('#Tags'),
				'required' => true,
				'filters' => array(
					array('name' => '\Application\Filter\HashTag'),
					),
				),
			'attributes' => array(
				'id' => 'tags',
				'required' => 0,
				'placeholder' => $this->translate('Keywords describing your note'),
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
					array('name' => '\Application\Filter\UrlToLinks'),
					array('name' => 'HtmlEntities'),
					),
				'validators' => array(
					),
				),
			'attributes' => array(
				'placeholder' => $this->translate('Question Description'),

				),
			));

		$this->add(array(
			'name' => 'anonym',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Hide My Name'),
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