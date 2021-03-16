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
use Application\Access\AccessList;
use Zend\Console\Request as ConsoleRequest;

class ConsoleController extends AbstractController
{

	public function indexAction()
	{

	}

	public function testAction()
	{
		$request = $this->getRequest();
		if (!$request instanceof ConsoleRequest){
			throw new \RuntimeException('You can only use this action from a console!');
		}

		$file_name = _CRONDIR_.'test.txt';
		file_put_contents($file_name, 'this is cron test file');


		return "Cron directory is: "._CRONDIR_." \n";
	}

	protected $errors;

	public function getLog($name)
	{
		$file_name = _CRONDIR_.$name.'.txt';
		$log_content = '';
		if(file_exists($file_name)) {
			$log_content = file_get_contents($file_name);
		}
		return $log_content;
	}

	public function saveLog($log_content, $name = 'cron_log')
	{
		$file_name = _CRONDIR_.$name.'.txt';
		file_put_contents($file_name, $log_content);
		echo 'Saved log to file '.$file_name.PHP_EOL;
	}



	public function cronstartAction()
	{
		$request = $this->getRequest();
		if (!$request instanceof ConsoleRequest){
			throw new \RuntimeException('You can only use this action from a console!');
		}
		$job_name = 'cron_log';
		$log_content = $this->getLog($job_name);
		$log_content .= PHP_EOL.'Cron job started '.$job_name.' '.date("H:i:s / d-M-Y", time());



		// $publics = $this->get('SocialPublicsTable')->getAll();
		// foreach ($publics as $public) {
		// 	$active_user = $this->get('UserTable')->get($public->user_id);
		// 	$log_content = $this->parseUserWall($active_user, $log_content, $public);
		// 	sleep(2);
		// }

		// $users = $this->get('UserTable')->getUsersForVkParsing();
		// foreach ($users as $active_user) {
		// 	$log_content = $this->parseUserWall($active_user, $log_content);
		// 	sleep(2);
		// }
		$this->saveLog($log_content,$job_name);
		return "Done! Cron $job_name has completed \n";
	}

	public function parseUserWall($active_user, $log_content, $public_options = null, $https = false)
	{
		if ($active_user->social_vk) {
			$log_content .= PHP_EOL.'VK wall parsing '.'| '.$active_user->id.'  |'.' STARTED : ';
			try {
				$api_vk = $this->get('api_vk');
				if ($active_user->social_vk_token && $active_user->social_vk_secret && !$https) {
					$api_vk->setUserToken($active_user->social_vk_token);
					$api_vk->setUserSecret($active_user->social_vk_secret);
				} else {
					$api_vk->setHttps(true);
					$api_vk->setUserToken(null);
					$api_vk->setUserSecret(null);
				}

				if($public_options) {
					$options = $public_options;
					$wall_id = $options->social_id;
				} else {
					$wall_id = $active_user->social_vk;
					if(!$active_user->social_vk_domain) {
						$vk_user_info = $api_vk->getUserInfo($active_user->social_vk);
						if(isset($vk_user_info->domain)) $active_user->social_vk_domain = $vk_user_info->domain;
					}
					$options = [
					'user_id' => $active_user->id,
					'user_login' => $active_user->login,
					'soc_domain' => $active_user->social_vk_domain,
					];
				}
				try {
					// $dump = $this->get('Dump');
					// $vk_wall_data = $dump->getDump('vk_wall_data');
					$vk_wall_data = [];
					$vk_wall_data = $api_vk->getWall($wall_id);
				} catch (\Exception $e) {
					$log_content .= $e->getMessage();
					if($this->errors == 0) {
						$this->errors++;
						$log_content .= ' --- ERROR DURING VK REQUEST , TRYING AGAIN WITH NO HTTP ----';
						$log_content = $this->parseUserWall($active_user, $log_content, $public_options, $https = 1);
					} else {
						$log_content .= ' --- ANOTHER ERROR, BYPASSING ----';
						$this->saveLog($log_content, 'cron_log');
						throw new \Application\Exception\Exception($e->getMessage(), $e->getCode());
					}
				}


				$result = $this->get('vk_wall_parsing')->parseVkWall($vk_wall_data, $wall_id, $options);

				$user_info['id'] = $active_user->id;
				$user_info['social_vk_parsed'] = time();
				$this->get('UserTable')->save($user_info);
				$this->errors = 0;
				$log_content .= PHP_EOL.'------ COMPLETED ---- '
				.'| '
				.$result['new_posts'].' new entries  parsed '
				.$result['refreshed'].' entries  refreshed ';
				if(isset($result['errors']) && count($result['errors'])) {
					$log_content .= 'ERRORS OCCURED: ';
					foreach ($result['errors'] as $section => $errors) {
						$log_content .= ' ['.$section. '] :';
						foreach ($errors as $key => $error) {
							if(is_array($error)) {
								foreach ($error as $error_text) {
									$log_content .= $error_text;
								}
							} else $log_content .= $error;
						}
					}
				}
			} catch (\Exception $e) {
				$log_content .= PHP_EOL.'VK wall parsing '.'| '.$active_user->id.'  |'.' ERROR : '.$e->getMessage();
				$this->saveLog($log_content, 'cron_log');
			}
		}

		return $log_content;
	}



