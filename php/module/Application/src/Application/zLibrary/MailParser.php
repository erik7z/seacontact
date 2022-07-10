<?php
namespace Application\zLibrary;

class MailParser {

	protected $storage;
	protected $folders;
	protected $current_folder = null;
	protected $mail_box;
	protected $uploadsTable;
	protected $mail_boxTable;
	protected $mailerCustom;

	public function __construct()
	{
		return $this;
	}

	public function init($options = [])
	{

		$mail_box = isset($options['mail_box'])? $options['mail_box'] : null;
		$host = isset($options['imap_host'])? $options['imap_host'] : null;
		$user_name = isset($options['user_name'])? $options['user_name'] : null;
		$password = isset($options['password'])? $options['password'] : null;
		$port = isset($options['imap_port'])? $options['imap_port'] : null;
		$ssl = isset($options['imap_ssl'])? $options['imap_ssl'] : null;

		$sl = \ServiceLocatorFactory\ServiceLocatorFactory::getInstance();

		$this->mailBoxTable = $sl->get('MailBoxTable');
		$this->uploadsTable = $sl->get('UploadsTable');
		$this->mailerCustom = new \Application\zLibrary\MailerCustom;
		$this->extMapping = new extMapping;
		$this->mail_box = $mail_box;
		$imap_options = [
			'host' => $host, 
			'user' => $user_name, 
			'password' => $password,
			];
		if($port) $imap_options['port'] = $port;
		if($ssl) $imap_options['ssl'] = $ssl;
		$this->storage = new \Zend\Mail\Storage\Imap($imap_options);

		$this->folders = new \RecursiveIteratorIterator($this->storage->getFolders(),
                                         \RecursiveIteratorIterator::SELF_FIRST);

		return $this;
	}

