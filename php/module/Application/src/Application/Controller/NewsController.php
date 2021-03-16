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

class NewsController extends AbstractController
{
    public function indexAction()
    {
        $no_json = $this->params()->fromQuery('no_json');
        $terminal = $this->params()->fromQuery('terminal');
        try {
            $success = 1;
            $message = '';
            $user_id = ($this->identity()) ? $this->identity()->id : 0;
            $q_options = $this->setDefaultOptions(['_limit' => 20]);
            $q_options['only_current'] = 1;
            $q_options['show_hidden'] = 0;
            $q_options['dump_time'] = 10;
            $data_list = $this->get('NewsTable')->getNews($user_id, $q_options['filters'], $q_options);
            $q_options['count'] = 1;
            $total_results = $this->get('NewsTable')->getNews($user_id, $q_options['filters'], $q_options)->current()->count;
            if(!$q_options['_offset']) $q_options['_offset'] = ($q_options['_limit'] * $q_options['_page']) - $q_options['_limit'];
        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }


        $viewModel = 0;
        if(isset($data_list)) 
            $viewModel = new ViewModel(array(
                'tags_map' => $this->get('TagMapTable')->getTagsMap(null, ['section' => [\Application\Model\NewsTable::SECTION_LOGBOOK, \Application\Model\NewsTable::SECTION_QUESTIONS]]),
                'data_list' => $data_list,
                'total_results' => $total_results,
                'q_options' => $q_options,
                ));

        return $this->viewResponse($success, $message, [
            'view_data' => $viewModel,
            'terminal' => $terminal,
            'no_json' => $no_json,
            'template' => 'application/news/index',
            'exception' => (isset($e)) ? $e : null
            ]);


    }
    
}
