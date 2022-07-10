<?php
namespace Application\Form\Fieldset;

use Zend\Form\Fieldset;

class User extends EmptyFieldset 
{
	public function __construct()
	{
	parent::__construct('user');
		$userTable = $this->sl->get('UserTable');
		$city_list = $userTable->getFieldCountForSelect('home_city', [], ['more_than' => 100]);
		$info_source_list = $userTable->getFieldCountForSelect('info_source', []);
		$desired_ranks = $userTable->getFieldCountForSelect('desired_rank', [], ['more_than' => 1]);

		$this->add(array(
			'name' => 'id',
			'type' => 'hidden',
			));


		$this->add(array(
			'name' => 'login_or_email',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Login Or Email'),
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
							'max'      => 60,
							'messages' => array(
								\Zend\Validator\
								StringLength::INVALID => sprintf($this->translate('Login length should be from %s to %s chars'), 4, 60),
								)
							),
						),			
					),
				),
			'attributes' => array(
				'required' => 'required',
				'placeholder' => $this->translate('Login Or Email'),
				),
			));

		$this->add(array(
			'name' => 'role',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('User Role'),
				'empty_option' => $this->translate('--Role--'),
				'options' => array(),
				'required' => true,
				),
			));	

		// $this->add(array(
		// 	'name' => 'type',
		// 	'type' => 'Zend\Form\Element\Select',
		// 	'options' => array(
		// 		'label' => $this->translate('User Type'),
		// 		'empty_option' => $this->translate('--Type--'),
		// 		'options' => \Application\Model\UserTable::getUserTypes(),
		// 		'required' => true,
		// 		),
		// 	));	

		$this->add([
		  'name' => 'type',
		  'type' => 'Zend\Form\Element\Radio',
		  'options' => array(
		    'label' => $this->translate('Register as'),
		    'value_options' => [
		      ['value' => \Application\Model\UserTable::TYPE_USER, 'label' => $this->translate('Seaman'), 'selected' => true],
		      ['value' => \Application\Model\UserTable::TYPE_COMPANY, 'label' => $this->translate('Company'), 'selected' => false],
		      ['value' => \Application\Model\UserTable::TYPE_OWNER, 'label' => $this->translate('Owner'), 'selected' => false]
		    ]
		    ),
		  ]);

		$this->add(array(
			'name' => 'login',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Login'),
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
							'max'      => 20,
							'messages' => array(
								\Zend\Validator\
								StringLength::INVALID => sprintf($this->translate('Login length should be from %s to %s chars'), 4, 20)
								)
							),
						),
					array(
						'name'    => '\Application\Validator\UserLoginValidator',
						),
					array(
						'name'    => 'DbNoRecordExists',
						'options' => array(
							'table' => 'user',
							'field' => 'login',
							'adapter' => \Application\Model\zAbstractTable::getAdapter(),
							'messages' => [\Zend\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => $this->translate('This login is already used in the system')],
							),
						),							
					),
				),
			'attributes' => array(
				'id' => 'login',
				'placeholder' => $this->translate('Nickname'),
				),
			));


		$this->add(array(
			'name' => 'password',
			'type' => 'Zend\Form\Element\Password',
			'options' => array(
				'label' => $this->translate('Password'),
				'required' => true,
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 5,
							'max'      => 32,
							'messages' => array(
								\Zend\Validator\
								StringLength::INVALID => sprintf($this->translate('Password length should be from %s to %s chars'), 5, 32),
								),

							),
						),
					),
				),
			'attributes' => array(
				'id' => 'password',
				'placeholder' => $this->translate('password'),
				),
			));

		$this->add(array(
			'name' => 'confirm_password',
			'type' => 'Zend\Form\Element\Password',
			'options' => array(
				'label' => $this->translate('Confirm Password'),
				'required' => true,
				'validators' => array(
					array(
						'name'    => 'Identical',
						'options' => array(
							'token' => 'password',
							'messages' => array(
								\Zend\Validator\
								Identical::NOT_SAME => $this->translate('Passwords are not same !')
								)
							),

						),
					),
				),
			'attributes' => array(
				'id' => 'confirm_password',
				'required' => 'required',
				'placeholder' => $this->translate('repeat password'),
				),
			));

		$this->add(array(
			'name' => 'email',
			'type' => 'Zend\Form\Element\Email',
			'options' => array(
				'label' => 'E-mail',
				'required' => true,
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
				'placeholder' => 'yourname@gmail.com',
				),
			));


		$this->add(array(
			'name' => 'email_confirmation_key',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Code'),
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(

					array(
						'name'    => 'Alnum',
						),
					array(
						'name'    => 'NotEmpty',
						),
					),
				),
			'attributes' => array(
				'required' => 'required',
				'placeholder' => $this->translate('code'),
				),
			));			

		$this->add(array(
			'name' => 'password_reset_key',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Code'),
				),
			'attributes' => array(
				'required' => 'required',
				'placeholder' => $this->translate('code'),
				),
			));			


		$this->add(array(
			'name' => 'cv_avatar',
			'type' => 'Zend\Form\Element\File',
			'options' => array(
				'label' => $this->translate('Change CV Photo'),
				'type' => '\Zend\InputFilter\FileInput',
	            'required' => false,
	            'allow_empty' => true,
	            'priority' => 300,
				'filters'  => array(
					),
				'validators' => array(
					/// some zend errors ???????????????????????????????????????????????????????//
					// array(
					// 	'name'    => 'fileuploadfile',
					// 	'options' => array(
					// 		),
					// 	),			
					// array(
					// 	'name'    => 'fileimagesize',
					// 	'options' => array(
					// 		// 'maxWidth'      => 1600,
					// 		// 'maxHeight'      => 1200,
					// 		),
					// 	),
					// array(
					// 	'name'    => 'filesize',
					// 	'options' => array(
					// 		'max'      => '9600kb',
					// 		),
					// 	),
					),
				),
			'attributes' => array(
				'id' => 'cv_avatar_upload',
				'multiple' => false,
				),
			));

		$this->add(array(
			'name' => 'avatar',
			'type' => 'Zend\Form\Element\File',
			'options' => array(
				'label' => $this->translate('Change Main Avatar'),
				),
			'attributes' => array(
				'id' => 'avatar_upload',
				'multiple' => false,
				),
			));

		$this->add(array(
			'name' => 'cv_file',
			'type' => 'Zend\Form\Element\File',
			'options' => array(
				'label' => $this->translate('Upload CV File'),
	            'required' => false,
	            'allow_empty' => true,
	            'priority' => 300,
				'filters'  => array(
					),
				'validators' => array(
					// array(
					// 	'name'    => 'filesize',
					// 	'options' => array(
					// 		'max'      => '5000kb',
					// 		),
					// 	),
					// array(
					// 	'name'    => 'fileextension',
					// 	'options' => array(
					// 		'extension'      => array('pdf', 'txt', 'xls', 'xlsx','doc', 'dot', 'docx', 'docm', 'rtf')
					// 		),
					// 	),
					),
				),
			'attributes' => array(
				'id' => 'cv_file_upload',
				'multiple' => false,
				),
			));



		$this->add(array(
			'name' => 'desired_rank',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Position Applied For'),
				'empty_option' => '--Select--',
				'options' => $desired_ranks,
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					),
				),
			'attributes' => array(
				'id' => 'desired_rank',
				'placeholder' => '2ND OFFICER'
				),
			));	

		$this->add(array(
			'name' => 'minimum_salary',
			'type' => 'Zend\Form\Element\Number',
			'options' => array(
				'label' => $this->translate('Minimum Salary'),
				),
			'attributes' => array(
				'min' => 0,
				'max' => 99999,
				),
			));

		$this->add(array(
			'name' => 'visa_usa',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Usa Visa'),
				'options' => array(
					0 => $this->translate('Not Stated'),
					1 => $this->translate('Yes'),
					2 => $this->translate('No'),
					),
				'required' => false,
				),
			));	

		$this->add(array(
			'name' => 'visa_usa_exp',
			'type' => 'Zend\Form\Element\Date',
			'options' => array(
				'label' => $this->translate('USA Visa Expiry'),
				'required' => false,
				'filters' => array(
					array('name' => '\Application\Filter\FormDateToUnix')
					)
				),
			'attributes' => array(
				'required' => false
				),
			));

		$this->add(array(
			'name' => 'visa_shenghen',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Shenghen Visa'),
				'options' => array(
					0 => $this->translate('Not Stated'),
					1 => $this->translate('Yes'),
					2 => $this->translate('No'),
					),
				'required' => false,
				),
			));	

		$this->add(array(
			'name' => 'visa_shenghen_exp',
			'type' => 'Zend\Form\Element\Date',
			'options' => array(
				'label' => $this->translate('Shenghen Visa Expiry'),
				'required' => false,
				'filters' => array(
					array('name' => '\Application\Filter\FormDateToUnix')
					)
				),
			));


		$this->add(array(
			'name' => 'name',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('First Name'),
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
							'min'      => 3,
							'max'      => 32,
							'messages' => array(
								\Zend\Validator\
								StringLength::INVALID => $this->translate('Name length should be from 3 to 32 chars')
								)
							),
						),
					),
				),
			'attributes' => array(
				'required' => 'required',
				'placeholder' => 'Alexander'
				),
			));

		$this->add(array(
			'name' => 'middle_name',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Middle Name'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 3,
							'max'      => 20,
							),
						),
					),
				),
			));

		$this->add(array(
			'name' => 'full_name',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Full Name'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 3,
							'max'      => 40,
							),
						),
					),
				),
			'attributes' => array(
				'placeholder' => 'Alexandr Bertsev'
				),
			));


		$this->add(array(
			'name' => 'surname',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Last Name'),
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
							'min'      => 3,
							'max'      => 32,
							),
						),
					),
				),
			'attributes' => array(
				'required' => 'required',
				'placeholder' => 'Bertsev'
				),
			));

		$this->add(array(
			'name' => 'sex',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Sex'),
				'empty_option' => '--Select--',
				'options' => array(
					'male' => $this->translate('Male'),
					'female' => $this->translate('Female')
					),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(

					),
				),
			));

		$this->add(array(
			'name' => 'dob',
			'type' => 'Zend\Form\Element\Date',
			'options' => array(
				'label' => $this->translate('Date of birth'),
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					array('name' => '\Application\Filter\FormDateToUnix')
					),
				'validators' => array(
					array(
						'name'    => 'Date',
						'options' => array(
						// 'format' => 'd-m-Y',
							'format' => 'Y-m-d',
							),
						),
					),
				),
			'attributes' => array(
				'required' => 'required',
				),
			));

		$this->add(array(
			'name' => 'place_of_birth',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Place of birth'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(

					),
				),
			));

		$this->add(array(
			'name' => 'nationality',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Nationality'),
				'empty_option' => '--Select country--',
				'options' => $this->getListFromDb('list-countries', 'country_name','country_name'),
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(

					),
				),
			'attributes' => array(
				'id' => 'nationality',
				'placeholder' => 'Ukraine',
				),
			));


		$this->add(array(
			'name' => 'place_of_residence',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Place of residence'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(

					),
				),
			));


		$this->add(array(
			'name' => 'home_country',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Country'),
				'empty_option' => '--Select country--',
				'options' => $this->getListFromDb('list-countries', 'country_name','country_name'),
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(

					),
				),
			));

		$this->add(array(
			'name' => 'home_city',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('City'),
				'empty_option' => '--Select--',
				'options' => $city_list,
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(

					),
				),
			'attributes' => array(
				'id' => 'home_city',
				),
			));

		$this->add(array(
			'name' => 'home_city_text',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('City Free Text'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(

					),
				),

			));

		$this->add(array(
			'name' => 'home_address',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Address'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(

					),
				),
			'attributes' => array(
				'placeholder' => '1, Deribasovskaya str., Odessa, Ukraine'
				),
			));

		$this->add(array(
			'name' => 'contact_email',
			'type' => 'Zend\Form\Element\Email',
			'options' => array(
				'label' => $this->translate('Additional E-mail'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
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
				'placeholder' => 'yourname@gmail.com',
				),
			));


		$this->add(array(
			'name' => 'contact_mobile',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Mobile phone'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				 //   'validators' => array(
					// 	array(
					// 		'name'    => 'Regex',
					// 		'options' => array(
					// 			'pattern' => '/^((\d|\+\d)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/'),

					// 	),
					// ),
				),
			'attributes' => array(
				'placeholder' => '+380.........',
				),			
			));

		$this->add(array(
			'name' => 'contact_mobile_2',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Mobile phone'),
				),
			'attributes' => array(
				'placeholder' => '+380.........',
				),			
			));

		$this->add(array(
			'name' => 'contact_phone',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Phone'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				// 'validators' => array(
				// 	array(
				// 		'name'    => 'Regex',
				// 		'options' => array(
				// 			'pattern' => '/^((\d|\+\d)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/'
				// 			),
				// 		),
				// 	),
				),
			'attributes' => array(
				'placeholder' => '+380.........',
				),			
			));

		$this->add(array(
			'name' => 'contact_phone_2',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Phone'),
				),
			'attributes' => array(
				'placeholder' => '+380.........',
				),			
			));

		$this->add(array(
			'name' => 'contact_fax',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => 'Fax',
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(

					),
				),
			));

		$this->add(array(
			'name' => 'info_website',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Website'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					array(
						'name' => 'Uri',
						'options' => array(
							),
						),
					),
				),
			'attributes' => array(
				'placeholder' => 'http://www....',
				),
			));

		$this->add(array(
			'name' => 'social_vk_parsing',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => sprintf($this->translate('Connect with %s'), 'Vkontakte'),
				'options' => array(
					0 => $this->translate('No'),
					1 => $this->translate('Yes'),
					),
				'required' => false,
				),
			));	

		$this->add(array(
			'name' => 'social_fb_parsing',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => sprintf($this->translate('Connect with %s'), 'Facebook'),
				'options' => array(
					0 => $this->translate('No'),
					1 => $this->translate('Yes'),
					),
				'required' => false,
				),
			));	



		$this->add(array(
			'name' => 'user_notes',
			'type' => 'Zend\Form\Element\Textarea',
			'options' => array(
				'label' => $this->translate('Personal Notes'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(

					),
				),
			'attributes' => array(
				'placeholder' => $this->translate('Any additional info'),
				'rows' => 3,
				),
			));			


		$this->add(array(
			'name' => 'english_knowledge',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('English Knowledge'),
				'empty_option' => '--Select--',
				'value_options' => array(
					0 => 'Not Stated',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					),
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					array(
						'name' => 'Digits',
						'options' => array(
							'min'      => 0,
							'max'      => 10,
							),
						),
					),
				),
			));


		$this->add(array(
			'name' => 'current_location',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Current Location'),
				'empty_option' => '--Location--',
				'value_options' => array(
					'sea' => $this->translate('At Sea'),
					'home' => $this->translate('At Home'),
					),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(

					),
				),
			));


		$this->add(array(
			'name' => 'marital_status',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Marital Status'),
				'empty_option' => '--Status--',
				'value_options' => array(
					'single' => $this->translate('single'),
					'married' => $this->translate('married'),
					'divorced' => $this->translate('divorced'),
					),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 3,
							'max'      => 10,
							),
						),
					),
				),
			));

		$this->add(array(
			'name' => 'next_of_kin',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Next of kin'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 3,
							'max'      => 30,
							),
						),
					),
				),
			));

		$this->add(array(
			'name' => 'childrens',
			'type' => 'Zend\Form\Element\Number',
			'options' => array(
				'label' => $this->translate('Childrens'),
				'required' => false,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					array(
						'name'    => 'Digits',
						'options' => array(
							'min'      => 0,
							'max'      => 10,
							),
						),
					),
				),
			'attributes' => array(
				'min' => 0,
				'max' => 10,
				),
			));

		$this->add(array(
			'name' => 'in_db_date',
			'type' => 'Zend\Form\Element\Date',
			'options' => array(
				'label' => $this->translate('Added To Database'),
				),
			));		

		$this->add(array(
			'name' => 'cv_last_view',
			'type' => 'Zend\Form\Element\Date',
			'options' => array(
				'label' => $this->translate('Last Time Opened CV'),
				),
			));		

		$this->add(array(
			'name' => 'cv_last_call',
			'type' => 'Zend\Form\Element\Date',
			'options' => array(
				'label' => $this->translate('Last Time Called'),
				),
			));

		$this->add(array(
			'name' => 'readiness_date',
			'type' => 'Zend\Form\Element\Date',
			'options' => array(
				'label' => $this->translate('Readiness Date'),
				'required' => false,
				'filters' => array(
					array('name' => '\Application\Filter\FormDateToUnix'),
					),
				'validators' => array(
					// array('name' => '\Application\Validator\FormDateInFuture'),
					),
				),
			));


		$this->add(array(
			'name' => 'info_source',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Information Source'),
				'empty_option' => '--Select--',
				'options' => $info_source_list,
				),
			));	

		$this->add(array(
			'name' => 'company_name',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Company Name'),
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
							'max'      => 64,
							'messages' => array(
								\Zend\Validator\
								StringLength::INVALID => $this->translate('Company Name length should be from 4 to 64 chars')
								)
							),
						),
					array(
						'name'    => 'DbNoRecordExists',
						'options' => array(
							'table' => 'user',
							'field' => 'company_name',
							'adapter' => \Application\Model\zAbstractTable::getAdapter(),
							'messages' => array (
								\Zend\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => $this->translate('This Company Name is already used in the system'),
								),
							),
						),							
					),
				),
			'attributes' => array(
				'placeholder' => $this->translate('Your Company Name')
				),			
			));

		$this->add(array(
			'name' => 'company_description',
			'type' => 'Zend\Form\Element\Textarea',
			'options' => array(
				'label' => $this->translate('Company Description'),
				),
			'attributes' => array(
				'placeholder' => $this->translate('Tell something about your company'),
				'rows' => 4,
				),
			));			

		$this->add(array(
			'name' => 'company_license',
			'type' => 'Zend\Form\Element\Text',
			'options' => array(
				'label' => $this->translate('Company License'),
				),
			'attributes' => array(
				'placeholder' => 'Your Company license number',
				),			
			));


	}

	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}


}