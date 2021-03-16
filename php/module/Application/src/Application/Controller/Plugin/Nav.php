<?php
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Nav extends AbstractPlugin
{
	private $sl;

	public function __construct($sl)
	{
		$this->sl = $sl;
	}

	public function __invoke($role = 'guest', $container = 'navigation')
	{
		$nav_help = $this->sl->get('viewHelperManager')->get('navigation'); 
		$nav_help->setRole($role)->setContainer($container);
		$nav_help->findOneBy('menu_id', 'my_menu')->setVisible(true);
		$cont = $this->sl->get('navigation')->getPages();
		return $this->getNav($cont, $nav_help);
	}


	public function getNav($cont, $nav_help){
		$menu = [];
		foreach ($cont as $page) {
			if (!$nav_help->accept($page)) continue;
			if(!$page->hasPages()) $menu[] = $page->getLabel();
			else {
				$access = false;
				$pages = $page->getPages();
				foreach ($pages as $child) {
					if ($nav_help->accept($child)) $access = true;
				}
				if ($access) {
					$menu[][$page->getLabel()] = $this->getNav($pages, $nav_help);
				}
			}
		}
		return $menu;
	}

}