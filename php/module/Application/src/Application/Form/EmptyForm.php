<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class EmptyForm extends Form implements InputFilterProviderInterface
{
	protected $inputFilters = array();
	protected $autoload_filters;
	protected $sl;
	private $inputFilterElement;

	private $count = 0;

	public function __construct($name = null)
	{
		parent::__construct($name);
		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype', 'multipart/form-data');
		$this->sl = \ServiceLocatorFactory\ServiceLocatorFactory::getInstance();

	}

	public function translate($string)
	{
		return \Application\Translator\StaticTranslator::getTranslator()->translate($string);
	}

	public function getCollection($name, $label, $fieldset, $allow_add = true, $create_template = true)
	{
		return array(
			'type' => 'Zend\Form\Element\Collection',
			'name' => $name,
			'options' => array(
				'label' => $label,
				'should_create_template' => $create_template,
				'allow_add' => $allow_add,
				'allow_remove' => true,
				'template_placeholder' => '__placeholder__',
				'target_element' => array(
					'type' => $fieldset,
					),
				),
			);
	}


	public function addOldPictureFields($items_from_db = null)
	{
		if(!$items_from_db) return;
		$old_items_field = new \Application\Form\Fieldset\EmptyFieldset('old_pics', false, false);
		foreach ($items_from_db as $item) {
			$old_items_field->add(array(
				'type' => 'Zend\Form\Element\Checkbox',
				'name' => $item->id,
				'options' => array(
					'label' => $item->alt,
					'use_hidden_element' => true,
					'checked_value' => 'stay',
					'unchecked_value' => 'delete',
					),
				'attributes' => array(
					'data-thumb' => $item->thumb,
					'data-img' => $item->img,
					'checked' => 'checked',
					),
				));		
		}

		$old_items_field->setLabel($this->translate('Attached pictures').' :');
		$this->add($old_items_field);
		return $this;
	}

	public function addOldLinkFields($items_from_db = null)
	{
		if(!$items_from_db) return;
		$old_items_field = new \Application\Form\Fieldset\EmptyFieldset('old_links', false, false);
		foreach ($items_from_db as $item) {
			$old_items_field->add(array(
				'type' => 'Zend\Form\Element\Checkbox',
				'name' => $item->id,
				'options' => array(
					'label' => $item->title,
					'use_hidden_element' => true,
					'checked_value' => 'stay',
					'unchecked_value' => 'delete',
					),
				'attributes' => array(
					'data-title' => strip_tags(htmlspecialchars_decode($item->title)),
					'data-description' => zlessChars(strip_tags(htmlspecialchars_decode($item->description)), 50),
					'data-thumb' => strip_tags(htmlspecialchars_decode($item->thumb)),
					'data-url' => strip_tags(htmlspecialchars_decode($item->url)),
					'checked' => 'checked',
					),
				));		
		}
		
		$old_items_field->setLabel($this->translate('Attached Links').' :');
		$this->add($old_items_field);
		return $this;
	}
	public function addOldVideoFields($items_from_db = null)
	{
		if(!$items_from_db) return;
		$old_items_field = new \Application\Form\Fieldset\EmptyFieldset('old_videos', false, false);
		foreach ($items_from_db as $item) {
			$old_items_field->add(array(
				'type' => 'Zend\Form\Element\Checkbox',
				'name' => $item->id,
				'options' => array(
					'label' => $item->title,
					'use_hidden_element' => true,
					'checked_value' => 'stay',
					'unchecked_value' => 'delete',
					),
				'attributes' => array(
					'data-title' => strip_tags(htmlspecialchars_decode($item->title)),
					'data-description' => zlessChars(strip_tags(htmlspecialchars_decode($item->description)), 50),
					'data-thumb' => strip_tags(htmlspecialchars_decode($item->thumb)),
					'data-url' => strip_tags(htmlspecialchars_decode($item->url)),
					'data-embed' => strip_tags(htmlspecialchars_decode($item->embed_url)),
					'checked' => 'checked',
					),
				));		
		}

		$old_items_field->setLabel($this->translate('Attached Videos').' :');
		$this->add($old_items_field);
		return $this;
	}

	public function addPicsField($multiple = true, $label = null, $id = 'pics_upload', $class = 'pics_upload')
	{
		if(!$label) $label = $this->translate('Add Pictures');
		$this->add(array(
			'name' => 'pics',
			'type' => 'Zend\Form\Element\File',
			'options' => array(
				'label' => $label,
				),
			'attributes' => array(
				'id' => $id,
				'class' => $class,
				'multiple' => $multiple,
				),
			));
	}

	public function addSubmit($value = null, $label = null, $name = 'submit')
	{
		// $this->add(array(
		// 	'type' => 'Zend\Form\Element\Csrf',
		// 	'name' => 'csrf',
		// 	));

		if(!$label && !$value) $label = $this->translate('Submit Form');
		if(!$value) $value = $this->translate('Submit');
		else if($value && !$label) {
			$label = $value; 
		}
		
		$this->add(array(
			'name' => $name,
			'attributes' => array(
				'type' => 'submit',
				'value' => $value,
				// 'class' => 'someclass',
				),
			'options' => array(
				'label' => $label,
				),
			));
		return $this;
	}

	public function getInputFilterSpecification()
	 {
	 	return $this->inputFilters;
	 }

	// public function setInputFilterSpecification(array $array)
	// {
	// 	$this->inputFilters = $array;
	// }
	// 
	
	public function getFiltersFromFieldsOptions()
	{
		$fields = $this->getElements();
		$input_filters = [];
		foreach ($fields as $field_name => $field_object) {
			$options = $field_object->getOptions();
			$input_filters[$field_name]['required'] 	= 	(isset($options['required']))? $options['required'] : false;
			$input_filters[$field_name]['filters'] 		=  	(isset($options['filters']))? $options['filters'] : [];
			$input_filters[$field_name]['validators'] 	=  	(isset($options['validators'])) ? $options['validators'] : [];
		}
		return $input_filters;
	}

	// leave only required fields in fieldset
	public function remainFields(array $array)
	{
		$fields = $this->getElements();
		foreach ($fields as $key => $value) {
			if(array_search($key, $array) === false)
				$this->remove($key);
		}
		return $this;
	}
	
	public function removeFields(array $array)
	{
		for($i = 0; $i < count($array); $i++) {
			if($this->has($array[$i])) $this->remove($array[$i]);
		}
		return $this;
	}


	public function setRequiredFields($fieldOrFieldSet, $required = true, $innerFieldSet = null) {
		if(is_array($fieldOrFieldSet)) {
			foreach ($fieldOrFieldSet as $fieldSetName => $field) {
				if(is_array($field)) {
					foreach ($field as $innerField) {
						$this->setRequiredFields($innerField, $required, $fieldSetName);
					}
				} else $this->setRequiredFields($fieldOrFieldSet, $required);
			}
		} else {
			if(isset($innerFieldSet) && $this->get($innerFieldSet)->has($fieldOrFieldSet)) {
				$this->getInputFilter()->get($innerFieldSet)->get($fieldOrFieldSet)->setRequired($required);
				$this->get($innerFieldSet)->get($fieldOrFieldSet)->setAttribute('required', $required);
			} else if($this->has($fieldOrFieldSet)) {
				$this->getInputFilter()->get($fieldOrFieldSet)->setRequired($required);
				$this->get($fieldOrFieldSet)->setAttribute('required', $required);
			}
		}
		return $this;
	}

	public function removeValidator(array $fields)
	{
		foreach ($fields as $fieldName => $validatorName) {
				$this->inputFilterElement = $this->getFormInputFilterElement($fieldName);
				$this->count++;
			if(is_array($validatorName)) {
				$this->removeValidator($validatorName);
			} else {
				$newValidatorChain = $this->removeValidatorFromChain($this->inputFilterElement, $validatorName);
				$this->inputFilterElement->setValidatorChain($newValidatorChain);
				$this->count = 0;				
			}
		}
		return $this;
	}


	private function removeValidatorFromChain($inputFilterElement, $validatorName)
	{
	$newValidatorChain = new \Zend\Validator\ValidatorChain;
	$instance = '\Zend\Validator\\'.$validatorName;
	if(!class_exists($instance)) throw new \Application\Exception\Exception("Cannot remove Validator, validator with name $validatorName Not Found", 1);
	

		foreach ($inputFilterElement->getValidatorChain()->getValidators() as $validator) {
		    if (!($validator['instance'] instanceof $instance)) {
		        $newValidatorChain->addValidator($validator['instance'], $validator['breakChainOnFailure']);
		    }
		}
		return $newValidatorChain;
	}

	public function getFormInputFilterElement($elementName)
	{
		if($this->count < 1) {
			return	$this->getInputFilter()->get($elementName);
		}
		return $this->inputFilterElement->get($elementName);
	}

}