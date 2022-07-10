<?php
namespace CompanyInfo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
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

        try {
            $company_login = $this->params()->fromRoute('user'); 
            if(isset($this->identity()->login) &&  ($this->identity()->login == $company_login)) {
                return $this->forward()->dispatch('My\Controller\Cv');
            }
            $viewer_id = ($this->identity())? $this->identity()->id : 0;
            $company_id = $this->get('UserTable')->getUserIdByLogin($company_login);
            $company = $this->get('UserTable')->getUsersList($viewer_id, ['id' => $company_id], ['_limit' => 1, 'user_type' => 'company', 'show_stats' => 1])->current();
            if(!$company) throw new \Application\Exception\Exception($this->translate('Company with such login not found !', 404));
            $this->get('ActivityViewsTable')->addView('user', $company_id, $viewer_id);
            $html = '';

            if($company->home_address && $company->home_address != '') {
                try {
                    $geocode = new \GoogleMapsTools\Api\Geocode($company->home_address);
                    $geocode->execute();
                    $point = $geocode->getFirstPoint();
                    $latitude = $point->getLatitude();
                    $longitude = $point->getLongitude();

                    $markers = array(
                            $company->company_name => $latitude.','.$longitude,
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
                } catch (\Exception $e) {
                    try {
                        $geocode = new \GoogleMapsTools\Api\Geocode('Odessa, Ukraine');
                        $geocode->execute();
                        $point = $geocode->getFirstPoint();
                        $latitude = $point->getLatitude();
                        $longitude = $point->getLongitude();
                        $markers = array(
                                $company->company_name => $latitude.','.$longitude,
                            );  //markers location with latitude and longitude

                        $config = array(
                            'sensor' => 'true',         //true or false
                            'div_id' => 'map',          //div id of the google map
                            'div_class' => 'grid_5',    //div class of the google map
                            'zoom' => 10,                //zoom level
                            'width' => "300px",         //width of the div
                            'height' => "300px",        //height of the div
                            'lat' => $latitude,         //lattitude
                            'lon' => $longitude,         //longitude 
                            'animation' => 'none',      //animation of the marker

                        );
                    } catch (\Exception $e) {
                        $this->fm()->addErrorMessage($e->getMessage());
                    }

                }

                if(isset($config)) {
                        $map = $this->get('GMaps\Service\GoogleMap'); //getting the google map object using service manager
                        $map->initialize($config);
                        $html = $map->generate();  
                }

            }       
            
            $my_id = (isset($this->identity()->id)) ? $this->identity()->id : null;
            $q_options = $this->setDefaultOptions(['_limit' => 10]);
            $q_options['only_current'] = 1;
            $q_options['show_hidden'] = 0;
            $q_options['filters']['owner_id'] = $company->id;
            $data_list = $this->get('NewsTable')->getNews($my_id, $q_options['filters'], $q_options);
            $q_options['count'] = 1;
            $total_results = $this->get('NewsTable')->getNews($my_id, $q_options['filters'], $q_options)->current()->count;
            if(!$q_options['_offset']) $q_options['_offset'] = ($q_options['_limit'] * $q_options['_page']) - $q_options['_limit'];

        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        
		$viewModel = new ViewModel(array(
			'company' => $company,
			'map_html' => $html,
			'data_list' => $data_list,
            'total_results' => $total_results,
            'q_options' => $q_options
			));
        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel,
            'template' => 'company-info/index/index',
            'exception' => (isset($e)) ? $e : null
            ]);
	}

}