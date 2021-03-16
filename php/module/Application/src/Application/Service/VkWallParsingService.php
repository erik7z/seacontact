<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use \Application\Model\NewsTable;
use \Application\Model\SocialPublicsTable as PublicsTable;

class VkWallParsingService
{
	protected $sl;

	CONST TAG_SECT_QUEST = '#questions';
	CONST TAG_NO_PARSE = '#no_copy';
	CONST TAG_FORCE_PUBLIC = '#sc_public';
	CONST TAG_ADD_LINK = '#sc_link';
	
	CONST TAG_FORCE_AUTHOR = '#sc_author';
	CONST TAG_ANONYM = '#anonym';
	
	public function __construct(ServiceLocatorInterface $serviceLocator)
	{
		$this->sl = $serviceLocator;
		return $this;
	}

	public function getVkParsedLogs($vk_wall_id)
	{
		$tmp = [];
		try {
			$already_parsed = $this->sl->get('NewsTable')->getVkParsedNews(['wall_id' => $vk_wall_id]);
			foreach ($already_parsed as $article) {
				$tmp[$article->post_vk_id] = $article;
			}
		} catch (\Exception $e) {}
		return $tmp;
	}


	public function parseVkWall($social_feed, $vk_wall_id, $options = [])
	{
		$user_id = (isset($options['user_id'])) ? $options['user_id'] : null;
		if(!$user_id) throw new \Application\Exception\Exception("User id should be provided", 1);

		$user_login = (isset($options['user_login'])) ? $options['user_login'] : '#';
		$soc_domain = (isset($options['soc_domain'])) ? $options['soc_domain'] : null;
		$parsing_tags = (isset($options['parsing_tags'])) ? str_replace(',', '|', $options['parsing_tags']) : _SOCIAL_PARSE_KEYWORD_.strtolower($user_login);
		$author_tags = (isset($options['author_tags'])) ? str_replace(',', '|', $options['author_tags']) : null;
		$without_tags = (isset($options['without_tags'])) ? $options['without_tags'] : null;
		$add_link = (isset($options['add_link'])) ? $options['add_link'] : null;
		$link_title = (isset($options['name'])) ? $options['name'] : null;
		$description = (isset($options['description'])) ? $options['description'] : null;
		$avatar = (isset($options['avatar'])) ? $options['avatar'] : null;
		
		$already_parsed = $this->getVkParsedLogs($vk_wall_id);
		$vk_users_in_db = $this->sl->get('UserTable')->getVkUsersMap();
		$tagsTable = $this->sl->get('TagMapTable');
		$new_posts = 0;
		$refreshed = 0;
		$errors = [];
		foreach ($social_feed as $social_post) {
			$tmp_author_tags = $author_tags;
			$tmp_domain = $soc_domain;
			try {
				if (is_object($social_post) && strpos($social_post->text, self::TAG_NO_PARSE) === false) {
					$parse = $this->detectParsing($social_post, $parsing_tags);
					$owner_id = null;
					if($without_tags || $tmp_author_tags) {
						$s_id = null;
						if(isset($social_post->signer_id) && isset($vk_users_in_db[$social_post->signer_id])) $s_id = $social_post->signer_id;
						if(isset($social_post->copy_owner_id) && isset($vk_users_in_db[$social_post->copy_owner_id])) {
							if(!$this->sl->get('NewsTable')->getVkParsedNews(['wall_id' => $social_post->copy_owner_id, 'post_vk_id' => $social_post->copy_post_id])->current()) 
								$s_id = $social_post->copy_owner_id;
						}
						// else if(isset($social_post->created_by) && isset($vk_users_in_db[$social_post->created_by])) $s_id = $social_post->created_by;
						if($s_id) {
							$tmp_author_tags .= '|'._SOCIAL_PARSE_KEYWORD_.strtolower($vk_users_in_db[$s_id]['login']);
							if($without_tags || $this->detectParsing($social_post, $tmp_author_tags)) {
								// if parse, means not found MY parsing tags in post, 
								if(strpos($social_post->text, self::TAG_FORCE_PUBLIC) === false){
									$tmp_domain = $vk_users_in_db[$s_id]['vk_domain'];
									$owner_id = $vk_users_in_db[$s_id]['id'];
								} 
								$parse++;
							}
						}
					}
				
					if($parse > 0) {
						sleep(1);
						$section = NewsTable::SECTION_LOGBOOK;
						$articleTable = $this->sl->get('LogbookTable');
						if(strpos($social_post->text, self::TAG_SECT_QUEST) !== false) {
							$section = NewsTable::SECTION_QUESTIONS;
							$articleTable = $this->sl->get('QuestionsTable');
						}
						$article = [];
						$p_data = $this->parseTags($parsing_tags, $social_post->text, $tmp_author_tags);
						$article['tags'] = $p_data['db_tags'];
						$article['text'] = htmlentities($p_data['post_text']);
						if(isset($already_parsed[$social_post->id])) {
							// refreshing post data
							$article_info = $already_parsed[$social_post->id];
							if(isset($social_post->likes) && $social_post->likes->count && $social_post->likes->count > $article_info['soc_likes']) {
								$this->saveLikesFromVk($section, $article_info['id'], $vk_wall_id, $social_post->id, $vk_users_in_db, $article_info['soc_likes']);
							}
							if(isset($social_post->comments) && $social_post->comments->count) {
								if($section == NewsTable::SECTION_QUESTIONS) {
									$this->saveAnswersFromVk($article_info['id'], $vk_wall_id, $social_post->id, $vk_users_in_db);
								} else if($social_post->comments->count > $article_info['soc_comments']) {
									$this->saveCommentsFromVk($section, $article_info['id'], $vk_wall_id, $social_post->id, $vk_users_in_db, $article_info['soc_comments']);
								}
							}
							$article['id'] = $article_info['id'];
							$articleTable->save($article);
							$tagsTable->addTags($section, $article['id'], array_filter(explode(',', $p_data['db_tags'])));
							$refreshed++;
						} else {
							$vk_page_link = ($tmp_domain)? '<i class="fa fa-vk bg-primary" style="padding: 0.2em;"></i> <a href="http://vk.com/'.$tmp_domain.'">vk.com/'.$tmp_domain.'</a><br />' : '';
							if(strpos($social_post->text, self::TAG_ANONYM) !== false) $owner_id = null;
							if(!$add_link) {
								if(strpos($social_post->text, self::TAG_ADD_LINK) !== false) $add_link = 1;
							}
							
							// $post_text = $vk_page_link.' '.$post_text ;
							$article['user'] = ($owner_id)? $owner_id : $user_id;
							$article['time'] = $social_post->date;
							$article['post_vk_time'] = $social_post->date;
							$article['post_vk_id'] = $social_post->id;
							$article['post_vk_wall'] = $vk_wall_id;
							$article['active'] = 1;
							$article['id'] = $articleTable->save($article);
							if($section == NewsTable::SECTION_QUESTIONS) {
								$article['title'] = zshorterText(strip_tags(html_entity_decode($p_data['post_text'])), 10);
								$article['id'] = $articleTable->save($article);
							}
							$new_posts++;

							$tagsTable->addTags($section, $article['id'], array_filter(explode(',', $p_data['db_tags'])));

							if($add_link && isset($options['page'])) {
								$this->sl->get('LinksTable')->addLink($article['user'], $section, $article['id'], $options['page'], $link_title, null, $avatar);
							}
							if (isset($social_post->attachments) && count($social_post->attachments)) {
								$errors['attachments'][] = $this->parseVkAttachments($social_post->attachments, $article['user'], $section, $article['id'], $parsing_tags);
							}
							if(isset($social_post->comments) && $social_post->comments->count) {
								if($section == NewsTable::SECTION_QUESTIONS) {
									$errors['answers'][] = $this->saveAnswersFromVk($article['id'], $vk_wall_id, $social_post->id, $vk_users_in_db);
								} else {
									$errors['comments'][] = $this->saveCommentsFromVk($section, $article['id'], $vk_wall_id, $social_post->id, $vk_users_in_db);
								}
							}
							if(isset($social_post->likes) && $social_post->likes->count) {
								$errors['likes'][] = $this->saveLikesFromVk($section, $article['id'], $vk_wall_id, $social_post->id, $vk_users_in_db);
							}
						}
					}
				}
			} catch (\Exception $e) {
				$errors['general'][] = 'ERROR : social post '.$social_post->id.' '.$e->getMessage();
			}
		}

		$this->updateSocialUsersInfo();
		return [
			'new_posts' => $new_posts,
			'refreshed' => $refreshed,
			'errors' => $errors,
		];
	}

