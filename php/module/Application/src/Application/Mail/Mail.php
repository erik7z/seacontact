<?php
namespace Application\Mail;

use Zend\View\Model\ViewModel;
use Zend\Mail\Transport\Sendmail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

use Zend\Mail\Message as MailMessage;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part;

class Mail
{
	protected $view;
	protected $mailMessage;
	protected $mimeMessage;
	protected $transport;
	protected $translator;
	protected $url;
	protected $dkimSigner;
	protected $sl;

	public function __construct($sl)
	{
		$this->view = $sl->get('ViewManager')->getView();
		$this->url = $sl->get('ControllerPluginManager')->get('url');
		$this->dkimSigner = $sl->get('DkimSigner');
		$this->sl = $sl;
		$this->mailMessage = new MailMessage();
		$this->mimeMessage = new MimeMessage();
		$this->transport = new Sendmail();
		$this->translator = \Application\Translator\StaticTranslator::getTranslator();
	}

	public function sendSubToVacancyCompanyMail($vacancy_info, $user_info, $experience_info)
	{
		$vacancy_page =  $this->url->fromRoute('sc/vacancies/actions', array('action' => 'view', 'id' => $vacancy_info->vacancy_id));
		$title = zgetUserName($user_info).' '.$this->translator->translate('subscribed to your vacancy');

		$str = zgetUserName($user_info);
		$textPart = $str;
		$htmlPart = '<b>'.$str.'</b>';

		$str = $this->translator->translate(' subscribed to your vacancy').' : ';
		$textPart .= $str;
		$htmlPart .= $str;

		$str = $vacancy_info->title;
		$textPart .= $str;
		$htmlPart .= "<a href='$vacancy_page' >".$str.'</a>';


		$user_info_view = new ViewModel(array(
			'user_info' => $user_info,
			'experience_info' => $experience_info,
		));
		$user_info_view->setTemplate('application/partial/mails/user_experience_info');
		$user_info_view->setOption('has_parent', true);
		$user_info_html = $this->view->render($user_info_view);
		$htmlPart .= $user_info_html;

		$str = $this->translator->translate('you can see all subscribers on').' : '.$vacancy_page;
		$textPart .= $str;
		$btn_link = $vacancy_page;
		$btn_text = $this->translator->translate('See all vacancy Subscribers');

		$viewModel = new ViewModel(array(
			'header' => $title,
			'admin_text' => $htmlPart, // possible array
			'btn_link' => $btn_link,
			'btn_text' => $btn_text,
		));
		$viewModel->setTemplate('application/partial/mails/default_template');

		return $this->sendMail($vacancy_info->email, $title, $textPart, ['to_name' => $vacancy_info->company_name, 'viewModel' => $viewModel]);
	}