	public function updateFusersAction()
	{
		$request = $this->getRequest();
		if (!$request instanceof ConsoleRequest){
			throw new \RuntimeException('You can only use this action from a console!');
		}
		echo 'Update Fusers started';


		$file_name =  _CRONDIR_.'f_users.txt';
		$time = date("H:i:s / d-M-Y", time());
		$log_content = '';
		if(file_exists($file_name)) {
			$log_content = file_get_contents($file_name);
		}
		$log_content .= PHP_EOL.' FUsers update started '.$time;

		try {
			$usersOnlineTable = $this->get('FUsersOnlineTable');
			$usersActivityTable = $this->get('FUsersActivityTable');
			$userTable = $this->get('UserTable');
			$curr_time = date("G", time());
			$curr_day = date("w", time());
			if($curr_time > 9) {

				$count_users = 0;
				$deleted_users = 0;
				$likes_added = 0;
				$votes_added = 0;
				$views_added = 0;

				$on_users = $usersOnlineTable->getFusersOnline();
				foreach ($on_users as $on_user) {
					try {
						$user_ip = ($on_user->last_ip)? $on_user->last_ip : zgenerateIP();
						//likes activity
						if($on_user->period_likes < _PERIOD_MAX_LIKES_)
							$likes_added = $likes_added + $this->fuserLikes($on_user);
						//rating activity
						if ($on_user->period_votes < _PERIOD_MAX_VOTES_)
							$votes_added = $votes_added + $this->fuserVotes($on_user, $user_ip);
						//views activity
						if ($on_user->period_votes < _PERIOD_MAX_VIEWS_)
							$views_added = $views_added + $this->fuserViews($on_user, $user_ip);
						// online activity
						$activity = $this->fuserOnlineActivity($on_user, $count_users, $deleted_users);
						$count_users = $activity['count_users'];
						$deleted_users = $activity['deleted_users'];
					} catch (\Exception $e) {
						$log_content .= PHP_EOL.'------ ERRORS - USERS SECTION ---- | '.$e->getMessage();
					}
				}
				// adding new users online
				$max_u = _MAX_F_USERS_ONLINE_;
				if($curr_time > 9 && $curr_time < 12) $max_u = 15;
				if($curr_time > 22) $max_u = 10;
				if($curr_day == 0 || $curr_day == 6) $max_u = 20;
				$added_users = $this->addNewFUsers($max_u, $count_users);

				$added_comps = 0;
				$count_comps = 0;
				$deleted_comps = 0;
				$mails_sended = 0;

				if($curr_time > 10 && $curr_time < 14 &&  $curr_day != 0 && $curr_day != 6) {
					$on_comps = $usersOnlineTable->getFusersOnline([], [
						'role' => AccessList::ROLE_SC_COMPANY,
						'_user_fields' => ['login', 'email', 'avatar', 'cv_avatar','home_country','home_city','home_address','contact_email','contact_mobile','contact_phone','info_website','company_name','company_description']
					]);
					foreach ($on_comps as $on_comp) {
						try {
							$user_ip = ($on_comp->last_ip)? $on_comp->last_ip : zgenerateIP();
							// mails

							$filters = [
								'nationality' => 'ukr',
								'registered' => 'no',
							];
							$options = [
								'_limit' => 5,
								'_order' => 'RAND()',
								'up' => 1,
								'_user_fields' => ['name', 'surname', 'full_name','login', 'email', 'avatar', 'cv_avatar', 'last_activity']
							];
							$to_users = $this->get('UserTable')->getUsersList(null,$filters, $options);
							foreach ($to_users as $to_user) {
								sleep(3);
								$this->get('mail')->sendCvUnlockedNotificationMail($to_user->email, $to_user, $on_comp);
								$usersActivityTable->save(['user_id' => $on_comp->user_id, 'act_time' => time(), 'act_type' => $usersActivityTable::ACT_TYPE_MAIL, 'act_value' => 1]);
								$mails_sended ++;
							}

							// online activity
							$activity = $this->fuserOnlineActivity($on_comp, $count_comps, $deleted_comps);
							$count_comps = $activity['count_users'];
							$deleted_comps = $activity['deleted_users'];

						} catch (\Exception $e) {
							$log_content .= PHP_EOL.'------ ERRORS - COMPANIES SECTION ---- | '.$e->getMessage();
						}
					}
					// adding new companies online
					$c_max_time = 12000;
					$added_comps = $this->addNewFUsers(_MAX_F_COMP_ONLINE_, $count_comps, $c_max_time, AccessList::ROLE_SC_COMPANY);

				}



				$log_content .= PHP_EOL.'------ COMPLETED ---- '.'| '
				.$added_comps.' new fcompanies added | '
				.$deleted_comps.' new fcompanies deleted | '
				.$count_comps.' new fcompanies was online | '
				.$mails_sended.' new mails sent | '

				.$added_users.' new fusers added | '
				.$deleted_users.' fusers deleted '
				.$count_users.' fusers was online | '
				.$likes_added.' likes added | '
				.$votes_added.' votes added |'
				.$views_added.' views added '
				;
			} else {
				$log_content .= PHP_EOL.'------ COMPLETED ---- NO USERS ADDED, NIGHT TIME' ;
				$usersOnlineTable->cleanUpTable();
			}
		} catch (\Exception $e) {
			$log_content .= PHP_EOL.'------ ERRORS ---- | USER '.$e->getMessage();
		}


		file_put_contents($file_name, $log_content);
		echo 'Writed to file '.$file_name.PHP_EOL;

		return "Done! Fusers update completed \n";
	}