	public function parseTags($p_tags, $post_text, $tmp_author_tags = null)
	{
		if($tmp_author_tags) $p_tags .= '|'.$tmp_author_tags;
		$p_tags = explode('|', $p_tags);
		$c_tags = [self::TAG_ADD_LINK,self::TAG_SECT_QUEST,self::TAG_FORCE_PUBLIC, self::TAG_NO_PARSE];
		$post_text = str_replace(array_merge($p_tags, $c_tags), '', $post_text);
		$tags = [];
		preg_match_all("/(?'tags'#\w+)/u", $post_text, $matches);
		if(count($matches['tags'])) $tags = $matches['tags'];
		$post_text = str_replace($tags, '', $post_text);
		$db_tags = str_replace(['#', ' '], '', implode(',', $tags));
		return ['post_text' => $post_text, 'db_tags' => $db_tags];
	}

	public function detectParsing($social_post, $parsing_tags)
	{
		$parse = 0;
		if(preg_match("%$parsing_tags%i", $social_post->text)) $parse++;
		else if(isset($social_post->copy_text) 
			&& preg_match("%$parsing_tags%i", $social_post->copy_text)) $parse++;
		else if(isset($social_post->media) 
			&& $social_post->media->type == 'share' 
			&& preg_match("%$parsing_tags%i", $social_post->media->share_url)) $parse++;
		else if(isset($social_post->attachment) 
			&& $social_post->attachment->type == 'link' 
			&& preg_match("%$parsing_tags%i", $social_post->attachment->link->url)) $parse++;
		else if(isset($social_post->attachments)) {
			foreach ($social_post->attachments as $attachment) {
				if($attachment->type == 'link' && preg_match("%$parsing_tags%i", $attachment->link->url)) $parse++;
			}
		}
		return $parse;
	}

