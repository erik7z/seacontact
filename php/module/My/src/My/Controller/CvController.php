<?php
namespace My\Controller;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class CvController extends AbstractController
{

	
	public function indexAction()
	{
		return $this->forward()->dispatch('UserInfo\Controller\Cv', array(
		                'controller' => 'UserInfo\Controller\Cv', 
		                'action' => 'index',
		                'user' =>  zgetUserLogin($this->identity()),
		                // 'dispatch' => true,
		                ));
	}


	public function personalAction()
	{

		$form = $this->get('EmptyForm');
		$userFields = $this->get('UserFields')->remainFields(
			array('id','cv_avatar', 'desired_rank', 'minimum_salary', 'readiness_date', 'name','surname', 
				'dob', 'sex', 'visa_usa', 'visa_usa_exp', 'visa_shenghen', 'visa_shenghen_exp',
				'nationality', 'home_city', 'contact_mobile', 'contact_phone', 
				 'info_website', 'user_notes', 'english_knowledge', 
				)
			);
		$form->add($form->getCollection('user_education', 'Education', 'Application\Form\Fieldset\UserEducation'));
		$form->add($userFields)->addSubmit('save', $this->translate('Save'), 'save');

		$userTable = $this->get('UserTable');
		$educationTable = new \Application\Model\zEmptyTable('user_education');
		$education = $educationTable->getAllOnField('user', $this->identity()->id)->toArray();

		foreach ($education as $key => $value) {
			$education[$key]['from'] = date('Y', $value['from']);
			$education[$key]['to'] = date('Y', $value['to']);
		}

		$data = $userTable->get($this->identity()->id, 'id')->toArray();

		$data['dob'] = ($data['dob'])? date('Y-m-d', $data['dob']) : '';
		$data['visa_usa_exp'] = ($data['visa_usa_exp'])? date('Y-m-d', $data['visa_usa_exp']) : '';
		$data['visa_shenghen_exp'] = ($data['visa_shenghen_exp'])? date('Y-m-d', $data['visa_shenghen_exp']) : '';
		$data['readiness_date'] = ($data['readiness_date'])? date('Y-m-d', $data['readiness_date']) : date('Y-m-d', time()) ;
		$form->setData(array('user' => $data, 'user_education' => $education));

		if($this->request->isPost()){
			$data = array_merge_recursive(
				$this->request->getPost()->toArray(),
				$this->request->getFiles()->toArray()
				);
			$remove = isset($data['remove'])? $data['remove'] : null;
			$form->setData($data);
			if($form->isValid()){
				$data = $form->getData();
				if($remove) {
					foreach ($remove as $value) {
						$educationTable->delete($value['school']);
					}
				}
				if($data['user_education']) {
					foreach ($data['user_education'] as $school) {
						if('' == $school['name']) continue;
						$school['from'] = ('' != $school['from'])? strtotime('01/01/'.$school['from']) : '';
						$school['to'] = ('' != $school['to'])? strtotime('01/01/'.$school['to']) : '';
						$school['user'] = $this->identity()->id;
						$educationTable->save($school);
					}
				}
				$data['user']['id'] = $this->identity()->id;
				try {
					if('' != $data['user']['cv_avatar']['tmp_name']) {
						$cv_avatar = $this->get('UploadImage')->uploadCVavatar($data['user']['cv_avatar'], $this->identity()->id);
						$data['user']['cv_avatar'] = $cv_avatar['img'];
						// DELETE OLD AVATAR
						$delete = $this->get('UploadsTable')->deleteUpload($this->identity()->cv_avatar, 'cv_avatar');
						if($delete['error']) $this->fm()->addErrorMessage($delete['error']);
					} else unset($data['user']['cv_avatar']);
					$data['user']['cv_last_update'] = time();
					$userTable->save($data['user']);
					$this->get('AdminNotifTable')->addCvEditNotification($this->identity()->id);
					$this->fm()->addSuccessMessage('Information Saved!');
					$this->get('RefreshUserId');
					$this->redirect()->refresh();										
				} catch (Exception $e) {
					$this->fm()->addErrorMessage($e->getMessage());
				}
			} else {
				$this->fm()->addErrorMessage($form->getMessages());
			}			

		}

		$view = new ViewModel(array(
			'form' => $form,
			'data' => $data
			));
		return $view;
	}


