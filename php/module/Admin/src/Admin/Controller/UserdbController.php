<?php
namespace Admin\Controller;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class UserdbController extends AbstractController
{

	public function indexAction()
	{
		$success = 1;
		$message ='';

		$q_options = $this->setDefaultOptions(['_limit' => 50, 'user_type' => 'user', 'show_fields' => ['id', 'office_note','name', 'role', 'email', 'nationality', 'desired_rank', 'cv_last_call']]);
		$q_options['_user_fields'] = ['id', 'name','login', 'role', 'surname', 'full_name', 'nationality','avatar', 'cv_avatar', 'email', 'desired_rank', 'cv_last_call', 'cv_last_update'];
		if($this->params()->fromRoute('dispatch')) {
			$q_options = array_merge($q_options, (array) $this->params()->fromRoute('options'));
			$q_options['filters'] = array_merge($q_options['filters'], (array) $this->params()->fromRoute('filters'));
		}
		try {
			$form = $this->get('EmptyForm');
			$form_fields = $this->get('UserFilterFields');
			if(isset($q_options['form_fields']) && $q_options['form_fields']) $form_fields->remainFields($q_options['form_fields']);
			// removing home_country field for users
			if (!isset($q_options['user_type']) ||  $q_options['user_type'] != 'company') { 
				$form_fields->removeFields(['home_country']);
				$form_fields->setup();
			} else {
				$form_fields->setup(['type_company' => 1]);
			}
			$form->add($form_fields);
			$form->addSubmit($this->translate('Filter'));
			$show_form = 0;

			if($q_options['filters']){
				$data = $this->request->getQuery();
				$data['filters'] = array_merge((array) $data['filters'], (array) $q_options['filters']);
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 100);
				$data = $form->getData();
				$q_options['filters'] = array_filter($data['filters']);
				$this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin', 'controller' => 'userdb', 'action' => 'index','query' => http_build_query(['filters' => $q_options['filters']])] ,'Search in users db');
			} 

			$table = $this->get('UserTable');
			
			$q_options['show_notes'] = 1;
			$q_options['count'] = 1;
			$total_results = $table->getUsersList($this->identity()->id, $q_options['filters'], $q_options)->current()->count;
			$q_options['notes_company_private'] = 1;
			$q_options['notes_admin_all'] = 1;
			$q_options['notes_admin_private'] = 1;
			$q_options['count'] = 0;
			$data_list = $table->getUsersList($this->identity()->id, $q_options['filters'], $q_options);
			$q_options['table_sorting'] = 1;
		} catch (\Exception $e) {
			if($e->getCode() == 100) $message = unserialize($e->getMessage());
			else $message = $e->getMessage();
			$success = 0;
			$show_form = 1;
		}