	public function parseMailbox()
	{
		if($this->current_folder) $this->storage->selectFolder($this->current_folder);

		$current_folder = $this->storage->getCurrentFolder()->getLocalName();
		$current_folder_full = $this->storage->getCurrentFolder()->getGlobalName();

		$delete_from_server = [];
		$mail_data = [];
		$result['errors'] = [];
		$result['total_messages'] = $this->storage->countMessages();
		$parse_quantity = ($result['total_messages'] > 10)? 10 : $result['total_messages'];
		for ($i=1; $i < $parse_quantity+1; $i++) {
			$error = [];
			$attachments = [];
			try {
				$mail_data['raw_header'] =  $this->storage->getRawHeader($i);
				$mail_data['raw_content'] =  $this->storage->getRawContent($i);
				$unique_id = null;
				try {
					// if message more than 10mb 
					if($this->storage->getSize($i) > 12582912) throw new \Application\Exception\Exception("Message is too big for parsing", 999);

					
					$message = $this->storage->getMessage($i);
				} catch (\Exception $e) {
					$error[] = "Folder {$current_folder},  Mail #{$i} Error :".$e->getMessage();
					$mail_data['real_unique_id'] = $this->storage->getUniqueId($i);
					$unique_id = $this->customGetId($this->storage->getRawHeader($i), $mail_data);

					if($e->getCode() == 999) throw new \Application\Exception\Exception($e->getMessage, 999);
					
					$mail_data = $this->customParse($mail_data['raw_header'], $mail_data['raw_content'], $mail_data);
					$this->mailBoxTable->updateMailBox($mail_data);
					$delete_from_server[] = $i;
					continue;
				}



				if(isset($message->date)) {
					$time = strtotime($message->getHeader('date', 'string'));
				} else {
					$error[] = "Folder {$current_folder},  Mail #{$i} Message Time Error";
					$time = time();
				}
				$mail_data['unique_id'] = $this->storage->getUniqueId($i);
				$mail_data['unique_id'] = $mail_data['unique_id'].$time;



				if($mail_data['unique_id']) {

					$mail_data['time'] = $time;
					$mail_data['folder'] = $current_folder;
					$mail_data['folder_full'] = $current_folder_full;
					$mail_data['mail_box'] = $this->mail_box;
					$mail_data['location'] = 'db';

					try {
						$mail_data['flag_seen'] = ($message->hasFlag(\Zend\Mail\Storage::FLAG_SEEN)) ? true : false;
						// $mail_data['flag_recent'] = ($message->hasFlag(\Zend\Mail\Storage::FLAG_RECENT)) ? true : false;
						// $mail_data['flag_answered'] = ($message->hasFlag(\Zend\Mail\Storage::FLAG_ANSWERED)) ? true : false;
					} catch (\Exception $e) {
						$error[] = "Folder {$current_folder},  Mail #{$i} Message flag Error :".$e->getMessage();
					}
					
					if(isset($message->from)) {
						try {
							$address_list = $message->getHeader('from')->getAddressList();
							foreach ($address_list as $address) {
								$mail_data['from_mail'] = $address->getEmail();
								$mail_data['from_name'] = $address->getName();
							}
						} catch (\Exception $e) {
							$mail_data['from_mail'] = 'no_email';
							$error[] = "Folder {$current_folder},  Mail #{$i} Header from_mail Error :".$e->getMessage();
						}

					}

					if(isset($message->to)) {
						$mail_to = [];
						try {
							$address_list = $message->getHeader('to')->getAddressList();
							$a = 0;
							foreach ($address_list as $address) {
								$mail_to[$a]['name'] = $address->getName();
								$mail_to[$a]['email'] = $address->getEmail();
								$a++;
							}
							$mail_data['mail_to'] = json_encode($mail_to);
						} catch (\Exception $e) {
							$mail_data['mail_to'] = json_encode(array(array('email' => $this->mail_box)));
							$error[] = "Folder {$current_folder},  Mail #{$i} Header mail_to Error :".$e->getMessage();
						}
					
					}


					if(isset($message->envelope_to) && !$mail_data['mail_to']) {
						$mail_data['mail_to'] = json_encode(array(array('email' => $message->envelope_to)));
					}

					if(isset($message->cc)) {
						try {
							$address_list = $message->getHeader('cc')->getAddressList();
							$a = 0;
							foreach ($address_list as $address) {
								$mail_cc[$a]['name'] = $address->getName();
								$mail_cc[$a]['email'] = $address->getEmail();
								$a++;
							}
							$mail_data['mail_cc'] = json_encode($mail_cc);
						} catch (\Exception $e) {
							$error[] = "Folder {$current_folder},  Mail #{$i} Header CC Error :".$e->getMessage();
						}
					
					}
					
					if(isset($message->subject)) {
						try {
							$mail_data['subject'] = $message->getHeader('subject', 'string');
							$string = $message->getHeader('subject', 'string');
							if(($pos = strpos($string,"=?")) === false) $mail_data['subject'] = $string;
							else $mail_data['subject'] = mb_decode_mimeheader($string);
							
						} catch (\Exception $e) {
							$error[] = "Folder {$current_folder},  Mail #{$i} Subject Error :".$e->getMessage();
						}
					}


					$mail_data['message_number'] = $i;

					$mail_data['generated_id'] = md5($mail_data['time'].$mail_data['subject']);
					if($message->isMultipart()) {
						try {
							$RecursiveIteratorIterator = new \RecursiveIteratorIterator($message);

							$n = 0;
							foreach ($RecursiveIteratorIterator as $part) {
								if(isset($part->content_type)) {
									if(strtok($part->content_type, ';') == 'text/plain') {
										$parsed = $this->parseMessage($part);
										$mail_data['text'] = $parsed['content'];
										$mail_data['text_charset'] = htmlentities($parsed['charset'], ENT_IGNORE);
										$mail_data['text_encoding'] = $parsed['transfer_encoding'];
									}
									else if(strtok($part->content_type, ';') == 'text/html') {
										$parsed = $this->parseMessage($part);
										$mail_data['html'] = htmlentities($parsed['content'], ENT_IGNORE);
										$mail_data['html_charset'] = $parsed['charset'];
										$mail_data['hmtl_encoding'] = $parsed['transfer_encoding'];
									}
									else if(preg_match("/name=(.*)/",$part->content_type,$regs)) {
										$file_content = $part->getContent();
										$attachments[] = $this->handleAttachment($file_content, $regs[1], $mail_data['generated_id']);
									}
									else {
										$mime_type = strtok($part->content_type, ';');
										$ext = $this->extMapping->getExt($mime_type);
										if(!$ext) $ext = 'unknown';
										$file_content = $part->getContent();
										$file_name = $this->generateFileName($mail_data['unique_id'], $n, $ext);
										$attachments[] = $this->handleAttachment($file_content, $file_name, $mail_data['generated_id']);
									}
								} else $mail_data['text'] = 'content type not found';
								$n++;
							}
							$mail_data['attachments'] = json_encode($attachments);
						} catch (\Exception $e) {
							$error[] = "Folder {$current_folder},  Mail #{$i} Multipart Error :".$e->getMessage();
							$mail_data = $this->customParse($this->storage->getRawHeader($i), $this->storage->getRawContent($i), $mail_data);
						}

					} else {

						$parsed = $this->parseMessage($message);
						$mail_data['html'] = htmlentities($parsed['content'], ENT_IGNORE);
						$mail_data['text_charset'] = $parsed['charset'];
						$mail_data['text_encoding'] = $parsed['transfer_encoding'];

						if(isset($message->content_type)) {
							if(preg_match("/name=(.*)/",$message->content_type,$regs)) {
								$file_content = $message->getContent();
								$attachments[] = $this->handleAttachment($file_content, $regs[1], $mail_data['generated_id']);
								$mail_data['attachments'] = json_encode($attachments);
							} else {
								$mime_type = strtok($message->content_type, ';');
								$ext = $this->extMapping->getExt($mime_type);
								if(!$ext) $ext = 'unknown';
								$file_content = $message->getContent();
								$file_name = $this->generateFileName($mail_data['unique_id'], $n, $ext);
								$attachments[] = $this->handleAttachment($file_content, $file_name, $mail_data['generated_id']);
							}
						}


					}
					try {
						$this->mailBoxTable->updateMailBox($mail_data);
						$delete_from_server[] = $i;
					} catch (\Exception $e) {
						$error[] = "Folder {$current_folder},  Mail #{$i} Saving Error :".$e->getMessage();
					}
				}
				

			} catch (\Exception $e) {
				$error[] = "Folder {$current_folder},  Mail #{$i} Parsing Error :".$e->getMessage();
			}
			if(count($error)) $result['errors'][] = $error;
		}

		$delete_count = count($delete_from_server);
		for ($i=0; $i < $delete_count; $i++) { 
			$this->storage->removeMessage($delete_from_server[$i]);
		}

		$result['parsed'] = $i;
		$result['message'] = $i.'/'.$result['total_messages'].' parsed from '.$current_folder.' folder';
		return $result;
	}

