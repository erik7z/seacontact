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

class TagsController extends AbstractController
{
    public function indexAction()
    {
        // if (!$this->request->isXmlHttpRequest()) return $this->notFoundAction();
        $query = $this->params()->fromQuery('q', null);
        $section = $this->params()->fromQuery('section', null);
        $section_id = $this->params()->fromQuery('section_id', null);
        $tags = $this->get('TagMapTable')->getTagsMap(null, ['section' => $section, 'section_id' => $section_id, 'query' => $query]);
        $response = [];
        foreach ($tags as $tag) {
           $response[] = $tag->name;
        }
        $view = new ViewModel(array(
            'response' => json_encode($response),
            ));
        $view->setTemplate('/user-info/json');
        $view->setTerminal(true);
        return $view;
    }
    
}
