<?php
namespace Admin\Model\Hydrators;


class MaritimeZone 
{


	public function hydrate($user_info)
	{
		$hydrated = array();
		if(isset($user_info['user']['avatar'])) {
			$user_info['user']['cv_avatar'] = pathinfo($user_info['user']['avatar'], PATHINFO_BASENAME);
		}


		if(isset($user_info['user']['minimum_salary'])) {
			$user_info['user']['minimum_salary'] = (int) mb_substr($user_info['user']['minimum_salary'], 0, 5);
		}

		if(isset($user_info['user']['full_name'])) $user_info['user']['full_name'] = trim(mb_substr($user_info['user']['full_name'], 0, 86));
		if(isset($user_info['user']['home_city'])) $user_info['user']['home_city'] = trim(mb_substr($user_info['user']['home_city'], 0, 48));

		$user_info['user']['in_db_date'] = time();
		$user_info['user']['info_source'] = 'web parsing - maritime-zone';

		if(isset($user_info['user_experience'])) {
			foreach($user_info['user_experience'] as $key => $contract){
				preg_match('/\d+/',$contract['dwt'],$matches);
				$user_info['user_experience'][$key]['dwt'] = (isset($matches[0]))? (int) trim(mb_substr($matches[0], 0, 8)) : null;
				if(isset($contract['bhp'])) {
					preg_match('/\d+/',$contract['bhp'],$matches); 
					$user_info['user_experience'][$key]['bhp'] = (isset($matches[0]))? (int) $matches[0] : null;
				}
				
				$user_info['user_experience'][$key]['ship_name'] = trim(mb_substr($user_info['user_experience'][$key]['ship_name'], 0, 32));
				if(isset($user_info['user_experience'][$key]['ship_type'])) 
				$user_info['user_experience'][$key]['ship_type'] = trim(mb_substr($user_info['user_experience'][$key]['ship_type'], 0, 32));
				$user_info['user_experience'][$key]['flag'] = trim(mb_substr($user_info['user_experience'][$key]['flag'], 0, 32));
				$user_info['user_experience'][$key]['company'] = trim(mb_substr($user_info['user_experience'][$key]['company'], 0, 99));
			}
		}

		if(isset($user_info['user_documents'])) {
			foreach($user_info['user_documents'] as $key => $doc){
				if(isset($user_info['user_documents'][$key]['date_from'])) $user_info['user_documents'][$key]['issue_date'] = $user_info['user_documents'][$key]['date_from'];
				if(isset($user_info['user_documents'][$key]['date_to'])) $user_info['user_documents'][$key]['expiry_date'] = $user_info['user_documents'][$key]['date_to'];
				
				
			}
		}

		return $user_info;
	}





}
