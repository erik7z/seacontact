<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class SettingsForm extends EmptyForm
{
	public function __construct()
	{
		parent::__construct('user_settings');

		$this->add(array(
			'type' => 'Zend\Form\Element\Radio',
			'name' => 'access_logbook',
			'options' => array(
				'label' => 'Who can access my Logbook',
				'value_options' => array(
					0 => 'Everybody',
					1 => 'Only Friends',
					),
				),
			));

		$this->add(array(
			'name' => 'Friends',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => 'Friends',
				'empty_option' => '--Select--',
				'options' => [],
				),
			));


		$this->addSubmit('Save', '');
	}

	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}
}