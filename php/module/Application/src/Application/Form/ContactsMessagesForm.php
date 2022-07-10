<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class ContactsMessagesForm extends EmptyForm
{
	public function __construct()
	{
		parent::__construct('user_messages');

		$this->add(array(
			'name' => 'text',
			'attributes' => array(
				'type' => 'textarea',
				'id' => 'message_text',
				'required' => 'required',
				),
			'options' => array(
				'label' => '',
				),
			));

		$this->addSubmit("Send Message &#x00A; (Ctrl+Enter)", '');
	}

	public function getInputFilterSpecification()
	{
		return array(
			'text' => array(
				'required' => true,
				'filters' => array(
					array('name' => '\Application\Filter\NewLineToBr'),
					array('name' => 'HtmlEntities'),
					array('name' => '\Application\Filter\UrlToLinks'),
					),
				),
			);
	}
}