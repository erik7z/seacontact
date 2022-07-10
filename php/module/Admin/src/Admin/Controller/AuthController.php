<?php
namespace Admin\Controller;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class AuthController extends AbstractController
{

	/**
	 * Index action of some controller
	 * @return [type]
	 */

	public function indexAction()
	{
        $form = $this->get('EmptyForm');
        $userFields = $this->get('UserFields')->remainFields(array('email', 'password'));
        if($this->get('ErrorLog')->count > 2) $form->add($this->get('CaptchaField'));
        $action = null;
        $redirect = ($this->params()->fromQuery('redirect')) ? $this->params()->fromQuery('redirect') : null;
        if($redirect) $action = $this->url()->fromRoute('sc/auth', [], ['query' => ['redirect' => $redirect]]);
        $form->add($userFields)->addSubmit('Enter', 'Enter');
	
        try {
            if($this->request->isPost()){
                $post = $this->request->getPost();
                $form->setData($post);
                if($form->isValid()) {
                    $data = $form->getData();                    
                    $data = $data['user'];
                    $userTable = $this->get('UserTable');
                    if(!$user = $userTable->getUserIfRegConfirmed($data['email']))
                        throw new \Application\Exception\Exception('E-mail address not confirmed!', 102);
                        					
                    $authService = $this->get('AuthService');
                    if($authService->hasIdentity()) $authService->clearIdentity();
                    $password = $this->get('salt')->format($data['password']);
                    $authService->getAdapter()
                        ->setIdentity($data['email'])
                        ->setCredential($password);
                    $result = $authService->authenticate();
                    if(!$result->isValid()) throw new \Application\Exception\Exception(serialize($result->getMessages()), 101);
                    
                    $authService->getStorage()->write($user);
                    $this->fm()->addSuccessMessage($this->translate('Authentication Successfull'));
                    $this->get('ErrorLog')->count = 0;
                    $this->get('AdminActivityTable')->addActivity($this->identity()->id, 
                                                                    array(
                                                                        'module' => 'Admin',
                                                                        'controller' => 'auth',
                                                                        'action' => 'index',
                                                                        'id' => $this->identity()->id
                                                                        ) ,
                                                                    'Logged In' 
                                                                );
                    if ($this->params()->fromQuery('redirect')) return $this->redirect()->toUrl($this->params()->fromQuery('redirect'));
                    else return $this->redirect()->toRoute('admin');
                    
               } else throw new \Application\Exception\Exception(serialize($form->getMessages()), 1);
            }         
        } catch(\Exception $e) {
                $this->get('ErrorLog')->count +=1;
                if(!$mesages = unserialize($e->getMessage())) $mesages = $e->getMessage();
                $this->fm()->addErrorMessage($mesages);
                $this->redirect()->refresh();
        }

	
		$view = new ViewModel(array(
    		'error' => $this->get('ErrorLog')->count,
    		'form' => $form,
            'action' => $action,
		));
		$view->setTerminal(true);
		return $view;	
	}

}