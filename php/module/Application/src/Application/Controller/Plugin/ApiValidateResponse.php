<?php
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\InjectApplicationEventInterface;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ApiValidateResponse extends AbstractPlugin
{

	public function __invoke($data_list = [], $q_options = [], $predicat = null)
	{
		$permissions = $this->getController()->get('Access');

		$role = $this->getController()->get('api-identity')->getRoleId();
		$auth_identity = $this->getController()->get('api-identity')->getAuthenticationIdentity();
		$resource_id = null;

		$c_d = count($data_list);
		for ($i=0; $i < $c_d; $i++) { 
		   foreach ($q_options as $f_section => $r_fields) {
		      if(strpos($f_section, '_fields') !== false && is_array($r_fields)) {
		      	if($f_section == '_user_fields' || $f_section == '_experience_fields' || $f_section == '_docs_fields') {
		      		$req_to_id = null;
		      		if(isset($data_list[$i]['user_id'])) $req_to_id = $data_list[$i]['user_id'];
		      		if($predicat && isset($data_list[$i][$predicat.'user_id'])) 
		      			$req_to_id = $data_list[$i][$predicat.'user_id'];
		      		$c_op = count($r_fields);
		      		for ($k=0; $k < $c_op; $k++) { 
		      			if(!$permissions->isPermitted($f_section.'\\'.$r_fields[$k], $req_to_id, $resource_id, $role, $auth_identity['user_id'])) 
		      		        $data_list[$i][$predicat.$r_fields[$k]] = '*******';
		      		}
		      	}
		      	if($f_section == '_vacancy_fields') {
		      		if(isset($data_list[$i]['company_id'])) {
		      			if(isset($data_list[$i]['vacancy_id'])) $resource_id = $data_list[$i]['vacancy_id'];
		      			$data_list[$i]['vacancy_can_edit'] = ($permissions->isPermitted('application\controller\vacancies.edit', $data_list[$i]['company_id'], $resource_id, $role, $auth_identity['user_id'])) ? 1 : 0;
		      			$data_list[$i]['vacancy_can_delete'] = ($permissions->isPermitted('application\controller\vacancies.delete', $data_list[$i]['company_id'], $resource_id, $role, $auth_identity['user_id']))  ? 1 : 0; 
		      		}
		      	}
		      	if($f_section == '_logbook_fields') {
		      		if(isset($data_list[$i]['user_id'])) {
		      			if(isset($data_list[$i]['logbook_id'])) $resource_id = $data_list[$i]['logbook_id'];
		      			$data_list[$i]['logbook_can_edit'] = ($permissions->isPermitted('application\controller\logbook.edit', $data_list[$i]['user_id'], $resource_id, $role, $auth_identity['user_id'])) ? 1 : 0;
		      			$data_list[$i]['logbook_can_delete'] = ($permissions->isPermitted('application\controller\logbook.delete', $data_list[$i]['user_id'], $resource_id, $role, $auth_identity['user_id']))  ? 1 : 0; 
		      		}
		      	}
		      	if($f_section == '_question_fields') {
		      		if(isset($data_list[$i]['user_id'])) {
		      			if(isset($data_list[$i]['question_id'])) $resource_id = $data_list[$i]['question_id'];
		      			$data_list[$i]['question_can_edit'] = ($permissions->isPermitted('application\controller\logbook.edit', $data_list[$i]['user_id'], $resource_id, $role, $auth_identity['user_id'])) ? 1 : 0;
		      			$data_list[$i]['question_can_delete'] = ($permissions->isPermitted('application\controller\logbook.delete', $data_list[$i]['user_id'], $resource_id, $role, $auth_identity['user_id']))  ? 1 : 0; 
		      		}
		      	}
		      	if($f_section == '_comments_fields') {
		      		if(isset($data_list[$i]['author_id'])) {
		      			if(isset($data_list[$i]['comment_id'])) $resource_id = $data_list[$i]['comment_id'];
		      			$data_list[$i]['comment_can_edit'] = ($permissions->isPermitted('my\controller\comments.edit', $data_list[$i]['author_id'], $resource_id, $role, $auth_identity['user_id'])) ? 1 : 0;
		      			$data_list[$i]['comment_can_delete'] = ($permissions->isPermitted('my\controller\comments.delete', $data_list[$i]['author_id'], $resource_id, $role, $auth_identity['user_id']))  ? 1 : 0; 
		      		}
		      	}
		      	if($f_section == '_answer_fields') {
		      		if(isset($data_list[$i]['q_author_id'])) {
		      			if(isset($data_list[$i]['answer_id'])) $resource_id = $data_list[$i]['answer_id'];
		      			$data_list[$i]['answer_can_edit'] = ($permissions->isPermitted('application\controller\questions.answer-edit', $data_list[$i]['q_author_id'], $resource_id, $role, $auth_identity['user_id'])) ? 1 : 0;
		      			$data_list[$i]['answer_can_delete'] = ($permissions->isPermitted('application\controller\questions.answer-delete', $data_list[$i]['q_author_id'], $resource_id, $role, $auth_identity['user_id']))  ? 1 : 0; 
		      		}
		      	}
		      }
		   }
		}
		return $data_list;
	}
}