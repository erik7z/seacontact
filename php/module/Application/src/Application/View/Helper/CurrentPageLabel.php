<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class CurrentPageLabel extends AbstractHelper
{

	private $hpm;
	public $activePageLabel;
	public function __construct($hpm)
	{
		$this->hpm = $hpm;

		$activePage = $this->hpm->get('navigation')->setContainer('navigation')->findOneBy('active', true);
		if(is_object($activePage)) $this->activePageLabel = $activePage->getLabel();
		else $this->activePageLabel =  '';
	}


	public function __invoke()
	{
		return $this->activePageLabel;
	}
}