	public function updateSocialUsersInfo($vk_users_in_db = [])
	{
		$soc_ids = $this->sl->get('SocialUsersTable')->getUnkownSocialUsersIds(_MAX_VK_USERS_PARSING_);
		$ids = [];
		foreach ($soc_ids as $soc_id) {
			$ids[$soc_id->soc_name][] = $soc_id->soc_user_id;
		}
		if(isset($ids['vk'])) {
			$ids['vk'] = implode(',', $ids['vk']);
			$profiles = $this->sl->get('api_vk')->getUsers($ids['vk']); 
			// $dump = $this->sl->get('Dump');
			// $dump->createDump($profiles, 'profiles');
			// $profiles = $dump->getDump('profiles');
			return $this->saveVkUsers($profiles, $vk_users_in_db);
		}
	}

	public function parseVkAttachments($attachments, $user_id, $section, $section_id, $parsing_tags = null){
		$errors = [];
		foreach ($attachments as $attachment) {
			try {
				if($attachment->type == 'photo') {
					$image_data = $this->sl->get('PicsTable')->getImageFromVkAttachment($attachment->photo, $user_id, 1);
					$this->sl->get('PicsTable')->addArticlePic($image_data, $user_id, $section, $section_id);
				} else if($attachment->type == 'sticker') {
					$image_data = $this->sl->get('PicsTable')->getImageFromVkAttachment($attachment->sticker, $user_id, 1);
					$this->sl->get('PicsTable')->addArticlePic($image_data, $user_id, $section, $section_id);
				} else if($attachment->type == 'video') {
					$this->addVideoFromVkAttachment($attachment, $user_id, $section, $section_id);
				} else if($attachment->type == 'link' && !preg_match("%$parsing_tags%i", $attachment->link->url)){
					$this->addLinkFromVkAttachment($attachment, $user_id, $section, $section_id);
				}
			} catch (\Exception $e) {
				$errors[] = 'error parsing vk_attachments: '.$section.' '.$section_id.' '.$e->getMessage();
			}

		}
		return $errors;
	}

	public function addLinkFromVkAttachment($attachment, $user_id, $section, $section_id){
		$thumb = null;
		try {
			if(isset($attachment->link->image_src)) {
				$image_data = $this->sl->get('PicsTable')->getImageFromUrl($attachment->link->image_src, $user_id, 1);
				$thumb = $image_data['img'];
			}
		} catch (\Exception $e) {}
		$description = isset($attachment->link->description)? $attachment->link->description : null;
		return $this->sl->get('LinksTable')->addLink($user_id, $section, $section_id, $attachment->link->url, $attachment->link->title, $description, $thumb);
	}


