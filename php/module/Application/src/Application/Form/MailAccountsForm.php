<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class MailAccountsForm extends EmptyForm 
{
	public function __construct()
	{
		parent::__construct('mail_accounts_form');

		$access_list = ['mail_office' => 'mail_office', 'mail_crew' => 'mail_crew'];
		$transport_list = ['smtp' => 'smtp', 'sendmail' => 'sendmail'];


		$this->add(array(
			'name' => 'id',
			'type' => 'hidden',
			));


		$this->add(array(
			'name' => 'title',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Mailbox Title'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StringTrim'),
					),
				'validators' => array(
				),
			),
			'attributes' => array(
				'placeholder' => 'Office mail',
				),
		));

		$this->add(array(
			'name' => 'mail_box',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Mail Box'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					array(
						'name' => 'EmailAddress',
						'options' => array(
							'domain' => true,
							'messages' => array(
								\Zend\Validator\
								EmailAddress::INVALID_FORMAT => $this->translate('Please check your e-mail address')
								)
							),
						),
					),
				),
			'attributes' => array(
				'placeholder' => 'office@mail.ru',
				),
			));

		$this->add(array(
			'name' => 'imap_host',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Imap Host'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StringTrim'),
					),
				'validators' => array(
				),
			),
			'attributes' => array(
				'placeholder' => 'imap.mail.ru',
				),
			));

		$this->add(array(
			'name' => 'imap_port',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Imap Port'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StringTrim'),
					),
				'validators' => array(
				),
			),
			'attributes' => array(
				'placeholder' => '147',
				),
			));

		$this->add(array(
			'name' => 'imap_ssl',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Imap SSL'),
				'options' => [
					NULL => 'No SSL',
					'ssl' => 'ssl',
					'tls' => 'tls',
				],
				'required' => false,
				'filters'  => array(
					),
				'validators' => array(
					),
				),
			));	



		$this->add(array(
			'name' => 'smtp_host',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Smtp Host'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StringTrim'),
					),
				'validators' => array(
				),
			),
			'attributes' => array(
				'placeholder' => 'smtp.mail.ru',
				),
			));

		$this->add(array(
			'name' => 'smtp_port',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Smtp Port'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StringTrim'),
					),
				'validators' => array(
				),
			),
			'attributes' => array(
				'placeholder' => '465',
				),
			));

		$this->add(array(
			'name' => 'smtp_ssl',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Smtp SSL'),
				'options' => [
					NULL => 'No SSL',
					'ssl' => 'ssl',
					'tls' => 'tls',
				],
				'required' => false,
				'filters'  => array(
					),
				'validators' => array(
					),
				),
			));	



		$this->add(array(
			'name' => 'user_name',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Login'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StringTrim'),
					),
				'validators' => array(
				),
			),
			'attributes' => array(
				'placeholder' => 'login',
				),
			));


		$this->add(array(
			'name' => 'password',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Password'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					),
				),
			'attributes' => array(
				'id' => 'password',
				'placeholder' => $this->translate('password'),
				),
			));


		$this->add(array(
			'name' => 'access_level',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Access Level'),
				'options' => $access_list,
				'required' => false,
				'filters'  => array(
					),
				'validators' => array(
					),
				),
			));	


		$this->add(array(
			'name' => 'transport',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Send Via'),
				'options' => $transport_list,
				'required' => false,
				'filters'  => array(
					),
				'validators' => array(
					),
				),
			));	

		$this->add(array(
			'name' => 'priority',
			'type' => 'Zend\Form\Element\Number',
			'options' => array(
				'label' => $this->translate('Priority'),
				),
			'attributes' => array(
				'min' => 0,
				'max' => 10,
				),
			));

	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}


}