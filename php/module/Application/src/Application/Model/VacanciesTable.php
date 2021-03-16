<?php
namespace Application\Model;

class VacanciesTable extends zEmptyTable
{

	public function __construct()
	{
		$this->init('vacancies');
	}

	public function updateVacancies()
	{
		$this->init('vacancies', new \Zend\Db\TableGateway\Feature\RowGatewayFeature('id'));
		$vacancies = $this->tableGateway->select();
		$vacancies->buffer();
		// foreach ($vacancies as $vacancy) {
		// 	$vacancy->contract_length = $vacancy->contract_length * 30;
		// 	$vacancy->save();
		// }
		d($vacancies->toArray());
	}

	public function delete($vacancy_id, $somevar = 'id')
	{
		// Revise delete procedure!!!!

		$this->query(
			"DELETE vacancies 
			-- , vacancy_subscribers  
			FROM vacancies  
			-- INNER JOIN vacancy_subscribers  
			WHERE vacancies.id = $vacancy_id 
			-- AND vacancies.id = vacancy_subscribers.vacancy_id
			"
			);

		$this->query(
			"DELETE vacancy_subscribers 
			FROM vacancy_subscribers  
			WHERE vacancy_subscribers.vacancy_id = $vacancy_id 
			"
			);

		
		$pics_list = $this->getTable('PicsTable')->getArticlePics('vacancy', $vacancy_id);
		foreach ($pics_list as $pic) {

			$this->getTable('PicsTable')->delete($pic['id'], ['delete_from_article_table' => false, 'article_table_name' => false, 'user_id' => 'force_delete']);
		}
	}

	public function getNextPubTime($user_id, $delay = 10800)
	{
		$time = time();
		$result = $this->query(
			"SELECT v.time
				FROM `vacancies` v
				WHERE v.user = '$user_id'
				AND v.time > '$time'
				ORDER BY v.time DESC
				LIMIT 1
			"
			)->current();
		if(is_object($result)) return $result['time'] + $delay;
		return time() + $delay;
	}

	private function getActiveVacanciesString()
	{
		return 'WHERE v.time <= '.time().' AND v.active = 1 ';
	}