	public function fuserOnlineActivity($on_user, $count_users, $deleted_users){
		$usersActivityTable = $this->get('FUsersActivityTable');
		if(($on_user->time_start + $on_user->planned_time) < time()) {
			$this->get('FUsersOnlineTable')->delete($on_user->user_id, 'user_id');
			$usersActivityTable->save(['user_id' => $on_user->user_id, 'act_time' => $on_user->time_start, 'act_type' => $usersActivityTable::ACT_TYPE_ONLINE, 'act_value' => $on_user->planned_time]);
			$deleted_users++;
		} else {
			$count_users++;
			$this->get('UserTable')->save(['id' => $on_user->user_id, 'last_activity' => time()]);
		}
		return ['count_users' => $count_users, 'deleted_users' => $deleted_users];
	}

	public function addNewFUsers($max_users, $count_users, $max_time = 3600, $role = AccessList::ROLE_SC_USER){
		$users_limit = ($count_users >= $max_users)? 0 : mt_rand(0, $max_users - $count_users);
		$new_users = $this->get('FUsersActivityTable')->getNewFUsers([], ['_limit' => $users_limit, 'role' => $role]);

		$planned_time = mt_rand(1800, $max_time);

		foreach ($new_users as $user) {
			$this->get('FUsersOnlineTable')->save(['user_id' => $user->id, 'time_start' => time(), 'planned_time' => $planned_time]);
			$this->get('UserTable')->save(['id' => $user->id, 'last_activity' => time(), 'last_ip' => zgenerateIP()]);
		}
		return $users_limit;
	}


