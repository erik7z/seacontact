<?php
namespace Application\Model;

class UserLogbookTable extends zAbstractTable
{

	protected $picsTable;
	protected $pics_logsTable;
	protected $uploadImageLib;


	public function __construct()
	{
		$this->init('user_logbook');
	}

	public function getNextPubTime($user_id, $delay = 10800)
	{
		$time = time();
		$result = $this->query(
			"SELECT ul.time
				FROM `user_logbook` ul
				WHERE ul.user = '$user_id'
				AND ul.time > '$time'
				ORDER BY ul.time DESC
				LIMIT 1
			"
			)->current();
		if(is_object($result)) return $result['time'] + $delay;
		return time() + $delay;
	}


	public function getAllLogbooks($viewer_id = null, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => 200, '_order' => '`time`']);
		
		$user_fields = isset($options['_user_fields'])? $options['_user_fields'] : ['name', 'surname', 'full_name', 'company_name', 'login', 'avatar', 'cv_avatar', 'type', 'role'];
		$user_fields = array_merge(['id' => 'user_id'], $user_fields);
		$user_fields_string =  $this->arrayToFields($user_fields,'u.');

		$db_fields = isset($options['_logbook_fields'])? $options['_logbook_fields'] : $this->getStandartFields();
		$db_fields = array_merge(['id' => 'logbook_id'], $db_fields);
		$db_fields_fields_string =  $this->arrayToFields($db_fields, 'ul.');

		$stats_fields = isset($options['_stats_fields'])? $options['_stats_fields'] : $this->getStatsFields();
		$stats_fields = array_flip($stats_fields);

		$only_current = (isset($options['only_current']))? $options['only_current'] : false;
		$where = '';

		if($only_current) {
			$time = time();
			$and = ($where == '') ? 'WHERE ' : ' AND ';
			$where .= "$and ul.`time` < $time ";
		}

		$where .= ($where == '') ? ' WHERE ' : ' AND ';
		$where .= ' ul.active = 1 ';

		
		foreach ($filters as $filter => $value) {
			if(!$value) next($filters);
			if($value) $where .= ($where == '') ? ' WHERE ' : ' AND ';

			if ($filter == 'owner_id') {
				$value = (int)$value;
				$where .= " `ul`.user = $value";
			} else if ($filter == 'logbook_id' || $filter == 'id') {
				$value = (int)$value;
				$where .= " `ul`.id = $value";
			} else if ($filter == 'user_id') {
				$where .= " `ul`.user = '$value' ";
			} else if ($filter == 'user_role') {
				$where .= " `u`.role = '$value' ";
			} else if ($filter == 'exclude') {
				$where .= " `ul`.id != '$value' ";
			} else if ($filter == 'posted_in_vk') {
				$where .= " `ul`.post_vk_id IS NOT NULL ";
			} else if ($filter == 'post_vk_wall') {
				$where .= " `ul`.post_vk_wall = '$value' ";
			} else if ($filter == 'posted_in_fb') {
				$where .= " `ul`.post_fb_id IS NOT NULL ";
			} else if ($filter == 'tag' || $filter == 'tags') {
				if(is_array($value)) {
					$where .= " ( ";
						for ($i=0; $i < count($value); $i++) { 
							if($i>0) $where .= ' OR ';
							$where .= " `ul`.tags LIKE '%{$value[$i]}%' "; 
						}
						$where .= ')';
				} else $where .= " `ul`.tags LIKE '%$value%' ";
			} else if ($filter == 'query') {
				$where .= " `ul`.text LIKE '%$value%' OR `ul`.tags LIKE '$value%' OR `ul`.title LIKE '%$value%' ";
			} else throw new \Application\Exception\Exception("Filter not recognized", 1);
		}


		if ($this->count) {
			$fields_string = ' COUNT(*) count ';
			$this->order_string = '';
			$this->limit_string = '';
			$this->offset_string = '';
		} else { 

			$fields_string = $db_fields_fields_string;
			$fields_string .= ', '.$user_fields_string;

			if(isset($stats_fields['pics'])) $fields_string .= ", {$this->getPicsSelect('', 'logbook','ul')} pics ";
			if(isset($stats_fields['total_comments'])) $fields_string .= ", {$this->getCommentsCountSelect('logbook','ul')} total_comments";
			// if(isset($stats_fields['comments_list'])) $fields_string .= ", {$this->getCommentsSelect($viewer_id,'logbook','ul')} comments_list ";
			if(isset($stats_fields['likes'])) $fields_string .= ", {$this->getLikesCountSelect('logbook','ul')} likes ";
			if(isset($stats_fields['views'])) $fields_string .= ", {$this->getViewsCountSelect('logbook','ul')} views ";
			if(isset($stats_fields['likers'])) $fields_string .= ", {$this->getLikersSelect('logbook','ul')} likers ";
			if(isset($stats_fields['like_status'])) $fields_string .= ", {$this->getLikeStatusSelect($viewer_id, 'logbook','ul')} like_status ";
			if(isset($stats_fields['videos'])) $fields_string .= ", {$this->getVideosSelect('logbook','ul')} videos ";
			if(isset($stats_fields['links'])) $fields_string .= ", {$this->getLinksSelect('logbook','ul')} links ";
			if(isset($stats_fields['soc_likes'])) $fields_string .= ", {$this->getSocialLikesCountSelect('logbook','ul')} soc_likes ";
			if(isset($stats_fields['soc_likers'])) $fields_string .= ", {$this->getSocialLikersSelect('logbook','ul')} soc_likers ";
			// if(isset($stats_fields['soc_comments_list'])) $fields_string .= ", {$this->getSocialCommentsSelect($viewer_id,'logbook','ul')} soc_comments_list ";
			if(isset($stats_fields['vk_last_comment_id'])) $fields_string .= ", {$this->getVkLastCommentId('logbook','ul')} vk_last_comment_id ";
			
		}

		$join_string = 'LEFT JOIN user u ON (ul.user = u.id)';	
		$this->query("SET SESSION group_concat_max_len = 1000000;");
		return $this->query(
			"SELECT $fields_string
				FROM `user_logbook` ul
				$join_string
				$where
				$this->order_string
				$this->limit_string
				$this->offset_string
			"
			);
	}


	public function parseFbWall($social_feed, $user_id, $options = [])
	{
		$user_login = (isset($options['user_login'])) ? $options['user_login'] : null;
		$user_domain = (isset($options['user_domain'])) ? $options['user_domain'] : null;
		$user_social_id = (isset($options['user_social_id'])) ? $options['user_social_id'] : null;
		$parsing_tags = (isset($options['parsing_tags'])) ? $options['parsing_tags'] : null;
		$last_parsed_time = (isset($options['last_parsed_time'])) ? $options['last_parsed_time'] : null;
		$already_parsed_ids = (isset($options['already_parsed_ids'])) ? $options['already_parsed_ids'] : null;
		
		$parsed = 0;
		$old_parsing_tag = '#seacontact_'.$user_login;
		if(isset($social_feed['data'])) {
			$tagsTable = $this->sl()->get('TagMapTable');
			$picsTable = $this->sl()->get('PicsTable');
			$videosTable = $this->sl()->get('VideosTable');
			$linksTable = $this->sl()->get('LinksTable');
			foreach ($social_feed['data'] as $social_post) {
				try {
					$to_parse_or_not = 0;
					if(isset($social_post['message'])) {
						if(strpos($social_post['message'], $parsing_tags) !== false) $to_parse_or_not++;
						if(strpos($social_post['message'], $old_parsing_tag) !== false) $to_parse_or_not++;
					}
					if(isset($social_post['description'])) {
						if(strpos($social_post['description'], $parsing_tags) !== false) $to_parse_or_not++;
						if(strpos($social_post['description'], $old_parsing_tag) !== false) $to_parse_or_not++;
					}
					if($to_parse_or_not > 0) {
						$social_post_date = strtotime($social_post['created_time']);
						if(is_array($already_parsed_ids)) $to_parse_or_not = !isset($already_parsed_ids[$social_post['id']]);
						else $to_parse_or_not = ($last_parsed_time < $social_post_date)? 1 : 0;
						if ($to_parse_or_not) {
							$user_social_link = ($user_domain && $user_social_id)? '<i class="fa fa-facebook bg-primary" style="padding: 0.2em;"></i> <a href="http://facebook.com/'.$user_social_id.'">facebook.com/'.$user_domain.'</a><br />' : '';
							$log_entry = [];
							$log_entry['user'] = $user_id;
							$tags = [];
							$tags[] = str_replace('#', '', $old_parsing_tag);
							if(isset($social_post['message'])) {
								$post_text = str_replace($parsing_tags, '', $social_post['message']);
								$post_text = str_replace($old_parsing_tag, '', $post_text);
								$post_text = $user_social_link.' '.$post_text ;
								$log_entry['text'] = htmlspecialchars($post_text);
								preg_match_all("/(#(?'tags'\w+))/u", $social_post['message'], $matches);
								if(count($matches['tags'])) $tags = array_merge($tags, $matches['tags']);
							}
							
							$log_entry['time'] = $social_post_date;
							$log_entry['post_fb_time'] = $social_post_date;
							$log_entry['post_fb_id'] = $social_post['id'];
							$log_entry['active'] = 1;
							$log_entry['tags'] = implode(',', $tags);
							$log_entry['id'] = $this->save($log_entry);
							$tagsTable->addTags(\Application\Model\NewsTable::SECTION_LOGBOOK, $log_entry['id'], $tags);
							
							if (isset($social_post['attachments']['data'])) {
								foreach ($social_post['attachments']['data'] as $attachment) {
									if($attachment['type'] == 'album') {
										foreach ($attachment['subattachments']['data'] as $subattachment) {
											if($subattachment['type'] == 'photo') {
												$image_data = $picsTable->getImageFromUrl($subattachment['media']['image']['src'], $user_id);
												$picsTable->addArticlePic($image_data, $user_id, 'logbook', $log_entry['id']);
											}
										}
									} else if($attachment['type'] == 'share') {
										if(isset($attachment['media']['image']['src'])) {
											$image_data = $picsTable->getImageFromUrl($attachment['media']['image']['src'], $user_id);
											$thumb = $image_data['img'];
										} else $thumb = null;
										$description = isset($attachment['description'])? $attachment['description'] : null;
										$linksTable->addLink($user_id, 'logbook', $log_entry['id'], $attachment['url'], $attachment['title'], $description, $thumb);
									} else if(strpos($attachment['type'], 'video') !== false){
										if(isset($attachment['media']['image']['src'])) {
											$image_data = $picsTable->getImageFromUrl($attachment['media']['image']['src'], $user_id);
											$thumb = $image_data['img'];
										} else $thumb = null;
										$embed_url = isset($social_post['source'])? $social_post['source'] : null;
										$description = isset($attachment['description'])? $attachment['description'] : null;
										$videosTable->addVideo($user_id, 'logbook', $log_entry['id'],  $embed_url, $attachment['url'], $attachment['title'], $description,  $thumb);
									} 
								}
							}
							$parsed++;
						}
					}
				} catch (\Exception $e) {}
			}
		}
		return $parsed;
	}




	public function delete($log_id, $user_id = 0)
	{
		// Revise delete procedure!!!!
		parent::delete($log_id);

		$pics_list = $this->getTable('PicsTable')->getArticlePics('logbook', $log_id);
		foreach ($pics_list as $pic) {
			$this->getTable('PicsTable')->delete($pic['id'], ['delete_from_article_table' => false, 'article_table_name' => false, 'user_id' => $user_id]);
		}

		$this->sl()->get('SocialCommentsTable')->deleteOnFields(['section' => 'logbook', 'section_id' => $log_id]);
		
		return true;

	}


}