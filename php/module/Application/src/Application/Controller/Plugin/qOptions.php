<?php
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\InjectApplicationEventInterface;

class qOptions extends AbstractPlugin
{

	public function __invoke($default_options = [])
	{
		$request = $this->getController()->getRequest();
		$q_options['q'] = $request->getQuery('q', (isset($default_options['q'])? $default_options['q'] : null ));
		$q_options['_order'] = $request->getQuery('_order', (isset($default_options['_order'])? $default_options['_order'] : null ));
		$q_options['up'] = $request->getQuery('up', (isset($default_options['up'])? $default_options['up'] : null ));
		$q_options['_limit'] = $request->getQuery('_limit', (isset($default_options['_limit'])? $default_options['_limit'] : 10 ));
		$q_options['_offset'] = $request->getQuery('_offset', (isset($default_options['_offset'])? $default_options['_offset'] : null ));
		$q_options['_page'] = $request->getQuery('_page', (isset($default_options['_page'])? $default_options['_page'] : 1 ));
		$q_options['layout'] = $request->getQuery('layout', (isset($default_options['layout'])? $default_options['layout'] : null ));
		$q_options['show_fields'] = $request->getQuery('show_fields', (isset($default_options['show_fields'])? $default_options['show_fields'] : null ));
		$q_options['form_fields'] = $request->getQuery('form_fields', (isset($default_options['form_fields'])? $default_options['form_fields'] : null ));
		$filters = $request->getQuery('filters');
		$q_options['filters'] = ($filters)? array_filter($filters) : (isset($default_options['filters'])? $default_options['filters'] : [] );
		return array_merge($default_options, $q_options);
	}
}