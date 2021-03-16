<?php
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\InjectApplicationEventInterface;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ApiQoptions extends AbstractPlugin
{

	public function __invoke($default_options = [], $permitted_fields = [])
	{
		$controller = $this->getController();
		$namespace = str_replace('\Controller', '', $controller->routeMatch()->getParam('controller'));
		$validator = $controller->get('InputFilterManager')->get($namespace.'\\Validator');
		$validator->setData($controller->queryParams());
		if (!$validator->isValid()) throw new \Application\Exception\Exception(serialize($validator->getMessages()), 777);
		$data = array_filter($validator->getValues());

		if(!isset($default_options['_limit'])) $q_options['_limit'] = 25;
		if(isset($data['_limit'])) {
			$q_options['_limit'] = $data['_limit'];
			unset($data['_limit']);
		}
		if(!isset($default_options['_page'])) $q_options['_page'] = 1;
		if(isset($data['_page'])) {
			$q_options['_page'] = $data['_page'];
			unset($data['_page']);
		}
		
		foreach ($permitted_fields as $fields_section => $fields) {
			if(isset($data[$fields_section])) {
				$data[$fields_section] = explode(',', $data[$fields_section]);
				for ($i=0; $i < count($data[$fields_section]); $i++) { 
					if(!in_array($data[$fields_section][$i], $fields)) 
						throw new \Application\Exception\Exception('Field '.$data[$fields_section][$i].' is not recognized', 406);
				}
				$q_options[$fields_section] = $data[$fields_section];
				unset($data[$fields_section]);
			}
		}

		return ['filters' => $data, 'q_options' => array_merge($default_options, $q_options)];
	}
}