	public function customGetId($raw_header, array $parsed_info)
	{
		$custom_parse = $this->mailerCustom->parse($raw_header);
		if(!$parsed_info['time']) $parsed_info['time'] = strtotime($custom_parse['time']);
		if(!$parsed_info['unique_id']) $parsed_info['unique_id'] = $parsed_info['real_unique_id'].$parsed_info['time'];
		return $parsed_info['unique_id'];
	}

	public function customParse($raw_header, $raw_content, array $parsed_info)
	{
		$parsed_info['raw_header'] = $raw_header;
		$parsed_info['raw_content'] = $raw_content;

		$custom_parse = $this->mailerCustom->parse($raw_header, $raw_content);
		if(!$parsed_info['time']) {
			$parsed_info['time'] = ($custom_parse['time'])?  strtotime($custom_parse['time']) : time();
		}
		if(!$parsed_info['unique_id']) $parsed_info['unique_id'] = $parsed_info['real_unique_id'].$parsed_info['time'];
		if(!$parsed_info['from_mail']) $parsed_info['from_mail'] = $custom_parse['from_mail'];
		if(!$parsed_info['mail_to']) $parsed_info['mail_to'] = json_encode($custom_parse['mail_to']);
		if(!$parsed_info['subject']) $parsed_info['subject'] = $custom_parse['subject'];
		if(!$parsed_info['generated_id']) $parsed_info['generated_id'] = md5($parsed_info['time'].$parsed_info['subject']);
		if(!$parsed_info['folder']) $parsed_info['folder'] = $this->current_folder->getLocalName();
		if(!$parsed_info['folder_full']) $parsed_info['folder_full'] = $this->current_folder->getGlobalName();
		if(!$parsed_info['mail_box']) $parsed_info['mail_box'] = $this->mail_box;
		if(!$parsed_info['location']) $parsed_info['location'] = 'db';

		if(isset($custom_parse['text'])) $parsed_info['text'] = $custom_parse['text'];
		if(isset($custom_parse['html'])) $parsed_info['html'] = $custom_parse['html'];
		if(isset($custom_parse['attachments'])) {
			$k = 0;
			foreach ($custom_parse['attachments'] as $attachment) {
				$ext = pathinfo($attachment['name'], PATHINFO_EXTENSION);
				$file_name = $parsed_info['unique_id'].'_'.$k.'.'.$ext;
				$attachments[$k] = $this->handleAttachment($attachment['content'], $file_name, $parsed_info['generated_id']);
				$parsed_info['attachments'] = json_encode($attachments);
				$k++;
			}
		}

		if(!$parsed_info['from_mail']) $parsed_info['from_mail'] = 'no_email';
		return $parsed_info;
	}