	public function sendPrivateMessageNotification($mail_to, $to_name, $message_info, $partner_info, $partnership_info = null)
	{
		$message_page =  $this->url->fromRoute('sc/messages/actions', array('action' => 'pm', 'id' => $message_info['chat_id']));
		$title = $this->translator->translate('New Message From ').zgetUserName($partner_info);



		$str = $this->translator->translate('You received new message from ');
		$textPart = $str;
		$htmlPart = $str;
		$str = zgetUserName($partner_info);
		$textPart .= $str;
		$htmlPart .= '<b>'.$str.'<b>';
		$str = ', '."\n";;
		$textPart .= $str;
		$htmlPart .= $str;

		$partnership_view = new ViewModel(array(
			'partner_info' => $partner_info,
			'partnership_info' => $partnership_info,
		));
		$partnership_view->setTemplate('application/partial/mails/partnership_info');
		$partnership_view->setOption('has_parent', true);
		$partnership_html = $this->view->render($partnership_view);
		$htmlPart .= $partnership_html;

		if(count($partnership_info) > 0) {
			$textPart .= "\n";
			$textPart .= $this->translator->translate('Worked together on').' : ';
			$c = 0;
			foreach ($partnership_info as $contract) {
				if($c > 0) $textPart .= '; ';
				$textPart .= "\n";
				$textPart .= '"'.$contract->ship_name.'"';
				$textPart .= ' '.$this->translator->translate('from').' : ';
				$textPart .= zformatDateYear($contract->worked_together_from).' ';
				$textPart .= ' '.$this->translator->translate('to').' : ';
				$textPart .= zformatDateYear($contract->worked_together_to).' ';
				$c++;
			}
			$textPart .= "\n";
		}
		$str = $this->translator->translate('Message').': ';
		$textPart .= $str."\n";
		$htmlPart .= '<h4 style="text-align: center;">'.$str.'</h4>';
		$str = zshorterText($message_info['text'], 10);
		$textPart .= '"'.$str.'"'."\n";
		$htmlPart .= "<p>".$str."</p>";
		$str = $this->translator->translate('you can read full message on : ').$message_page;
		$textPart .= $str;
		$btn_link = $message_page;
		$btn_text = $this->translator->translate('Read Full Message');

		$viewModel = new ViewModel(array(
			'header' => $title,
			'admin_text' => $htmlPart, // possible array
			'btn_link' => $btn_link, // text on button
			'btn_text' => $btn_text, // text on button
		));
		$viewModel->setTemplate('application/partial/mails/default_template');

		return $this->sendMail($mail_to, $title, $textPart, ['to_name' => $to_name, 'viewModel' => $viewModel]);
	}


	public function sendPostActivitytNotificationMail($mail_to, $to_name, $activity_info, $from_user_info, $partnership_info = null)
	{
		$title = $this->translator->translate('New Message From ').zgetUserName($from_user_info);
		if($activity_info['type'] == \Application\Model\UserNotificationsTable::TYPE_COMMENT)
			$str = $this->translator->translate('Your post was commented by ');
		else if($activity_info['type'] == \Application\Model\UserNotificationsTable::TYPE_LIKE)
			$str = $this->translator->translate('Your post was liked by ');
		else if($activity_info['type'] == \Application\Model\UserNotificationsTable::TYPE_ANSWER)
			$str = $this->translator->translate('New answer from ');
		else $str = '';

		$textPart = $str;
		$htmlPart = $str;

		$str = zgetUserName($from_user_info);
		$textPart .= $str;
		$htmlPart .= '<b>'.$str.'<b>';
		$str = ', '."\n";;
		$textPart .= $str;
		$htmlPart .= $str;

		$partnership_view = new ViewModel(array(
			'partner_info' => $from_user_info,
			'partnership_info' => $partnership_info,
		));
		$partnership_view->setTemplate('application/partial/mails/partnership_info');
		$partnership_view->setOption('has_parent', true);
		$partnership_html = $this->view->render($partnership_view);
		$htmlPart .= $partnership_html;

		if(count($partnership_info) > 0) {
			$textPart .= "\n";
			$textPart .= $this->translator->translate('Worked together on').' : ';
			$c = 0;
			foreach ($partnership_info as $contract) {
				if($c > 0) $textPart .= '; ';
				$textPart .= "\n";
				$textPart .= '"'.$contract->ship_name.'"';
				$textPart .= ' '.$this->translator->translate('from').' : ';
				$textPart .= zformatDateYear($contract->worked_together_from).' ';
				$textPart .= ' '.$this->translator->translate('to').' : ';
				$textPart .= zformatDateYear($contract->worked_together_to).' ';
				$c++;
			}
			$textPart .= "\n";
		}
		if($activity_info['type'] == \Application\Model\UserNotificationsTable::TYPE_COMMENT)  {
			$str = $this->translator->translate('Comment').': ';
			$textPart .= $str."\n";
			$htmlPart .= '<h4 style="text-align: center;">'.$str.'</h4>';
			$str = zshorterText($activity_info['text'], 10);
			$textPart .= '"'.$str.'"'."\n";
			$htmlPart .= "<p>".$str."</p>";
		} else if($activity_info['type'] == \Application\Model\UserNotificationsTable::TYPE_ANSWER)  {
			$str = $this->translator->translate('Answer').': ';
			$textPart .= $str."\n";
			$htmlPart .= '<h4 style="text-align: center;">'.$str.'</h4>';
			$str = zshorterText($activity_info['a_text'], 10);
			$textPart .= '"'.$str.'"'."\n";
			$htmlPart .= "<p>".$str."</p>";

			$str = $this->translator->translate('on Question').': ';
			$textPart .= $str."\n";
			$htmlPart .= '<h4 style="text-align: center;">'.$str.'</h4>';
			$str = zshorterText($activity_info['q_text'], 10);
			$textPart .= '"'.$str.'"'."\n";
			$htmlPart .= "<p>".$str."</p>";
		}

		$link_text = '';
		$btn_text = '';
		if($activity_info['type'] == \Application\Model\UserNotificationsTable::TYPE_COMMENT)  {
			$link_text = $this->translator->translate('you can view all comments on');
			$btn_text = $this->translator->translate('Read All Comments');
		} else if($activity_info['type'] == \Application\Model\UserNotificationsTable::TYPE_LIKE) {
			$link_text = $this->translator->translate('you can view all likes on');
			$btn_text = $this->translator->translate('View Post');
		} else if($activity_info['type'] == \Application\Model\UserNotificationsTable::TYPE_ANSWER) {
			$link_text = $this->translator->translate('you can view all answers on');
			$btn_text = $this->translator->translate('View All Answers');
		}

		$str = $link_text.' '.$activity_info['post_url'];
		$textPart .= $str;
		$btn_link = $activity_info['post_url'];


		$viewModel = new ViewModel(array(
			'header' => $title,
			'admin_text' => $htmlPart, // possible array
			'btn_link' => $btn_link, // text on button
			'btn_text' => $btn_text, // text on button
		));
		$viewModel->setTemplate('application/partial/mails/default_template');

		return $this->sendMail($mail_to, $title, $textPart, ['to_name' => $to_name, 'viewModel' => $viewModel]);
	}


