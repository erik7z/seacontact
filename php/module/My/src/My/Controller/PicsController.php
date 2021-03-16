<?php
namespace My\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class PicsController extends AbstractController
{
	public function indexAction()
	{
		return $this->forward()->dispatch('UserInfo\Controller\Pics', array(
		                'controller' => 'UserInfo\Controller\Pics', 
		                'action' => 'index',
		                'user' =>  'id'.$this->identity()->id,
		                ));
	}

	public function uploadImageAction()
	{
		$response = [];
	    try {
	        if(!$this->identity()) throw new \Application\Exception\Exception("Sign in required !", 1);
	        if(!isset($_FILES['file'])) throw new \Application\Exception\Exception("Image upload file required!", 1);
	        $result = $this->get('UploadImage')->upload($_FILES['file'],true, $this->identity()->id);

	        $response['success'] = true;
	        $response['location'] = $result['img'];    
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

	public function deletePicAction()
	{
		$pic_id = $this->params()->fromRoute('id');
		if(!$pic_id) $this->redirect()->toRoute('sc/home', [],[], true);

		$picsTable = $this->get('PicsTable');
		$pic = $picsTable->get($pic_id);
		$user = $this->identity();
		if($pic->user != $user->id) {
			$this->fm()->addErrorMessage('You cannot perform this action');
			return $this->redirect()->toRoute('sc/home', [],[], true);
		}

		$result = $picsTable->delete($pic_id);
		if($result) $this->fm()->addSuccessMessage('Picture Deleted');
		else $this->fm()->addErrorMessage('Error during deletion');
		return $this->redirect()->toRoute('sc/home', [],[], true);
	}


	public function deleteAvatarPicAction()
	{
		$ava_id = $this->params()->fromRoute('id');
		if(!$pic_id) $this->redirect()->toRoute('sc/home', [],[], true);

		$avatarTable = $this->get('AvatarTable');
		$avatar = $avatarTable->get($ava_id);
		$user = $this->identity();
		if($avatar->user != $user->id) {
			$this->fm()->addErrorMessage('You cannot perform this action');
			return $this->redirect()->toRoute('sc/home', [],[], true);
		}

		$result = $avatarTable->deleteUnusedAvatar($ava_id);

		if($result) $this->fm()->addSuccessMessage('Unused Avatar deleted');
		else $this->fm()->addErrorMessage('This is your current avatar, delete it from you personal settings page');
		return $this->redirect()->toRoute('sc/home', [],[], true);
	}

}