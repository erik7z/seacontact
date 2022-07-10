<?php
namespace Application\Model;

class LikesTable extends zAbstractTable
{
	public function __construct()
	{
		$this->init('likes');
	}


	public function like($user_id, $section, $section_id)
	{
		$time = time();
		return $this->query(
			"INSERT INTO `$this->tableName` (user_id, section, section_id, time)
				SELECT * FROM (SELECT '$user_id' user_id, '$section', '$section_id' section_id, '$time') as tmp
				WHERE NOT EXISTS (
				    SELECT id FROM `$this->tableName`
				    WHERE user_id = '$user_id'
				    AND section = '$section'
				    AND section_id = '$section_id'
				) LIMIT 1
			");
	}


	public function unLike($user_id, $section, $section_id)
	{
		return $this->query(
			"DELETE l.* 
				FROM `{$this->tableName}` l
				WHERE l.`user_id` = '$user_id'
				AND l.`section` = '$section'
				AND l.`section_id` = '$section_id'
				");
	}

	public function isLiked($user_id, $section, $section_id)
	{
		$result =  $this->query(
			"SELECT id
			FROM {$this->tableName}
			WHERE `user_id` = '$user_id'
			AND `section` = '$section'
			AND `section_id` = '$section_id'
			LIMIT 1
			")->current();
		return (bool) $result;
	}

	public function getLikes($section, $section_id, $filters = [], $options = [])
	{
		$this->setDefaultOptions($options, ['_limit' => _VIEW_LIKERS_LIMIT_, '_order' => 'like_id', 'up' => 0]);

		$viewer_id = (isset($options['viewer_id'])) ? ($options['viewer_id']) : 0;
		$user_fields = isset($options['_user_fields'])? $options['_user_fields'] : ['name', 'surname', 'full_name', 'company_name', 'login', 'avatar', 'cv_avatar', 'type'];
		$user_fields = array_merge(['id' => 'user_id'], $user_fields);

		$db_fields = isset($options['_likes_fields'])? $options['_likes_fields'] : ['id'];
		$db_fields = array_merge(['id' => 'like_id'], $db_fields);

		$where = "WHERE l.`section` = '$section' AND l.`section_id` = '$section_id'";

		$joins = '';
		$soc_join = '';
		if($this->count) {
			$db_str = ' l.id ';
			$soc_str = ' l.id ';
		} else { 
			$db_fields["'local'"] = 'source';
			$soc_fields['soc_name'] = 'source';
			$soc_u_fields = ['soc_page', 'name', 'surname', 'avatar'];

			$union_u_str = $this->getUnionFieldsStrings(['soc' => ['alias' => 'su', 'fields' => $soc_u_fields], 'db' => ['alias' => 'u', 'fields' => $user_fields]]);
			$union_str = $this->getUnionFieldsStrings(['soc' => ['alias' => 'l', 'fields' => $soc_fields], 'db' => ['alias' => 'l', 'fields' => $db_fields]]);
			$db_str = $union_str['db'].' ,'.$union_u_str['db'];
			$soc_str = $union_str['soc'].' ,'.$union_u_str['soc'];
			$joins = ' LEFT JOIN `user` u ON (u.id = l.user_id) ';
			$soc_join = ' INNER JOIN `social_users` su ON (su.soc_user_id = l.soc_user_id) ';
		}


		$select = 
			"SELECT 
				$db_str
				FROM `{$this->tableName}` l
				$joins
				$where
			UNION 
			SELECT
				$soc_str
				FROM `social_likes` l
				$joins
				$soc_join
				$where
			";
		if($this->count){
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

}