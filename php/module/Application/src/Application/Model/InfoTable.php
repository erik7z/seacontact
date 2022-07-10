<?php
namespace Application\Model;

class InfoTable extends zAbstractTable
{


	public function __construct()
	{
		$this->init('guests_online');
	}

	// returns some CONSTANT
	protected function con($const)
	{
		return constant($const);
	}


	public function updateStatus()
	{
		$time = time();
		$ip = $_SERVER['REMOTE_ADDR'];
		if($this->is_bot() || $this->is_bot2()) return false;
		return $this->query(
						"INSERT INTO `$this->tableName` 
						(ip, time) 
						VALUES
						('$ip', '$time')
						ON DUPLICATE KEY UPDATE
						time = VALUES(time)
						");
	}

	public function is_bot() {
	  if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) return TRUE;
	  else return FALSE;
	}

	public function is_bot2() {
		$bots = array(
		    'rambler','googlebot','aport','yahoo','msnbot','turtle','mail.ru','omsktele',
		    'yetibot','picsearch','sape.bot','sape_context','gigabot','snapbot','alexa.com',
		    'megadownload.net','askpeter.info','igde.ru','ask.com','qwartabot','yanga.co.uk',
		    'scoutjet','similarpages','oozbot','shrinktheweb.com','aboutusbot','followsite.com',
		    'dataparksearch','google-sitemaps','appEngine-google','feedfetcher-google',
		    'liveinternet.ru','xml-sitemaps.com','agama','metadatalabs.com','h1.hrn.ru',
		    'googlealert.com','seo-rus.com','yaDirectBot','yandeG','yandex',
		    'yandexSomething','Copyscape.com','AdsBot-Google','domaintools.com',
		    'Nigma.ru','bing.com','dotnetdotcom'
		  );
		  foreach($bots as $bot)
		    if(stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false){
		      $botname = $bot;
		      return true;
		    }
		  return false;
	}


	public function getUserStats($user_id = null)
	{
		return $this->query(
			"SELECT
				u.*
				, ( SELECT COUNT(*) FROM `user_experience` WHERE user = '$user_id') contracts
				, ( SELECT MAX(date_to) FROM `user_experience` WHERE user = '$user_id') last_contract
				, ( SELECT COUNT(*) FROM `user_documents` WHERE user = '$user_id') docs
				, (	SELECT COUNT(*) FROM ( 
						SELECT id FROM `vacancies` v WHERE v.user = '$user_id'
						UNION
						SELECT id FROM `user_logbook` lb WHERE lb.user = '$user_id'
						UNION
						SELECT id FROM `questions` q WHERE q.user = '$user_id'
						UNION
						SELECT id FROM `question_answers` qa WHERE qa.user = '$user_id'
						) x
					) posts
				, ( SELECT MAX(time) FROM ( 
						SELECT time FROM `vacancies` v WHERE v.user = '$user_id'
						UNION
						SELECT time FROM `user_logbook` lb WHERE lb.user = '$user_id'
						UNION
						SELECT time FROM `questions` q WHERE q.user = '$user_id'
						UNION
						SELECT time FROM `question_answers` qa WHERE qa.user = '$user_id'
						) y
					) last_post
				
				FROM `user` u WHERE u.id = '$user_id'
			")->current();
	}


	public function getSiteInfo($viewer_id = null, $options = [])
	{
		$user_fields = isset($options['_user_fields'])? $options['_user_fields'] : ['id' => 'user_id', 'login', 'role', 'type', 'name', 'full_name', 'surname', 'company_name', 'avatar', 'cv_avatar', 'last_activity'];
		$user_fields_string = $this->generateJsonFields($user_fields, '', 5);
		$viewer_ip = $_SERVER['REMOTE_ADDR'];
		$latest_time = time() - _ONLINE_TIME_;

		$online_seamans_count_select = "
			( SELECT COUNT(*) count
				FROM `user` u
				WHERE (`u`.`last_activity` > {$latest_time})
				AND id != '$viewer_id'
				AND u.type = '{$this->con('Application\Model\UserTable::TYPE_USER')}'
			)";

		$online_seamans_select = "
			( SELECT $user_fields_string
				FROM `user` u
				WHERE (`u`.`last_activity` > {$latest_time})
				AND id != '$viewer_id'
				AND u.type = '{$this->con('Application\Model\UserTable::TYPE_USER')}'
			)";

		$online_companies_count_select = "
			( SELECT COUNT(*) count
				FROM `user` u
				WHERE (`u`.`last_activity` > {$latest_time})
				AND id != '$viewer_id'
				AND u.type = '{$this->con('Application\Model\UserTable::TYPE_COMPANY')}'
			)";

		$online_companies_select = "
			( SELECT $user_fields_string
				FROM `user` u
				WHERE (`u`.`last_activity` > {$latest_time})
				AND id != '$viewer_id'
				AND u.type = '{$this->con('Application\Model\UserTable::TYPE_COMPANY')}'
			)";



		$online_admins_select = "  
		( SELECT $user_fields_string
			FROM `user` u
			WHERE (`u`.`last_access_admin_panel` > {$latest_time})
			AND id != '$viewer_id' )
		";

		$online_guests_select = "  
		( SELECT COUNT(*) count
			FROM `guests_online` go
			WHERE go.`time` > {$latest_time}
			AND go.`ip` != '$viewer_ip' )
		";

		$new_msgs_select = "  
		( SELECT COUNT(*) count
				FROM `user_messages`
				WHERE `to_id` = '$viewer_id'
				AND `readed` IS NULL)
		";

		$new_contacts_query = $this->sl()->get('ContactsTable')->getContacts($viewer_id, [], ['query_only' => 1, 'count' => 1, 'relations' => \Application\Model\UserContactsTable::RELATION_RCVD]);
		$online_friends_query = $this->sl()->get('ContactsTable')->getUserOnlineFriends($viewer_id, ['query_only' => 1, '_fields' => $user_fields, 'json_fields' => 1]);
		$online_friends_count_query = $this->sl()->get('ContactsTable')->getUserOnlineFriends($viewer_id, ['query_only' => 1, 'count' => 1]);
		$notif_query = $this->sl()->get('UserNotificationsTable')->getNotifications($viewer_id, ['not_readed' => 1], ['query_only' => 1, 'count' => 1]);

		
		$query = "SELECT 
				$online_seamans_select online_seamans
				, $online_seamans_count_select online_seamans_count
				, $online_companies_select online_companies
				, $online_companies_count_select online_companies_count
				, ( $online_friends_query ) online_friends
				, ( $online_friends_count_query ) online_friends_count
				, $online_admins_select online_admins
				, $online_guests_select online_guests
				, $new_msgs_select new_msgs
				, ($new_contacts_query) new_contacts
				, ( $notif_query ) new_notif
				FROM `$this->tableName` info
				LIMIT 1
				";

		$predicat = '_'.md5(implode('.',$options));
		$dump_name = z_generateNameFromMethod(get_class($this).'::'.__FUNCTION__, 0);
		$result = $this->getCachedRequest($query, $dump_name, $predicat, 1, 100);
		if(is_object($result)) return $result->current();
		else return $result[0];
	}

	public function getOnlineGuests()
	{
		$viewer_ip = $_SERVER['REMOTE_ADDR'];
		$latest_time = time() - 900;
		return $this->query(
		"SELECT COUNT(*) count
			FROM `$this->tableName` go
			WHERE go.`time` > {$latest_time}
			AND go.`ip` != '$viewer_ip'
			")->current()->count;
	}

}