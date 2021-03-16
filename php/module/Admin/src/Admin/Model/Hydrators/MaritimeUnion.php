<?php
namespace Admin\Model\Hydrators;


class MaritimeUnion 
{


	public function hydrate($user_info)
	{
		$hydrated = array();
		if($user_info['user']['avatar']) {
			$user_info['user']['cv_avatar'] = pathinfo($user_info['user']['avatar'], PATHINFO_BASENAME);
		}		
		if(isset($user_info['user_notes'])) {
			$user_info['user']['user_notes'] = $user_info['user_notes'];
			unset($user_info['user_notes']);
		}
		$user_info['user']['in_db_date'] = time();
		$user_info['user']['cv_last_update'] = time();					
		$user_info['user']['info_source'] = 'web parsing - maritime-union';
		

		if(isset($user_info['user_documents'])) {
			foreach ($user_info['user_documents'] as $doc_id => $doc) {
				$exp_date = strtotime($doc['expiry_date']);
				$user_info['user_documents'][$doc_id]['expiry_date'] = ($exp_date > 0) ? $exp_date : null;
			}
		}

		if(isset($user_info['user_experience'])) {
			foreach ($user_info['user_experience'] as $exp_id => $exp) {
				$user_info['user_experience'][$exp_id]['dwt'] = (int)$exp['dwt'];
				$user_info['user_experience'][$exp_id]['bhp'] = (int)$exp['bhp'];
				$user_info['user_experience'][$exp_id]['grt'] = (int)$exp['grt'];
			}
		}

		return $user_info;
	}





}