	public function docsAction()
	{

		$docsTable = $this->get('DocumentsTable');

		$user = $this->identity();

		$user_docs = $docsTable->getAllUserDocs($user->id);

		$form = $this->get('EmptyForm');
		$form->add($this->get('DocsFields'));
		$form->addSubmit(null, $this->translate('Add Document'));
		$form->setAttribute('action', $this->url()->fromRoute('sc/cv/actions', array('action' => 'docs')));
		$show_form = false;
		$modal_header = $this->translate('Add Document');

		if($doc_id = $this->params()->fromQuery('edit')) {
			$document = $docsTable->getUserDocument($doc_id);
			if($this->identity()->id != $document['user']) throw new \Application\Exception\Exception("You cannot access this page", 1);
			$form->setData(array('user_documents' => $document));
			$form->setAttribute('action', $this->url()->fromRoute('sc/cv/actions', 
														array('action' => 'docs'), 
														array('query' => array('edit' => $doc_id))
													));
			$form->get('submit')->setLabel('Save');		
			$show_form = true;
			$modal_header = 'Edit Document';
		} else $doc_id = null;


		if($this->request->isPost()){
			$data = $this->request->getPost()->toArray();
			$form->setData($data);
			if($form->isValid()){
				$data = $form->getData();
				if(isset($data['user_documents']['id']) && ('' != $data['user_documents']['id'])) $data['user_documents']['id'] = $doc_id;
				$docsTable->saveUserDocument($user->id, $data['user_documents']);
				$this->fm()->addSuccessMessage('Information Saved!');
				$this->get('RefreshUserId');
				$this->redirect()->toRoute('sc/cv/actions', ['action' => 'docs'], [], true);
			} else {
				$this->fm()->addErrorMessage('Please check entered data');
				$show_form = true;
			}
		}


		$view = new ViewModel(array(
			'form' => $form,
			'user_docs' => $user_docs,
			'show_form' => $show_form,
			'modal_header' => $modal_header
			));

		return $view;
	}



