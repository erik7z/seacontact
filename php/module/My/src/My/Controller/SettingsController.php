<?php
namespace My\Controller;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class SettingsController extends AbstractController
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
			$form = $this->get('EmptyForm');
			$userFields = $this->get('UserFields')->remainFields(['login', 'password', 'confirm_password','social_vk_parsing', 'social_fb_parsing']);

			$form->add($userFields);
			$form->add([
				'name' => 'change_login',
				'type' => 'Zend\Form\Element\Checkbox',
				'options' => array(
					'label' => $this->translate('Change'),
					),
			]);
			$form->add([
				'name' => 'change_password',
				'type' => 'Zend\Form\Element\Checkbox',
				'options' => array(
					'label' => $this->translate('Change'),
					),
			]);
			$form->addSubmit($this->translate('Save'));
			$form->setRequiredFields(array('user' => array('password', 'confirm_password')), false);
			$user = $this->get('UserTable')->getUserById($this->identity()->id);
			$form->setData(['user' => $user]);

			$pTable = $this->get('SocialPublicsTable');
			$fields = $pTable->getStandartFields();
			$publics = $pTable->getItems(NULL,['user_id' => $this->identity()->id], ['_fields' => $fields, '_order' => 'id']);

			if($this->request->isPost())
			{
				$data = $this->request->getPost();
				if ($data['change_login'] != 1) $form->removeValidator(['user' => ['login' => 'Db\NoRecordExists'] ] ); 
				if ($data['change_password'] == 1) $form->setRequiredFields(array('user' => array('password', 'confirm_password')), true);
				$form->setData($data);
				if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 777 );
				$data = $form->getData();
				$user_info = [];
				if($data['change_login'] == 1) {
					$user_info['login'] = $data['user']['login'];
					$message[] = $this->translate('Login succesfully changed');
				}
				if($data['change_password'] == 1) {
					$clear_pass = $data['user']['password'];
					$user_info['password'] = $this->get('salt')->hash($clear_pass);
					$this->get('Mail')->sendNewPasswordMail($user->email, zgetUserName($user), $clear_pass);
					$message[] =  $this->translate('Password succesfully changed');		
				}
				$user_info['social_vk_parsing'] = $data['user']['social_vk_parsing'];
				$user_info['id'] = $this->identity()->id;
				$this->get('UserTable')->save($user_info);
				$this->get('RefreshUserId');
				$success = 1;
			}
		} catch (\Exception $e) {
			$success = 0;
			$message = ($e->getCode() == 777)? unserialize($e->getMessage()) : $e->getMessage();
		}


		$viewModel = 0;
		if(isset($form)) 
		    $viewModel = new ViewModel(array(
		        'form' => $form,
		        'publics' => $publics
		        )); 

		return $this->viewResponse($success, $message, [
			'redirect' => isset($redirect)? $redirect : null,
			'force_redirect' => isset($redirect)? 1 : 0,
		    'view_data' => $viewModel, 
		    'template' => 'my/settings/index',
		    'exception' => (isset($e)) ? $e : null
		    ]);
	}




}