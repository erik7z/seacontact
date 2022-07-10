<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractController
{

		public function jsonAction()
		{
			$response['success'] = $this->params()->fromQuery('success');
			$response['code'] = $this->params()->fromQuery('code');
			$response['message'] = $this->params()->fromQuery('message');
			$response['data'] = $this->params()->fromQuery('data');
			$view = new ViewModel(array(
					'response' => json_encode($response),
					));
			$view->setTemplate('/user-info/json');
			$view->setTerminal(true);
			return $view;
		}


		public function indexAction()
		{
			if($this->identity()) {
				// d(($this->routeMatch()->getParams()));
				// d(get_class_methods($this->routeMatch()));
				return $this->redirect()->toRoute('sc/userinfo', ['user' => zgetUserLogin($this->identity()), 'controller' => null], ['query' => $this->params()->fromQuery()], true);
				// после регистрации при заходе на главную выдается личная страница
				// $module = ($this->identity()->type == \Application\Model\UserTable::TYPE_COMPANY)? 'Company' : 'My';
				// return $this->forward()->dispatch($module.'\Controller\Index', array(
				// 		'controller' => $module.'\Controller\Index',
				// 		'action' => 'index',
				// 		'dispatch' => true,
				// 		));
			}

			$form = $this->get('EmptyForm');
			$userFields = $this->get('UserFields')->remainFields(array('email'));
			if($this->get('ErrorLog')->count > 2) $form->add($this->get('CaptchaField'));
			$form->add($userFields)->addSubmit('Join', 'Join');

			 if($this->request->isPost()){
					$post = $this->request->getPost();
					$form->setData($post);
					try {
							if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 100);
							$this->get('ErrorLog')->count = 0;
							$data = $form->getData();
							$userTable = $this->get('UserTable');
							if(!$userTable->checkUserByEmail($data['user']['email'])) {
									return $this->registerNewUser($data);

							} else {
									$user = $userTable->getUserByEmail($data['user']['email']);
									if(\Application\Access\AccessList::ROLE_UNREG == $user->role) {
											$data['user']['id'] = $user->id;
											return $this->registerNewUser($data, ['user_status' => 'found_in_db']);
									}
									$this->fm()->addSuccessMessage($this->translate('Welcome Back! Please enter your password'));
									$this->get('ErrorLog')->count = 0;
									return $this->redirect()->toRoute('sc/auth', [], ['query' => ['email' => $data['user']['email']]], true);
							}
					}
					catch(\Exception $e) {
							$this->get('ErrorLog')->count +=1;
							if(!$messages = unserialize($e->getMessage())) $messages = $e->getMessage();
							$this->fm()->addErrorMessage($messages);
					}

				}

				$this->layout('layout/landing');
				$viewModel = new ViewModel(array(
						'error' => $this->get('ErrorLog')->count,
						'form' => $form,
						'vacancies' => $this->get('VacanciesTable')->getAllVacancies(null, [], array('_limit' => 15)),
						'fleet_type' => $this->get('VacanciesTable')->getVacanciesFleetType(),
						'latest_cvs' => $this->get('UserTable')->getLatestCvs(),
						'users_count' => $this->get('UserTable')->getCount(),
						'vacancies_count' => $this->get('VacanciesTable')->getCount(),
						'companies_count' => $this->get('UserTable')->getUsersList(null, [], ['user_type' => 'company', 'count' => 1])->current()->count,
						'news' => $this->get('LogBookTable')->getAllLogbooks(null, [], ['only_current' => 1, '_limit' => 12]),
						));
				return $viewModel;
		}





		public function fastRegAction()
		{
			$success = 1;
			$message = '';
			$social_reg = ($this->params()->fromQuery('reg_type') == 'social')? 1 : 0;
			try {
				$form = $this->get('EmptyForm');
				$fields = ['email', 'cv_file'];
				if($social_reg) $fields = ['email'];
				$userFields = $this->get('UserFields')->remainFields($fields);
				if($social_reg) {

				}

				if($this->get('ErrorLog')->count > 2) $form->add($this->get('CaptchaField'));
				$submit_text = $this->translate('Join');
				if($social_reg) $submit_text = $this->translate('Check email');
				$form->add($userFields)->addSubmit($submit_text);
				$form->setName('fast_reg_form');
				$form->setAttribute('action', $this->url()->fromRoute('sc/fast-reg', [], ['query' => $this->params()->fromQuery()]));

				$title = null;
				$vacancy_id = null;
				$vacancy_title = null;
				if($vacancy_id = $this->params()->fromPost('subscribe_vacancy')) {
					try {
						$vacancy = $this->get('VacanciesTable')->getAllVacancies(null, ['id' => $vacancy_id])->current();
						if(!$vacancy) throw new \Application\Exception\Exception($this->translate('Vacancy with such id not found', 404));
						$form->setAttribute('action', $this->url()->fromRoute('sc/fast-reg', [], array('query' => array('role' => 'seaman', 'subscribe_vacancy' => $vacancy_id))));
						$form->get('submit')->setValue($this->translate('Subscribe for Vacancy'));
						$title = $this->translate('Subscription for vacancy:');
						$vacancy_title = $vacancy->title;
						$vacancy_redirect =  $this->url()->fromRoute('sc/vacancies/actions', ['action' => 'toggle-subscribe', 'id' => $vacancy_id], ['query' => ['redirect' => $this->url()->fromRoute('sc/vacancies')]]);
					} catch (Exception $e) {
						$this->fm()->addErrorMessage($e->getMessage());
					}

				}

				if($this->request->isPost()){
						 $data = array_merge_recursive(
								$this->request->getPost()->toArray(),
								$this->request->getFiles()->toArray()
								);
						 $form->setData($data);
							 if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
							 $this->get('ErrorLog')->count = 0;
							 $data = $form->getData();

							 $userTable = $this->get('UserTable');
							 if(!$userTable->checkUserByEmail($data['user']['email'])) {
									 return $this->registerNewUser($data,['vacancy' => $vacancy_id, 'vacancy_title' => $vacancy_title, 'query_params' => $this->params()->fromQuery()]);

							 } else {
									 $user = $userTable->getUserByEmail($data['user']['email']);
									 if(\Application\Access\AccessList::ROLE_UNREG == $user->role || $social_reg) {
											 $data['user']['id'] = $user->id;
											 return $this->registerNewUser($data,['user_status' => 'found_in_db', 'vacancy' => $vacancy_id, 'vacancy_title' => $vacancy_title, 'query_params' => $this->params()->fromQuery()]);
									 }
									 $message = $this->translate('Welcome back! You already registered before! Please enter your password');
									 $this->get('ErrorLog')->count = 0;
									 $redirect =  $this->url()->fromRoute('sc/auth', [], [
											 'query' => [
													 'email' => $data['user']['email'],
													 'redirect' => (isset($vacancy_redirect))? $vacancy_redirect : null,
													 ]
											 ]);
							 }

				 }
			} catch (\Exception $e) {
				$this->get('ErrorLog')->count +=1;
				$success = 0;
				$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
			}

				$this->layout('layout/landing');
				$viewModel = 0;
				if(isset($form))
						$viewModel = new ViewModel(array(
							'error' => $this->get('ErrorLog')->count,
							'form' => $form,
							'title' => $title,
							'vacancy_title' => $vacancy_title,
							'social_reg' => $social_reg
							));
				return $this->viewResponse($success, $message, [
						'view_data' => $viewModel,
						'template' => 'application/index/fast-reg',
						'exception' => (isset($e)) ? $e : null,
						'redirect' => (isset($redirect))? $redirect : null,
						'force_redirect' => (isset($redirect))? 1 : 0,
						'terminal' => $this->params()->fromPost('terminal'),
						'no_json' => $this->params()->fromPost('no_json')
						]);
		}

			public function confirmEmailAction()
			{
				$success = 1;
				$message = '';
				$social_reg = ($this->params()->fromQuery('reg_type') == 'social')? 1 : 0;
				try {
					$form = $this->get('EmptyForm');
					$userFields = $this->get('UserFields')->remainFields(array('email_confirmation_key'));
					$form->setAttribute('action', $this->url()->fromRoute('sc/confirm-email', [], ['query' => $this->params()->fromQuery()]));
					$form->setName('confirm_email_form');
					if($this->get('ErrorLog')->count > 2) $form->add($this->get('CaptchaField'));
					$form->add($userFields)->addSubmit($this->translate('Confirm'));

					$result = false;
					$userTable = $this->get('UserTable');
						if($this->request->isPost()){
								$post = $this->request->getPost();
								$form->setData($post);
								if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
								$this->get('ErrorLog')->count = 0;
								$code = $post['user']['email_confirmation_key'];
								$result = $userTable->checkRegCode($code);
						} // if not post
						else if($code = $this->params()->fromQuery('code')) {
							if(!$this->get('validation-secr_code')->isValid($code)) throw new \Application\Exception\Exception($this->translate('The confirmation code is incorrect'), 101);
							$this->get('ErrorLog')->count = 0;
							$result = $userTable->checkRegCode($code);
						}

					if($result === true) {
							$this->get('ErrorLog')->count = 0;
							$user_info = $this->params()->fromQuery('user_info');
							$user_info['id'] = $userTable->getUserIdBySecretKey($code);
							$this->saveSocialUserInfo($user_info);
							$redirect = $this->url()->fromRoute('sc/complete-registration', [], ['query' => ['code' => $code] ]);
					}
				} catch (\Exception $e) {
					$this->get('ErrorLog')->count +=1;
					$success = 0;
					$message =($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
				}

				$this->layout('layout/landing');
				$viewModel = 0;
				if(isset($form))
					$viewModel = new ViewModel(array(
							'error' => $this->get('ErrorLog')->count,
							'form' => $form,
							'social_reg' => $social_reg
							));

				return $this->viewResponse($success, $message, [
						'view_data' => $viewModel,
						'template' => 'application/index/confirm-email',
						'exception' => (isset($e)) ? $e : null,
						'redirect' => (isset($redirect))? $redirect : null,
						'force_redirect' => (isset($redirect))? 1 : 0,
						'extra_data' => ($result === true)? ['status' => 'email_confirmed', 'code' => $code] : null
						]);
			}

		public function completeRegistrationAction()
			{
				$success = 1;
				$message = '';
				$status = '';
				$code = null;
				$type = $this->params()->fromQuery('type');
				$social_reg = ($this->params()->fromQuery('reg_type') == 'social')? 1 : 0;
				$social_secret = $this->params()->fromQuery('social_secret');
				$social_type = $this->params()->fromQuery('social_type');
				try {
					$form = $this->get('EmptyForm');
					$userTable = $this->get('UserTable');
					if(!$type) {
						$form_fields = ['email_confirmation_key','type'];
					} else {
						$form_fields = ['email_confirmation_key', 'password', 'login'];
						if ($type == $userTable::TYPE_USER) $form_fields = array_merge($form_fields, ['name' ,'surname', 'contact_mobile', 'desired_rank', 'dob', 'nationality']);
						if ($type == $userTable::TYPE_COMPANY) $form_fields = array_merge($form_fields, ['company_name', 'info_website','contact_mobile', 'contact_phone', 'home_country', 'home_city', 'home_address']);
					}

					$userFields = $this->get('UserFields')->remainFields($form_fields);
					if($this->get('ErrorLog')->count > 2) $form->add($this->get('CaptchaField'));

					$form->add($userFields)->addSubmit();
					if($form->get('user')->has('type')) {
						$form->get('user')->get('type')->setValueOptions([
						      ['value' => \Application\Model\UserTable::TYPE_USER, 'label' => $this->translate('Seaman')],
						      ['value' => \Application\Model\UserTable::TYPE_COMPANY, 'label' => $this->translate('Company')],
						    ]);
					}

					if($code = $this->params()->fromQuery('code')) {
							$user = $this->get('UserTable')->getActiveUserBySecretKey($code);
							$user->dob = date('Y-m-d', $user->dob);
							$form->setData(array('user' => $user->toArray()));
					}
					$form->setAttribute('action', $this->url()->fromRoute('sc/complete-registration', [], ['query' => $this->params()->fromQuery()]));
					if($this->request->isPost()){
							$post = $this->request->getPost();
							$form->setData($post);
							if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
							$this->get('ErrorLog')->count = 0;
							$data = $form->getData();
							$user_data = $data['user'];
							if(!$this->get('validation-secr_code')->isValid($code)) throw new \Application\Exception\Exception($this->translate("Your request denied by authorisation. Try again later"), 1);

							$user = $userTable->getActiveUserBySecretKey($code);
							if(isset($user_data['type'])) {
								$user->type = $user_data['type'];
								if($user_data['type'] == $userTable::TYPE_USER) $user->role = \Application\Access\AccessList::ROLE_USER;
								if($user_data['type'] == $userTable::TYPE_COMPANY) $user->role = \Application\Access\AccessList::ROLE_COMPANY_UNKNOWN;
								$redirect = $this->url()->fromRoute('sc/complete-registration', [], ['query' => array_merge($this->params()->fromQuery(), ['type' => $user_data['type']])]);
								$status = 'type_selected';
							} else {
								$clear_pass = $user_data['password'];
								$code = $user_data['email_confirmation_key'];
								$login = $user_data['login'];
								// if($userTable->checkUserByLogin($login)) throw new \Application\Exception\Exception("This login is already used !", 1); // проверка на уровне fieldset inputfilters
								$user->password = $this->get('salt')->hash($clear_pass);
								$user->email_confirmation_key = NULL;
								$user->login = $login;
								if(isset($user_data['name']))           $user->name =  $user_data['name'];
								if(isset($user_data['surname']))        $user->surname =  $user_data['surname'];
								if(isset($user_data['nationality']))    $user->nationality =  $user_data['nationality'];
								if(isset($user_data['sex']))            $user->sex =  $user_data['sex'];
								if(isset($user_data['company_name']))   $user->company_name =  $user_data['company_name'];
								if(isset($user_data['info_website']))   $user->info_website =  $user_data['info_website'];
								if(isset($user_data['contact_phone']))  $user->contact_phone =  $user_data['contact_phone'];
								if(isset($user_data['home_country']))   $user->home_country =  $user_data['home_country'];
								if(isset($user_data['home_city']))      $user->home_city =  $user_data['home_city'];
								if(isset($user_data['home_address']))   $user->home_address =  $user_data['home_address'];
								$status = 'profile_completed';
								$mail = $this->get('Mail');
								$mail->sendSuccessMail($user->email, $user->name, $clear_pass);
								$message = $this->translate('Registration Successfull! Welcome to Seacontact!');
								$user->reg_date = time();
								if(!$user->in_db_date) $user->in_db_date = time();
							}
							if($social_secret) {
								if($social_type == 'vk') $user->social_vk_secret = $social_secret;
								if($social_type == 'fb') $user->social_fb_token = $social_secret;
								if($social_type == 'in') $user->social_in_secret = $social_secret;
							}
							$user->save();

							if($status == 'profile_completed') {
								$user = $this->get('UserTable')->getUserById($user->id);
								$this->get('AuthService')->getStorage()->write($user);
								$redirect = $this->url()->fromRoute('sc/home');
							}

							if($user->type == $userTable::TYPE_USER) $this->get('AdminNotifTable')->addNotification('new_user_reg', 'user', $user->id, $user->id,$this->translate('User Registered On Website'));
							if($user->type == $userTable::TYPE_COMPANY) $this->get('AdminNotifTable')->addNotification('new_company_reg', 'company', $user->id, $user->id,$this->translate('New Company Completed registration'));
							$this->get('ErrorLog')->count = 0;
					} else { // if not post
						$code = $this->params()->fromQuery('code');
						if(!$this->get('validation-secr_code')->isValid($code)) throw new \Application\Exception\Exception($this->translate("Your request will not be accepted, Please repeat previous step"), 1);
						$form->get('user')->get('email_confirmation_key')->setValue($code);
					}
				} catch (\Exception $e) {
					$this->get('ErrorLog')->count +=1;
					$success = 0;
					$message =($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
				}


				$this->layout('layout/landing');
				$viewModel = 0;
				if(isset($form))
					$viewModel = new ViewModel(array(
							'error' => $this->get('ErrorLog')->count,
							'form' => $form,
							'type' => $type,
							'code' => $code,
							'social_reg' => $social_reg
							));

				return $this->viewResponse($success, $message, [
						'view_data' => $viewModel,
						'template' => 'application/index/complete-registration',
						'exception' => (isset($e)) ? $e : null,
						'redirect' => (isset($redirect))? $redirect : null,
						'force_redirect' => (isset($redirect))? 1 : 0,
						'extra_data' => ['status' => $status, 'code' => $code, 'social_type' => $social_type, 'social_secret' => $social_secret, 'social_reg' => $social_reg]
						]);
			}

		public function authAction()
		{
				$success = 1;
				$message = '';
				$email = $this->params()->fromQuery('email', null);


				try {
						$form = $this->get('EmptyForm');
						$redirect = $this->params()->fromQuery('redirect', null);
						$force_redirect = 0;
						$action = ($redirect)? $this->url()->fromRoute('sc/auth', [], ['query' => ['redirect' => $redirect]]) : null;
						$userFields = $this->get('UserFields')->remainFields(array('login_or_email', 'password'));
						$userFields->get('login_or_email')->setValue($email);
						if($this->get('ErrorLog')->count > 2) $form->add($this->get('CaptchaField'));
						$form->add($userFields)->addSubmit('Enter', 'Enter');
						if($this->request->isPost()){
								$post = $this->request->getPost();
								$form->setData($post);
								if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
								$this->get('ErrorLog')->count = 0;
								$data = $form->getData();
								$data = $data['user'];
								$credential = $data['login_or_email'];
								$userTable = $this->get('UserTable');
								if(strpos($credential, '@') === false) {
										$email = $userTable->getUserEmailByLogin($credential);
								} else {
										$validator = new \Zend\Validator\EmailAddress();
										if(!$validator->isValid($credential)) throw new \Application\Exception\Exception(serialize($validator->getMessages()), 777);
										$email = $credential;
								}

								if(!$user = $userTable->getUserIfRegConfirmed($email))
										throw new \Application\Exception\Exception($this->translate('You are in database, but email is not confirmed. Please request resetting your password'), 1);

								$authService = $this->get('AuthService');
								if($authService->hasIdentity()) $authService->clearIdentity();
								$password = $this->get('salt')->format($data['password']);
								$authService->getAdapter()
										->setIdentity($email)
										->setCredential($password);
								$result = $authService->authenticate();
								if(!$result->isValid()) throw new \Application\Exception\Exception(serialize($result->getMessages()), 777);

								$authService->getStorage()->write($user);
								$this->get('ErrorLog')->count = 0;
								$success = 1;
								$message = $this->translate('Authentication Successfull');
								$redirect = ($redirect)? $redirect : $this->url()->fromRoute('sc/home');
								$force_redirect = 1;
						}
				} catch(\Exception $e) {
						$this->get('ErrorLog')->count +=1;
						$success = 0;
						$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
				}

				$this->layout('layout/landing');

				$viewModel = 0;
				if(isset($form))
						$viewModel = new ViewModel(array(
								'error' => $this->get('ErrorLog')->count,
								'action' => $action,
								'form' => $form,
								));

				return $this->viewResponse($success, $message, [
						'view_data' => $viewModel,
						'template' => 'application/index/auth',
						'exception' => (isset($e)) ? $e : null,
						'redirect' => ($force_redirect)? $redirect : null,
						'force_redirect' => $force_redirect,
						]);
		}


		public function forgotPasswordAction()
		{
				$success = 1;
				$message = '';
				try {
					 $form = $this->get('EmptyForm');
					 $userFields = $this->get('UserFields')->remainFields(array('email'));
					 if($this->get('ErrorLog')->count > 2) $form->add($this->get('CaptchaField'));
					 $form->add($userFields)->addSubmit('Send Password');

					if($this->request->isPost()){
							 $post = $this->request->getPost();
							 $form->setData($post);
							 if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
							 $this->get('ErrorLog')->count = 0;
							 $data = $form->getData();
							 $userTable = $this->get('UserTable');
							 if(!$userTable->checkUserByEmail($data['user']['email'])) throw new \Application\Exception\Exception($this->translate('User with such email not found!', 1));

							 $user = $userTable->getUserByEmail($data['user']['email']);
							 $data['user']['id'] = $user->id;

							 if(\Application\Access\AccessList::ROLE_UNREG == $user->role) return $this->registerNewUser($data, ['user_status' => 'found_in_db']);
							 else return $this->forgotPasswordUser($data);
					 }
				} catch (\Exception $e) {
					$this->get('ErrorLog')->count +=1;
					$message = ($e->getCode() == 777 ) ? unserialize($e->getMessage()) : $e->getMessage();
					$success = 0;
				}

				$this->layout('layout/landing');

				$viewModel = 0;
				if(isset($form))
					$viewModel = new ViewModel(array(
							'error' => $this->get('ErrorLog')->count,
							'form' => $form,
							));

				return $this->viewResponse($success, $message, [
						'view_data' => $viewModel,
						'template' => 'application/index/forgot-password',
						'exception' => (isset($e)) ? $e : null,
						]);
		}


		public function confirmResetPasswordAction()
		{
			$success = 1;
			$message = '';
			try {
				$form = $this->get('EmptyForm');
				$userFields = $this->get('UserFields')->remainFields(array('password_reset_key'));
				$form->setAttribute('action', $this->url()->fromRoute('sc/confirm-reset-password'));
				if($this->get('ErrorLog')->count > 2) $form->add($this->get('CaptchaField'));
				$form->add($userFields)->addSubmit($this->translate('Confirm'), $this->translate('Confirm'));
				$result = false;
				$userTable = $this->get('UserTable');
				if($this->request->isPost()){
						$post = $this->request->getPost();
						$form->setData($post);
						if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 100);
						$this->get('ErrorLog')->count = 0;
						$code = $post['user']['password_reset_key'];
						$result = $userTable->checkPassResetCode($code);
				} // if not post
				else if($code = $this->params()->fromQuery('code')) {
					if(!$this->get('validation-secr_code')->isValid($code)) throw new \Application\Exception\Exception($this->translate('The confirmation code is incorrect'), 100);
					$this->get('ErrorLog')->count = 0;
					$result = $userTable->checkPassResetCode($code);
				}
				if($result === true) {
						$this->get('ErrorLog')->count = 0;
						$redirect =  $this->url()->fromRoute('sc/set-new-password', [], ['query' => ['code' => $code]]);
				}
			} catch (\Exception $e) {
				$this->get('ErrorLog')->count +=1;
				$message = ($e->getCode() == 777 ) ? unserialize($e->getMessage()) : $e->getMessage();
				$success = 0;
			}


			$this->layout('layout/landing');
			$viewModel = 0;
			if(isset($form))
				$viewModel = new ViewModel(array(
						'error' => $this->get('ErrorLog')->count,
						'form' => $form,
						));
			return $this->viewResponse($success, $message, [
					'view_data' => $viewModel,
					'template' => 'application/index/confirm-reset-password',
					'exception' => (isset($e)) ? $e : null,
					'redirect' => (isset($redirect))? $redirect : null,
					'force_redirect' => (isset($redirect))? 1 : 0,
					]);
		}


		public function setNewPasswordAction()
		{
			$success = 1;
			$message = '';
			try {
				$form = $this->get('EmptyForm');
				$userFields = $this->get('UserFields')->remainFields(['password_reset_key', 'password', 'confirm_password']);
				if($this->get('ErrorLog')->count > 2) $form->add($this->get('CaptchaField'));
				$form->add($userFields)->addSubmit($this->translate('Set New Password'), $this->translate('Set New Password'));

				if($this->request->isPost()){
					$post = $this->request->getPost();
					$form->setData($post);
					if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
					$this->get('ErrorLog')->count = 0;
					$data = $form->getData();
					$clear_pass = $data['user']['password'];
					$code = $data['user']['password_reset_key'];
					if(!$this->get('validation-secr_code')->isValid($code)) throw new \Application\Exception\Exception($this->translate("Your request denied by authorisation. Please repeat previous steps"), 1);
					$userTable = $this->get('UserTable');
					$user = $userTable->getUserByPasswordResetKey($code);
					$user->password = $this->get('salt')->hash($clear_pass);
					$user->password_reset_key = NULL;
					$user->email_confirmation_key = NULL;
					$user->save();
					$mail = $this->get('Mail');
					$mail->sendNewPasswordMail($user->email, $user->name, $clear_pass);

					$authService = $this->get('AuthService');
					if($authService->hasIdentity()) $authService->clearIdentity();
					$password = $this->get('salt')->format($clear_pass);
					$authService->getAdapter()
							->setIdentity($user->email)
							->setCredential($password);
					$result = $authService->authenticate();
					if(!$result->isValid()) throw new \Application\Exception\Exception(serialize($result->getMessages()), 777);
					if(!$user = $userTable->getUserIfRegConfirmed($user->email))
							throw new \Application\Exception\Exception($this->translate('E-mail address not confirmed!'), 1);
					$authService->getStorage()->write($user);
					$this->get('ErrorLog')->count = 0;
					$success = 1;
					$message = $this->translate('Authentication Successfull');
					$redirect = ($redirect)? $redirect : $this->url()->fromRoute('sc/home');

					// $message = $this->translate('Password is successfully changed ! Now you can enter website with your login and new password');
					// $this->get('ErrorLog')->count = 0;
					// $redirect = $this->url()->fromRoute('sc/auth', [], ['query' => ['email' => $user->email]]);
				} //if not post
				else if($code = $this->params()->fromQuery('code')) {
						if(!$this->get('validation-secr_code')->isValid($code)) throw new \Application\Exception\Exception($this->translate("Your request will not be accepted, Please repeat previous steps"), 1);
								$form->get('user')->get('password_reset_key')->setValue($code);
				}
			} catch (\Exception $e) {
				$this->get('ErrorLog')->count +=1;
				$message = ($e->getCode() == 777 ) ? unserialize($e->getMessage()) : $e->getMessage();
				$success = 0;
			}

			$this->layout('layout/landing');
			$viewModel = 0;
			if(isset($form))
				$viewModel = new ViewModel(array(
						'error' => $this->get('ErrorLog')->count,
						'form' => $form,
						));
			return $this->viewResponse($success, $message, [
					'view_data' => $viewModel,
					'template' => 'application/index/set-new-password',
					'exception' => (isset($e)) ? $e : null,
					'redirect' => (isset($redirect))? $redirect : null,
					'force_redirect' => (isset($redirect))? 1 : 0,
					]);
		}


		public function exitAction()
		{
				$this->get('AuthService')->clearIdentity();
				$this->redirect()->toRoute('sc/home',[],[], true);
		}



		// shortcuts to social auth
		public function vkOauthAction()
		{
			return $this->socialAuthAction('vk');
		}

		public function fbOauthAction()
		{
			return $this->socialAuthAction('fb');
		}

		public function inOauthAction()
		{
			return $this->socialAuthAction('in');
		}

		public function fbOpenApiAction()
		{
			return $this->socialAuthAction('fb');
		}


		public function socialCaptchaAction()
		{
				$success = 1;
				$message = '';
				$force_redirect = 0;
				$captcha_sid = $this->params()->fromQuery('captcha_sid');
				$captcha_img = $this->params()->fromQuery('captcha_img');
				$redirect = $this->params()->fromQuery('redirect');

				$form = $this->get('EmptyForm');
				try {
						if (!$captcha_sid || !$captcha_img) throw new \Application\Exception\Exception("Captcha data not provided", 1);
						$action = ($redirect)? $this->url()->fromRoute('sc/auth', [], ['query' => ['redirect' => $redirect]]) : null;

						$form->add(array(
						'name' => 'captcha_sid',
						'type' => 'hidden',
						'attributes' => [
							'value' => $captcha_sid
						]
						));
						$form->add([
							'name' => 'captcha_key',
							'type' => 'Zend\Form\Element\Text',
							'options' => array(
								'label' => $this->translate('Captcha'),
								'required' => true,
								),
							]);
						$form->addSubmit($this->translate('Send'));

						if($this->request->isPost()){
								$post = $this->request->getPost();
								$form->setData($post);
								if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777);
								$data = $form->getData();
								unset($data['submit']);
								$redirect = (strpos($redirect, '?') !== false)? $redirect.'&'.http_build_query($data) : $redirect.'?'.http_build_query($data);
								return $this->redirect()->toUrl($redirect);
						}
				} catch(\Exception $e) {
						$this->get('ErrorLog')->count +=1;
						$success = 0;
						$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
				}

				$this->layout('layout/landing');

				$viewModel = 0;
				if(isset($form))
						$viewModel = new ViewModel(array(
								'form' => $form,
								'captcha_img' => $captcha_img,
								));

				return $this->viewResponse($success, $message, [
						'view_data' => $viewModel,
						'template' => 'application/index/social-captcha',
						'exception' => (isset($e)) ? $e : null,
						'redirect' => ($force_redirect)? $redirect : null,
						'force_redirect' => $force_redirect,
						]);
		}




		protected function getVkOauthUser() {
			$error = $this->params()->fromQuery('error');
			if($error) throw new \Application\Exception\Exception($this->params()->fromQuery('error_description'), 17);

			$code = $this->params()->fromQuery('code');
			$success_action = $this->params()->fromQuery('success_action');
			$captcha_sid = $this->params()->fromQuery('captcha_sid');
			$captcha_key = $this->params()->fromQuery('captcha_key');
			$access_token = $this->params()->fromQuery('access_token');

			$user_vk_api = $this->get('api_vk');
			if($captcha_sid && $captcha_key) {
				$user_vk_api->setCaptcha($captcha_sid, $captcha_key);
			}
			$login_url = $user_vk_api->getLoginUrl($success_action);
			if ($access_token) {
				$social_info = $this->get('UserTable')->getUserInfoByVkToken($access_token);
				if(!$social_info) return ['redirect' => $login_url];

				$user_vk_api->setUserToken($social_info['social_vk_token']);
				$user_vk_api->setUserSecret($social_info['social_vk_secret']);
			} else if($code) {
				$user_vk_api->authenticate($code, $success_action);
				$social_info['social_vk'] = $user_vk_api->getUserId();
				$social_info['social_vk_token'] = $user_vk_api->getUserToken();
				$social_info['social_vk_secret'] = $user_vk_api->getUserSecret();
				if($this->identity()) {
					$social_info['id'] = $this->identity()->id;
					$this->get('UserTable')->save($social_info);
					$this->get('RefreshUserId');
					if($success_action == 'vk_wall')
						return ['redirect' => $this->url()->fromRoute('sc/social/actions', ['action' => 'parse-vk'])];
				}
				$social_info['email'] = $user_vk_api->getTokenData()->email;
			}
			else return ['redirect' => $login_url];

			// $social_info['email'] = 'z_loy@mail.ru';
			// $social_info['social_vk_token'] = 'usertoken32864872637482';
			// $social_info['social_vk_secret'] = 'secret';
			// $social_info['social_vk'] = '15004627';

			try {
				$vk_user_info = $user_vk_api->getUserInfo($social_info['social_vk']);
			} catch (\Exception $e) {
				if ($e->getCode() == 14) {
					if(!isset($social_info['email']) || !$social_info['email']) {
						return [
						'redirect' => $this->url()->fromRoute('sc/fast-reg', [], ['query' => ['user_info' => $social_info]]),
						'exc' => $this->translate("We have a large database, maybe your CV is already there. Please enter your E-mail address to check."),
						'exc_code' => 702
						];
					}
					$social_info['id'] = $this->get('UserTable')->getUserIdByEmail($social_info['email'], 0);
					if($social_info['id']) $social_info = $this->saveSocialUserInfo($social_info);
					else $social_info = $this->addNewSocialUser($social_info);
					$data = unserialize($e->getMessage());
					$data['access_token'] = $social_info['social_vk_token'];
					throw new \Application\Exception\Exception(serialize($data), 14);
				}
				throw new \Exception($e->getMessage(), $e->getCode());
			}

			$social_info['social_vk_domain'] = $vk_user_info->domain;
			//writing personal info in additional variables (if user is already in system not to overwrite old info)
			if($social_info['social_vk_domain']) $personal_info['login'] = 'vk_'.$social_info['social_vk_domain'];
			$personal_info['avatar'] = $vk_user_info->photo_max;
			$personal_info['name'] = $vk_user_info->first_name;
			$personal_info['surname'] = $vk_user_info->last_name;
			$personal_info['full_name'] = $personal_info['name'].' '.$personal_info['surname'];
			$personal_info['dob'] = strtotime($vk_user_info->bdate);

			return ['social_info' => $social_info, 'personal_info' => $personal_info];
		}


		public function socialAuthAction($social_type = null)
		{
			$success = 1;
			$message = '';
			if(!$social_type) $social_type = $this->params()->fromQuery('social_type');
			try {
				if ($social_type == 'fb') $social_data = $this->getfbOauthUser();
				elseif ($social_type == 'vk') $social_data = $this->getVkOauthUser();
				elseif ($social_type == 'in') $social_data = $this->getInOauthUser();

				if(isset($social_data)) {
					if(isset($social_data['redirect'])) {
						$redirect = $social_data['redirect'];
						if(isset($social_data['exc'])) throw new \Application\Exception\Exception($social_data['exc'], $social_data['exc_code']);
					}
					else {
						$social_info = $social_data['social_info'];
						$personal_info = $social_data['personal_info'];
						if(!$social_info['email']) {
							$redirect = $this->url()->fromRoute('sc/fast-reg', [], ['query' => ['user_info' => array_merge($social_info, $personal_info)]]);
							throw new \Application\Exception\Exception($this->translate("We have a large database, maybe your CV is already there. Please enter your E-mail address to check."), 702);
						}
						$social_info['id'] = $this->get('UserTable')->getUserIdByEmail($social_info['email'], 0);
						if($social_info['id']) $social_info = $this->saveSocialUserInfo($social_info);
						else $social_info = $this->addNewSocialUser(array_merge($social_info, $personal_info));

						$user = $this->get('UserTable')->getUserById($social_info['id']);
						if($user->email_confirmation_key != NULL) {
							$redirect = $this->url()->fromRoute('sc/complete-registration', [], ['query' => ['code' => $user->email_confirmation_key]]);
							throw new \Application\Exception\Exception($this->translate('Please choose your role'), 702);
						}
						$authService = $this->get('AuthService');
						if($authService->hasIdentity()) $authService->clearIdentity();
						$authService->getStorage()->write($user);
						$extra_data['code'] = $social_info['email_confirmation_key'];
						$success = 1;
						$message = sprintf($this->translate('Hello %s, Welcome to Seacontact'), zgetUserName($this->identity()));
						$redirect = $this->url()->fromRoute('sc/home');
					}
				}
			} catch (\Exception $e) {
				if ($e->getCode() == 14) {
					$data = unserialize($e->getMessage());
					$request = $_SERVER['REQUEST_URI'];
					if(isset($data['access_token'])) {
						if(strpos($request, '?') !== false) $request .= '&';
						else $request .= '?';
						$request .= 'access_token='.$data['access_token'];
					}
					return $this->redirect()->toRoute('sc/social_captcha', [], ['query' => [
						'captcha_sid' => $data['captcha_sid'],
						'captcha_img' => $data['captcha_img'],
						'redirect' => _ADDRESS_NO_SLASH_.$request
						]
					], true);
				}
				$success = 0;
				$message = $e->getMessage();
			}

			$viewModel = new ViewModel(array(
			    ));
			$this->layout('layout/landing');
			return $this->viewResponse($success, $message, [
				'view_data' => $viewModel,
				'template' => 'application/index/social-auth',
				'exception' => (isset($e)) ? $e : null,
				'redirect' => (isset($redirect)) ? $redirect : null,
				'force_redirect' => (isset($redirect)) ? 1 : 0,
				'extra_data' => (isset($extra_data))? $extra_data : null,
				]);

		}


		public function getfbOauthUser(){
			$code = $this->params()->fromQuery('code');
			$error = $this->params()->fromQuery('error');
			$success_action = $this->params()->fromQuery('success_action');

			$fb = $this->get('api_fb');

			if($error) throw new \Application\Exception\Exception($this->params()->fromQuery('error_description'), 17);
			if(!$code) {
				// if not a redirect with code from VK auth redirecting to VK api
				$permissions = ['email', 'public_profile', 'user_friends', 'user_birthday', 'user_hometown', 'user_videos','user_photos', 'user_posts', 'publish_actions']; // optional
				$login_url = $fb->getRedirectLoginHelper()->getLoginUrl(_ADDRESS_NO_SLASH_.'/fb-oauth', $permissions);
				return ['redirect' => $login_url];
			}

			$accessToken = $fb->getRedirectLoginHelper()->getAccessToken();
			if(!$accessToken->isLongLived()) {
				$accessToken = $fb->getOAuth2Client()->getLongLivedAccessToken($accessToken);
			}
			$fb->setDefaultAccessToken($accessToken);
			$response = $fb->get('/me?fields=id,email,name,first_name,last_name,gender,picture.type(large)');
			$fb_info = $response->getDecodedBody();

			$social_info['social_fb'] = $fb_info['id'];
			$social_info['social_fb_token'] = $accessToken->getValue();
			$social_info['social_fb_expiry'] = $accessToken->getExpiresAt();
			$social_info['email'] = $fb_info['email'];

			$personal_info['name'] = $fb_info['first_name'];
			$personal_info['surname'] = $fb_info['last_name'];
			$personal_info['full_name'] = $personal_info['name'].' '.$personal_info['surname'];
			$personal_info['sex'] = $fb_info['gender'];
			$personal_info['avatar'] = $fb_info['picture']['data']['url'];
			return ['social_info' => $social_info, 'personal_info' => $personal_info];
		}

		public function getInOauthUser(){
			$code = $this->params()->fromQuery('code');
			$error = $this->params()->fromQuery('error');
			$success_action = $this->params()->fromQuery('success_action');

			$in = $this->get('api_in');

			if($error) throw new \Application\Exception\Exception($this->params()->fromQuery('error_description'), 17);
			if(!$code) {
				// if not a redirect with code from VK auth redirecting to VK api
				$permissions = [$in::SCOPE_BASIC_PROFILE, $in::SCOPE_EMAIL_ADDRESS, $in::SCOPE_WRITE_SHARE]; // optional
				$login_url = $in->getLoginUrl($permissions);
				return ['redirect' => $login_url];
			}

			$accessToken = $in->getAccessToken($code);
			$in_info = $in->get('/people/~:(id,email-address,first-name,last-name,picture-url,picture-urls::(original),positions,public-profile-url,api-standard-profile-request,site-standard-profile-request)');
			$social_info['social_in'] = $in_info['id'];
			$social_info['social_in_token'] = $accessToken;
			$social_info['social_in_expiry'] = $in->getAccessTokenExpiration();
			$social_info['social_in_page'] = $in_info['publicProfileUrl'];
			@$social_info['social_in_domain'] = array_pop(explode('/', $social_info['social_in_page']));
			$social_info['email'] = $in_info['emailAddress'];

			if($social_info['social_in_domain'])
				$personal_info['login'] = 'in_'.$social_info['social_in_domain'];
			$personal_info['name'] = $in_info['firstName'];
			$personal_info['surname'] = $in_info['lastName'];
			$personal_info['full_name'] = $personal_info['name'].' '.$personal_info['surname'];
			$personal_info['avatar'] = $in_info['pictureUrl'];
			if(isset($in_info['pictureUrls']['values'])) {
				foreach ($in_info['pictureUrls']['values'] as $pictureUrl) {
					$personal_info['avatar'] = $pictureUrl;
				}
			}
			return ['social_info' => $social_info, 'personal_info' => $personal_info];
		}


		public function addNewSocialUser($user_info)
		{
			$clear_pass = $this->get('salt')->regKey(6, true);
			$user_info['password'] = $this->get('salt')->hash($clear_pass);
			$user_info['email_confirmation_key'] = $clear_pass;
			$user_info['type'] = \Application\Model\UserTable::TYPE_USER;
			$user_info['role'] = \Application\Access\AccessList::ROLE_USER;
			$saved_user = $this->get('user_reg')->saveUserData(['user' => $user_info]);
			//downloading avatar from social
			if(isset($user_info['avatar'])) $this->saveSocialAvatar($saved_user['user']['id'], $user_info['avatar']);
			$this->get('Mail')->sendSuccessMail($user_info['email'], zgetUserName($user_info), $clear_pass);
			return $saved_user['user'];
		}

		protected function saveSocialAvatar($user_id, $social_avatar_link)
		{
			if ($social_avatar_link) {
				$image_source = $this->get('UploadImage')->__loadJpeg($social_avatar_link);
				$uploaded_avatar = $this->get('UploadImage')->getImageFromSource($image_source,true, $user_id);
				$this->get('UserTable')->save(['id' => $user_id, 'avatar' => $uploaded_avatar['thumb']]);
			}
		}

		public function saveSocialUserInfo($social_info)
		{
			if(isset($social_info['id']) && $social_info['id'])                       $user_info['id'] = $social_info['id'];
			if(isset($social_info['avatar']) && $social_info['avatar'])               $this->saveSocialAvatar($social_info['id'], $social_info['avatar']);

			if(isset($social_info['full_name']) && $social_info['full_name'])         $user_info['full_name'] = $social_info['full_name'];
			if(isset($social_info['name']) && $social_info['name'])                   $user_info['name'] = $social_info['name'];
			if(isset($social_info['surname']) && $social_info['surname'])             $user_info['surname'] = $social_info['surname'];
			if(isset($social_info['dob']) && $social_info['dob'])                     $user_info['dob'] = $social_info['dob'];
			if(isset($social_info['sex']) && $social_info['sex'])                     $user_info['sex'] = $social_info['sex'];
			if(isset($social_info['home_city']) && $social_info['home_city'])         $user_info['home_city'] = $social_info['home_city'];
			if(isset($social_info['home_country']) && $social_info['home_country'])   $user_info['home_country'] = $social_info['home_country'];

			// update social login data
			if(isset($social_info['social_fb']) && $social_info['social_fb'])                       $user_info['social_fb'] = $social_info['social_fb'];
			if(isset($social_info['social_fb_token']) && $social_info['social_fb_token'])           $user_info['social_fb_token'] = $social_info['social_fb_token'];
			if(isset($social_info['social_fb_expiry']) && $social_info['social_fb_expiry'])         $user_info['social_fb_expiry'] = $social_info['social_fb_expiry'];

			if(isset($social_info['social_vk']) && $social_info['social_vk'])                       $user_info['social_vk'] = $social_info['social_vk'];
			if(isset($social_info['social_vk_domain']) && $social_info['social_vk_domain'])         $user_info['social_vk_domain'] = $social_info['social_vk_domain'];
			if(isset($social_info['social_vk_token']) && $social_info['social_vk_token'])           $user_info['social_vk_token'] = $social_info['social_vk_token'];
			if(isset($social_info['social_vk_secret']) && $social_info['social_vk_secret'])         $user_info['social_vk_secret'] = $social_info['social_vk_secret'];

			if(isset($social_info['social_in']) && $social_info['social_in'])                       $user_info['social_in'] = $social_info['social_in'];
			if(isset($social_info['social_in_token']) && $social_info['social_in_token'])           $user_info['social_in_token'] = $social_info['social_in_token'];
			if(isset($social_info['social_in_expiry']) && $social_info['social_in_expiry'])         $user_info['social_in_expiry'] = $social_info['social_in_expiry'];
			if(isset($social_info['social_in_page']) && $social_info['social_in_page'])             $user_info['social_in_page'] = $social_info['social_in_page'];
			if(isset($social_info['social_in_domain']) && $social_info['social_in_domain'])         $user_info['social_in_domain'] = $social_info['social_in_domain'];

			$saved_user = $this->get('user_reg')->saveUserData(['user' => $user_info]);
			return $saved_user['user'];
		}

			protected function getFbOpenapiUser()
			{
				$social_auth_params = $this->params()->fromPost('social_auth_params');
				$social_info = $this->params()->fromPost('social_info');

				$fb = new \Facebook\Facebook([
					'app_id' => _FB_APP_ID_,
					'app_secret' => _FB_APP_SECRET_,
					'default_graph_version' => 'v2.5',
				]);
				$fb->setDefaultAccessToken($social_auth_params['accessToken']);
				$response = $fb->get('/me?fields=email,name,first_name,last_name,gender,picture.type(large)');
				$fb_info = $response->getDecodedBody();

				$social_info['social_fb'] = $social_auth_params['userID'];
				$social_info['social_fb_token'] = $social_auth_params['accessToken'];
				$social_info['social_fb_expiry'] = time() + (int)$social_auth_params['expiresIn'];
				$social_info['email'] = $fb_info['email'];

				$personal_info['full_name'] = $fb_info['name'];
				$personal_info['name'] = $fb_info['first_name'];
				$personal_info['surname'] = $fb_info['last_name'];
				$personal_info['sex'] = $fb_info['gender'];
				$personal_info['avatar'] = $fb_info['picture']['data']['url'];
				return ['social_info' => $social_info, 'personal_info' => $personal_info];
			}

			protected  function vkOpenApiMember($post_params) {
					$auth_info = [];
					$member = FALSE;
					$valid_keys = array('expire', 'mid', 'secret', 'sid', 'sig');
						//parsing cookie
						if (isset($_COOKIE['vk_app_'._VK_APP_ID_])) {
							$session_data = explode ('&', $_COOKIE['vk_app_'._VK_APP_ID_], 10);
							foreach ($session_data as $pair) {
								list($key, $value) = explode('=', $pair, 2);
								if (empty($key) || empty($value) || !in_array($key, $valid_keys)) {
									continue;
								}
								$auth_info[$key] = $value;
							}
						} else $auth_info = $post_params; // if no cookie, using ajax post params

						foreach ($valid_keys as $key) {
							if (!isset($auth_info[$key])) return $member;
						}
						ksort($auth_info);
						$sign = '';
						foreach ($auth_info as $key => $value) {
							if ($key != 'sig') {
								$sign .= ($key.'='.$value);
							}
						}
						$sign .= _VK_APP_SECR_KEY_;
						$sign = md5($sign);
						if ($auth_info['sig'] == $sign && $auth_info['expire'] > time()) {
							$member = array(
								'id' => intval($auth_info['mid']),
								'secret' => $auth_info['secret'],
								'sid' => $auth_info['sid']
							);
						}

					return $member;
				}



				protected function registerNewUser($user_data, $options = [])
				{
					$type = (isset($options['type']))? $options['type'] : 'unknown';
					$vacancy_id = (isset($options['vacancy_id']))? $options['vacancy_id'] : null;
					$vacancy_title = (isset($options['vacancy_title']))? $options['vacancy_title'] : null;
					$query_params = (isset($options['query_params']))? $options['query_params'] : [];
					$user_status = (isset($options['user_status']))? $options['user_status'] : 'new_user';

					$success = 1;
					$message = 0;
					try {
						$user_data['user']['email_confirmation_key'] = $this->get('salt')->regKey(6, true);
						$user_data['user']['info_source'] = (isset($user_data['user']['info_source']))? $user_data['user']['info_source'] : 'registration';
						$user_data['user']['role'] = \Application\Access\AccessList::ROLE_UNREG;

						$user_data = $this->get('user_reg')->saveUserData($user_data);

						$mail = $this->get('Mail');
						$mail->sendConfMail($user_data['user']['email'], '', $user_data['user']['email_confirmation_key'], $type, $query_params);

						if($vacancy_id) {
							$this->get('VacancySubsTable')->subscribe($user_data['user']['id'], $vacancy_id);
							$this->get('AdminNotifTable')->addVacancySubNotification($vacancy_id, $user_data['user']['id']);
							$mail->sendSubToVacancyUserMail($user_data['user']['email'], '',$vacancy_id, $vacancy_title);
						}

						$message = [];
						if ($user_status == 'new_user') {
							$message[] = $this->translate('We send a confirmation code to you. Please check your email, to complete registration !');
							$redirect = $this->url()->fromRoute('sc/confirm-email', [], ['query' => array_merge([ 'status' => 'new_user', 'type' => $type], $this->params()->fromQuery())]);
							$extra_data = ['status' => 'new_user'];
						} else {
							$message[] = $this->translate('Congratulations! We found you in our database ! Please check your e-mail to complete registration. ');
							$redirect = $this->url()->fromRoute('sc/confirm-email', [], ['query' => array_merge([ 'status' => 'found_in_db', 'type' => $type], $this->params()->fromQuery())]);
							$extra_data = ['status' => 'found_in_db'];
						}

						$message[] = $this->translate('If mail is not comming for a long time, please check in the SPAM folder of your mailbox');
						$this->get('ErrorLog')->count = 0;

					} catch (\Exception $e) {
						$success = 0;
						$message = $e->getMessage();
					}

					return $this->viewResponse($success, $message, [
							'exception' => (isset($e)) ? $e : null,
							'redirect' => (isset($redirect)) ? $redirect : $this->url()->fromRoute('sc/home'),
							'force_redirect' => 1,
							'extra_data' => $extra_data
							]);
				}

				protected function forgotPasswordUser($user_data)
				{
					$success = 1;
					$message = '';
					try {
						$user_data['user']['password_reset_key'] = $this->get('salt')->resetKey(6, true);
						$this->get('UserTable')->save($user_data['user']);
						$mail = $this->get('Mail');
						$mail->sendResetPassMail($user_data['user']['email'], '', $user_data['user']['password_reset_key']);
						$message = $this->translate('Please check e-mail to reset your password');

						$this->get('ErrorLog')->count = 0;
					} catch (\Exception $e) {
						$success = 0;
						$message = $e->getMessage();
					}

					return $this->viewResponse($success, $message, [
							'exception' => (isset($e)) ? $e : null,
							'redirect' => $this->url()->fromRoute('sc/confirm-reset-password', [], ['query' => ['status' => 'forgot_password']]),
							'force_redirect' => 1,
							]);
				}



}