	public function sendCvUnlockedNotificationMail($mail_to, $user_info, $company_info)
	{
		$title = $this->translator->translate('Company').' : '.zgetUserName($company_info).' ';
		$title .= $this->translator->translate('requested your contacts');

		$str = $this->translator->translate('Company').' : ';

		$textPart = $str;
		$htmlPart = $str;

		$str = zgetUserName($company_info);
		$textPart .= $str;
		$htmlPart .= '<b>'.$str.'</b>';

		$str = ' ';
		$str .= $this->translator->translate('checked your CV, and requested contacts information');
		$textPart .= $str;
		$htmlPart .= $str;

		$company_view = new ViewModel(array(
			'company_info' => $company_info,
		));
		$company_view->setTemplate('application/partial/mails/company_info');
		$company_view->setOption('has_parent', true);
		$htmlPart .= $this->view->render($company_view);

		$str = $this->translator->translate('We recommend you to update your cv on');
		$textPart .= $str;
		$htmlPart .= '<br />'.$str;


		$btn_text = $this->translator->translate('Your CV Page');

		$btn_link = _ADDRESS_.zgetUserLogin($user_info).'/cv';
		$text2 = $this->translator->translate('To login use e-mail').' : <b>'.$mail_to.'</b> <br />';
		$text2 .= $this->translator->translate('Password will be sent during registration, to your e-mail').' : <b>'.$mail_to.'</b> <br />';

		$viewModel = new ViewModel(array(
			'header' => $title,
			'admin_text' => $htmlPart, // possible array
			'btn_link' => $btn_link, // text on button
			'btn_text' => $btn_text, // text on button
			'text2' => $text2,
		));
		$viewModel->setTemplate('application/partial/mails/default_template');

		return $this->sendMail($mail_to, $title, $textPart, ['to_name' => zgetUserName($user_info), 'viewModel' => $viewModel]);
	}


	public function sendAdminMail($mail_to, $mail_from, $title, $text, $options = [])
	{
		$viewModel = new ViewModel(array(
			'title' => $title,
			'text' => $text,
		));
		$viewModel->setTemplate('application/partial/mails/admin_mail');
		$options['viewModel'] = $viewModel;
		$options['mail_from'] = $mail_from;
		return $this->sendMail($mail_to, $title, $text, $options);
	}