	public function addVideoFromVkAttachment($attachment, $user_id, $section, $section_id)
	{
		$video_id = null;
		$access_key = (isset($attachment->video->access_key))? $attachment->video->access_key : null;
		$vk_video = $this->sl->get('api_vk')->getVideo($attachment->video->owner_id, $attachment->video->vid, $access_key);
		if(isset($vk_video[1])) {
			$image = null;
			if(isset($vk_video[1]->image_medium)) $image = $vk_video[1]->image_medium;
			if(isset($vk_video[1]->image)) $image = $vk_video[1]->image;
			if($image) {
				$image_data = $this->sl->get('PicsTable')->getImageFromUrl($image, $user_id);
				$thumb = $image_data['img'];
			} else $thumb = null;
			$title = isset($vk_video[1]->title)? $vk_video[1]->title : null;
			$description = isset($vk_video[1]->description)? $vk_video[1]->description : null;
			$video_id = $this->sl->get('VideosTable')->addVideo($user_id, $section, $section_id,  $vk_video[1]->player, null, $title, $description,  $thumb);
		}
		return $video_id;
	}

	public function saveLikesFromVk($section, $section_id, $vk_wall_id, $vk_post_id, $vk_users_in_db = [], $offset = 0)
	{
		$likes = $this->sl->get('api_vk')->getLikes('post', $vk_wall_id, $vk_post_id, ['offset' => $offset]); 
		// $dump = $this->sl->get('Dump');
		// $dump->createDump($likes, 'likes');
		// $likes = $dump->getDump('likes');
		$fields = [
			'section'
			, 'section_id'
			, 'user_id'
			, 'soc_name'
			, 'soc_post_id'
			, 'soc_user_id'
		];

		$values = [];
		$errors = [];
		if(isset($likes->items)) {
			$l_c = count($likes->items);
			for ($i=0; $i < $l_c; $i++) { 
				try {
					$user_id = (isset($vk_users_in_db[$likes->items[$i]]))? $vk_users_in_db[$likes->items[$i]]['id'] : null;
					$values[] = [
						$section, 
						$section_id,
						$user_id,
						'vk',
						$vk_post_id,
						$likes->items[$i]
					];
				} catch (\Exception $e) {
					$errors[] = $e->getMessage();
				}

			}
		}

		// if(count($values)) 
		// 	$this->sl->get('SocialLikesTable')->insertMultiple($fields, $values, ['ignore' => 1]);
		return $errors;
	}

	
	public function saveAnswersFromVk($question_id, $vk_wall_id, $vk_post_id, $vk_users_in_db = [], $offset = 0)
	{
		$answers = $this->sl->get('api_vk')->getWallComments($vk_wall_id, $vk_post_id, ['offset' => $offset, 'count' => 30]); 

		// $dump = $this->sl->get('Dump');
		// $dump->createDump($answers, 'wall_comments2');
		// $answers = $dump->getDump('wall_comments');
		$sAnswersTable = $this->sl->get('QuestionAnswersTable');
		$fields = [
			 'question_id'
			, 'user'
			, 'text'
			, 'time'
			, 'soc_name'
			, 'soc_post_id'
			, 'soc_answer_id'
			, 'soc_user_id'
			, 'soc_likes'
		];
		$options = [
			'upd_dupl' => [
				'user', 'soc_likes'
			]
		];
		$errors = [];
		foreach ($answers->items as $answer) {
			try {
				$values = [];
				$user_id = (isset($vk_users_in_db[$answer->from_id]))? $vk_users_in_db[$answer->from_id]['id'] : null;
				$likes = isset($answer->likes->count)? $answer->likes->count : null;
				$text = $answer->text;
				if(preg_match("%(?'all'\[(?'id'id\d+)\|(?'name'\S+)\])%i", $text, $matches)) {
					$url = ($user_id)? _ADDRESS_NO_SLASH_.'/id'.$user_id : 'http://vk.com/'.$matches['id'];
					$link = '<a href="'.$url.'" target="_blank">&nbsp;'.$matches['name'].'</a> ';
					$text = str_replace($matches['all'], '', $text);
					$text .= ' <br />'.$link;
				}
				$text = htmlentities($text);
				$text = str_replace("'", '', $text);
				$values[] = [
					$question_id,
					$user_id,
					$text,
					$answer->date,
					'vk',
					$vk_post_id,
					$answer->id,
					$answer->from_id,
					$likes
				];
				$new_answer = $sAnswersTable->insertMultiple($fields, $values, $options);
				if($new_answer && isset($answer->attachments)) {
					$answer_id = $new_answer->getGeneratedValue();
					if($answer_id)	$this->parseVkAttachments($answer->attachments, $user_id, NewsTable::SECTION_ANSWERS, $answer_id);
				}
			} catch (\Exception $e) {
				$errors[] = $e->getMessage();
			}

		}

		if(isset($answers->profiles) && count($answers->profiles))
			$this->saveVkUsers($answers->profiles, $vk_users_in_db);
		return $errors;
	}