	public function parseMessage($message)
	{
		if(isset($message->content_type)) {
			try {
				$methods = get_class_methods($message->getHeader('content_type'));
				if(in_array('getParameter', $methods)) {
					$result['charset'] = $message->getHeader('content_type')->getParameter('charset');
				} else $result['charset'] = '';
				
			} catch (\Exception $e) {
				$result['charset'] = '';
			}
			
		}
		if(isset($message->content_transfer_encoding)) {
			try {
				$result['transfer_encoding'] = $message->getHeader('content_transfer_encoding', 'string');
			} catch (\Exception $e) {
				$result['transfer_encoding'] = '';
			}
			
		} 
		$result['content'] = $this->decodeBody($message->getContent(), $result['charset'], $result['transfer_encoding']);
		return $result;
	}

	public function generateFileName($unique_id, $counter, $ext = null, $name = null, $path = null)
	{
		$file_name = $unique_id.'_'.$counter;
		if(!$ext && $name != null) $ext = pathinfo($name, PATHINFO_EXTENSION);
		if($ext) $file_name = $file_name.'.'.$ext;

		if($path) {
			if(file_exists($path.$file_name)) return z_generateName($path, $ext, $file_name, $chars);
			else return $file_name;
		}
		return $file_name;
	}

	public function handleAttachment($file_content, $file_name, $generated_id)
	{
		if(!$file_content) return false;
		$mail_folder = _MAILSROOT_.$generated_id;
		z_createDir($mail_folder);
		$file_name = str_replace('"', '', $file_name);
		if(!file_exists($mail_folder.'/'.$file_name)) {
			$attachment = base64_decode($file_content);
			file_put_contents($mail_folder.'/'.$file_name, $attachment);  
			$this->uploadsTable->recordUpload($generated_id.'/'.$file_name, $mail_folder.'/'.$file_name, 'mail_attachment', 52);
		}
		return $file_name;
	}

	public function decodeBody($content, $charset, $transfer_encoding) {
		switch ($transfer_encoding) {
			case 'quoted-printable':
				$content = quoted_printable_decode($content);
				break;
			case 'base64':
				$content = base64_decode($content);
				break;	
			default:
				break;
		}
		if($charset == 'koi8-r') $content = mb_convert_encoding($content, 'UTF-8', 'KOI8-R');				
		return $content;

	}

	public function getStorage()
	{
		return $this->storage;
	}

	public function	getFolders()
	{
		return $this->folders;
	}

	public function	getCurrentFolder()
	{
		return	$this->current_folder;
	}

	public function setCurrentFolder($folder)
	{
		$this->current_folder = $folder;
	}

	public function getMailBox()
	{
		return $this->mail_box;
	}

}