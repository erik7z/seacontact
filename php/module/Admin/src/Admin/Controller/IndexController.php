<?php
namespace Admin\Controller;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class IndexController extends AbstractController
{

	
	/**
	 * Index action of some controller
	 * @return [type]
	 */

	public function indexAction()
	{


	$tasks = $this->get('TasksTable')->getTasks($this->identity()->id, ['my_tasks' => 1]);


	$view = new ViewModel(array(
		'tasks' => $tasks
		));
	return $view;		
	}


	public function readNotificationAction()
	{
		$not_type = ($this->params()->fromQuery('type')) ? $this->params()->fromQuery('type') : null;
		$not_section = $this->params()->fromQuery('section');
		$not_id = $this->params()->fromRoute('id');
		
		$result = $this->get('AdminNotifReadedTable')->readNotifications($this->identity()->id, $not_type, $not_section, $not_id);

		return $this->viewResponse(1, $this->translate('Notifications Readed'), [
			'view_data' => zformatDateTimeYear(time()),
			'redirect' => $this->url()->fromRoute('admin/actions', array('controller' => 'userdb', 'action' => 'index'))
			]);
	}

	public function deleteAction()
	{

		$section = $this->params()->fromQuery('section');
		$delete_id = $this->params()->fromQuery('id');
		$redirect_action = ($this->params()->fromQuery('redirect')) ? $this->params()->fromQuery('redirect') : $this->request->getServer()->HTTP_REFERER;

		try {
			if(!$section || !$delete_id) throw new \Application\Exception\Exception("Please provide more information", 1);
			
			$form = $this->get('EmptyForm');
			$form->addSubmit('cancel', 'Cancel', 'cancel');
			$form->addSubmit('delete', 'Delete', 'delete');

			$this->get('ErrorLog')->count = 0;
			switch ($section) {
				case 'user':
					if(!$this->isPermitted('_admin_\delete\user')) throw new \Application\Exception\Exception($this->translate("You cannot delete users"), 1);
					
					$table = $this->get('UserTable');
					$show_form = true;
					$modal_header = $this->translate('Delete User');
					$result_message = $this->translate('User Deleted !');
					$redirect_action = $this->url()->fromRoute('admin/actions', ['controller' => 'userdb']);
					break;
				case 'company':
					if(!$this->isPermitted('_admin_\delete\user')) throw new \Application\Exception\Exception($this->translate("You cannot delete companies"), 1);
					
					$table = $this->get('UserTable');
					$show_form = true;
					$modal_header = $this->translate('Delete Company');
					$result_message = $this->translate('Company Deleted !');
					$redirect_action = $this->url()->fromRoute('admin/actions', ['controller' => 'userdb', 'action' => 'companies']);
					break;
				case 'contract':
					$table = $this->get('ExperienceTable');
					$show_form = true;
					$modal_header = $this->translate('Delete Contract');
					$result_message = $this->translate('Contract Deleted !');
					break;
				case 'document':
					$table = $this->get('DocumentsTable');
					$show_form = true;
					$modal_header = $this->translate('Delete Document');
					$result_message = $this->translate('Document Deleted !');
					break;
				case 'vacancy':
					if(!$this->isPermitted('_admin_\delete\vacancy')) throw new \Application\Exception\Exception($this->translate("You cannot delete vacancies"), 1);
					$table = $this->get('VacanciesTable');
					$show_form = true;
					$modal_header = $this->translate('Delete Vacancy');
					$result_message = $this->translate('Vacancy Deleted !');
					$redirect_action = $this->url()->fromRoute('admin/actions', ['controller' => 'vacancies', 'action' => 'index']);
					break;
				case 'mailtemplate':
					if($delete_id == 1) throw new \Application\Exception\Exception("You cannot delete base template", 1);
					
					$table = $this->get('AdminMailTemplatesTable');
					$show_form = true;
					$modal_header = $this->translate('Delete Mail Template');
					$result_message = $this->translate('Mail Template Deleted !');
					break;
				case 'mail_box':
					if(!$this->isPermitted('admin\controller\mailbox.edit-account')) throw new \Application\Exception\Exception($this->translate("You cannot delete Mailboxes"), 1);
					$table = $this->get('MailAccountsTable');
					$show_form = true;
					$modal_header = $this->translate('Delete Mail Box');
					$result_message = $this->translate('Mail Box Deleted !');
					$redirect_action = $this->url()->fromRoute('admin/actions', ['controller' => 'mailbox', 'action' => 'accounts']);
					break;
				case 'tag':
					if(!$this->isPermitted('admin\controller\content.edit-tag')) throw new \Application\Exception\Exception($this->translate("You cannot delete Tags"), 1);
					$table = $this->get('TagsTable');
					$show_form = true;
					$modal_header = $this->translate('Delete Tag');
					$result_message = $this->translate('Tag Deleted !');
					$redirect_action = $this->url()->fromRoute('admin/actions', ['controller' => 'content', 'action' => 'tags']);
					break;
				default:
					# code...
					break;
			}		
			$form->setAttribute('action', $this->url()->fromRoute('admin/actions', 
														array('controller'=>'index', 'action' => 'delete'), 
														array('query' => array('section' => $section, 'id' => $delete_id, 'redirect' => $redirect_action))
														));

			if($this->request->isPost()){
				$data = $this->request->getPost()->toArray();
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception($form->getMessages(), 1);
					if(isset($data['delete'])) {
						try {
							$table->delete($delete_id);
							$this->fm()->addSuccessMessage($result_message);
							$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
																			array(
																				'module' => 'Admin',
																				'controller' => $this->routeMatch()->getParam('__CONTROLLER__'),
																				'action' => $this->routeMatch()->getParam('action'),
																				'id' => $delete_id
																				) ,
																			$result_message
																		);
						} catch (\Exception $e) {
							$this->fm()->addErrorMessage($e->getMessage());
						}
					} 
					if(isset($data['json'])) {
						$view = new ViewModel();
						$view->setTerminal(true);
						$view->setTemplate('/user-info/json');
						return $view;
					}
					if($redirect_action != '') return $this->redirect()->toUrl($redirect_action);
					else return $this->redirect()->toRoute('admin/actions', array('controller' => 'userdb'));
			}

			if(!isset($show_form)) throw new \Application\Exception\Exception("Nothing to delete", 1);
			
		} catch (\Exception $e) {
			$this->fm()->addErrorMessage($e->getMessage());
			if($redirect_action != '') return $this->redirect()->toUrl($redirect_action);
			return $this->redirect()->toRoute('admin/actions', array('controller' => 'userdb'));
		}
		

		$view = new ViewModel(array(
			'form' => $form,
			'show_form' => $show_form,
			'modal_header' => $modal_header
			));

		$view->setTemplate('my/cv/delete');
		return $view;		

	}

}