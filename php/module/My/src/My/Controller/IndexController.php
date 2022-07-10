<?php
namespace My\Controller;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractController;

class indexController extends AbstractController
{

	
	/**
	 * Index action of some controller
	 * @return [type]
	 */
	public function indexAction()
	{
		$params = $this->params()->fromRoute();
		$dispatch = (isset($params['dispatch']))? true : false;
		$q_options = $this->setDefaultOptions(['_limit' => 10]);
		$success = 1;
		$message = '';
		try {
			//setting default section
			if (empty($q_options['filters'])) $q_options['filters']['section'] = 'logbook';

			$q_options['only_current'] = true;
			$q_options['show_hidden'] = false;
			$q_options['count'] = true;
			$q_options['filters']['owner_id'] = $this->identity()->id;
			$total_results = $this->get('NewsTable')->getNews($this->identity()->id, $q_options['filters'], $q_options)->current()->count;
			$q_options['count'] = false;
			$curr_news = $this->get('NewsTable')->getNews($this->identity()->id, $q_options['filters'], $q_options);
			$later_news = $this->get('NewsTable')->getNews($this->identity()->id, ['owner_id' => $this->identity()->id], ['only_later' => true]);

			if(!$q_options['_offset']) $q_options['_offset'] = ($q_options['_limit'] * $q_options['_page']) - $q_options['_limit'];

			$logbookform = $this->get('LogBookRecordForm');
			$logbookform->setAttribute('action', $this->url()->fromRoute('sc/logbook/actions', ['action'=>'add', 'lang' => $this->routeMatch()->getParam('lang')]));
	        if($this->isPermitted('_info_\social\post_vk')) $logbookform->addPostToVk();

	        $this->get('Zend\View\Renderer\PhpRenderer')->headTitle($this->translate('My Page'));
		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}


        
		$viewModel = new ViewModel(array(
			'dispatch' => $dispatch,
			'curr_news' =>  $curr_news,
			'later_news' =>  $later_news,
			'logbookform' => $logbookform,
			'next_pub_time' => $this->get('LogBookTable')->getNextPubTime($this->identity()->id),
			'partners' => $this->get('ContactsTable')->getContacts($this->identity()->id, [], ['relations' => \Application\Model\UserContactsTable::RELATION_COLLEGUE, '_limit' => 6]),
			'rating' => $this->get('user_rating')->getUserRating($this->identity()->id),
			'total_results' => $total_results,
			'q_options' => $q_options,
			));

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel,
		    'template' => 'my/index/index',
		    'exception' => (isset($e)) ? $e : null
		    ]);
	}

}