<?php
namespace Application\Model;


class ArticleVideosTable extends zEmptyTable
{

	public function __construct()
	{
		$this->init('article_videos');
	}

	public function addVideo($user_id, $section, $section_id, $embed_url = '', $url = '', $title = '', $description = '', $thumb = '')
	{
		$this->save(array(
			'user_id' => $user_id,
			'section' => $section,
			'section_id' => $section_id,
			'embed_url' => $embed_url,
			'url' => $url,
			'title' => $title,
			'description' => $description,
			'thumb' => $thumb,
			'time' => time()
		));
	}

	public function saveArticleVideos($data, $section, $section_id, $user_id)
	{
		if(isset($data['old_videos'])) {
			foreach ($data['old_videos'] as $id => $value) {
				$id = (int)$id;
				if($value == 'delete') $this->delete($id);
			}
		}
	}
}