	public function experienceAction()
	{
		$success = 1;
		$message = '';

		try {
			throw new \Exception("Error Processing Request", 1);
			
		} catch (\Exception $e) {
			\Application\Exception\StaticExcLog::log($e);
			$success = 0;
			$message = ($e->getCode() == 100)? unserialize($e->getMessage()) : $e->getMessage(); 
		}

	    $viewModel = new ViewModel(array(
		'partners' => $this->get('ContactsTable')->getContacts($this->identity()->id, [], ['relations' => \Application\Model\UserContactsTable::RELATION_COLLEGUE, '_limit' => 6]),
		'user_experience' => $this->get('ExperienceTable')->getUserExperience($this->identity()->id),
		));

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'my/cv/experience',
		    'exception' => (isset($e)) ? $e : null,
		    ]);

	}

	public function experienceAddAction()
	{
	    $success = 1;
	    $message = '';
	    $redirect = 0;

	    $table = $this->get('ExperienceTable');

	    try {
	    	$form = $this->get('EmptyForm');
	    	$form->add($this->get('ContractFields')->remainFields(['id', 'ship_name', 'date_from', 'date_to', 'rank', 'flag', 'ship_type', 'ship_built', 'dwt' ,'engine', 'bhp', 'company']));
	    	$form->addSubmit(null, $this->translate('Add Contract'));
	    	$form->get('submit')->setAttributes(['data-ajax' => 1]);
	    	$form->setAttribute('action', $this->url()->fromRoute('sc/cv/actions', array('action' => 'experience-add')));

	        if($this->request->isPost()){
	            $data = array_merge_recursive(
	                $this->request->getPost()->toArray(),
	                $this->request->getFiles()->toArray()
	                );
	            $form->setData($data);
	            if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 100);
            	$data = $form->getData();
            	$data['user_experience']['user'] = $this->identity()->id;
            	$data['user_experience']['time'] = time();
            	$table->save($data['user_experience']);
            	$this->get('AdminNotifTable')->addCvEditNotification($this->identity()->id);
    
	            $success = 1;
	            $message = $this->translate('Experience record added!');
	            $redirect = $this->url()->fromRoute('sc/cv/actions', ['action' => 'experience'], [], true);
	        }

	    } catch (\Exception $e) {
	        $success = 0;
	        $message = ($e->getCode() == 100)? unserialize($e->getMessage()) : $e->getMessage(); 
	    }

	    $viewModel = 0;
	    if(isset($form)) 
	        $viewModel = new ViewModel(array(
	            'form' => $form,
	            ));

	    return $this->viewResponse($success, $message, [
	        'view_data' => $viewModel, 
	        'template' => 'my/cv/experience-add',
	        'exception' => (isset($e)) ? $e : null,
	        'redirect' => (isset($redirect))? $redirect : $this->url()->fromRoute('sc/cv/actions', ['action' => 'experience']),
	        'force_redirect' => ($redirect)? 1 : 0
	        ]);
	}

	public function experienceEditAction()
	{
	    $success = 1;
	    $message = '';
	    $redirect = 0;
	    $id = $this->params()->fromRoute('id');

	    try {
	    	$table = $this->get('ExperienceTable');
	    	$data = $table->getExperience(null, ['id' => $id])->current();
	    	if(!$data) throw new \Application\Exception\Exception("Record with such id not found", 1);
	    	if($this->identity()->id != $data['user']) throw new \Application\Exception\Exception("You cannot perform this action", 1);
	    	$data['date_from'] = ($data['date_from'])? date('Y-m-d', $data['date_from']) : '';
	    	$data['date_to'] = ($data['date_to'])? date('Y-m-d', $data['date_to']) : '';
	    	
	    	$form = $this->get('EmptyForm');
	    	$form->add($this->get('ContractFields')->remainFields(['id', 'ship_name', 'date_from', 'date_to', 'rank', 'ship_type', 'ship_built', 'flag', 'dwt' ,'engine', 'bhp', 'company']));
	    	$form->addSubmit(null, $this->translate('Save'));
	    	$form->get('submit')->setAttributes(['data-ajax' => 1]);
	    	$form->setAttribute('action', $this->url()->fromRoute('sc/cv/actions', array('action' => 'experience-edit')));
	    	$form->setData(array('user_experience' => $data));

	    	if($this->request->isPost()){
	            $data = array_merge_recursive(
	                $this->request->getPost()->toArray(),
	                $this->request->getFiles()->toArray()
	                );
	            $form->setData($data);
	            if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 100);
            	$data = $form->getData();
            	$data['user_experience']['user'] = $this->identity()->id;
            	$data['user_experience']['time'] = time();
            	$table->save($data['user_experience']);
            	$this->get('AdminNotifTable')->addCvEditNotification($this->identity()->id);
    
	            $success = 1;
	            $message = $this->translate('Experience record updated!');
	            $redirect = $this->url()->fromRoute('sc/cv/actions', ['action' => 'experience'], [], true);
	        }

	    } catch (\Exception $e) {
	        $success = 0;
	        $message = ($e->getCode() == 100)? unserialize($e->getMessage()) : $e->getMessage(); 
	    }

	    $viewModel = 0;
	    if(isset($form)) 
	        $viewModel = new ViewModel(array(
	            'form' => $form,
	            ));


	    return $this->viewResponse($success, $message, [
	        'view_data' => $viewModel, 
	        'template' => 'my/cv/experience-edit',
	        'exception' => (isset($e)) ? $e : null,
	        'redirect' => (isset($redirect))? $redirect : $this->url()->fromRoute('sc/cv/actions', ['action' => 'experience']),
	        'force_redirect' => ($redirect)? 1 : 0
	        ]);
	}


	public function experienceDeleteAction()
	{
	    $id = $this->params()->fromRoute('id');
	    try {
	    	$table = $this->get('ExperienceTable');
	    	$data = $table->getExperience(null, ['id' => $id])->current();
	    	if(!$data) throw new \Application\Exception\Exception("Record with such id not found", 1);
	    	if($this->identity()->id != $data['user']) throw new \Application\Exception\Exception("You cannot perform this action", 1);
	    	$table->delete($id);
	        // $table->save(['id' => $id, 'active' => 0]);
	        $success = 1;
	        $message = $this->translate('Experience Record Deleted');
	    } catch (\Exception $e) {
	        $success = 0;
	        $message = $e->getMessage();
	    }
	    return $this->viewResponse($success, $message, [
	        'redirect' => $this->url()->fromRoute('sc/cv/actions', ['action' => 'experience']), 
	        'force_redirect' => 1,
	        'exception' => (isset($e)) ? $e : null
	        ]);     
	}

	public function deleteAction()
	{

		$form = $this->get('EmptyForm');
		$form->addSubmit('cancel', $this->translate('Cancel'), 'cancel');
		$form->addSubmit('delete', $this->translate('Delete'), 'delete');


		//delete contract
		if($delete_id = $this->params()->fromQuery('contract')) {
			$delete_id = $this->params()->fromQuery('contract');
			$table = $this->get('ExperienceTable');
			$contract = $table->getUserContract($delete_id);
			if($this->identity()->id != $contract['user']) throw new \Application\Exception\Exception("You cannot perform this action", 1);
			$form->setAttribute('action', $this->url()->fromRoute('sc/cv/actions', 
														array('action' => 'delete'), 
														array('query' => array('contract' => $delete_id))
														));
			$redirect_action = 'experience';
			$show_form = true;
			$modal_header = 'Delete Contract';
		}

		//delete document
		if($this->params()->fromQuery('document')) {
			$delete_id = $this->params()->fromQuery('document');
			$table = $this->get('DocumentsTable');
			$document = $table->getUserDocument($delete_id);
			if($this->identity()->id != $document['user']) throw new \Application\Exception\Exception("You cannot perform this action", 1);
			$form->setAttribute('action', $this->url()->fromRoute('sc/cv/actions', 
														array('action' => 'delete'), 
														array('query' => array('document' => $delete_id))
														));
			$redirect_action = 'docs';
			$show_form = true;
			$modal_header = 'Delete document';
		}		

		if($this->request->isPost()){
			$data = $this->request->getPost()->toArray();

			$form->setData($data);
			if($form->isValid()){
				if(isset($data['delete'])) {

					$table->delete($delete_id);
					$this->fm()->addSuccessMessage('Information deleted!');
					$this->get('RefreshUserId');
				}
				return $this->redirect()->toRoute('sc/cv/actions', array('action' => $redirect_action), [], true);
			} else {
				$this->fm()->addErrorMessage('Ooops! something went wrong...');
				$show_form = true;
			}
		}

		if(!isset($show_form)) throw new \Application\Exception\Exception("Nothing to delete", 1);
		

		$view = new ViewModel(array(
			'form' => $form,
			'show_form' => $show_form,
			'modal_header' => $modal_header
			));

		return $view;		

	}

	public function uploadCvAction()
	{

		try {
			if(!$this->identity()) throw new \Application\Exception\Exception("Sign in required !", 1);
			if (!isset($_POST['cv_file']) && isset($_FILES['userfile'])) $result = $this->get('UploadCv')->upload($_FILES['userfile'],$this->identity()->id);
			else if(isset($_POST['cv_file']))  $result =  $this->get('UploadCv')->upload($data['user']['cv_file'], $this->identity()->id);
			if(!$result) throw new \Application\Exception\Exception("No File Uploaded !", 1);
			$this->get('UserTable')->addNewCvFile($this->identity()->id,$result['name']);
			if($this->identity()->cv_file != '') {
				$delete = $this->get('UploadsTable')->deleteUpload($this->identity()->cv_file, 'cv_file');
				if(isset($delete['error'])) $this->fm()->addErrorMessage($delete['error']);
			}
			$this->get('AdminNotifTable')->addCvUploadedNotification($this->identity()->id);
			$this->get('RefreshUserId');
			$response['success'] = true;
			$response['data'] = $result;	
		} catch (\Exception $e) {
			$response['success'] = false;
			$response['data'] = $e->getMessage();
		}

		$view = new ViewModel(array(
				'response' => json_encode($response),
				)
			);
		$view->setTemplate('/user-info/json');
		$view->setTerminal(true);
		return $view;
	}

	public function cleanNotLoadedAvatarAction()
	{
		$file_name = $this->params()->fromQuery('file_name');
		try {
			if(!$file_name) throw new \Application\Exception\Exception("No file name provided", 1);
			$result = $this->get('UploadsTable')->deleteUpload($file_name, 'avatar');
			$response['success'] = true;
			$response['data'] = $result;
		} catch (\Exception $e) {
			$response['success'] = false;
			$response['data'] = $e->getMessage();
		}
		
		$view = new ViewModel(array(
				'response' => json_encode($response),
				)
			);
		$view->setTemplate('/user-info/json');
		$view->setTerminal(true);
		return $view;
	}

	public function setAvatarAction()
	{
		try {
			if(!$this->identity()) throw new \Application\Exception\Exception("Sign in required !", 1);
			//image for crop will be uploaded by ajax
			if (!isset($_POST['image']) && isset($_FILES['userfile'])) $result = $this->get('UploadImage')->upload($_FILES['userfile'],true, $this->identity()->id);

			// save of image will be submitted by form, so will be checked here
			else if(isset($_POST['image'])) {
				$result =  $this->get('UploadImage')->crop($this->identity()->id, $_POST['image'],$_POST['w'],$_POST['h'],$_POST['x1'],$_POST['y1']);
				
				$delete = $this->get('UploadsTable')->deleteUpload($this->identity()->avatar, 'avatar');
				if(isset($delete['error'])) $this->fm()->addErrorMessage($delete['error']);
				
				$this->get('UserTable')->addNewAvatar($this->identity()->id,$result);
				$this->get('RefreshUserId');
			} 
			if(!$result) throw new \Application\Exception\Exception("No File Uploaded !", 1);
			$response['success'] = true;
			$response['data'] = $result;	
		} catch (\Exception $e) {
			$response['success'] = false;
			$response['data'] = $e->getMessage();
		}

		$view = new ViewModel(array(
				'response' => json_encode($response),
				)
			);
		$view->setTemplate('/user-info/json');
		$view->setTerminal(true);
		return $view;
		
	}


	public function deleteCvAction()
	{
		if($this->identity()){
			$delete = $this->get('UploadsTable')->deleteUpload($this->identity()->cv_file, 'cv_file');
			if($delete['error']) $this->fm()->addErrorMessage($delete['error']);
			$this->get('UserTable')->deleteCv($this->identity()->id);
			$this->get('RefreshUserId');
			$this->fm()->addSuccessMessage($this->translate('Cv file deleted!'));
		} else {
			$this->fm()->addErrorMessage($this->translate('You should sign in to perform this action'));
		}
		$url = $this->request->getServer()->HTTP_REFERER;
		if($url) return $this->redirect()->toUrl($url);
		else return $this->redirect()->toRoute('sc/home', [], [], true);
	}

	public function deleteAvatarAction()
	{
		if($this->identity()){		
			$this->get('UserTable')->deleteAvatar($this->identity()->id);
			$delete = $this->get('UploadsTable')->deleteUpload($this->identity()->avatar, 'avatar');
			if($delete['error']) $this->fm()->addErrorMessage($delete['error']);

			$this->get('RefreshUserId');
			$this->fm()->addSuccessMessage('Avatar deleted!');
		} else {
			$this->fm()->addErrorMessage('You should sign in to perform this action');
		}
		return $this->redirect()->toRoute('sc/home', [], [], true);
	}


}