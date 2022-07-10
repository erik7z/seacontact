<?php
namespace Application\Form\Fieldset;

class Pics extends EmptyFieldset
{
	public function __construct()
	{
		parent::__construct('pics');

		// $this->add(array(
		// 	'name' => 'id',
		// 	'type' => 'Zend\Form\Element\Hidden',
		// 	'options' => [
		// 		'required' => false,
		// 		'filters' => array(
		// 			array('name' => 'Digits'),
		// 			),
		// 		'validators' => array(
		// 			array('name' => 'Digits'),
		// 			),
		// 	]
		// 	));

		$this->add(array(
			'name' => 'img',
			'type' => 'Zend\Form\Element\File',
			'options' => array(
				'label' => $this->translate('Upload Photos'),
				'required' => false,
				'filters'  => array(
					// check zend manual for how to use 

					// array(
					// 	'name' => 'filerenameupload'
					// 	'options' => array(
					// 		'target' => './data/tmpuploads/avatar.png',
					// 		'randomize' => true,
					// 		)
					// 	),
					),
				'validators' => array(
					// Error when uploading multiple images

					// array(
					// 	'name'    => 'fileuploadfile',
					// 	'options' => array(
					// 		),
					// 	),			
					// array(
					// 	'name'    => 'fileimagesize',
					// 	'options' => array(
					// 		'maxWidth'      => 1024,
					// 		'maxHeight'      => 768,
					// 		),
					// 	),
					// array(
					// 	'name'    => 'filesize',
					// 	'options' => array(
					// 		'max'      => '600kb',
					// 		),
					// 	),
					),
				),
			'attributes' => array(
				'id' => 'pics_img',
				'multiple' => true,
				),
			));

		// $this->add(array(
		// 	'name' => 'alt',
		// 	'type' => 'Zend\Form\Element\Text',
		// 	'options' => array(
		// 		'label' => $this->translate('Picture Title'),
		// 		'required' => false,
		// 		'filters' => array(
		// 			array('name' => 'StripTags'),
		// 			),
		// 		'validators' => array(
		// 			array(
		// 				'name' => 'StringLength',
		// 				'options' => array(
		// 					'max' => 256
		// 					),
		// 				),
		// 			),
		// 		),
		// 	'attributes' => array(
		// 		'required' => false,
		// 		),
		// 	));


		// $this->add(array(
		// 	'name' => 'place',
		// 	'type' => 'Zend\Form\Element\Text',
		// 	'options' => array(
		// 		'label' => 'Place',
		// 		'required' => false,
		// 		'filters' => array(
		// 			array('name' => 'StripTags'),
		// 			),
		// 		'validators' => array(
		// 			array(
		// 				'name' => 'StringLength',
		// 				'options' => array(
		// 					'max' => 255
		// 					),
		// 				),
		// 			),
		// 		),
		// 	'attributes' => array(
		// 		'required' => false,
		// 		),
		// 	));

	}


	public function getInputFilterSpecification()
	 {
	 	return $this->getFiltersFromFieldsOptions();
	}

}