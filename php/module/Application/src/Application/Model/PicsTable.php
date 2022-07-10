<?php
namespace Application\Model;


class PicsTable extends zEmptyTable
{
	
	protected $uploadImageLib;
	protected $uploadsTable;

	public function __construct()
	{
		$this->init('pics');
		$this->uploadImageLib = new \Application\zLibrary\uploadImageCrop;
		$this->uploadsTable =  new UserUploadsTable;
	}


	public function getPics($viewer_id = null, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, [
			'_limit' => 100, 
			'_order' => 'id'
			]);

		$db_fields = isset($options['_pics_fields'])? $options['_pics_fields'] : $this->getStandartFields();
		$db_fields = array_merge(['id' => 'pic_id'], $db_fields);
		$db_fields_string =  $this->arrayToFields($db_fields, 'p.');


		$where = '';
		if(isset($filters['section']) && isset($filters['section_id'])) {
			$where .= ($where == '') ? ' WHERE ' : ' AND ';
			$article_table_name = 'pics-'.$filters['section'];
			$where .= " `id` IN (
				SELECT `pics_id` FROM `$article_table_name` pl 
				WHERE `article_id` = '{$filters['section_id']}'
			) 
			";
			unset($filters['section'], $filters['section_id']);
		} else if(isset($filters['section'])) {
			$where .= ($where == '') ? ' WHERE ' : ' AND ';
			$article_table_name = 'pics-'.$filters['section'];
			$where .= " `id` IN (
				SELECT `pics_id` FROM `$article_table_name` pl 
			) 
			";
			unset($filters['section']);
		} else if(isset($filters['section_id'])) throw new \Application\Exception\Exception("section should be provided", 1);
		

		foreach ($filters as $filter => $value) {
			if($value === null) next($filters);
			$where .= ($where == '') ? ' WHERE ' : ' AND ';
			if ($filter == 'id') {
				$where .= " `id` = '$value' ";
			} else if ($filter == 'pics_ids') {
				if(!is_array($value)) throw new \Application\Exception\Exception("Filter ids should be array", 1);
				$ids = implode(',', $value);
				$where .= " `id` IN ($ids) ";
			} else if ($filter == 'user_id') {
				$where .= " `user` = '$value' ";
			} else {
				$permitted_fields = array_flip($this->getStandartFields(1, 1));
				if(!isset($permitted_fields[$filter])) throw new \Application\Exception\Exception("Filter not recognized", 1);
				$where .= " `$filter` = '$value' ";
			}

		}

		
		if($this->count) {
			$select = " COUNT(*) count";
			$this->limit_string = '';
			$this->offset_string = '';
			$this->order_string = '';
		} else {
			$select = $db_fields_string;
		}

		return $this->query(
			"SELECT $select
			FROM `{$this->tableName}` p
			$where 
			$this->order_string
			$this->limit_string
			$this->offset_string
			");
	}


	public function addPic($user_id, $img_src, $thumb_src, $alt = '', $place = '')
	{
		return $this->insert(array(
			'user' => $user_id,
			'img' => $img_src,
			'thumb' => $thumb_src,
			'alt' => $alt,
			'place' => $place,
			'time' => time()
			));
	}




	public function getUserPics($user_id)
	{
		if(is_bool($user_id) || is_null($user_id)) throw new \Application\Exception\Exception("No user id supplied", 000);
		return $this->query(
			"SELECT * FROM `pics`
				WHERE `user` = $user_id
				ORDER BY `time` DESC
			");
	}

	public function getArticlePics($section, $article_id)
	{
		$article_table_name = 'pics-'.$section;
		$article_id = (int)$article_id;
		$result = $this->query(
			"SELECT * FROM `pics` 
				WHERE `pics`.`id` IN (
					SELECT `pics_id` FROM `$article_table_name` pl 
					WHERE `article_id` = $article_id
				)
			")->toArray();
		return $result;
	}

	public function addArticlePic($image, $user_id, $section, $article_id){
		$image['user'] = $user_id;
		$image['time'] = time();
		$image['id'] = $this->insert($image);
		$this->attachPicsToArticle($image['id'], $section, $article_id);
		return $image['id'];
	}

	// public function attachPicToArticle($pic_id, $section, $article_id)
	// {
	// 	$article_table_name = 'pics-'.$section;
	// 	return $this->query(
	// 		"INSERT INTO `$article_table_name` (pics_id, article_id)
	// 			VALUES ('{$image['id']}', '{$article_id}')
	// 		");
	// }

	public function attachPicsToArticle($pics_ids, $section, $article_id)
	{
		if(!is_array($pics_ids)) $pics_ids = [$pics_ids];

		$article_table_name = 'pics-'.$section;
		$values_str = '';
		$c = count($pics_ids);
		for ($i=0; $i < $c; $i++) { 
			if($i > 0) $values_str .= ', ';
			$values_str .= " ('{$pics_ids[$i]}', '{$article_id}') ";
		}

		return $this->query(
			"INSERT IGNORE INTO `$article_table_name` (pics_id, article_id)
				VALUES $values_str
			");
	}

	public function removePicsFromArticle($pics_ids = null, $section, $article_id)
	{
		if(!is_array($pics_ids)) $pics_ids = [$pics_ids];

		$article_table_name = 'pics-'.$section;
		$pics_ids_string = '';
		if(count($pics_ids)) {
			$values_str = implode(',', $pics_ids);
			$pics_ids_string = " AND `pics_id` IN ($values_str) ";
		}

		return $this->query(
			"DELETE p.* 
				FROM `{$article_table_name}` p 
				WHERE `article_id` = '{$article_id}'
				$pics_ids_string
			");
	}


	public function getImageFromVkAttachment($vk_attachment, $user_id, $exc = 0) {
		$largest_pic = null;
		if(isset($vk_attachment->src_xxbig)) $largest_pic = $vk_attachment->src_xxbig;
		elseif(isset($vk_attachment->src_xbig)) $largest_pic = $vk_attachment->src_xbig;
		elseif(isset($vk_attachment->src_big)) $largest_pic = $vk_attachment->src_big;
		elseif(isset($vk_attachment->src)) $largest_pic = $vk_attachment->src;
		elseif(isset($vk_attachment->photo_big)) $largest_pic = $vk_attachment->photo_big;
		elseif(isset($vk_attachment->photo_medium)) $largest_pic = $vk_attachment->photo_medium;
		elseif(isset($vk_attachment->photo)) $largest_pic = $vk_attachment->photo;
		else {
			$sizes = [];
			foreach ($vk_attachment as $key => $value) {
				if(strpos($key, 'photo_') !== false) {
					$sizes[] = (int) str_replace('photo_', '', $key);
				}
			}
			if(count($sizes)) {
				sort($sizes); 
				$pic_key = 'photo_'.array_pop($sizes);
				$largest_pic = $vk_attachment->$pic_key;
			}
		}
		if(!$largest_pic && $exc) throw new \Application\Exception\Exception("Image not found", 1);
		$image_source = $this->uploadImageLib->__loadJpeg($largest_pic, $exc);
		return $this->uploadImageLib->getImageFromSource($image_source,true, $user_id);
	}

	public function getImageFromUrl($url, $user_id, $exc = 0) {
		$image_source = $this->uploadImageLib->__loadJpeg($url, $exc);
		return $this->uploadImageLib->getImageFromSource($image_source,true, $user_id);
	}


	public function saveArticlePics($data, $section, $article_id, $user_id)
	{
		if(!$user_id) throw new \Application\Exception\Exception("user id should be supplied to save Article pics", 1);
		$article_table_name = 'pics-'.$section;
		
		if(isset($data['old_pics'])) {
			foreach ($data['old_pics'] as $old_pic => $value) {
				$old_pic = (int)$old_pic;

				if($value == 'delete') $this->delete($old_pic, ['delete_from_article_table' => true, 'article_table_name' => $article_table_name, 'user_id' => $user_id]);
			}
		}

		$uploaded_images = array();
		foreach ($data['pics'] as $key => $postImage) {
			$uploaded_image = $this->uploadAndSave($postImage, $user_id);
		if($uploaded_image['id'] == false) continue;
			$this->query(
				"INSERT INTO `$article_table_name` (pics_id, article_id)
					VALUES ('{$uploaded_image['id']}', '{$article_id}')
				"
				);
			$uploaded_images[] = $uploaded_image;
		}

		return $uploaded_images;
	}

	public function uploadAndSave(array $postImage, $user_id)
	{
		$image = $this->uploadImageLib->upload($postImage,true, $user_id);
		if(!$image) return false;
		$image['user'] = $user_id;
		$image['time'] = time();
		$image['id'] = $this->insert($image);
		return $image;
	}

	public function delete($pic_id, $options = array())
	{
		$delete_from_article_table = (isset($options['delete_from_article_table'])) ? $options['delete_from_article_table'] : false;
		$article_table_name = (isset($options['article_table_name'])) ? $options['article_table_name'] : null;
		if(!$options['user_id']) throw new \Application\Exception\Exception("User id should be provided", 1);
		$user_id = $options['user_id'];

		$pic = $this->get($pic_id);

		$this->uploadsTable->deleteUpload($pic['img'], 'image');
		
		parent::delete($pic_id);
		if($delete_from_article_table) $this->getTable($article_table_name)->delete($pic_id, 'pics_id');
		
		return true;
	}


}
