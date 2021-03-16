<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class MailForm extends EmptyForm 
{
	public function __construct()
	{
		parent::__construct('mail_form');

		$accounts_list = $this->sl->get('MailAccountsTable')->getFieldCountForSelect('mail_box', [], ['use_cache' => 0, 'show_count' => 0]);
		

		$this->add(array(
			'name' => 'mail_template',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Template'),
				'empty_option' => '--Select--',
				'options' => array(),
				),
			'attributes' => array(
				'id' => 'mail_template_select',
				'required' => false,
				),
			));	

		$this->add(array(
			'name' => 'mail_to',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'To',
				'required' => true,
				'filters'  => array(
					array('name' => 'StringTrim'),
					array('name' => '\Application\Filter\ComaStringToArray'),
					),
				'validators' => array(
					array(
						'name'    => '\Application\Validator\EmailFieldValidator',
						),
					),
				),
			'attributes' => array(
				'placeholder' => 'to:',
				),
			));

		$this->add(array(
			'name' => 'mail_cc',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Cc',
				'required' => false,
				'filters'  => array(
					array('name' => 'StringTrim'),
					array('name'    => '\Application\Filter\ComaStringToArray'),
					),
				'validators' => array(
					array('name'    => '\Application\Validator\EmailFieldValidator'),
					),
				),
			'attributes' => array(
				'placeholder' => 'cc:',
				),
			));


		$this->add(array(
			'name' => 'mail_from',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('From Mail Box'),
				'options' => $accounts_list,
				'required' => true,
				'filters'  => array(
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					),
				),
			));	

		$this->add(array(
			'name' => 'title',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Title'),
				'required' => true,
				'filters'  => array(
					array('name' => 'HtmlEntities'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'max'      => 255,
							'messages' => array(
								\Zend\Validator\
								StringLength::INVALID => 'Title is too long')
							),
						),
					array(
						'name'    => 'NotEmpty',
						),
					),
				),
			'attributes' => array(
				'placeholder' => $this->translate('Title'),
				),
			));

		$this->add(array(
			'name' => 'text',
			'type' => 'Zend\Form\Element\Textarea',
			'options' => array(
				'label' => 'Text',
				'required' => true,
				'filters'  => array(
					array('name' => 'HtmlEntities'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					array(
						'name'    => 'NotEmpty',
						),
					),
				),
			'attributes' => array(
				'placeholder' => '',
				'id' => 'text',
				'rows' => 18,
				),
			));		

		// $this->add(array(
		// 	'name' => 'signature',
		// 	'type' => 'Zend\Form\Element\Textarea',
		// 	'options' => array(
		// 		'label' => 'Signature',
		// 		'required' => true,
		// 		'filters'  => array(
		// 			array('name' => 'HtmlEntities'),
		// 			),
		// 		'validators' => array(
		// 			),
		// 		),
		// 	'attributes' => array(
		// 		'placeholder' => '',
		// 		'rows' => 18,
		// 		'id' => 'signature',
		// 		),
		// 	));

		$this->add(array(
			'name' => 'attachments',
			'type' => 'Zend\Form\Element\File',
			'options' => array(
				'label' => 'Attachments',
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
				'id' => 'attachmentss',
				'multiple' => true,
				),
			));

		$this->add(array(
			'name' => 'template_name',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'New Template Name',
				'required' => true,
				'filters'  => array(
					),
				'validators' => array(
					array(
						'name'    => 'Alnum'
,						'options' => array(
							'allowWhiteSpace' => true,
							)
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