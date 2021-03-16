<?php
namespace Application\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
 
class EmptyFieldset extends Fieldset implements InputFilterProviderInterface
{
	protected $inputFilters = array();
	protected $autoload_filters;
	protected $translator;
	protected $sl;


	public function __construct($name = null, $base_fieldset = true, $autoload_filters = true)
	{
		$options = ($base_fieldset)? array('use_as_base_fieldset', true) : array();
		parent::__construct($name, $options);
		$this->autoload_filters = $autoload_filters;
		$this->translator = \Application\Translator\StaticTranslator::getTranslator();
		$this->sl = \ServiceLocatorFactory\ServiceLocatorFactory::getInstance();

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
				$this->remove($array[$i]);
		}
		return $this;
	}


	public function getInputFilterSpecification()
	 {
	 	return $this->getFiltersFromFieldsOptions();
	 }

	public function cleanAndSetInputFilters(array $filters)
	{
	 	if(empty($this->inputFilters) && $this->autoload_filters) {
	 		$this->inputFilters = $this->cleanupFilters($filters);
	 	}
	 	return $this->inputFilters;		
	}

	protected function getListFromDb($db_table, $value_field, $id_field = 'id', $count = 50)
	{
		$table = new \Application\Model\zEmptyTable($db_table);
		return $this->getListFromArray($table->getAll(), $value_field, $id_field, $count);
	}

	public function getListFromArray($arrayObj, $value_field, $id_field = 'id')
	{

		$list = array();
		foreach ($arrayObj as $item) {
			$item = (array) $item;
			if (isset($item[$id_field])) {
				$list[$item[$id_field]] = $item[$value_field];
			}

		}
		return $list;
	}

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

	//loading input filters configuration from file. 
	// unfortunately doesnt work with Collections ($this->getName() returns 0)
	protected function loadFiltersFromFile($fileName = null)
	 {
	 	if(!$fileName) $fileName = $this->getName();

	 	$file = __DIR__.'/'.$fileName.'.php';
	 	if(file_exists($file)) {
		 	$inputFilters = include $file;
	 	} else throw new \Application\Exception\Exception("Filters file $file not found!", 1);
	 	
		return $inputFilters;
	 }

	 protected function cleanupFilters($filters)
	 {
	 	$fields = $this->getElements();
	 	$inputFilters = array();
	 	foreach ($fields as $key => $value) {
	 		if(array_key_exists($key, $filters))
	 			$inputFilters[$key] = $filters[$key];
	 	}
	 	return $inputFilters;
	 }


	// public function setFilters(array $filters_names = array())
	// {
	// 	if(empty($filters_names)) $this->inputFilters = $this->getDefaultFilters();
	// 	else {
	// 		$this->inputFilters = array();
	// 		$defaultFilters = $this->getDefaultFilters();
	// 		foreach ($filters_names as $key => $value) {
	// 	 		if(array_key_exists($value, $defaultFilters)) {
	// 	 			$this->inputFilters[$value] = $defaultFilters[$value];
	// 	 		}
	//  		}
	// 	}
	// 	return $this;
	// }
	

	protected function translate($text)
	{
		return $this->translator->translate($text);
	}
}