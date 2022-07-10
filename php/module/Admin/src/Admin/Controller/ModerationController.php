<?php
namespace Admin\Controller;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class ModerationController extends AbstractController
{

	public function indexAction()
	{
		return [];
	}

	public function adminActivityAction()
	{
		$success = 1;
		$message ='';
		$q_options = $this->setDefaultOptions(['_limit' => 50]);

		try {
			$table = $this->get('AdminActivityTable');
			$data_list = $table->getActivity(null, $q_options['filters'], $q_options);
			$q_options['count'] = 1;
			$total_results = $table->getActivity(null, $q_options['filters'], $q_options)->current()->count;

			$admin_roles = $this->get('Access')->getAccessList()->getAdminRoles();
			$admins = $this->get('UserTable')->getAdmins($admin_roles);
		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}

		$viewModel = new ViewModel(array(
		'admins' => $admins,
		'total_results' => $total_results,
		'data_list' => $data_list,
		'q_options' => $q_options,
		));

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'admin/moderation/index',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect))? $redirect : null
		    ]);	
	}

	public function exceptionsAction()
	{
		$success = 1;
		$message ='';
		$q_options = $this->setDefaultOptions(['_limit' => 50]);

		try {
			$table = $this->get('ExceptionsTable');
			$q_options['_fields'] = $table->getStandartFields();
			$data_list = $table->getItems(null, $q_options['filters'], $q_options);
			$q_options['count'] = 1;
			$total_results = $table->getItems(null, $q_options['filters'], $q_options)->current()->count;
		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}

		$viewModel = new ViewModel(array(
		'total_results' => $total_results,
		'data_list' => $data_list,
		'q_options' => $q_options,
		));

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'admin/moderation/exceptions',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect))? $redirect : null
		    ]);	
	}

	public function bugsAction() {
		$success = 1;
		$message ='';
		$q_options = $this->setDefaultOptions(['_limit' => 50]);

		try {
			// $table = $this->get('ExceptionsTable');
			// $q_options['_fields'] = $table->getStandartFields();
			// $data_list = $table->getItems(null, $q_options['filters'], $q_options);
			// $q_options['count'] = 1;
			// $total_results = $table->getItems(null, $q_options['filters'], $q_options)->current()->count;
		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}

		$viewModel = new ViewModel(array(
		// 'total_results' => $total_results,
		// 'data_list' => $data_list,
		'q_options' => $q_options,
		));

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'admin/moderation/bugs',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect))? $redirect : null
		    ]);	
	}
}