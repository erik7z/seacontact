<?php
namespace Company\Controller;
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
		$html = null;
		try {
			if($this->identity()->home_address && $this->identity()->home_address != '') {
				try {
					$geocode = new \GoogleMapsTools\Api\Geocode($this->identity()->home_address);
					$geocode->execute();
					$point = $geocode->getFirstPoint();
					$latitude = $point->getLatitude();
					$longitude = $point->getLongitude();

					$markers = array(
					        $this->identity()->company_name => $latitude.','.$longitude,
					    );  //markers location with latitude and longitude

					    $config = array(
					        'sensor' => 'true',         //true or false
					        'div_id' => 'map',          //div id of the google map
					        'div_class' => 'grid_5',    //div class of the google map
					        'zoom' => 14,                //zoom level
					        'width' => "300px",         //width of the div
					        'height' => "300px",        //height of the div
					        'lat' => $latitude,         //lattitude
					        'lon' => $longitude,         //longitude 
					        'animation' => 'none',      //animation of the marker
					        'markers' => $markers       //loading the array of markers
					    );

					    $map = $this->get('GMaps\Service\GoogleMap'); //getting the google map object using service manager
					    $map->initialize($config);
					    $html = $map->generate();  
				} catch (\Exception $e) {
					$this->fm()->addErrorMessage($e->getMessage());
				}

			}
	        $later_news = $this->get('NewsTable')->getNews($this->identity()->id, ['owner_id' => $this->identity()->id], ['only_later' => true]);
	        $q_options = $this->setDefaultOptions(['_limit' => 10]);
	        $q_options['only_current'] = 1;
	        $q_options['show_hidden'] = 1;
	        $q_options['filters']['owner_id'] = $this->identity()->id;
	        $curr_news = $this->get('NewsTable')->getNews($this->identity()->id, $q_options['filters'], $q_options);
	        $q_options['count'] = 1;
	        $total_results = $this->get('NewsTable')->getNews($this->identity()->id, $q_options['filters'], $q_options)->current()->count;
	        if(!$q_options['_offset']) $q_options['_offset'] = ($q_options['_limit'] * $q_options['_page']) - $q_options['_limit'];
	        

	        $logbookform =  $this->get('LogBookRecordForm');
	        $logbookform->setAttribute('action', $this->url()->fromRoute('sc/logbook/actions', ['action'=>'add']));
	        if($this->isPermitted('_info_\social\post_vk')) $logbookform->addPostToVk();
		} catch (\Exception $e) {
			$success = 0;
			$message = $e->getMessage();
		}

        
		$viewModel = new ViewModel(array(
			'map_html' => $html,
			'curr_news' =>  $curr_news,
			'later_news' =>  $later_news,
			'logbookform' => $logbookform,
			'next_pub_time' => $this->get('LogBookTable')->getNextPubTime($this->identity()->id),
			'total_results' => $total_results,
			'q_options' => $q_options,
			));

		return $this->viewResponse($success, $message, [
		    'view_data' => $viewModel,
		    'template' => 'company/index/index',
		    'exception' => (isset($e)) ? $e : null
		    ]);
	}



}