		$viewModel = new ViewModel(array(
		'show_form' => $show_form,
		'form' => $form,
		'total_results' => $total_results,
		'data_list' => $data_list,
		'q_options' => $q_options,
		));

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'admin/userdb/index',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect))? $redirect : null
		    ]);		
	}



	public function userAction() {
		$user_id = (int)$this->params()->fromRoute('id');
		if($user_id == 0) throw new \Application\Exception\Exception("User Not Found", 1);

		$user = $this->get('UserTable')->getUserById($user_id);
		if($user->type == \Application\Model\UserTable::TYPE_COMPANY) return $this->redirect()->toRoute('admin/actions', ['controller' => 'userdb', 'action' => 'company', 'id' => $user_id]);
		if($user->type == \Application\Model\UserTable::TYPE_OWNER) return $this->redirect()->toRoute('admin/actions', ['controller' => 'userdb', 'action' => 'owner', 'id' => $user_id]);

		$this->get('UserTable')->refreshUserCvLastView($user_id);

		$form = $this->get('EmptyForm');
		$userFields = array('id','cv_avatar', 'cv_file', 'desired_rank', 'minimum_salary', 'readiness_date', 'name','surname','middle_name', 'full_name', 
				'dob', 'sex', 'visa_usa', 'visa_usa_exp', 'visa_shenghen', 'visa_shenghen_exp',
				'nationality', 'home_city', 'home_city_text', 'home_address', 'contact_mobile', 'contact_mobile_2', 'email', 'contact_email',
				'contact_phone', 'contact_phone_2', 'info_website', 'user_notes', 'english_knowledge', 
				'marital_status', 'next_of_kin', 'childrens');
		
		$default_role = $user->role;

		$assign_roles = $this->isPermitted('_admin_\access\assign_roles');
		if ($assign_roles) {
			$userFields[] = 'role';
			$roles = $this->get('Access')->getAccessList()->getUserRoles();
			for ($i=0; $i < count($roles); $i++) { 
				$roles_list[$roles[$i]] = $roles[$i];
			}
		}

		$assign_types = $this->isPermitted('_admin_\access\assign_types');
		if ($assign_types) $userFields[] = 'type';

		$userFields = $this->get('UserFields')->remainFields($userFields);

		if($assign_roles === true) if(isset($roles_list)) $userFields->get('role')->setValueOptions($roles_list)->setValue($user->role);
		if($assign_types) $userFields->get('type')->setValue($user->type);

		$form->add($form->getCollection('user_education', 'Education', 'Application\Form\Fieldset\UserEducation'));
		$userFields->get('name')->setAttribute('required', false);
		$form->add($userFields);
		$form->add($form->getCollection('user_experience', 'Experience', 'Application\Form\Fieldset\UserExperience'));
		$form->addSubmit('save', 'Save', 'save');

		$form->setRequiredFields(array('user' => array('name', 'surname', 'sex', 'email')), false);

		$educationTable = new \Application\Model\zEmptyTable('user_education');
		$education = $educationTable->getAllOnField('user', $user_id)->toArray();

		foreach ($education as $key => $value) {
			$education[$key]['from'] = date('Y', $value['from']);
			$education[$key]['to'] = date('Y', $value['to']);
		}

		$user['dob'] = date('Y-m-d', $user['dob']);
		if($user['visa_usa_exp']) $user['visa_usa_exp'] = date('Y-m-d', $user['visa_usa_exp']);
		if($user['visa_shenghen_exp']) $user['visa_shenghen_exp'] = date('Y-m-d', $user['visa_shenghen_exp']);

		$user['readiness_date'] = ($user['readiness_date']) ? date('Y-m-d', $user['readiness_date']) : null;
		$form->setData(array('user' => $user, 'user_education' => $education));
		$old_avatar = $user['cv_avatar'];

		$user_experience = $this->get('ExperienceTable')->getUserExperience($user_id);

		$user_docs = $this->get('DocumentsTable')->getAllUserDocs($user_id);

		if($this->request->isPost()){
			$data = array_merge_recursive(
				$this->request->getPost()->toArray(),
				$this->request->getFiles()->toArray()
				);

				if(isset($data['remove'])) $remove = $data['remove'];
				$form->setData($data);
				try {
					if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
					$data = $form->getData();
					if(isset($remove)) {
						foreach ($remove as $value) {
							$educationTable->delete($value['school']);
						}
					}
					if($data['user_education']) {
						foreach ($data['user_education'] as $school) {
							if('' == $school['name']) continue;
							$school['from'] = ('' != $school['from'])? strtotime('01/01/'.$school['from']) : '';
							$school['to'] = ('' != $school['to'])? strtotime('01/01/'.$school['to']) : '';
							$school['user'] = $user->id;
							$educationTable->save($school);
						}
					}
					$data['user']['id'] = $user->id;
					$data['user']['cv_last_update'] = time();
					if ($data['user']['home_city_text'] != '') {
						$data['user']['home_city'] = $data['user']['home_city_text'];
					}
					if('' != $data['user']['cv_avatar']['tmp_name']) {
						$cv_avatar = $this->get('UploadImage')->uploadCVavatar($data['user']['cv_avatar'], $user_id);
						$data['user']['cv_avatar'] = $cv_avatar['img'];
						// DELETE OLD AVATAR
						if($user->cv_avatar != '') {
							$delete = $this->get('UploadsTable')->deleteUpload($user->cv_avatar, 'cv_avatar');
							if($delete['error']) $this->fm()->addErrorMessage($delete['error']);
						}
					} else unset($data['user']['cv_avatar']);

					if('' != $data['user']['cv_file']['tmp_name']) {
						$cv_file = $this->get('UploadCv')->upload($data['user']['cv_file'], $user_id);
						$data['user']['cv_file'] = $cv_file['name'];
						// DELETE OLD CV FILE
						if($user->cv_file != '') {
							$delete = $this->get('UploadsTable')->deleteUpload($user->cv_file, 'cv_file');
							if($delete['error']) $this->fm()->addErrorMessage($delete['error']);
						}
					} else unset($data['user']['cv_file']);

					if(isset($data['user']['role']) && $data['user']['role'] != $default_role) {
						if(!$this->isPermitted('_admin_\access\assign_roles\\'.$data['user']['role']) || !$this->isPermitted('_admin_\access\assign_roles\\'.$default_role)) {
							$form->get('user')->get('role')->setValue($default_role);
							throw new \Application\Exception\Exception($this->translate('You cannot assign role ').$data['user']['role'].$this->translate(' to that user'), 1);
						}
						$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
																		array(
																			'module' => 'Admin',
																			'controller' => 'access',
																			'action' => 'assign-roles',
																			'id' => $user_id
																			) ,
																		$this->translate('Modified user role to').' :'.$data['user']['role'] 
																	);
					}
					$this->get('UserTable')->save($data['user']);
					$this->fm()->addSuccessMessage('Information Saved!');
					$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
																	array(
																		'module' => 'Admin',
																		'controller' => 'userdb',
																		'action' => $this->routeMatch()->getParam('action'),
																		'id' => $user_id
																		) ,
																	$this->translate('Updated User Info') 
																);

					$this->get('RefreshUserId');
					$this->redirect()->refresh();
				} catch (\Exception $e) {
					$message = ($e->getCode() == 777) ? unserialize($e->getMessage()) : $e->getMessage();
					$this->fm()->addErrorMessage($message);
				}

		} else { // not post action
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
															array(
																'module' => 'Admin',
																'controller' => 'userdb',
																'action' => $this->routeMatch()->getParam('action'),
																'id' => $user_id
																) ,
															$this->translate('Opened User Page') 
														);
			$this->get('AdminNotifReadedTable')->readNotifications($this->identity()->id, 'new_user', 'user', $user_id);
		}

		$mails_count = 0;
		if($user->email) {
			$mail_to_obj = new \stdClass();
			$mail_to_obj->email = $user->email;
			$mails_count = $this->get('MailBoxTable')->getMailsFlow(null, _MBCREW_, array($mail_to_obj), [], ['count' => 1])->current()->count;
		}


		$cv_notes_options = [
		    'show_admin_notes' => true,
		    'show_admin_private_notes' => true,
		    'show_company_private_notes' => true
		];
		$view = new ViewModel(array(
				'user' => $user,
				'form' => $form,
				'user_experience' => $user_experience,
				'user_docs' => $user_docs,
				'cv_notes' => $this->get('UserCvNotesTable')->getUserCvNotes($user_id, [], $cv_notes_options, $this->identity()->id),
				'last_note' => $this->get('UserCvNotesTable')->getLastUserCvNote($user_id, $this->identity()->id, 1,1,1),
				'mails_count' => $mails_count,
				'messages_count' => $this->get('MessageTable')->getMessagesCount($user_id)->current()->count,
				'mailbox' => _MBCREW_,
				'in_favorites' => $this->get('AdminUserFavoritesTable')->isInFavorites($this->identity()->id, $user_id),
				'is_banned' => $this->get('BanListTable')->isBanned(null, $user_id),
				'wf_list' => $this->get('WorkedForTable')->getWorkedFor($this->identity()->id, ['user_id' => $user_id], ['notes_field' => 'company_id']),
				'wf_total_results' => $this->get('WorkedForTable')->getWorkedFor($this->identity()->id, ['user_id' => $user_id], ['count' => 1])->current()->count,

			));
		return $view;		
	}


	public function companyAction()
	{
		$company_id = (int)$this->params()->fromRoute('id');
		$company = $this->get('UserTable')->getUserById($company_id);
		if($company->type == \Application\Model\UserTable::TYPE_USER) return $this->redirect()->toRoute('admin/actions', ['controller' => 'userdb', 'action' => 'user', 'id' => $company_id]);
		if($company->type == \Application\Model\UserTable::TYPE_OWNER) return $this->redirect()->toRoute('admin/actions', ['controller' => 'userdb', 'action' => 'owner', 'id' => $company_id]);
		$form = $this->get('EmptyForm');
		$userFields = array('email',
							'company_name', 
							'company_description', 
							'company_license', 
							'home_country', 
							'home_city', 
							'home_address',
							'contact_phone',
							'contact_phone_2',
							'contact_mobile',
							'contact_mobile_2',
							'contact_fax',
							'contact_email',
							'info_website',
							'avatar',
							'user_notes',
							);

		$default_role = $company->role;

		$assign_roles = $this->isPermitted('_admin_\access\assign_roles');
		if ($assign_roles) {
			$userFields[] = 'role';
			$roles = $this->get('Access')->getAccessList()->getCompanyRoles();
			for ($i=0; $i < count($roles); $i++) { 
				$roles_list[$roles[$i]] = $roles[$i];
			}
		}

		$assign_types = $this->isPermitted('_admin_\access\assign_types');
		if ($assign_types) $userFields[] = 'type';

		$userFields = $this->get('UserFields')->remainFields($userFields);
		
		if($assign_roles === true) if(isset($roles_list)) $userFields->get('role')->setValueOptions($roles_list)->setValue($company->role);
		if($assign_types) $userFields->get('type')->setValue($company->type);
								
		$form->add($userFields);
		$form->addSubmit($this->translate('Save'));

		
		$form->setData(array('user' => $company));

		$original_name = $company['company_name'];
		$form->removeValidator(array(
			'user' => array(
				'company_name' => 'Db\NoRecordExists'
				),
			)
		);

		if($this->request->isPost()){
			$data = array_merge_recursive(
				$this->request->getPost()->toArray(),
				$this->request->getFiles()->toArray()
				);
			$form->setData($data);
			if($form->isValid()){
				$data = $form->getData();
				$data['user']['id'] = $company_id;
				try {
					if('' != $data['user']['avatar']['tmp_name']) {
						$avatar = $this->get('UploadImage')->uploadCVavatar($data['user']['avatar'], $company_id);
						$data['user']['avatar'] = $avatar['img'];
						// DELETE OLD AVATAR
						if($this->identity()->avatar != '') 
							$delete = $this->get('UploadsTable')->deleteUpload($company['avatar'], 'avatar');
							if($delete['error']) $this->fm()->addErrorMessage($delete['error']);
					} else unset($data['user']['avatar']);
					if($data['user']['company_name'] != $original_name) {
						$validator = new \Zend\Validator\Db\NoRecordExists(
							 array(
							 'table' => 'user',
							 'field' => 'company_name',
							 'adapter' => \Application\Model\zAbstractTable::getAdapter()
							 )
						 );
						if(!$validator->isValid($data['user']['company_name'])) throw new \Application\Exception\Exception($this->translate('This Company Name is already used in the system'), 1);
					}
					if(isset($data['user']['role']) && $data['user']['role'] != $default_role) {
						if(!$this->isPermitted('_admin_\access\assign_roles\\'.$data['user']['role']) || !$this->isPermitted('_admin_\access\assign_roles\\'.$default_role)) {
							$form->get('user')->get('role')->setValue($default_role);
							throw new \Application\Exception\Exception($this->translate('You cannot assign role ').$data['user']['role'].$this->translate(' to that company'), 1);
						}
						$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
																		array(
																			'module' => 'Admin',
																			'controller' => 'access',
																			'action' => 'assign-roles',
																			'id' => $company_id
																			) ,
																		$this->translate('Modified company role to').' :'.$data['user']['role'] 
																	);
					}
					$this->get('UserTable')->save($data['user']);
					$this->fm()->addSuccessMessage($this->translate('Company information updated!'));
					$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
																	array(
																		'module' => 'Admin',
																		'controller' => 'userdb',
																		'action' => 'company',
																		'id' => $company_id
																		) ,
																	$this->translate('Updated Company Info') 
																);
					$this->get('RefreshUserId');
					$this->redirect()->refresh();										
				} catch (\Exception $e) {
					$this->fm()->addErrorMessage($e->getMessage());
					$this->redirect()->refresh();
				}
			} else {
				$this->fm()->addErrorMessage($form->getMessages());
			}			

		} else {
			$this->get('AdminNotifReadedTable')->readNotifications($this->identity()->id, null, 'company', $company_id);
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
															array(
																'module' => 'Admin',
																'controller' => 'userdb',
																'action' => 'company',
																'id' => $company_id
																) ,
															'Open Company page'
														);
		}

		
		$vacancy_form = $this->get('VacancyForm');
		$vacancy_form->setup($this->get('VacancyFields'), $this->identity()->id, ['admin_form' => 1, 'company_id' => $company_id, 'post_vk' => $this->isPermitted('_info_\social\post_vk')]);
		$vacancy_form->setRequiredFields(array('vacancies' => array('user')), 1);
		$vacancy_form->setAttribute('action', $this->url()->fromRoute('admin/actions', array('controller' => 'vacancies', 'action' => 'index')));
		
		$vf_options = [
			'show_hidden' => 1,
			'count_candidates' => 1,
			'_limit' => $this->params()->fromQuery('vf_limit', 5), 
			'_page' => $this->params()->fromQuery('vf_page', 1),
		];
		$vacancies_for_company = $this->get('VacanciesTable')->getAllVacancies(null, ['for_company' => $company_id], $vf_options);
		$vf_options['count'] = 1;
		$vf_total_results = $this->get('VacanciesTable')->getAllVacancies(null, ['for_company' => $company_id], $vf_options)->current()->count;


		$v_options = [
			'show_hidden' => 1,
			'count_candidates' => 1,
			'_limit' => $this->params()->fromQuery('v_limit', 5), 
			'_page' => $this->params()->fromQuery('v_page', 1),
		];
		$vacancies = $this->get('VacanciesTable')->getAllVacancies(null, ['company_id' => $company_id], $v_options);
		$v_options['count'] = 1;
		$v_total_results = $this->get('VacanciesTable')->getAllVacancies(null, ['company_id' => $company_id], $v_options)->current()->count;
		$mail_to_obj = new \stdClass();
		$mail_to_obj->email = $company->email;
		$mails_count = $this->get('MailBoxTable')->getMailsFlow(null, _MBOFFICE_, array($mail_to_obj), [], ['count' => 1])->current()->count;
	

		$cu_options = ['show_notes' => 1, 'notes_company_private' => 1, 'notes_admin_all' => 1, 'notes_admin_private' => 1, '_limit' => $this->params()->fromQuery('cu_limit', 5), '_page' => $this->params()->fromQuery('cu_page', 1) ];
		$company_users = $this->get('CompanyUsersTable')->getCompanyUsers($company_id, $this->identity()->id, $cu_options);
		$cu_options['count'] = 1;
		$cu_total_results = $this->get('CompanyUsersTable')->getCompanyUsers($company_id, $this->identity()->id, $cu_options)->current()->count;
		$view = new ViewModel(array(
			'form' => $form,
			'company' 		=> 		$company,
			'cv_notes' 		=> 		$this->get('UserCvNotesTable')->getUserCvNotes($company_id),
			'mails_count' 	=> 		$mails_count,
			'messages_count' => $this->get('MessageTable')->getMessagesCount($company_id)->current()->count,
			'last_note' 	=> 		$this->get('UserCvNotesTable')->getLastUserCvNote($company_id),
			'in_favorites' 	=> 		$this->get('AdminUserFavoritesTable')->isInFavorites($this->identity()->id, $company_id),
			'is_banned' 	=> 		$this->get('BanListTable')->isBanned(null, $company_id),
			'vacancies' 	=>		$vacancies,
			'v_total_results' 	=>	$v_total_results,
			'v_options' 	=> 		$v_options,

			'vacancies_for_company' 	=>		$vacancies_for_company,
			'vf_total_results' 	=>	$vf_total_results,
			'vf_options' 	=> 		$vf_options,

			'vacancy_form' 	=> 		$vacancy_form,
			'company_users' =>		$company_users,
			'cu_total_results' => $cu_total_results,
			'cu_options' => $cu_options
			));
		return $view;
	}


	public function ownerAction()
	{
		$company_id = (int)$this->params()->fromRoute('id');
		$company = $this->get('UserTable')->getUserById($company_id);
		if($company->type == \Application\Model\UserTable::TYPE_USER) return $this->redirect()->toRoute('admin/actions', ['controller' => 'userdb', 'action' => 'user', 'id' => $company_id]);
		if($company->type == \Application\Model\UserTable::TYPE_COMPANY) return $this->redirect()->toRoute('admin/actions', ['controller' => 'userdb', 'action' => 'company', 'id' => $company_id]);
		$form = $this->get('EmptyForm');
		$userFields = array('email',
							'company_name', 
							'company_description', 
							'home_country', 
							'home_city', 
							'home_address',
							'contact_phone',
							'contact_phone_2',
							'contact_mobile',
							'contact_mobile_2',
							'contact_fax',
							'contact_email',
							'info_website',
							'avatar',
							'user_notes',
							);

		$assign_types = $this->isPermitted('_admin_\access\assign_types');
		$assign_roles = $this->isPermitted('_admin_\access\assign_roles');
		if ($assign_types) $userFields[] = 'type';

		$userFields = $this->get('UserFields')->remainFields($userFields);
		
		if($assign_roles === true) if(isset($roles_list)) $userFields->get('role')->setValueOptions($roles_list)->setValue($company->role);
		if($assign_types) $userFields->get('type')->setValue($company->type);
								
		$form->add($userFields);
		$form->addSubmit($this->translate('Save'));

		
		$form->setData(array('user' => $company));

		$original_name = $company['company_name'];
		$form->removeValidator(array(
			'user' => array(
				'company_name' => 'Db\NoRecordExists'
				),
			)
		);

		if($this->request->isPost()){
			$data = array_merge_recursive(
				$this->request->getPost()->toArray(),
				$this->request->getFiles()->toArray()
				);
			$form->setData($data);
			if($form->isValid()){
				$data = $form->getData();
				$data['user']['id'] = $company_id;
				try {
					if('' != $data['user']['avatar']['tmp_name']) {
						$avatar = $this->get('UploadImage')->uploadCVavatar($data['user']['avatar'], $company_id);
						$data['user']['avatar'] = $avatar['img'];
						// DELETE OLD AVATAR
						if($this->identity()->avatar != '') 
							$delete = $this->get('UploadsTable')->deleteUpload($company['avatar'], 'avatar');
							if($delete['error']) $this->fm()->addErrorMessage($delete['error']);
					} else unset($data['user']['avatar']);
					if($data['user']['company_name'] != $original_name) {
						$validator = new \Zend\Validator\Db\NoRecordExists(
							 array(
							 'table' => 'user',
							 'field' => 'company_name',
							 'adapter' => \Application\Model\zAbstractTable::getAdapter()
							 )
						 );
						if(!$validator->isValid($data['user']['company_name'])) throw new \Application\Exception\Exception($this->translate('This Company Name is already used in the system'), 1);
					}
					if(isset($data['user']['role']) && $data['user']['role'] != $default_role) {
						if(!$this->isPermitted('_admin_\access\assign_roles\\'.$data['user']['role']) || !$this->isPermitted('_admin_\access\assign_roles\\'.$default_role)) {
							$form->get('user')->get('role')->setValue($default_role);
							throw new \Application\Exception\Exception($this->translate('You cannot assign role ').$data['user']['role'].$this->translate(' to that company'), 1);
						}
						$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
																		array(
																			'module' => 'Admin',
																			'controller' => 'access',
																			'action' => 'assign-roles',
																			'id' => $company_id
																			) ,
																		$this->translate('Modified owner role to').' :'.$data['user']['role'] 
																	);
					}
					$this->get('UserTable')->save($data['user']);
					$this->fm()->addSuccessMessage($this->translate('Owner information updated!'));
					$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
																	array(
																		'module' => 'Admin',
																		'controller' => 'userdb',
																		'action' => 'company',
																		'id' => $company_id
																		) ,
																	$this->translate('Updated Owner Info') 
																);
					$this->get('RefreshUserId');
					$this->redirect()->refresh();										
				} catch (\Exception $e) {
					$this->fm()->addErrorMessage($e->getMessage());
					$this->redirect()->refresh();
				}
			} else {
				$this->fm()->addErrorMessage($form->getMessages());
			}			

		} else {
			$this->get('AdminNotifReadedTable')->readNotifications($this->identity()->id, null, 'company', $company_id);
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
															array(
																'module' => 'Admin',
																'controller' => 'userdb',
																'action' => 'company',
																'id' => $company_id
																) ,
															'Open Owner page'
														);
		}

		
		$vacancy_form = $this->get('VacancyForm');
		$vacancy_form->setup($this->get('VacancyFields'), $this->identity()->id, ['admin_form' => 1, 'owner_id' => $company_id, 'post_vk' => $this->isPermitted('_info_\social\post_vk')]);
		$vacancy_form->setRequiredFields(array('vacancies' => array('user')), 1);
		$vacancy_form->setAttribute('action', $this->url()->fromRoute('admin/actions', array('controller' => 'vacancies', 'action' => 'index')));
		
		$v_options = [
			'show_hidden' => 1,
			'count_candidates' => 1,
			'_limit' => $this->params()->fromQuery('v_limit', 5), 
			'_page' => $this->params()->fromQuery('v_page', 1),
		];
		$vacancies_for_company = $this->get('VacanciesTable')->getAllVacancies(null, ['for_company' => $company_id], $v_options);
		$v_options['count'] = 1;
		$v_total_results = $this->get('VacanciesTable')->getAllVacancies(null, ['for_company' => $company_id], $v_options)->current()->count;
		$mail_to_obj = new \stdClass();
		$mail_to_obj->email = $company->email;
		$mails_count = $this->get('MailBoxTable')->getMailsFlow(null, _MBOFFICE_, array($mail_to_obj), [], ['count' => 1])->current()->count;
	

		$view = new ViewModel(array(
			'form' => $form,
			'company' 		=> 		$company,
			'cv_notes' 		=> 		$this->get('UserCvNotesTable')->getUserCvNotes($company_id),
			'mails_count' 	=> 		$mails_count,
			'messages_count' => $this->get('MessageTable')->getMessagesCount($company_id)->current()->count,
			'last_note' 	=> 		$this->get('UserCvNotesTable')->getLastUserCvNote($company_id),
			'in_favorites' 	=> 		$this->get('AdminUserFavoritesTable')->isInFavorites($this->identity()->id, $company_id),
			'vacancies_for_company' 	=>		$vacancies_for_company,
			'v_total_results' 	=>	$v_total_results,
			'v_options' 	=> 		$v_options,
			'vacancy_form' 	=> 		$vacancy_form,
			'wf_list' => $this->get('WorkedForTable')->getWorkedFor($this->identity()->id, ['company_id' => $company_id]),
			'wf_total_results' => $this->get('WorkedForTable')->getWorkedFor($this->identity()->id, ['company_id' => $company_id], ['count' => 1])->current()->count,
			));
		return $view;
	}

	public function generateCvAction()
	{
		$user_id = (int) $this->params()->fromRoute('id');
		if($user_id == 0) throw new \Application\Exception\Exception("User Id should be provided", 1);

		$userFields = array(
				'email', 'contact_email','contact_mobile','contact_phone',
				'info_website',
				'name','surname','middle_name', 'full_name', 
				'user_notes'
				);
		$userFieldSet = $this->get('UserFields')->remainFields($userFields);
		$checkboxes = $this->get('EmptyFieldset');
		$checkboxes->setName('user');
		foreach ($userFieldSet as $field) {
			$checkboxes->add([
			'name' => $field->getName(),
			'type' => 'Zend\Form\Element\Checkbox',
			'options' => array(
				'label' => $field->getLabel(),
				),
			]);
		}
		$form = $this->get('EmptyForm');
		$form->add($checkboxes);
		$form->add([
			'name' => 'lang',
			'type' => 'Zend\Form\Element\Radio',
			'options' => array(
				'label' => $this->translate('Language'),
				'value_options' => [
					['value' => 'en', 'label' => 'English', 'selected' => true],
					['value' => 'ru', 'label' => 'Russian', 'selected' => false]
				]
				),
			]);
		$form->addSubmit($this->translate('Generate'));
		$form->setAttribute('action', $this->url()->fromRoute('admin/actions', ['controller'=>'userdb', 'action' => 'generate-cv','id' => $user_id]));
		if($this->request->isPost()){ 
			$data = $this->request->getPost();
			$hide = [];
			foreach ($data['user'] as $key => $value) {
				if($value == 1) $hide[] = $key;
			}
			return $this->forward()->dispatch('UserInfo\Controller\Cv', array(
			                'controller' => 'UserInfo\Controller\Cv', 
			                'action' => 'index',
			                'user' =>  'id'.$user_id,
			                'query' => ['format' => 'pdf', 'hide' => $hide, 'lang' => $data['lang']]
			                ));
		}
		$view = new ViewModel(array(
			'form' => $form,
			));
		if ($this->params()->fromQuery('response') == 'terminal') $view->setTerminal(true);
		return $view;
	}

	public function registeredUsersAction()
	{
		$this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin', 'controller' => 'userdb', 'action' => 'registered-users'] ,'Opened registered users page');
		return $this->forward()->dispatch('Admin\Controller\Userdb', array(
		    'controller' => 'Admin\Controller\Userdb', 
		    'action' => 'index',
		    'dispatch' => true,
		    'filters' => ['registered' => 1],
		    'options' => [
		    	'user_type' => 'user',
		    	'_user_fields' => ['id', 'name','login','role', 'surname', 'full_name', 'nationality', 'email', 'desired_rank','avatar', 'cv_avatar', 'reg_date'],
		    	'show_fields' => ['id', 'office_note','name', 'role', 'email', 'nationality', 'desired_rank', 'reg_date'],
		    	]
		    ));
	}

	public function CompaniesAction()
	{
		$this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin', 'controller' => 'userdb', 'action' => 'companies'] ,'Opened companies page');
		return $this->forward()->dispatch('Admin\Controller\Userdb', array(
		    'controller' => 'Admin\Controller\Userdb', 
		    'action' => 'index',
		    'dispatch' => true,
		    'options' => [
		    	'user_type' => \Application\Model\UserTable::TYPE_COMPANY,
		    	'_user_fields' => ['id', 'company_name','login','role', 'avatar', 'home_country', 'home_address', 'home_city', 'contact_phone', 'contact_mobile', 'email', 'contact_email', 'reg_date', 'last_activity'],
		    	'_stats_fields' => ['company_vacancies', 'company_users', 'vacancies_for_company'],
		    	'show_fields' => ['office_note','name', 'role', 'email', 'address', 'last_activity', 'company_users', 'company_vacancies', 'vacancies_for_company'],
		    	'form_fields' => ['name', 'email', 'home_city', 'home_country', 'home_address', 'registered', 'in_db_from', 'in_db_to', 'cv_last_call_from', 'cv_last_call_to', 'role', 'notes', 'notes_text']
		    	]
		    ));	
	}

	public function OwnersAction()
	{
		$this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin', 'controller' => 'userdb', 'action' => 'owners'] ,'Opened owners page');
		return $this->forward()->dispatch('Admin\Controller\Userdb', array(
		    'controller' => 'Admin\Controller\Userdb', 
		    'action' => 'index',
		    'dispatch' => true,
		    'options' => [
		    	'user_type' => \Application\Model\UserTable::TYPE_OWNER,
		    	'_user_fields' => ['id', 'company_name','login','role', 'avatar', 'home_country', 'home_address', 'home_city', 'contact_phone', 'contact_mobile', 'email', 'contact_email', 'reg_date', 'last_activity'],
		    	'_stats_fields' => ['vacancies_for_company'],
		    	'show_fields' => ['id', 'office_note','name', 'email', 'address', 'vacancies_for_company'],
		    	'form_fields' => ['name', 'email', 'home_city', 'home_country', 'home_address', 'in_db_from', 'in_db_to', 'cv_last_call_from', 'cv_last_call_to', 'notes', 'notes_text']
		    	]
		    ));	
	}


	public function favoriteUsersAction()
	{
		$this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin', 'controller' => 'userdb', 'action' => 'favorite-users'] ,'Opened Favorite users page');

		return $this->forward()->dispatch('Admin\Controller\Userdb', array(
		    'controller' => 'Admin\Controller\Userdb', 
		    'action' => 'index',
		    'dispatch' => true,
		    'options' => [
		    	'admin_favorites' => 1,
		    	'user_type' => 'user',
		    	'_user_fields' => ['id', 'name','login', 'role','surname', 'full_name', 'nationality', 'email', 'desired_rank','avatar', 'cv_avatar', 'cv_last_call', 'cv_last_update'],
		    	'show_fields' => ['id', 'office_note','name', 'role', 'email', 'nationality', 'desired_rank', 'cv_last_call', 'fav_time', 'remove_favorites']
		    	]
		    ));
	}

	public function favoriteCompaniesAction()
	{
		$this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin', 'controller' => 'userdb', 'action' => 'favorite-companies'] ,'Opened Favorite companies page');
		return $this->forward()->dispatch('Admin\Controller\Userdb', array(
		    'controller' => 'Admin\Controller\Userdb', 
		    'action' => 'index',
		    'dispatch' => true,
		    'options' => [
		    	'admin_favorites' => 1,
		    	'user_type' => 'company',
		    	'_user_fields' => ['id', 'company_name','login','role','avatar',  'home_country', 'home_country', 'home_city', 'contact_phone', 'contact_mobile', 'email', 'contact_email', 'reg_date','cv_last_call', 'last_activity'],
		    	'_stats_fields' => ['company_vacancies', 'company_users'],
		    	'show_fields' => ['id', 'office_note','name', 'role', 'email', 'address', 'cv_last_call', 'fav_time', 'remove_favorites', 'company_users', 'company_vacancies'],
		    	'form_fields' => ['name', 'email', 'home_city', 'home_country', 'home_address', 'registered', 'in_db_from', 'in_db_to', 'cv_last_call_from', 'cv_last_call_to', 'role',  'notes', 'notes_text']
		    	]
		    ));	
	}




	public function addToFavoritesAction() {
		$user_id = (int)$this->params()->fromRoute('id');
		try {
			if($user_id == 0) throw new \Application\Exception\Exception("User Not Found", 1);
			$this->get('AdminUserFavoritesTable')->AddToFavorites($this->identity()->id, $user_id);
			$success = 1;
			$message = $this->translate('Added User To Favorites');
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
															array(
																'module' => 'Admin',
																'controller' => 'userdb',
																'action' => $this->routeMatch()->getParam('action'),
																'id' => $user_id
																) ,
															$message
														);

		} catch (\Exception $e) {
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
			$success = 0;
		}

		return $this->viewResponse($success, $message, [
			'redirect' => $this->url()->fromRoute('admin/actions', array('controller' => 'userdb', 'action' => 'user', 'id' => $user_id)), 
			'exception' => (isset($e)) ? $e : null
			]);
	}

	public function removeFromFavoritesAction() {
		$user_id = (int)$this->params()->fromRoute('id');
		try {
			if($user_id == 0) throw new Exception("User Not Found", 1);
			$this->get('AdminUserFavoritesTable')->removeFromFavorites($this->identity()->id, $user_id);
			$success = 1;
			$message = $this->translate('Removed User From Favorites');
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
															array(
																'module' => 'Admin',
																'controller' => 'userdb',
																'action' => $this->routeMatch()->getParam('action'),
																'id' => $user_id
																) ,
															$message
														);
		} catch (\Exception $e) {
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
			$success = 0;
		}
		return $this->viewResponse($success, $message, [
			'redirect' => $this->url()->fromRoute('admin/actions', array('controller' => 'userdb', 'action' => 'user', 'id' => $user_id)), 
			'exception' => (isset($e)) ? $e : null
			]);
	}




	public function refreshLastCallAction() {
		$user_id = (int)$this->params()->fromRoute('id');
		try {
			$this->get('UserTable')->refreshUserCvLastCall($user_id);
			$success = 1;
			$message = $this->translate('Last Call Refreshed');
			$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
															array(
																'module' => 'Admin',
																'controller' => 'userdb',
																'action' => $this->routeMatch()->getParam('action'),
																'id' => $user_id
																) ,
															$message
														);
		} catch (\Exception $e) {
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
			$success = 0;
		}
		return $this->viewResponse($success, $message, [
			'redirect' => $this->url()->fromRoute('admin/actions', array('controller' => 'userdb', 'action' => 'user', 'id' => $user_id)), 
			'view_data' => zformatDateTime(time()),
			'exception' => (isset($e)) ? $e : null
			]);
	}

	public function checkCompanyAction()
	{
		return $this->forward()->dispatch('Admin\Controller\Userdb', array(
		    'controller' => 'Admin\Controller\Userdb', 
		    'action' => 'check-user',
		    'dispatch' => true,
		    'type' => \Application\Model\UserTable::TYPE_COMPANY
		    ));
	}

	public function checkOwnerAction()
	{
		return $this->forward()->dispatch('Admin\Controller\Userdb', array(
		    'controller' => 'Admin\Controller\Userdb', 
		    'action' => 'check-user',
		    'dispatch' => true,
		    'type' => \Application\Model\UserTable::TYPE_OWNER
		    ));
	}

	public function checkUserAction() {
		$success = 1;
		$message ='';

		try {
			if($this->params()->fromRoute('type') == \Application\Model\UserTable::TYPE_COMPANY) {
				$company_name = $this->get('UserFields')->get('company_name');
				$form_action = 'check-company';
				$submit = $this->translate('Check Company');
				$action = 'add-company';
				$red_action = 'company';
				$red_submit = $this->translate('Create New Company');
				$red_label = $this->translate('Company with such email not found. Create New Company ?');
				$header = $this->translate('Check Company');
				$this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin', 'controller' => 'userdb', 'action' => 'check-company'] ,'Checked company');
			} else if($this->params()->fromRoute('type') == \Application\Model\UserTable::TYPE_OWNER) {
				$company_name = $this->get('UserFields')->get('company_name');
				$form_action = 'check-owner';
				$submit = $this->translate('Check Ship Owner');
				$action = 'add-owner';
				$red_action = 'owner';
				$red_submit = $this->translate('Create New Owner');
				$red_label = $this->translate('Ship Owner with such email not found. Create New Owner ?');
				$header = $this->translate('Check Ship Owner');
				$this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin', 'controller' => 'userdb', 'action' => 'check-owner'] ,'Checked shipowner');
			} else {
				$form_action = 'check-user';
				$submit = $this->translate('Check User');
				$action = 'add-user';
				$red_action = 'user';
				$red_submit = $this->translate('Create New User');
				$red_label = $this->translate('User with such email not found. Create New User ?');
				$header = $this->translate('Check User');
				$this->get('AdminActivityTable')->addActivity($this->identity()->id, ['module' => 'Admin', 'controller' => 'userdb', 'action' => 'check-user'] ,'Checked user');
			}
			$userFields = $this->get('UserFields')->remainFields(array('email'));
			$form = $this->get('EmptyForm');
			$form->setName('check_user_form');
			$form->setAttribute('action', $this->url()->fromRoute('admin/actions', array('controller' => 'userdb', 'action' => $form_action)));
			
			$userFields->get('email')->setLabel('Email');

			$form->add($userFields)->addSubmit($submit, $submit, 'submit');
			$form->get('submit')->setAttributes(['data-ajax' => 1, 'data-response' => 'follow']);
			if($this->request->isPost()) {
				$data = $this->request->getPost();
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()),777 );
				$data = $form->getData();
				$userTable = $this->get('UserTable');
				$user = $userTable->getUserByEmail($data['user']['email'], 0);
				if($user && isset($user->id)) $redirect = $this->url()->fromRoute('admin/actions', array('controller' => 'userdb', 'action' => $red_action, 'id' => $user->id));
				else {
					if($this->params()->fromRoute('type') == \Application\Model\UserTable::TYPE_COMPANY) $form->get('user')->add($company_name);
					$form->remove('submit');
					$form->setName('add_user_form');
					$form->setAttribute('method', 'POST');
					$form->setAttribute('action', $this->url()->fromRoute('admin/actions', array('controller' => 'userdb', 'action' => $action)));
					$form->addSubmit($red_submit, $red_label, 'submit');
					$form->get('submit')->setAttributes(['data-ajax' => 1]);
					// $form->get('submit')->setAttribute('data-response', null);
				}
			}
			
		} catch (\Exception $e) {
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
			$success = 0;
		}

		$viewModel = 0;
		if($form) $viewModel = new ViewModel(array(
			'form' => $form,
			'header' => $header,
		));

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'admin/userdb/check-user',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect))? $redirect : null,
		    'force_redirect' => (isset($redirect))? 1 : 0
		    ]);	
	}


	public function AddCompanyAction()
	{
		return $this->forward()->dispatch('Admin\Controller\Userdb', array(
		    'controller' => 'Admin\Controller\Userdb', 
		    'action' => 'add-user',
		    'dispatch' => true,
		    'type' => \Application\Model\UserTable::TYPE_COMPANY
		    ));
	}

	public function AddOwnerAction()
	{
		return $this->forward()->dispatch('Admin\Controller\Userdb', array(
		    'controller' => 'Admin\Controller\Userdb', 
		    'action' => 'add-user',
		    'dispatch' => true,
		    'type' => \Application\Model\UserTable::TYPE_OWNER
		    ));
	}

	public function addUserAction() {

		$success = 1;
		$message ='';

		try {
			if($this->params()->fromRoute('type') == \Application\Model\UserTable::TYPE_COMPANY) {
				$userFields = $this->get('UserFields')->remainFields(array('email', 'company_name'));
				$curr_action = 'add-company';
				$submit = $this->translate('Add Company');
				$red_action = 'company';
				$type = \Application\Model\UserTable::TYPE_COMPANY;
				$role = \Application\Access\AccessList::ROLE_UNREG;
				$activity = $this->translate('Created New Company');
				$notif_type = 'new_company_add';
				$notif_section = 'company';
				$notif_comment = 'Company added to database';
				$header = $this->translate('Create New Company');
			} else if($this->params()->fromRoute('type') == \Application\Model\UserTable::TYPE_OWNER) {
				$userFields = $this->get('UserFields')->remainFields(array('email', 'company_name'));
				$curr_action = 'add-owner';
				$submit = $this->translate('Add Owner');
				$red_action = 'owner';
				$type = \Application\Model\UserTable::TYPE_OWNER;
				$role = \Application\Access\AccessList::ROLE_UNREG;
				$activity = $this->translate('Created New Owner');
				$notif_type = 'new_owner_add';
				$notif_section = 'company';
				$notif_comment = 'Owner added to database';
				$header = $this->translate('Create New Ship Owner');
			} else {
				$userFields = $this->get('UserFields')->remainFields(array('email'));
				$curr_action = 'add-user';
				$submit = $this->translate('Add User');
				$red_action = 'user';
				$type = \Application\Model\UserTable::TYPE_USER;
				$role = \Application\Access\AccessList::ROLE_UNREG;
				$activity = $this->translate('Created New User');
				$notif_type = 'new_user_add';
				$notif_section = 'user';
				$notif_comment = 'User added to database';
				$header = $this->translate('Create New User');
			}


			$form = $this->get('EmptyForm');
			$form->setName('add_user_form');
			$userFields->get('email')->setLabel('Email');
			$form->setAttribute('action', $this->url()->fromRoute('admin/actions', array('controller' => 'userdb', 'action' => $curr_action)));
			
			$form->add($userFields)->addSubmit($submit, $submit, 'submit');
			$form->get('submit')->setAttributes(['data-ajax' => 1]);
					
			if($this->request->isPost()) {
				$data = $this->request->getPost();
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
				
				$data = $form->getData();
				$userTable = $this->get('UserTable');
				$user = $userTable->getUserByEmail($data['user']['email'], 0);
				if($user && isset($user->id)) $redirect = $this->url()->fromRoute('admin/actions', array('controller' => 'userdb', 'action' => $red_action, 'id' => $user->id));
				else {
					$data['user']['type'] = $type;
					$data['user']['role'] = $role;
					$data['user']['in_db_date'] = time();
					$data['user']['info_source'] = 'manual';
					$user_id = $userTable->save($data['user']);
					$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
																	array(
																		'module' => 'Admin',
																		'controller' => 'userdb',
																		'action' => $curr_action,
																		'id' => $user_id
																		) ,
																	$activity
																);

					$this->get('AdminNotifTable')->addNotification($notif_type, $notif_section, $user_id, $user_id, $notif_comment);
					$new_user_id = $userTable->getUserIdByEmail($data['user']['email']);
					if(isset($new_user_id) && $new_user_id != 0) $redirect =  $this->url()->fromRoute('admin/actions', array('controller' => 'userdb', 'action' => $red_action, 'id' => $new_user_id));
				}
			}
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}

		$viewModel = 0;
		if($form) $viewModel = new ViewModel(array(
			'form' => $form,
			'header' => $header,
		));

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'admin/userdb/check-user',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect))? $redirect : null,
		    'force_redirect' => (isset($redirect))? 1 : 0
		    ]);	
	}

		public function removeWorkForAction()
		{
		    $wf_id = $this->params()->fromRoute('id');
		    try {
		        if(!$wf_id) throw new \Application\Exception\Exception("CV note id should be provided", 1);
		        $table = $this->get('WorkedForTable');
		        $wf_record = $table->get($wf_id);
		        if(!$wf_record) throw new \Application\Exception\Exception("Record not found", 1);
		        $user_id = $wf_record->user_id;
		        $table->delete($wf_id);
		        $success = 1;
		        $message = $this->translate('Record deleted');
		    } catch (\Exception $e) {
		        $success = 0;
		        $message = $e->getMessage();
		    }

		    return $this->viewResponse($success, $message, [
		        'redirect' => $this->url()->fromRoute($route, ['controller' => 'userdb','action' => 'add-work-for', 'id' => $user_id]),
		        'exception' => (isset($e)) ? $e : null
		        ]); 
		}

		public function addWorkForAction() {

		try {
			$success = 1;
			$message ='';
			$user_id = $this->params()->fromRoute('id');
			$q_options = $this->setDefaultOptions(['_limit' => 20, 't_options' => ['predicat' => 'company_', 'd_controller' => 'userdb', 'd_action' => 'remove-work-for', 'd_id_field' => 'wf_id'], 'show_fields' => ['office_note','name', 'email', 'remove']]);
			
			if(!$user_id) throw new \Application\Exception\Exception("User id should be provided", 1);
			
			$form = $this->get('EmptyForm');
			$cl_options = ['user_type' => ['company', 'owner'], '_user_fields' => ['id', 'company_name'], '_limit' => 0];
			$companies_list = $this->get('UserTable')->getUsersList($this->identity()->id, [], $cl_options);
			$companies_list = $this->get('UserTable')->resultToFormSelect($companies_list, 'id', null, null, 'company_name');
			$form->setAttribute('action', $this->url()->fromRoute('admin/actions', array('controller' => 'userdb', 'action' => 'add-work-for', 'id' => $user_id)));

			$form->add(array(
				'name' => 'user_id',
				'type' => 'hidden',
				'attributes' => array(
					'value' => $user_id,
					)
				));

			$form->add(array(
			'name' => 'company_id',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => $this->translate('Company'),
				'required' => 1,
				'empty_option' => '--Select--',
				'options' => $companies_list,
				),
			'attributes' => array(
				'id' => 'company_id',
				)
			));

			$form->addSubmit();
			$form->get('submit')->setAttributes(['data-ajax' => 1]);
			
			if($this->request->isPost()) {
				$data = $this->request->getPost();
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
				$data = $form->getData();
				$this->get('WorkedForTable')->addWorkedFor($data['user_id'], $data['company_id']);
				$success = 1;
				$message = $this->translate('User information updated');
			}
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}

		

		$viewModel = 0;
		if($form) $viewModel = new ViewModel(array(
			'form' => $form,
			'data_list' => $this->get('WorkedForTable')->getWorkedFor($this->identity()->id, ['user_id' => $user_id], ['notes_field' => 'company_id']),
			'total_results' => $this->get('WorkedForTable')->getWorkedFor($this->identity()->id, ['user_id' => $user_id], ['count' => 1])->current()->count,
			'q_options' => $q_options
		));

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel, 
		    'template' => 'admin/userdb/add-work-for',
		    'exception' => (isset($e)) ? $e : null,
		    'redirect' => (isset($redirect))? $redirect : null,
		    'force_redirect' => (isset($redirect))? 1 : 0
		    ]);	
	}

	public function userMessagesAction()
	{
		$from_id = (int)$this->params()->fromRoute('id');

		return $this->forward()->dispatch('My\Controller\Messages', array(
		                'controller' => 'My\Controller\Messages', 
		                'action' => 'index',
		                'from_id' =>  $from_id,
		                'dispatch' => 1
		                ));

	}

	public function pmAction()
	{
		$chat_id = $this->params()->fromRoute('id');
		$from_id = $this->params()->fromQuery('from_id');
		$send_to_id = $this->params()->fromQuery('talk');


		return $this->forward()->dispatch('My\Controller\Messages', array(
		                'controller' => 'My\Controller\Messages', 
		                'action' => 'pm',
		                'id' =>  $chat_id,
		                'from_id' => $from_id,
		                'dispatch' => 1
		                ));

	}

	public function pmflowAction()
	{
		$chat_id = $this->params()->fromRoute('id');
		$send_to_id = $this->params()->fromQuery('talk');
		$from_id = $this->params()->fromQuery('from_id');


		return $this->forward()->dispatch('My\Controller\Messages', array(
		                'controller' => 'My\Controller\Messages', 
		                'action' => 'pmflow',
		                'id' =>  $chat_id,
		                'from_id' => $from_id,
		                'dispatch' => 1
		                ));

	}

	public function userCvNotesAction()
	{
		$user_id = (int)$this->params()->fromRoute('id');
		$filters = ($this->params()->fromQuery('filters')) ? array_filter($this->params()->fromQuery('filters')) : [];
		$page = (int) $this->params()->fromQuery('_page', 1);
		$limit = (int) $this->params()->fromQuery('_limit', 5);

		if($this->request->isPost()){
				$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
																array(
																	'module' => 'Admin',
																	'controller' => 'userdb',
																	'action' => $this->routeMatch()->getParam('action'),
																	'id' => $user_id
																	) ,
																$this->translate('Added New Note To User Page') 
															);
		}

		return $this->forward()->dispatch('Application\Controller\Seamansdb', array(
		                'controller' => 'Application\Controller\Seamansdb', 
		                'action' => 'user-cv-notes',
		                'id' =>  $user_id,
		                'show_admin_notes' => true,
		                'show_admin_private_notes' => true,
		                'query' => [
		                	'filters' => $filters, 
		                	'_page' => $page, 
		                	'_limit' => $limit, 
		                	'form_action' => $this->url()->fromRoute('admin/actions', ['controller' => 'userdb', 'action' => 'user-cv-notes', 'id' => $user_id])
		                	]
		                ));

	}

	public function deleteCvNoteAction()
	{
		return $this->forward()->dispatch('Application\Controller\Seamansdb', array(
		                'controller' => 'Application\Controller\Seamansdb', 
		                'action' => 'delete-cv-note',
		                'id' =>  $this->params()->fromRoute('id'),
		                ));
	}


	public function notesCallFailAction(){
		return $this->forward()->dispatch('Application\Controller\Seamansdb', array(
		                'controller' => 'Application\Controller\Seamansdb', 
		                'action' => 'notes-call-fail',
		                'id' =>  $this->params()->fromRoute('id'),
		                ));
	}

	public function notesAskSalaryAction()
	{
		return $this->forward()->dispatch('Application\Controller\Seamansdb', array(
		                'controller' => 'Application\Controller\Seamansdb', 
		                'action' => 'notes-ask-salary',
		                'id' =>  $this->params()->fromRoute('id'),
		                ));
	}
	
	public function notesAskPositionAction()
	{
		return $this->forward()->dispatch('Application\Controller\Seamansdb', array(
		                'controller' => 'Application\Controller\Seamansdb', 
		                'action' => 'notes-ask-position',
		                'id' =>  $this->params()->fromRoute('id'),
		                ));
	}

	public function notesReadinessAction()
	{
		return $this->forward()->dispatch('Application\Controller\Seamansdb', array(
		                'controller' => 'Application\Controller\Seamansdb', 
		                'action' => 'notes-readiness',
		                'id' =>  $this->params()->fromRoute('id'),
		                ));
	}

	public function notesCvUpdatedAction()
	{
		return $this->forward()->dispatch('Application\Controller\Seamansdb', array(
		                'controller' => 'Application\Controller\Seamansdb', 
		                'action' => 'notes-cv-updated',
		                'id' =>  $this->params()->fromRoute('id'),
		                ));
	}



	public function userExperienceAction()
	{

		$user_id = (int)$this->params()->fromRoute('id');
		try {
			if($user_id == 0) throw new \Application\Exception\Exception("User Not Found", 1);
			
			$user = $this->get('UserTable')->getUserById($user_id);
			$experienceTable = $this->get('ExperienceTable');

			$form = $this->get('EmptyForm');
			$form->add($this->get('ContractFields'));
			$form->addSubmit(null, $this->translate('Add Contract'));
			$form->get('submit')->setAttribute('data-ajax', true);
			$form->setAttribute('action', $this->url()->fromRoute('admin/actions', array('controller' => 'userdb', 'action' => 'user-experience', 'id' => $user_id)));

			if($contract_id = $this->params()->fromQuery('edit_contract')) {
				$contract = $experienceTable->getUserContract($contract_id);
				$form->setData(array('user_experience' => $contract));
				$form->setAttribute('action', $this->url()->fromRoute('admin/actions', 
															array('controller' => 'userdb', 'action' => 'user-experience', 'id' => $user_id), 
															array('query' => array('edit_contract' => $contract_id))
														));
				$form->get('submit')->setLabel($this->translate('Save Contract'));		
			} else $contract_id = null;

			$form->setRequiredFields(array('user_experience' => array('ship_name','rank', 'ship_built')), false);

			$success = 1;
			$message = null;
			if($this->request->isPost()){
				$data = $this->request->getPost()->toArray();
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
				$data = $form->getData();
				if(isset($data['user_experience']['rank_text']) && ($data['user_experience']['rank_text'] != '') ) $data['user_experience']['rank'] = $data['user_experience']['rank_text'];
				if(!isset($data['user_experience']['id']) || ('' == $data['user_experience']['id'])) $data['user_experience']['id'] = $contract_id;
				$data['user_experience']['user'] = $user->id;
				$data['user_experience']['time'] = time();
				$experienceTable->save($data['user_experience']);
				$success = 1;
				$message = $this->translate('User Experience Updated');
				$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
																array(
																	'module' => 'Admin',
																	'controller' => 'userdb',
																	'action' => $this->routeMatch()->getParam('action'),
																	'id' => $user_id
																	) ,
																$message
															);
			}			
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}

		$viewModel = 0;
		if(isset($form)) 
			$viewModel = new ViewModel(array(
				'form' => $form,
				));

		return $this->viewResponse($success, $message, [
			'view_data' => $viewModel, 
			'template' => 'admin/userdb/user-experience',
			'exception' => (isset($e)) ? $e : null
			]);
	}



	public function userDocumentsAction()
	{

		$user_id = (int)$this->params()->fromRoute('id');
		try {
			if($user_id == 0) throw new \Application\Exception\Exception("User id not provided", 1);
			
			$user = $this->get('UserTable')->getUserById($user_id);
			$docsTable = $this->get('DocumentsTable');
			$form = $this->get('EmptyForm');
			$form->add($this->get('DocsFields'));
			$form->addSubmit(null, $this->translate('Add Document'));
			$form->get('submit')->setAttribute('data-ajax', true);
			$form->setAttribute('action', $this->url()->fromRoute('admin/actions', 
				array('controller' => 'userdb', 'action' => 'user-documents', 'id' => $user_id)));

			if($doc_id = $this->params()->fromQuery('edit_document')) {
				$document = $docsTable->getUserDocument($doc_id);
				$form->setData(array('user_documents' => $document));
				$form->setAttribute('action', $this->url()->fromRoute('admin/actions', 
															array('controller' => 'userdb', 'action' => 'user-documents', 'id' => $user_id), 
															array('query' => array('edit_document' => $doc_id))
														));
				$form->get('submit')->setLabel($this->translate('Save Document'));		
			} else $doc_id = null;

			$success = 1;
			$message = null;

			if($this->request->isPost()){
				$data = $this->request->getPost()->toArray();
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
				$data = $form->getData();
				if(!isset($data['user_documents']['id']) || ('' == $data['user_documents']['id'])) $data['user_documents']['id'] = $doc_id;
				$docsTable->saveUserDocument($user->id, $data['user_documents']);
				$success = 1;
				$message = $this->translate('User Documents Updated');
				$this->get('AdminActivityTable')->addActivity($this->identity()->id, 
																array(
																	'module' => 'Admin',
																	'controller' => 'userdb',
																	'action' => $this->routeMatch()->getParam('action'),
																	'id' => $user_id
																	) ,
																$message
															);
			}
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}

		$viewModel = 0;
		if(isset($form)) 
			$viewModel = new ViewModel(array(
				'form' => $form,
				));

		return $this->viewResponse($success, $message, [
			'view_data' => $viewModel, 
			'template' => 'admin/userdb/user-documents',
			'exception' => (isset($e)) ? $e : null
			]);
	}




	public function updateAction() {
		// $table = new \Application\Model\zEmptyTable('list-ship_group');
		// $string = "";

	// $result = $table->query(
	// 	"INSERT IGNORE INTO `list-s_group-s_type` (ship_group_id,ship_type_id)
	// 		SELECT (SELECT s.id FROM `list-ship_group` s WHERE s.ship_group = 'OFFSHORE FLEET') , 
	// 		t.id FROM `list-ship_type` t WHERE t.ship_type IN ($string);
	// 	"
	// 	);

		// d('stop');
		// $result =  $table->query(
		// 	"SELECT `email`
		// 		FROM `user` u
		// 		WHERE `home_city` = 'izmail'
		// 	"
		// 	);
		// $str = '';
		// foreach ($result as $user) {
		// 	$str .= $user->email;
		// 	$str .= ';';
		// }
		// d($str);

		// $crypt = new \Zend\Crypt\Password\Bcrypt;
		// $password = $crypt->create('qazWSXmkoNJI');
		// d($password);
		// d(get_class_methods($crypt));

		// $client = new \Zend\Http\Client('http://api.seacontact.com/oauth');
		// $client->setMethod('POST');
		// $client->setHeaders([
		// 	'Accept' => 'application/json', 
		// 	'Content-Type' => 'application/json'
		// 	]);
		// $client->setAuth('sc_api_client', 'qazWSXmkoNJI', \Zend\Http\Client::AUTH_BASIC);
		// $client->setRawBody('{
		// 			"grant_type": "password",
		// 			"username": "zzzloy777@gmail.com",
		// 			"password": "cr3wsilent"
		// 		 }');
		// $client->send();
		// d($client->getResponse()->__toString());

		// try {
		// 	$response = \Zend\Http\ClientStatic::post(
		// 		'http://api.seacontact.com/oauth'
		// 		, null
		// 		,['Accept' => 'application/json','Content-Type' => 'application/json']
		// 		,
		// 		'{
		// 			"grant_type": "password",
		// 			"username": "testuser",
		// 			"password": "testpass",
		// 			"client_id": "testclient"
		// 		 }'
		// 		);
		// 	d($response->__toString());
		// } catch (\Exception $e) {
		// 	d($e->getMessage());
		// }


		// try {
		// 	$response = \Zend\Http\ClientStatic::get(
		// 		'http://api.seacontact.com/users.get'
		// 		, [
		// 			'access_token' => '2e717d1492eaceeff3636b7f73296ac528415ac0',
		// 		]
		// 		,['Accept' => 'application/json','Content-Type' => 'application/json',]
		// 		,
		// 		'{
		// 			"_user_fields": "name,email"
		// 		 }'
		// 		);
		// 	d($response->__toString());
		// } catch (\Exception $e) {
		// 	d($e->getMessage());
		// }

		// $client = new \Zend\Http\Client('http://api.seacontact.com/pics.upload');
		// $client->setMethod('POST');
		// $client->setFileUpload(_IMGROOT_.'anonym_ava.png', 'pic');
		// // $client->setEncType(\Zend\Http\Client::ENC_URLENCODED);
		// $client->setHeaders([
		// 	'Accept' => 'application/json', 
		// 	// 'Content-Type' => 'application/json',
		// 	// 'Authorization' => 'Bearer 788844747405d2e3c3e582129d6f1f6b7466aab1'
		// 	'Authorization' => 'Bearer 5b817fb7c51e77ce0510186746c2d14cf0cb0dd8'
		// 	]);
		// // $client->setRawBody('{
		// // 	"user_id": "52",
		// // 	"message": "new message "
		// // 		 }');
		// // d(($client->getRequest()));
		// // d(get_class_methods($client));
		// $client->send();
		// d($client->getResponse()->__toString());
		
		
		// try {
		// 	$response = \Zend\Http\ClientStatic::get(
		// 		'http://api.seacontact.com/news.get'
		// 		, [
		// 			// 'access_token' => '788844747405d2e3c3e582129d6f1f6b7466aab1',
		// 			'access_token' => '5b817fb7c51e77ce0510186746c2d14cf0cb0dd8',
		// 			// 'section' => 'logbook',
		// 			// 'section_id' => 12793,
		// 			'_limit' => 2,
		// 			'_stats_fields' => 'total_comments'
		// 		]
		// 		,['Accept' => 'application/json']
		// 		// ,
		// 		// '{
		// 		// 	"access_token": "2e717d1492eaceeff3636b7f73296ac528415ac0",
		// 		// 	"title": "new question",
		// 		// 	"tags": "banana,apple,tomato",
		// 		// 	"text": "which fruits do you prefer",
		// 		// 	"anonym": "0"
		// 		//  }'
		// 		);
		// 	d($response->__toString());
		// } catch (\Exception $e) {
		// 	d($e->getMessage());
		// }

		// d(phpinfo());

		// $table = $this->get('UserTable');

		// $d_emails = $table->getAllGroupCount('email', null, 1);
		// $k = 0;
		// foreach ($d_emails as $d_email) {
		// 	$duplicates = $table->getFieldsByFields('id', ['email' => $d_email->email]);
		// 	$c_dup = count($duplicates);
		// 	$c = 0;
		// 	foreach ($duplicates as $dup) {
		// 		$c++;
		// 		if($c == $c_dup) d('#'.$dup->id.' id remained', 1);
		// 		else {
		// 			$table->delete($dup->id);
		// 		}
		// 		d($c.' duplicates deleted', 1);
		// 	}
		// $k = $k + $c;
		// }
		
		// $table->updateDb();

		// d($k.' total cvs deleted');
		$dump = $this->get('Dump');
		$dump->setDumpFolder(_PUBLICROOT_.'dump_mz');

		$last_dump = $dump->getDump('cv_database_30');

		// d($last_dump);
		$userTable = $this->get('UserTable');
		$experienceTable = $this->get('ExperienceTable');
		$docsTable = $this->get('DocumentsTable');

		$hydrator = new \Admin\Model\Hydrators\MaritimeZone;
		$saved_users = 0;
		$updated_users = 0;
		$total_users = 0;
		foreach ($last_dump as $dump_item => $user_info) {
			$total_users++;
			if(is_array($user_info)){
				$email = $user_info['user']['email'];
				$user_id = $userTable->getIdByField('email', $email);
				if($user_id) {
					d('user '.$user_id.' already in database', 1);
					$user_info = $hydrator->hydrate($user_info);
					$user_in_db = $userTable->get($user_id);
					// check user main info
					if($user_in_db->cv_last_update > (time() - 86400)) {
						d('already updated...', 1);
						continue;
					}
					d('updating main info :...', 1);
					if(isset($user_info['user']['contact_mobile']))
						$user_info['user']['contact_mobile_2'] = $user_in_db->contact_mobile;
					if(isset($user_info['user']['contact_phone']))
						$user_info['user']['contact_phone_2'] = $user_in_db->contact_phone;
					if(isset($user_info['user']['minimum_salary']) && $user_info['user']['minimum_salary'] < $user_in_db->minimum_salary) $user_info['user']['minimum_salary'] = $user_in_db->minimum_salary; 
					if(isset($user_info['user']['in_db_date']) && $user_info['user']['in_db_date'] < $user_in_db->in_db_date) $user_info['user']['in_db_date'] = $user_in_db->in_db_date; 
					$user_info['user']['desired_rank'] = $user_in_db->desired_rank;					
					$user_info['user']['id'] = $user_in_db->id;
					
					$userTable->save($user_info['user']);

					$exp_updated = false;
					if(isset($user_info['user_experience'])) {
						$last_contract = $experienceTable->getUserLastContract($user_id)->current();
						if($last_contract) {
							foreach ($user_info['user_experience'] as $contract) {
								if(isset($contract['date_from'])) {
									if($contract['date_from'] > $last_contract->latest_date) {
										d('New contract found ! updating...', 1);
										$contract['user'] = $user_id;
										$experienceTable->save($contract);
										$exp_updated = true;
									}
								} else d('contract dates not found', 1);

							}
						} else {
							d('no contract records, inserting new ones...', 1);
							foreach($user_info['user_experience'] as $contract){
								$contract['user'] = $user_id;
								$experienceTable->save($contract);
								$exp_updated = true;
							}
						}
					}


					if(isset($user_info['user_documents'])) {
						$user_docs = $docsTable->getAllUserDocs($user_id);
						if($exp_updated == true || count($user_docs) == 0) {
							d('updating user documents section...', 1);
							if(count($user_docs) != 0) {
								foreach ($user_docs as $doc) {
									$docsTable->delete($doc['id']);
								}
							}
							foreach ($user_info['user_documents'] as $new_doc) {
								$new_doc['user'] = $user_id;
								$docsTable->save($new_doc);
							}
						}
					}
					$updated_users++;
				} else {
					$hydrated = $hydrator->hydrate($user_info);
					// d($hydrated, 1);
					$new_user = $userTable->multiSave($hydrated);
					d('saved user '.$user_info['user']['email'], 1);
					$saved_users++;
					// if($saved_users == 3) d('stop');
				}

			}
		}        
		d('Total '.$saved_users. ' users added! ,'.$updated_users.' users updated!, '.$total_users.' total users checked');


	}

}