	public function getAllVacancies($viewer_id = 0, $filters = [], $options = []) 
	{
		$this->setDefaultOptions($options, ['_limit' => 200, '_order' => 'time']);

		$user_fields = isset($options['_company_fields'])? $options['_company_fields'] : ['name', 'surname', 'full_name', 'email','contact_email', 'contact_phone', 'contact_mobile', 'contact_phone_2', 'contact_mobile_2', 'info_website', 'user_notes', 'last_activity', 'reg_date', 'company_name', 'company_description', 'company_license', 'home_country','home_city', 'home_address', 'login', 'avatar', 'cv_avatar', 'type', 'role'];
		$user_fields = array_merge(['id' => 'company_id'], $user_fields);
		$user_fields_string =  $this->arrayToFields($user_fields,'u.');
		$for_company_fields_string =  $this->arrayToFields(['company_name'],'fc.', 'for_company_');

		$db_fields = isset($options['_vacancy_fields'])? $options['_vacancy_fields'] : $this->getStandartFields();
		$db_fields = array_merge(['id' => 'vacancy_id'], $db_fields);
		$db_fields_string =  $this->arrayToFields($db_fields, 'v.');

		$stats_fields = isset($options['_stats_fields'])? $options['_stats_fields'] : $this->getStatsFields();
		$stats_fields = array_flip($stats_fields);

		$last_week = time() - 604800;
		$where = '';
		$predicat = 0;

		$request = (isset($options['request']))? $options['request'] : 'all';
		$show_hidden = (isset($options['show_hidden']))? $options['show_hidden'] : false;
		$count_candidates = (isset($options['count_candidates']))? $options['count_candidates'] : false;

		if(!$show_hidden) $where .= $this->getActiveVacanciesString();

		$seacontact_id = _COMPANY_ID_;
		$oceancontract_id = _CREWING_ID_;

		$candidate_vacancies_join = '';
		$candidate_vacancy_fields_string = '';
		foreach ($filters as $filter => $value) {
			if($value == '' || $value == null || $value == 0 || $value == false) next($filters);
			if($value) $where .= ($where == '') ? ' WHERE ' : ' AND ';

			if ($filter == 'ship_type') {
				$where .= " `ship_type` LIKE '$value'";
			} else if ($filter == 'id') {
				$where .= " v.`id` = '$value' ";
			} else if ($filter == 'company_id') {
				$where .= " `user` = $value";
			} else if ($filter == 'for_company') {
				$where .= " `for_company` = $value";
			} else if ($filter == 'candidate_id') {
				$where .= " `user` > 0";
				$candidate_vacancy_fields_string = ', vc.admin_id, vc.status candidate_status, vc.time candidate_assigned_date';
				$candidate_vacancies_join = "INNER JOIN `vacancy_candidates` vc ON (vc.vacancy_id = v.id AND vc.user_id = $value)";
			} else if ($filter == 'active_companies') {
				if($value == 1 ) $where .= " `user` IN (SELECT id FROM `user` WHERE `type` = 'company' AND `reg_date` > 0) AND `user` != $seacontact_id AND `user` != $oceancontract_id ";
			} else if ($filter == 'passive_companies') {
				if($value == 1 ) $where .= " `user` IN (SELECT id FROM `user` WHERE `type` = 'company' AND `reg_date` = 0) ";
			} else if ($filter == 'rank') {
				$where .= " `rank` LIKE '$value'";
			} else if ($filter == 'minimum_salary') {
				$where .= " `salary` > '$value'";
			} else if ($filter == 'max_contract') {
				$where .= " `contract_length` < '$value'";
			} else if ($filter == 'only_new') {
				if($value == true ) $where .= " ( v.date_join > $last_week OR v.date_join = 0) ";
			} 
		}

		if($this->count) {
			$fields_string = ' COUNT(*) count ';
			$join_string = '';
			$this->order_string = '';
			$this->limit_string = '';
			$this->offset_string = '';
		} else {

			$fields_string = $db_fields_string;
			$fields_string .= ' ,'. $user_fields_string;
			$fields_string .= ' ,'. $for_company_fields_string;
			$fields_string .= $candidate_vacancy_fields_string;

			if(isset($stats_fields['views'])) $fields_string .= ", {$this->getViewsCountSelect('vacancy','v')} views ";
			if(isset($stats_fields['subscribers'])) $fields_string .= ", ({$this->getVacanciesSubsCountQuery()}) subscribers ";
			if(isset($stats_fields['reports'])) $fields_string .= ", ({$this->getVacanciesRepsCountQuery()}) reports ";
			if(isset($stats_fields['likes'])) $fields_string .= ", {$this->getLikesCountSelect('vacancy','v')} likes ";
			if(isset($stats_fields['likers'])) $fields_string .= ", {$this->getLikersSelect('vacancy','v')} likers ";
			if(isset($stats_fields['like_status'])) $fields_string .= ", {$this->getLikeStatusSelect($viewer_id, 'vacancy','v')} like_status ";
			if(isset($stats_fields['total_comments'])) $fields_string .= ", {$this->getCommentsCountSelect('vacancy','v')} total_comments";
			if(isset($stats_fields['pics'])) $fields_string .= ", {$this->getPicsSelect('', 'vacancy','v')} pics ";

			if($count_candidates) $fields_string .= " , ({$this->getVacanciesCandsCountQuery()}) candidates ";
			if($viewer_id) $fields_string .= " , (SELECT vs.id FROM `vacancy_subscribers` vs WHERE vs.user_id = '$viewer_id' AND vs.vacancy_id = v.id) subscribe_status ";
			if($viewer_id) $fields_string .= " , (SELECT vr.id FROM `vacancy_reports` vr WHERE vr.user_id = '$viewer_id' AND vr.vacancy_id = v.id) report_status ";
	
			$join_string = "LEFT JOIN user u ON (v.user = u.id) ";
			$join_string .= "LEFT JOIN user fc ON (v.for_company = fc.id) ";
			$join_string .= $candidate_vacancies_join;
		}
		
		$this->query("SET SESSION group_concat_max_len = 1000000;");
		$result = $this->query(
			"SELECT $fields_string
				FROM `vacancies` v 
				$join_string
				$where
				$this->order_string
				$this->limit_string $this->offset_string
			");
		if($this->buffer) $result->buffer();
		return $result;
	}

	public function getVacancy($vacancy_id, $user_id = null, $company_fields = null)
	{
		$vacancy_id = (int)$vacancy_id;
		if(!$vacancy_id) throw new \Application\Exception\Exception("Vacancy id not provided", 1);
		$subscribe_status_req = ", '0' subscribe_status";
		if($user_id) {
			$subscribe_status_req = " ,(SELECT vs.id FROM `vacancy_subscribers` vs WHERE vs.user_id = '$user_id' AND vs.vacancy_id = v.id) subscribe_status";
		}

		$this->query("SET SESSION group_concat_max_len = 1000000;");
		$vacancy = $this->query(
			"SELECT {$this->getVacanciesFields()}
					, {$this->getCompanyFields($company_fields)}
					, {$this->getViewsCountSelect('vacancy','v')} views
					, ({$this->getVacanciesSubsCountQuery()}) subscribers
					, ({$this->getVacanciesRepsCountQuery()}) reports
					, {$this->getPicsSelect('', 'vacancy','v')} pics
					, {$this->getLikesCountSelect('vacancy','v')} likes
					, {$this->getCommentsCountSelect('vacancy','v')} total_comments
					, {$this->getCommentsSelect($user_id,'vacancy','v')} comments_list
					, {$this->getLikersSelect('vacancy','v')} likers
					, {$this->getLikeStatusSelect($user_id, 'vacancy','v')} like_status
					$subscribe_status_req
					
				FROM `vacancies` v
				LEFT JOIN user u ON (v.user = u.id)
				WHERE v.id = '$vacancy_id'
				LIMIT 1
			"
			)->current();


		if(!$vacancy) throw new \Application\Exception\Exception("Vacancy with such id not found", 1);
		// $vacancy['pics'] = $this->getTable('PicsTable')->getArticlePics('vacancy', $vacancy_id);

		$vacancy['text'] = zbr2nl(htmlspecialchars_decode($vacancy['text']));
		return $vacancy;
	}
	
	public function saveVacancy($user_id, array $vacancy)
	{
		$user_id = (int)$user_id;

		if(!$user_id) throw new \Application\Exception\Exception("User id not provided in vacancy data", 1);
		
		$vacancy['date_join'] = isset($vacancy['date_join'])? zconvertFormDate($vacancy['date_join']) : 0;
		$vacancy['contract_length'] = $vacancy['contract_length'] * $vacancy['contract_unit'];
		$vacancy['user'] = $user_id;
		$vacancy_id = $this->save($vacancy);
		return $vacancy_id;		
	}



	// to be withdrawn.  for now using only on landing
	public function getVacanciesFleetType($show_hidden = false, $limit = 20)
	{
		$where = '';
		if(!$show_hidden) {
			$where = $this->getActiveVacanciesString();
		}

		return $this->query(
			"SELECT v.ship_type
				FROM `vacancies` v
				$where
				GROUP BY v.ship_type
				LIMIT $limit
			"
			);		
	}

	public function getTagString($data)
	{
		$title = zstripForHashTag($data['title']);
		$rank = zstripForHashTag($data['rank']);
		$ship_type = zstripForHashTag($data['ship_type']);
		$tags = implode(',', [$title, $rank, $ship_type]);
		return $tags;
	}


	public function getVacanciesSubsCountQuery()
	{
		return "SELECT COUNT(DISTINCT `user_id`)
						FROM `vacancy_subscribers`
						WHERE `vacancy_id` = `v`.`id`
						";
	}

	public function getVacanciesRepsCountQuery()
	{
		return "SELECT COUNT(DISTINCT `user_id`)
						FROM `vacancy_reports`
						WHERE `vacancy_id` = `v`.`id`
						";
	}

	public function getVacanciesCandsCountQuery()
	{
		return "SELECT COUNT(DISTINCT `user_id`)
						FROM `vacancy_candidates`
						WHERE `vacancy_id` = `v`.`id`
						";
	}


}