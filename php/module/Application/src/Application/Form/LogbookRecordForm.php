<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class LogbookRecordForm extends EmptyForm 
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
			));
		return $this;
	}

	public function __construct()
	{
		parent::__construct('logbook_record');

		$this->add(array(
			'name' => 'id',
			'type' => 'hidden',
			));

		$this->add(array(
			'name' => 'text',
			'type' => 'textarea',
			'options' => array(
				'label' => $this->translate('Text'),
				'required' => false,
				'filters' => array(
					array('name' => 'StringTrim'),
					array('name' => '\Application\Filter\NewLineToBr'),
					array('name' => '\Application\Filter\UrlToLinks'),
					array('name' => 'HtmlEntities'),
					),
				),
			'attributes' => array(
				'placeholder' => $this->translate('What news you have ?'),
				'rows' => 3,
				'id' => 'logbook_data',
				),
			));

		$this->add(array(
			'name' => 'tags',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('#Tags'),
				'required' => false,
				'filters' => array(
					array('name' => '\Application\Filter\HashTag'),
					),
				),
			'attributes' => array(
				'required' => 0,
				'placeholder' => $this->translate('Keywords describing your note'),
				'id' => 'tags'
				),
			));

		$this->add(array(
			'name' => 'time',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Publish Time'),
				),
			'attributes' => array(
				'required' => false,
				'placeholder' => $this->translate('Publish Now'),
				),
			));

		$this->add(array(
			'name' => 'pics',
			'type' => 'Zend\Form\Element\File',
			'options' => array(
				'label' => $this->translate('Add Image'),
				'required' => false,
				'filters'  => array(
					),
				'validators' => array(
					array(
						'name'    => 'fileuploadfile',
						'options' => array(
							),
						),			
					// array(
					// 	'name'    => 'fileimagesize',
					// 	'options' => array(
					// 		// 'maxWidth'      => 1600,
					// 		// 'maxHeight'      => 1200,
					// 		),
					// 	),
					array(
						'name'    => 'filesize',
						'options' => array(
							'max'      => '9600kb',
							),
						),
					),
				),
			'attributes' => array(
				'id' => 'pics_upload',
				'class' => 'pics_upload',
				'multiple' => true,
				),
			));

		$this->add(array(
			'name' => 'video',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Youtube Video'),
				'required' => false,
				'filters' => [],
				),
			'attributes' => array(
				'required' => 0,
				'placeholder' => $this->translate('Youtube embed video link'),
				),
			));

		$this->add(array(
			'name' => 'link',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Reference Link'),
				'required' => false,
				'filters' => [],
				),
			'attributes' => array(
				'required' => 0,
				'placeholder' => $this->translate('Link'),
				),
			));

		$this->add(array(
			'name' => 'link_title',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Title'),
				'required' => false,
				'filters' => [],
				),
			'attributes' => array(
				'required' => 0,
				),
			));

		$this->add(array(
			'name' => 'link_description',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Description'),
				'required' => false,
				'filters' => [],
				),
			'attributes' => array(
				'required' => 0,
				),
			));


		$this->add(array(
			'name' => 'link_img',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Link Image'),
				'required' => false,
				'filters' => [],
				),
			'attributes' => array(
				'required' => 0,
				),
			));


		$this->addSubmit($this->translate('Add Record'), '');

	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}


}