	public function sendSubToVacancyUserMail($mail_to, $to_name, $vacancy_id, $vacancy_title)
	{
		$vacancy_page =  $this->url->fromRoute('sc/vacancies/actions', array('action' => 'view', 'id' => $vacancy_id));
		$viewModel = new ViewModel(array(
			'login' => $mail_to,
			'name' => $to_name,
			'vacancy_title' => $vacancy_title,
			'vacancy_page' => $vacancy_page,
		));
		$viewModel->setTemplate('application/partial/mails/vacancy_subscribe');
		$title = $this->translator->translate('Subscription to vacancy: ').$vacancy_title;
		$textPart = $this->translator->translate('You are subscribed to vacancy ').$vacancy_title.', '
					.$this->translator->translate('you can check vacancy status on : ').$vacancy_page;
		return $this->sendMail($mail_to, $title, $textPart, ['to_name' => $to_name, 'viewModel' => $viewModel]);
	}

	public function sendConfMail($mail_to, $to_name, $code, $role = 'unknown', $query_params = [])
	{
		$query_string = (count($query_params))? '&'.http_build_query($query_params) : '';
		$viewModel = new ViewModel(array(
			'confirm_code' => $code,
			'name' => $to_name,
			'confirm_page' => $this->url->fromRoute('sc/confirm-email').'?role='.$role.$query_string,
			'confirm_link' => $this->url->fromRoute('sc/confirm-email').'?code='.$code.'&role='.$role.$query_string,
		));
		$viewModel->setTemplate('application/partial/mails/reg_confirm');
		$title = $this->translator->translate('Confirm Registration on ')._ADDRESS_NO_SLASH_;
		$textPart = $this->translator->translate('To complete registration on ')._ADDRESS_NO_SLASH_.','.$this->translator->translate('please enter the following code: ').$code;
		return $this->sendMail($mail_to, $title, $textPart, ['to_name' => $to_name, 'viewModel' => $viewModel]);
	}

	public function sendSuccessMail($mail_to, $to_name, $clear_password)
	{
		$viewModel = new ViewModel(array(
			'login' => $mail_to,
			'password' => $clear_password,
			'name' => $to_name,
			'login_page' => $this->url->fromRoute('sc/auth'),
		));
		$viewModel->setTemplate('application/partial/mails/reg_success');
		$title = $this->translator->translate('Succesfull registration on ')._ADDRESS_NO_SLASH_;
		$textPart = $this->translator->translate('Registration successfully completed on ')._ADDRESS_NO_SLASH_.', '
					.$this->translator->translate('you can now enter the site with login: ').$mail_to
					.$this->translator->translate(' and password: ').$clear_password;
		return $this->sendMail($mail_to, $title, $textPart, ['to_name' => $to_name, 'viewModel' => $viewModel]);
	}

	public function sendResetPassMail($mail_to, $to_name, $code)
	{
		$viewModel = new ViewModel(array(
			'confirm_code' => $code,
			'name' => $to_name,
			'confirm_page' => $this->url->fromRoute('sc/confirm-reset-password'),
			'confirm_link' => $this->url->fromRoute('sc/confirm-reset-password').'?code='.$code,
		));
		$viewModel->setTemplate('application/partial/mails/pass_reset_confirm');
		$title = $this->translator->translate('Reset password on ')._ADDRESS_NO_SLASH_;
		$textPart = $this->translator->translate('To reset password on ')._ADDRESS_NO_SLASH_.', '
					.$this->translator->translate('please follow this link ').$this->url->fromRoute('sc/confirm-reset-password').'?code='.$code
					.' ,'.$this->translator->translate('or enter the following code').' : '.$code
					.' '.$this->translator->translate('on confirmation page').' '.$this->url->fromRoute('sc/confirm-reset-password');
		return $this->sendMail($mail_to, $title, $textPart, ['to_name' => $to_name, 'viewModel' => $viewModel]);
	}

