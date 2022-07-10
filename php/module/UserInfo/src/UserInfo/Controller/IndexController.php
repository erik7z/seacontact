<?php
namespace UserInfo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
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
		$success = 1;
		$message = '';
		try {
			$user_login = $this->params()->fromRoute('user');
			$viewer_id = (isset($this->identity()->id)) ? $this->identity()->id : null;
			if(isset($this->identity()->login) &&  ($this->identity()->login == $user_login)) {
				$controller = ($this->identity()->type == \Application\Model\UserTable::TYPE_USER) ? 'My\Controller\Index' : 'Company\Controller\Index';
		        return $this->forward()->dispatch($controller, array(
								'controller' => $controller, 
								'action' => 'index',
								'dispatch' => true,
								));
	        }
	        $user = $this->get('UserTable')->getUserByLogin($user_login);
	        if($user->type == \Application\Model\UserTable::TYPE_COMPANY) {
	        	return $this->forward()->dispatch('CompanyInfo\Controller\Index');
	        }
	    	if($user->type == \Application\Model\UserTable::TYPE_OWNER) return $this->redirect()->toRoute('sc/notfound');
	    	

	        if(!$user) return $this->redirect()->toRoute('sc/notfound');
	        $relations = ($viewer_id && $viewer_id != $user->id) ? $this->get('ContactsTable')->getContacts($viewer_id,['user_id' => $user->id])->current()->relations : null;


	        $q_options = $this->setDefaultOptions(['_limit' => 50]);
	        
	        $q_options['only_current'] = 1;
	        $q_options['show_hidden'] = 0;

	        $data_list = $this->get('NewsTable')->getNews($viewer_id, ['section' => 'logbook', 'owner_id' => $user->id], $q_options);
	        $q_options['count'] = 1;
	        $total_results = $this->get('NewsTable')->getNews($viewer_id, ['section' => 'logbook', 'owner_id' => $user->id], $q_options)->current()->count;
	        if(!$q_options['_offset']) $q_options['_offset'] = ($q_options['_limit'] * $q_options['_page']) - $q_options['_limit'];


		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}


		$viewModel = new ViewModel(array(
			'user' => $user,
			'data_list' => $data_list,
			'total_results' => $total_results,
			'q_options' => $q_options,
			'partners' => $this->get('ContactsTable')->getContacts($user->id, [], ['relations' => \Application\Model\UserContactsTable::RELATION_COLLEGUE, '_limit' => 6]),
			'relations' => $relations,
			'hide_info' => true,
			));

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel,
		    'template' => 'user-info/index/index',
		    'exception' => (isset($e)) ? $e : null
		    ]);
	}

}