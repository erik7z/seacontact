<?php

namespace Application\view\Helper;
use Zend\View\Helper\AbstractHelper;

class CustomFlashMessenger extends AbstractHelper
{
	private $helperManager;

	public function __construct($helperManager)
	{
		$this->helperManager = $helperManager;

	}

	public function __invoke()
	{
		$message = '';
		$fm = $this->helperManager->get('FlashMessenger');
			$message .= $fm->renderCurrent('error');
			$message .= $fm->renderCurrent('success');
		return $message;
	}
}