	public function sendNewPasswordMail($mail_to, $to_name, $clear_password)
	{
		$viewModel = new ViewModel(array(
			'login' => $mail_to,
			'password' => $clear_password,
			'name' => $to_name,
			'login_page' => $this->url->fromRoute('sc/auth'),
		));
		$viewModel->setTemplate('application/partial/mails/pass_reset_complete');
		$title = $this->translator->translate('Password changed on ')._ADDRESS_NO_SLASH_;
		$textPart = $this->translator->translate('Password successfully changed on ')._ADDRESS_NO_SLASH_.', '
					.$this->translator->translate('you can now enter the site with login: ').$mail_to
					.$this->translator->translate(' and password: ').$clear_password;
		return $this->sendMail($mail_to, $title, $textPart, ['to_name' => $to_name, 'viewModel' => $viewModel]);
	}


	private function sendMail($mail_to, $title, $text, $options = [])
	{
		$mail_from = isset($options['mail_from'])? $options['mail_from'] : _SITEMAIL_;
		$mail_cc = isset($options['mail_cc'])? $options['mail_cc'] : [];
		$to_name = isset($options['to_name'])? $options['to_name'] : null;
		$from_name = isset($options['from_name'])? $options['from_name'] : _SITEADMIN_;
		$viewModel = isset($options['viewModel'])? $options['viewModel'] : null;
		$unique_id = isset($options['unique_id'])? $options['unique_id'] : null;
		$attachments = isset($options['attachments'])? $options['attachments'] : null;
		$mailService = $this->sl->get('acmailer.mailservice.default');
		$mailService->setTemplate($viewModel, ['charset' => 'utf-8', 'date' => date('Y-m-d')]);
		$message = $mailService->getMessage();
		$message->setSubject($title)
		->addTo($mail_to, $to_name)
		->addFrom($mail_from, $from_name)
		->setSender($mail_from, $from_name)
		->setEncoding('UTF-8')
		;
		if($mail_cc) $message->addCc($mail_cc);

		$attachments_size = 0;
		if($attachments) {
			if(!is_array($attachments)) throw new \Application\Exception\Exception("Attachments should be in array format ! [MAIL]", 1);
			if(!$unique_id) throw new \Application\Exception\Exception("Unique id not provided [Mail]", 1);
			$att_list = [];
			foreach ($attachments as $att_file_name) {
				$file_path = _MAILSROOT_.$unique_id.'/'.$att_file_name;
				$attachments_size += round(filesize($file_path) / 1048576, 2);
				$att_list[] = $file_path;
			}
			$mailService->addAttachments($att_list);
		}

		if($attachments_size > 6) {
			$mq_table = $this->sl->get('MailQueryTable');
			$message = $mailService->getMessage();
			$mq_table->save(['unique_id' => $unique_id, 'mailbox' => $mail_from, 'content' => serialize($message), 'attachments' => serialize($att_list), 'time' => time(), 'attachments_size' => $attachments_size]);
			throw new \Application\Exception\Exception($this->translator->translate('Mail with large attachments, will be sent in silent mode'), 707);
		}

		$mailService->setTransport($this->getTransport($mail_from));

		// if($mailService->getTransport() instanceof \Zend\Mail\Transport\Sendmail)
		// 	$this->dkimSigner->signMessage($message);

		$result = $mailService->send();
		if (!$result->isValid()) {
			if ($result->hasException()) throw new \Application\Exception\Exception(sprintf('An error occurred. Exception: \n %s', $result->getException()->getMessage()), 1);
			else throw new \Application\Exception\Exception(sprintf('An error occurred. Message: %s', $result->getMessage()), 704);
		}
		return true;
	}

	public function getTransport($mail_box)
	{
		$account = $this->sl->get('MailAccountsTable')->getFields(['transport', 'smtp_host', 'smtp_port', 'user_name', 'password'], 'mail_box', $mail_box)->current();
		if($account && $account->transport == 'smtp') {
			$options = new \Zend\Mail\Transport\SmtpOptions([
				'host' => $account['smtp_host'],
				'port' => $account['smtp_port'],
				'connection_class' => 'plain',
				'connection_config' => array(
					'username' => $account['user_name'],
					'password' => $account['password'],
					'ssl' => 'ssl',
					),
				]);
			$transport = new \Zend\Mail\Transport\Smtp($options);
			// d($transport);
		} else {
			$transport = new \Zend\Mail\Transport\Sendmail;
		}
		return $transport;
	}

