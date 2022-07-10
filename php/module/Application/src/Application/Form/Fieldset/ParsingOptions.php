<?php
namespace Application\Form\Fieldset;

class ParsingOptions extends EmptyFieldset
{
	public function __construct()
	{
		parent::__construct('parsing_options');

		$this->add(array(
			'name' => 'id',
			'type' => 'hidden',
			));

		$this->add([
			'name' => 'page',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('VK Public URL'),
				'required' => true,
				'filters'  => array(
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					array(
						'name' => 'Uri',
						'options' => array(
							),
						),
					),
					array('name'    => 'NotEmpty'),
				),
			'attributes' => array(
				'placeholder' => 'http://vk.com/....',
				'required' => true,
				),
			]);

		$this->add([
			'name' => 'parsing_tags',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Parsing Tags'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StringTrim'),
					array('name' => '\Application\Filter\StripSpaces'),
					),
				),
			'attributes' => array(
				'placeholder' => '#seacontact_vasya,seacontact.com/vasya',
				'value' => _SOCIAL_PARSE_KEYWORD_.$this->sl->get('AuthService')->getIdentity()->login,
				'description' => $this->translate('Comma separated list of key tags').' : <b>#vasya,#my_public,#typical_seaman,#ts</b>'
				),
			]);


		// $this->add([
		// 	'name' => 'author_tags',
		// 	'type' => 'Zend\Form\Element\Text',
		// 	'options' => array(
		// 		'label' => $this->translate('Author Tags'),
		// 		'required' => false,
		// 		'filters'  => array(
		// 			array('name' => 'StringTrim'),
		// 			),
		// 		),
		// 	'attributes' => array(
		// 		'placeholder' => '#sc_blog',
		// 		'value' => '#sc_blog',
		// 		'description' => $this->translate('Posts marked with these tags would be published from Author account, if author found in SC db')
		// 		),
		// 	]);

		// $this->add([
		// 'name' => 'without_tags',
		// 'type' => 'Zend\Form\Element\Select',
		// 'options' => array(
		// 	'label' => $this->translate('Publish Without Tags'),
		// 	'options' => array(
		// 		0 => $this->translate('No'),
		// 		1 => $this->translate('Yes'),
		// 		),
		// 	'required' => true,
		// 	),
		// 'attributes' => [
		// 	'description' => $this->translate('Publish posts from Author account if found, even no tags in post?')
		// ]
		// ]);

		// $this->add([
		// 'name' => 'add_link',
		// 'type' => 'Zend\Form\Element\Select',
		// 'options' => array(
		// 	'label' => $this->translate('Add Link To Public'),
		// 	'options' => array(
		// 		0 => $this->translate('No'),
		// 		1 => $this->translate('Yes'),
		// 		),
		// 	'required' => true,
		// 	),
		// 'attributes' => [
		// 	'description' => $this->translate('Add link to this public for every post ?')
		// ]
		// ]);

	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();	
	}

}