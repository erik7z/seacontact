<?php
namespace Admin\Model\Hydrators;


class Ukrcrewing 
{


	public function hydrate($user_info)
	{
		$hydrated = array();
		if(isset($user_info['user']['avatar'])) {
			$user_info['user']['cv_avatar'] = pathinfo($user_info['user']['avatar'], PATHINFO_BASENAME);
		}		
		if(isset($user_info['user_notes'])) {
			$user_info['user']['user_notes'] = $user_info['user_notes'];
			unset($user_info['user_notes']);
		}

		if(isset($user_info['user']['visa_shenghen_exp'])) {
			$user_info['user']['visa_shenghen_exp'] = strtotime($user_info['user']['visa_shenghen_exp']);
		}

		if(isset($user_info['user']['minimum_salary'])) {
			$user_info['user']['minimum_salary'] = (int) substr($user_info['user']['minimum_salary'], 0, 5);
		}

		if(isset($user_info['user']['full_name'])) {
			$user_info['user']['full_name'] = trim(substr($user_info['user']['full_name'], 0, 86));
		}

		if(isset($user_info['user']['visa_usa_exp'])) {
			$user_info['user']['visa_usa_exp'] = strtotime($user_info['user']['visa_usa_exp']);
		}

		$user_info['user']['in_db_date'] = time();
		$user_info['user']['info_source'] = 'web parsing - ukrcrewing';

		if(isset($user_info['user_experience'])) {
			foreach($user_info['user_experience'] as $key => $contract){
				preg_match('/\d+/',$contract['dwt'],$matches);
				$user_info['user_experience'][$key]['dwt'] = (isset($matches[0]))? (int) $matches[0] : null;
				preg_match('/\d+/',$contract['bhp'],$matches); 
				$user_info['user_experience'][$key]['bhp'] = (isset($matches[0]))? (int) $matches[0] : null;
				$user_info['user_experience'][$key]['ship_name'] = trim(substr($user_info['user_experience'][$key]['ship_name'], 0, 64));
				$user_info['user_experience'][$key]['ship_type'] = $user_info['user_experience'][$key]['type'];
			}
		}

		// if(isset($user_info['user_documents'])) {
		// 	d($user_info['user_documents']);
		// 	foreach($user_info['user_documents'] as $key => $doc){
		// 		if($doc['type'] == 1) {
		// 			$user_info['user_documents'][$key]['expiry_date'] = strtotime(str_replace('.', '-',$doc['expiry_date']));
		// 		}
		// 	}
		// }

		return $user_info;
	}





}
