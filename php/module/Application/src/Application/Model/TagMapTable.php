<?php
namespace Application\Model;

class TagMapTable extends zAbstractTable
{
	public $sections = [
		\Application\Model\NewsTable::SECTION_VACANCY
		, \Application\Model\NewsTable::SECTION_LOGBOOK
		, \Application\Model\NewsTable::SECTION_QUESTIONS
	];

	public function __construct()
	{
		$this->init('tagmap');
	}

	// returns some class CONSTANT
	protected function con($const)
	{
		return constant($const);
	}

	public function getTagsMap($user_id = null, $filters = null, $options = [] )
	{
		$this->setDefaultOptions($options, ['_limit' => 36, '_order' => 'count']);
		$more_than = isset($options['more_than'])? $options['more_than'] : null;

		$where = '';
		$having = '';
		if($more_than) $having = " HAVING count > $more_than ";
		
		if ($filters) {
			foreach ($filters as $filter => $value) {
				if($value){
					$where .= ($where == '') ? ' WHERE ' : ' AND ';
					if ($filter == 'section') {
						if(is_array($value)) {
							for ($i=0; $i < count($value); $i++) { 
								if(!in_array($value[$i], $this->sections)) throw new \Application\Exception\Exception("Filter not recognised", 1);
								if($i > 0) $where .= ' OR ';
								$where .= " tm.section = '{$value[$i]}' ";
							}
						} else {
							if(!in_array($value, $this->sections)) throw new \Application\Exception\Exception("Filter not recognised", 1);
							$where .= " tm.section = '{$value}' ";
						}
						
					} else if ($filter == 'query') {
						$where .= " t.name LIKE '%$value%' ";
					} else if ($filter == 'section_id') {
						$where .= " tm.section_id = '$value' ";
					} else throw new \Application\Exception\Exception("Filter not recognised", 1);
				}
			}		
		}

		$select = "SELECT 
				t.id, t.name, t.description, tm.section, tm.section_id, COUNT(tm.tag_id) count
				FROM  `tagmap` tm
				INNER JOIN `tags` t ON (t.id = tm.tag_id)
				$where
				GROUP BY tm.tag_id
				$having
			";

		if ($this->count) {
			$query = "
						SELECT COUNT(*) count
						FROM (
							$select
							) x
						";
		} else {
			$query = "
				$select
				$this->order_string
				$this->limit_string $this->offset_string
			";
		}
		return $this->query($query);
	}

	public function addTags($section, $section_id, $tags = [], $delete_old_tags = 1) {
		if(!count($tags)) return false;
		$tags_string = '';$tags_map_string = ''; $c = 0;
		foreach ($tags as $tag) {
			if($c > 0) {
				$tags_map_string .= ', ';
				$tags_string .= ', ';
			}
			$tags_map_string .= "'$tag'";

			$tags_string .= '( ';
			$tags_string .= "'$tag'";
			$tags_string .= ') ';
			$c++;
		}

		//adding tags
		$this->query(
			"INSERT IGNORE INTO tags (name)
				VALUES $tags_string;
			");

		//adding tags connections
		$this->query(
			"INSERT IGNORE INTO tagmap (section, section_id, tag_id)
				SELECT '$section', '$section_id', t.id FROM tags t WHERE t.name IN ($tags_map_string);
			");

		if($delete_old_tags) {
			//deleting obsolete tag connections
			$this->query(
				"DELETE FROM tagmap
					WHERE `section` = '$section' AND `section_id` = '$section_id'
					AND tag_id NOT IN (
					        SELECT t.id FROM tags t
					        WHERE t.name IN ($tags_map_string)
					    )
				");
		}
		return 1;
	}

	public function deleteTag($tag)
	{
		$this->query(
			"DELETE FROM `tags` t
				WHERE t.name = '$tag'
			");
	}

	public function deleteArticleTags($section, $section_id)
	{
		$this->query(
			"DELETE FROM tagmap
				WHERE `section` = '$section' AND `section_id` = '$section_id'
			");
	}


}