	public function saveCommentsFromVk($section, $section_id, $vk_wall_id, $vk_post_id, $vk_users_in_db = [], $offset = 0)
	{
		$wall_comments = $this->sl->get('api_vk')->getWallComments($vk_wall_id, $vk_post_id, ['offset' => $offset]); 
		// $dump = $this->sl->get('Dump');
		// $dump->createDump($wall_comments, 'wall_comments');
		// $wall_comments = $dump->getDump('wall_comments');
		$sCommentTable = $this->sl->get('SocialCommentsTable');
		$fields = [
			'section'
			, 'section_id'
			, 'user_id'
			, 'soc_name'
			, 'soc_post_id'
			, 'soc_com_id'
			, 'soc_user_id'
			, 'soc_likes'
			, 'soc_reply_to_post'
			, 'soc_reply_to_user'
			, 'comment'
			, 'time'
		];
		$options = [
			'upd_dupl' => [
				'user_id','comment', 'soc_likes'
			]
		];
		$errors = [];
		// foreach ($wall_comments->items as $comment) {
		// 	try {
		// 		$values = [];
		// 		$user_id = (isset($vk_users_in_db[$comment->from_id]))? $vk_users_in_db[$comment->from_id]['id'] : null;
		// 		$likes = isset($comment->likes->count)? $comment->likes->count : null;
		// 		$text = $comment->text;
		// 		if(preg_match("%(?'all'\[(?'id'id\d+)\|(?'name'\S+)\])%i", $text, $matches)) {
		// 			$url = ($user_id)? _ADDRESS_NO_SLASH_.'/id'.$user_id : 'http://vk.com/'.$matches['id'];
		// 			$link = '<a href="'.$url.'" target="_blank">&nbsp;'.$matches['name'].'</a> ';
		// 			$text = str_replace($matches['all'], $link, $text);
		// 		}
		// 		$text = htmlentities($text);
		// 		$values[] = [
		// 			$section, 
		// 			$section_id,
		// 			$user_id,
		// 			'vk',
		// 			$vk_post_id,
		// 			$comment->id,
		// 			$comment->from_id,
		// 			$likes,
		// 			null,
		// 			null,
		// 			$text,
		// 			$comment->date
		// 		];

		// 		$new_comment = $sCommentTable->insertMultiple($fields, $values, $options);
		// 		if($new_comment && isset($comment->attachments)) {
		// 			$new_comment = $new_comment->getGeneratedValue();
		// 			$this->parseVkAttachments($comment->attachments, $user_id, NewsTable::SECTION_SOC_COMMENTS, $new_comment);
		// 		}
		// 	} catch (\Exception $e) {
		// 		$errors[] = $e->getMessage();
		// 	}

		// }

		if(isset($wall_comments->profiles) && count($wall_comments->profiles))
			$this->saveVkUsers($wall_comments->profiles, $vk_users_in_db);
		return $errors;
	}

	public function saveVkUsers($vk_users, $vk_users_in_db = [])
	{
		$fields = [
			'soc_name'
			, 'soc_user_id'
			, 'user_id'
			, 'name'
			, 'surname'
			, 'soc_domain'
			, 'soc_page'
			, 'avatar'
		];
		$values = [];
		$errors = [];
		foreach ($vk_users as $vk_user) {
			try {
				$vk_user_id = isset($vk_user->id)? $vk_user->id : (isset($vk_user->uid)? $vk_user->uid : null);
				$soc_domain = isset($vk_user->domain)? $vk_user->domain : (isset($vk_user->screen_name)? $vk_user->screen_name : 'id'.$vk_user_id);
				$user_id = (isset($vk_users_in_db[$vk_user_id]))? $vk_users_in_db[$vk_user_id]['id'] : null;
				$name = (isset($vk_user->first_name))? htmlentities(str_replace(['"', "'"], '', $vk_user->first_name)) : null;
				$surname = (isset($vk_user->surname))? htmlentities(str_replace(['"', "'"], '', $$vk_user->surname)) : null;
				$avatar = '';
				$image_data = $this->sl->get('PicsTable')->getImageFromVkAttachment($vk_user, $user_id);
				$avatar = $image_data['img'];
				$values[] = [
					'vk', 
					$vk_user_id,
					$user_id,
					$name,
					$surname,
					$soc_domain,
					'http://vk.com/'.$soc_domain,
					$avatar
				];
			} catch (\Exception $e) {
				$errors[] = $e->getMessage();
			}

		}
		// if(count($values))
			// $this->sl->get('SocialUsersTable')->insertMultiple($fields, $values, ['upd_dupl' => ['user_id','avatar']]);
		return $errors;
	}


}