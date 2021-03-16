<?php
namespace Application\Form\Fieldset;

class UserFiles extends EmptyFieldset
{
	public function __construct()
	{
		parent::__construct('user_files');

		$this->add(array(
			'name' => 'id',
			'type' => 'Zend\Form\Element\Hidden',
			));

		$this->add(array(
			'name' => 'file',
			'type' => 'Zend\Form\Element\File',
			'options' => array(
				'label' => $this->translate('Upload My CV File'),
				'required' => false,
				'filters'  => array(
					),
				'validators' => array(
					array(
						'name'    => 'fileuploadfile',
						'options' => array(
							),
						),			
					array(
						'name'    => 'filesize',
						'options' => array(
							'max'      => '2400kb',
							),
						),
					),
				),
			'attributes' => array(
				'id' => 'cv_file_upload',
				'multiple' => false,
				),
			));


		$this->add(array(
			'name' => 'comments',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Comments'),
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'max'      => 255,
							'messages' => array(
								\Zend\Validator\
								StringLength::INVALID => 'Your comment is too long')
							),
						),
					),
				),
			));


	}



	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}


}