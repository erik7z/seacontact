<?php
namespace Application\Model;


class ArticleLinksTable extends zEmptyTable
{

	public function __construct()
	{
		$this->init('article_links');
	}

	public function addLink($user_id, $section, $section_id, $url, $title = null, $description = null, $thumb = null)
	{
		return $this->insert(array(
			'user_id' => $user_id,
			'section' => $section,
			'section_id' => $section_id,
			'url' => $url,
			'title' => $title,
			'description' => $description,
			'thumb' => $thumb,
			'time' => time()
			));
	}


	public function saveArticleLinks($data, $section, $section_id, $user_id)
	{
		if(isset($data['old_links'])) {
			foreach ($data['old_links'] as $id => $value) {
				$id = (int)$id;
				if($value == 'delete') $this->delete($id, $user_id);
			}
		}
	}

	public function delete($id, $field = 'id')
	{
		$item = $this->get($id);
		$this->sl()->get('UploadsTable')->deleteUpload($item['thumb'], 'image');
		parent::delete($id);
		return true;
	}

}
