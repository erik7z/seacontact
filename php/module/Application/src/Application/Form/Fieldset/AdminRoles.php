<?php
namespace Application\Form\Fieldset;

class AdminRoles extends EmptyFieldset
{
	public function __construct()
	{
		parent::__construct('admin_roles');


		$this->add(array(
			'name' => 'roles',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Inherit From'),
				'empty_option' => $this->translate('--Role--'),
				'options' => array(),
				'required' => false,
				),
			));	

		$this->add(array(
			'name' => 'new_role',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Role Name'),
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 4,
							'max'      => 32,
							'messages' => array(
								\Zend\Validator\
								StringLength::INVALID => $this->translate('Role length should be from 4 to 32 chars')
								)
							),
						),
					// array(
						// 'name'    => '\Application\Validator\UserRoleValidator',
						// ),						
					),
				),
			'attributes' => array(
				'required' => 'required',
				'placeholder' => $this->translate('content_manager'),
				),
			));



	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();	
	}

}