	public function fuserLikes($on_user){

		$likes_added = 0;
		$likesTable = $this->get('LikesTable');
		$usersActivityTable = $this->get('FUsersActivityTable');

		$sections = [
			0 => [ 'table' => 'LogbookTable', 'section' => \Application\Model\NewsTable::SECTION_LOGBOOK ],
			1 => [ 'table' => 'VacanciesTable', 'section' => \Application\Model\NewsTable::SECTION_VACANCY ],
			2 => [ 'table' => 'QuestionAnswersTable', 'section' => \Application\Model\NewsTable::SECTION_ANSWERS ],
			3 => [ 'table' => 'UserTable', 'section' => \Application\Model\NewsTable::SECTION_USER ],
		];

		$curr_section = mt_rand(0, 3);
		$table = $this->get($sections[$curr_section]['table']);
		if($sections[$curr_section]['section'] == \Application\Model\NewsTable::SECTION_USER) {
		 null;
		 $section_id = $this->get('UserTable')
		 	->getUsersList(null, [], ['_user_fields' => ['id'], 'user_type' => 'company', '_limit' => 1, '_order' => 'RAND()', 'up' => 1])
		 	->current()->id;
		} else {
			$item = $table->getItems(NULL,['time_more' => time() - _F_POSTS_ACT_PERIOD_, 'time_less' => time()], ['_limit' => 1, '_order' => 'RAND()', 'up' => 1])->current();
			$section_id = ($item)? $item->id : null;
		}
		$section = $sections[$curr_section]['section'];
		if($section_id) {
			$result = $likesTable->like($on_user->user_id, $section , $section_id);
			if($result->getGeneratedValue()) {
				$likes_added++;
				$to_id = $this->getPostAuthorBySectionId($section, $section_id);
				$time = time() - mt_rand(0, 900);
				if($to_id) $this->addUserNotification(\Application\Model\UserNotificationsTable::TYPE_LIKE, $section, $section_id, $on_user->user_id, $to_id, ['time' => $time]);
				$usersActivityTable->save(['user_id' => $on_user->user_id, 'act_time' => $on_user->time_start, 'act_type' => $usersActivityTable::ACT_TYPE_LIKE, 'act_value' => 1]);
			}

		}
		return $likes_added;
	}

	public function fuserVotes($on_user, $user_ip)
	{
		$votes_added = 0;
		$usersActivityTable = $this->get('FUsersActivityTable');
		$votesTable = $this->get('ActivityVotesTable');
		$sections = [
		0 => [ 'table' => 'QuestionsTable', 'section' => \Application\Model\NewsTable::SECTION_QUESTIONS ],
		1 => [ 'table' => 'QuestionAnswersTable', 'section' => \Application\Model\NewsTable::SECTION_ANSWERS ],
		2 => [ 'table' => 'CommentsTable', 'section' => \Application\Model\NewsTable::SECTION_COMMENTS ],
		];

		$curr_section = mt_rand(0, 2);
		$table = $this->get($sections[$curr_section]['table']);
		$item = $table->getItems(NULL,['time_more' => time() - _F_POSTS_ACT_PERIOD_, 'time_less' => time()], ['_limit' => 1, '_order' => 'RAND()', 'up' => 1])->current();
		$section_id = ($item)? $item->id : null;
		$section = $sections[$curr_section]['section'];
		if($section_id) {
			$votes = [0 => 'up', 1 => 'down'];
			$curr_vote = mt_rand(0, 1);
			$vote = ($curr_vote > 0)? 'up' : 'down';
			$result = $votesTable->addVote($vote, $section , $section_id, $on_user->user_id, $user_ip);
			if($result->getAffectedRows()) {
				$votes_added++;
				$to_id = $this->getPostAuthorBySectionId($section, $section_id);
				$time = time() - mt_rand(0, 900);
				if($to_id) $this->addUserNotification(\Application\Model\UserNotificationsTable::TYPE_VOTE, $section, $section_id, $on_user->user_id, $to_id, ['time' => $time]);
				$usersActivityTable->save(['user_id' => $on_user->user_id, 'act_time' => $on_user->time_start, 'act_type' => $usersActivityTable::ACT_TYPE_VOTES, 'act_value' => 1]);
			}

		}
		return $votes_added;
	}

	public function fuserViews($on_user, $user_ip)
	{
		$views_added = 0;
		$usersActivityTable = $this->get('FUsersActivityTable');
		$viewsTable = $this->get('ActivityViewsTable');
		$sections = [
			0 => [ 'table' => 'LogbookTable', 'section' => \Application\Model\NewsTable::SECTION_LOGBOOK ],
			1 => [ 'table' => 'VacanciesTable', 'section' => \Application\Model\NewsTable::SECTION_VACANCY ],
			2 => [ 'table' => 'QuestionsTable', 'section' => \Application\Model\NewsTable::SECTION_ANSWERS ],
			3 => [ 'table' => 'UserTable', 'section' => \Application\Model\NewsTable::SECTION_USER ],
		];

		$curr_section = mt_rand(0, 3);
		$table = $this->get($sections[$curr_section]['table']);
		if($sections[$curr_section]['section'] == \Application\Model\NewsTable::SECTION_USER) {
			$section_id = $this->get('UserTable')->getUsersList(null, [], ['_user_fields' => ['id'], 'user_type' => 'company', '_limit' => 1, '_order' => 'RAND()', 'up' => 1])->current()->id;
		} else {
			$item = $table->getItems(NULL,['time_more' => time() - _F_POSTS_ACT_PERIOD_, 'time_less' => time()], ['_limit' => 1, '_order' => 'RAND()', 'up' => 1])->current();
			$section_id = ($item)? $item->id : null;
		}
		$section = $sections[$curr_section]['section'];

		if($section_id) {
			$result = $viewsTable->addView($section, $section_id, $on_user->user_id, $user_ip);
			if($result) {
				$views_added++;
				$usersActivityTable->save(['user_id' => $on_user->user_id, 'act_time' => $on_user->time_start, 'act_type' => $usersActivityTable::ACT_TYPE_VIEWS, 'act_value' => 1]);
			}

		}
		return $views_added;
	}


