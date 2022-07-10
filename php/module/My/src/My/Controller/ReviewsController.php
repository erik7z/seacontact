<?php
namespace My\Controller;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class ReviewsController extends AbstractController
{

	
	/**
	 * Index action of some controller
	 * @return [type]
	 */
	public function indexAction()
	{
		
		$reviews = $this->get('ReviewsTable')->getReviews();
		// d($reviews->toArray());

		$view = new ViewModel(array(
			'reviews' => $reviews->toArray(),
			));
		return $view;
	}

	public function readAction()
	{

	}


	public function addAction()
	{
		$form = $this->get('EmptyForm');
		$form->add($this->get('ReviewFields'))
				->add($this->get('PicsFields'));
		$form->addSubmit('Add', 'Add Review');
		//for adding review, id is not required
		$form->getInputFilter()->get('ships-reviews')->get('id')->setRequired(false);


		if($this->request->isPost()) {

			$result = $this->saveReview($form);
			if($result['result'] === true) return $this->redirect()->refresh();
			else $form = $result['form'];
		}

		$view = new ViewModel(array(
			'form' => $form,
			));
		return $view;

	}


	public function editAction()
	{
		$review_id = $this->params()->fromRoute('id');
		if(!$review_id) return $this->redirect()->toRoute('sc/home', [],[], true);
		$review = $this->get('ReviewsTable')->getReview($review_id);
		$user = $this->identity();
		if($review->user != $user->id) {
			$this->fm()->addErrorMessage('You cannot access this page');
			return $this->redirect()->toRoute('sc/home', [],[], true);
		}

		$form = $this->get('EmptyForm');
		$form->add($this->get('ReviewFields'))
				->add($this->get('PicsFields'));
		$form->addOldPictureFields($review['pics']);
		$form->addSubmit('Save', 'Save Review');

		//for editting id of review is compulsory
		$form->getInputFilter()->get('ships-reviews')->get('id')->setRequired(true);

		$form->setData(array('ships-reviews' => $review, 'pics' => $review['pics']));

		if($this->request->isPost()) {
			$data = $this->request->getPost()->toArray();
			// if(!);
			$result = $this->saveReview($form);
			if($result['result'] === true) return $this->redirect()->refresh();
			else $form = $result['form'];
		}

		$ship = $this->get('ShipsTable')->getShipByReviewId($review_id);


		$view = new ViewModel(array(
			'form' => $form,
			'ship' => $ship->current()
			));
		// $view->setTerminal(true);
		return $view;

	}

	public function deleteAction()
	{

	}

	private function saveReview($form)
	{

		$data = array_merge_recursive(
			$this->request->getPost()->toArray(),
			$this->request->getFiles()->toArray()
			);
		$form->setData($data);
		try {
			if(!$form->isValid()) throw new \Application\Exception\Exception(serialize($form->getMessages()), 100);

			$data = $form->getData();
			$review = $data['ships-reviews'];
			$review['user'] = $this->identity()->id;
			$review['date_from'] = zconvertFormDate($review['date_from']);
			$review['date_to'] = zconvertFormDate($review['date_to']);
			$review['time'] = time();
			$review_id = $this->get('ReviewsTable')->save($review);
			if(isset($data['pics']) && !empty($data['pics'])) {
				$data['pics'] = $data['pics']['img'];
				$data['user'] = $this->identity()->id;
				$this->get('PicsTable')->saveArticlePics($data, 'reviews', $review_id, $this->identity()->id);
			}
			$this->fm()->addSuccessMessage('Review Saved!');
			return array('result' => true, 'form' => $form);
			
		} catch (\Exception $e) {
			$this->get('ErrorLog')->count +=1;
			if(!$mesages = unserialize($e->getMessage())) $mesages = $e->getMessage();
			$this->fm()->addErrorMessage($mesages);
		}

		return array('result' => false, 'form' => $form);
	}

}