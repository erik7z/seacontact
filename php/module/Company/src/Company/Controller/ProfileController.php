<?php
namespace Company\Controller;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class ProfileController extends AbstractController
{

	
	/**
	 * Index action of some controller
	 * @return [type]
	 */
	public function indexAction()
	{

		$form = $this->get('EmptyForm');
		$userFields = $this->get('UserFields')
								->remainFields(array(
									'email',
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
									));
		$form->add($userFields);
		$form->addSubmit('Save', 'Save');

		$data = $this->get('UserTable')->get($this->identity()->id)->toArray();
		$form->setData(array('user' => $data));

		$original_name = $data['company_name'];
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
				$data['user']['id'] = $this->identity()->id;
				try {
					if('' != $data['user']['avatar']['tmp_name']) {
						$avatar = $this->get('UploadImage')->uploadCVavatar($data['user']['avatar'], $this->identity()->id);
						$data['user']['avatar'] = $avatar['img'];
						// DELETE OLD AVATAR
						if($this->identity()->avatar != '') 
							$delete = $this->get('UploadsTable')->deleteUpload($this->identity()->avatar, 'avatar');
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
					$this->get('UserTable')->save($data['user']);
					$this->fm()->addSuccessMessage('Information Saved!');
					$this->get('RefreshUserId');
					$this->redirect()->refresh();										
				} catch (\Exception $e) {
					$this->fm()->addErrorMessage($e->getMessage());
				}
			} else {
				$this->fm()->addErrorMessage($form->getMessages());
			}			

		}



		$view = new ViewModel(array(
			'form' => $form,
			));
		return $view;
	}
	
}