	public function adminMailQueryAction()
	{
		$request = $this->getRequest();
		if (!$request instanceof ConsoleRequest){
			throw new \RuntimeException('You can only use this action from a console!');
		}

		$job_name = 'MailQuery';

		$file_name =  _CRONDIR_.'mail_query.txt';
		$time = date("H:i:s / d-M-Y", time());
		$log_content = '';
		if(file_exists($file_name)) {
			$log_content = file_get_contents($file_name);
		}
		$log_content = $log_content.PHP_EOL.'Cron job started '.$job_name.' '.$time;

		$mq_table = $this->get('MailQueryTable');
		$m_query = $mq_table->getFields(['id', 'unique_id'], 'status', 0);
		$mailService = $this->get('Mail');
		foreach ($m_query as $mail) {
			$mq_table->save(['id' => $mail->id, 'status' => 1]);
			try {
				$mailService->sendSavedMail($mail->unique_id);
				$time = date("H:i:s / d-M-Y", time());
				$log_content .= PHP_EOL.' Mail sended '.$mail->unique_id.' '.$time;
			} catch (\Exception $e) {
				$mq_table->save(['id' => $mail->id, 'status' => 0]);
				$log_content .= PHP_EOL.'Error during sending mail '.$mail->unique_id.' '.$e->getMessage();
			}
		}

		file_put_contents($file_name, $log_content);
		echo 'Writed to file '.$file_name.PHP_EOL;

		return "Done! Cron $job_name job completed \n";
	}

	public function updateMailBoxAction()
	{
		$request = $this->getRequest();
		if (!$request instanceof ConsoleRequest){
			throw new \RuntimeException('You can only use this action from a console!');
		}
		echo 'Update Mailbox started';


		$file_name =  _CRONDIR_.'mail_box.txt';
		$time = date("H:i:s / d-M-Y", time());
		$log_content = '';
		if(file_exists($file_name)) {
			$log_content = file_get_contents($file_name);
		}
		$log_content .= PHP_EOL.' Mailbox update started '.$time;

		$mail_accounts = $this->get('MailAccountsTable')->getAccounts()->toArray();
		$mailParser = $this->get('MailParser');

		foreach ($mail_accounts as $account) {
			try {
				$mailParser->init($account, 52);
				$foldersTable = $this->get('MailBoxFoldersTable');
				$mb_folders = $foldersTable->getFieldsByFields(['id','folder', 'folder_full'], ['mail_box' => $account['mail_box']])->toArray();
				$folders = $mailParser->getFolders();
				foreach ($folders as $folder) {
					$server_folder_full = $folder->getGlobalName();
					$server_folder = $folder->getLocalName();
					// adding new folders to database
					$found = 0;
					for ($i=0; $i < count($mb_folders); $i++) {
						if($server_folder_full == $mb_folders[$i]['folder_full']) $found++;
					}
					if(!$found) $foldersTable->save(['mail_box' => $account['mail_box'], 'folder' => $server_folder, 'folder_full' => $server_folder_full]);

					//check to parse mailbox folder or not
					$to_parse = 0;
					if($folder_name) {
						if($server_folder_full == $folder_name) $to_parse++;
					} else $to_parse++;

					if($to_parse) {
						$mailParser->setCurrentFolder($folder);
						$result = $mailParser->parseMailbox();
					}
				}

			} catch (\Exception $e) {
				$log_content .= PHP_EOL.'------ ERRORS ---- | MAILBOX '.$account['mail_box'].' '.$e->getMessage();
			}
			if(isset($result)) {
				$message = 'Mail'.$account['mail_box'].' updated, '.$result['parsed'].'/'.$result['total_messages'].' new messages saved ';
				if($result['errors']) $message .= '<br />'.$result['errors'].' errors occured during mailbox parsing';
			}
		}


		file_put_contents($file_name, $log_content);
		echo 'Writed to file '.$file_name.PHP_EOL;

		return "Done! Mailbox update completed \n";
	}

}
