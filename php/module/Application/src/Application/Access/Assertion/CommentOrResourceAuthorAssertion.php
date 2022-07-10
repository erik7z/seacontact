<?php
namespace Application\Access\Assertion;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class CommentOrResourceAuthorAssertion implements \Zend\Permissions\Acl\Assertion\AssertionInterface
{

	public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
	{
		/*
			BE AWARE TO USE WITH MENU 
			check if request_from and resource id is provided !	

		 */
		// allow if requests to self resources
		if($acl->request_from_id && $acl->request_to_id && ($acl->request_from_id == $acl->request_to_id)) return true;

		// converting null values
		$request_from_id = (int)$acl->request_from_id;
		$resource_id = (int)$acl->resource_id;

		$sl = \ServiceLocatorFactory\ServiceLocatorFactory::getInstance();
		$comment = $sl->get('CommentsTable')->get($resource_id);

		if($comment) {
			// if im author of the comment allow
			$parent_resource = null;
			if($comment->user_id == $request_from_id) return true;
			// if im author of the resource
			if($comment->section == \Application\Model\NewsTable::SECTION_LOGBOOK) {
				$parent_resource = $sl->get('LogbookTable')->get($comment->section_id);
				$owner_id = ($parent_resource) ? $parent_resource->user : null;
			} elseif ($comment->section == \Application\Model\NewsTable::SECTION_VACANCY) {
				$parent_resource = $sl->get('VacanciesTable')->get($comment->section_id);
				$owner_id = ($parent_resource) ? $parent_resource->user : null;
			} elseif ($comment->section == \Application\Model\NewsTable::SECTION_USER) {
				$owner_id = $comment->section_id;
			} elseif ($comment->section == \Application\Model\NewsTable::SECTION_QUESTIONS) {
				$parent_resource = $sl->get('QuestionsTable')->get($comment->section_id);
				$owner_id = ($parent_resource) ? $parent_resource->user : null;
			} elseif ($comment->section == \Application\Model\NewsTable::SECTION_ANSWERS) {
				$parent_resource = $sl->get('QuestionAnswersTable')->get($comment->section_id);
				$owner_id = ($parent_resource) ? $parent_resource->user : null;
			}
	
			// if im the parent resource author allow
			if($owner_id && ($owner_id == $request_from_id)) return true;
		}

		return false;
	}

	
}