	public function sendSavedMail($unique_id)
	{
		$mq_table = $this->sl->get('MailQueryTable');
		$mail = $mq_table->getOnField($unique_id, 'unique_id');
		if(!$mail) throw new \Application\Exception\Exception("Message not found", 1);
		$saved_msg = unserialize($mail->content);
		$saved_att = unserialize($mail->attachments);
		if(!$saved_msg instanceof \Zend\Mail\Message) throw new \Application\Exception\Exception("Message not found", 1);

		$mailService = new \AcMailer\Service\MailService($saved_msg, new \Zend\Mail\Transport\Sendmail, new \Zend\View\Renderer\PhpRenderer);
		if(count($saved_att)) $mailService->addAttachments($saved_att);
		$mq_table->save(['id' => $mail->id, 'status' => 1]);

		$message = $mailService->getMessage();
		// if($mailService->getTransport() instanceof \Zend\Mail\Transport\Sendmail)
		// 	$this->dkimSigner->signMessage($message);

		$result = $mailService->send();
		if (!$result->isValid()) {
			if ($result->hasException()) throw new \Application\Exception\Exception(sprintf('An error occurred. Exception: \n %s', $result->getException()->getMessage()), 1);
			else throw new \Application\Exception\Exception(sprintf('An error occurred. Message: %s', $result->getMessage()), 704);
		}
		$mq_table->delete($unique_id, 'unique_id');
		return true;
	}


	private function oldSendMail($mail_to,$to_name, $title, $textPart, $viewModel, $mail_from = _SITEMAIL_ , $unique_id = null, $attachments = null)
	{
		$body = new \Zend\Mime\Message();

		$textPart    = new \Zend\Mime\Part($textPart);
		$textPart->type = 'text/plain';
		$textPart->language = 'ru';
		$textPart->charset = 'UTF-8';
		$textPart->disposition = 'inline';

		$viewModel->setOption('has_parent', true);
		$htmlPart    = new \Zend\Mime\Part($this->view->render($viewModel));
		$htmlPart->type = 'text/html';
		$htmlPart->language = 'ru';
		$htmlPart->charset = 'UTF-8';
		$htmlPart->disposition = 'inline';

		$body->addPart($htmlPart);

		if($attachments) {
			if(!is_array($attachments)) throw new \Application\Exception\Exception("Attachments should be in array format ! [MAIL]", 1);
			if(!$unique_id) throw new \Application\Exception\Exception("Unique id not provided [Mail]", 1);
			$att_list = [];
			foreach ($attachments as $att_file_name) {
				$file_path = _MAILSROOT_.$unique_id.'/'.$att_file_name;
				$fileContent = fopen($file_path, 'r');
				$attachment = new \Zend\Mime\Part($fileContent);

				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$attachment->type = finfo_file($finfo, $file_path);
				finfo_close($finfo);
				$attachment->filename = $att_file_name;
				$attachment->disposition = \Zend\Mime\Mime::DISPOSITION_ATTACHMENT;
				$attachment->encoding = \Zend\Mime\Mime::ENCODING_BASE64;

				$body->addPart($attachment);
			}
		}

		$this->mailMessage->setBody($body);
		$this->mailMessage->setFrom($mail_from, _SITEADMIN_);
		$this->mailMessage->setSender($mail_from, _SITEADMIN_);
		$this->mailMessage->addTo($mail_to, $to_name);
		$this->mailMessage->setSubject($title);
		// $this->mailMessage->getHeaders()->get('content-type')->setType('multipart/alternative');
		$this->mailMessage->setEncoding('UTF-8');

		// $this->dkimSigner->signMessage($this->mailMessage);
		$this->transport->send($this->mailMessage);
	}

}
