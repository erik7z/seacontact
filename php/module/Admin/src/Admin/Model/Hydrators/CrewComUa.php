<?php
namespace Admin\Model\Hydrators;


class CrewComUa 
{


	public function hydrate($user_info)
	{
		$hydrated = array();

		$user['reference_id'] = $user_info['user_id'];
		$user['reference_url'] = $user_info['URI'];

		$user['cv_avatar'] = (isset($user_info['PERSONAL_INFO']['avatar']))?
		$user['reference_id'].'.jpg' : '';

		$user['full_name'] = (isset($user_info['PERSONAL_INFO']['FULL NAME']))?
		$user_info['PERSONAL_INFO']['FULL NAME'] : '';

		$user['desired_rank'] = (isset($user_info['PERSONAL_INFO']['RANK']))?
		$user_info['PERSONAL_INFO']['RANK'] : '';

		$user['home_address'] = (isset($user_info['PERSONAL_INFO']['HOME ADRESS']))?
		$user_info['PERSONAL_INFO']['HOME ADRESS'] : '';

		$user['contact_phone'] = (isset($user_info['PERSONAL_INFO']['PHONE']))?
		$user_info['PERSONAL_INFO']['PHONE'] : '';

		$user['contact_fax'] = (isset($user_info['PERSONAL_INFO']['FAX']))?
		$user_info['PERSONAL_INFO']['FAX'] : '';

		$user['contact_mobile'] = (isset($user_info['PERSONAL_INFO']['MOBILE PHONE']))?
		$user_info['PERSONAL_INFO']['MOBILE PHONE'] : '';

		$user['email'] = (isset($user_info['PERSONAL_INFO']['E-MAIL']))?
		$user_info['PERSONAL_INFO']['E-MAIL'] : '';

		$user['info_website'] = (isset($user_info['PERSONAL_INFO']['URL']))? 
		$user_info['PERSONAL_INFO']['URL'] : '';
		$user['nationality'] = (isset($user_info['PERSONAL_INFO']['NATIONALITY']))?
		$user_info['PERSONAL_INFO']['NATIONALITY'] : '';

		$user['dob'] = (isset($user_info['PERSONAL_INFO']['DATE OF BIRTH']))?
		strtotime(str_replace('.', '/',$user_info['PERSONAL_INFO']['DATE OF BIRTH'])) : 0;

		$user['place_of_birth'] = (isset($user_info['PERSONAL_INFO']['PLACE OF BIRTH']))?
		$user_info['PERSONAL_INFO']['PLACE OF BIRTH'] : '';

		if(isset($user_info['PERSONAL_INFO']['SPOKEN ENGLISH (0-10)'])) {
			$user['english_knowledge'] = ceil(($user_info['PERSONAL_INFO']['SPOKEN ENGLISH (0-10)']) / 2);
		} else $user['english_knowledge'] = 0;

		$user['current_location'] = (isset($user_info['PERSONAL_INFO']['IN RESERVE / ON BOARD']))?
		$user_info['PERSONAL_INFO']['IN RESERVE / ON BOARD'] : '';

		$user['marital_status'] = (isset($user_info['PERSONAL_INFO']['MARITAL STATUS']))?
		$user_info['PERSONAL_INFO']['MARITAL STATUS'] : '';

		$user['childrens'] = (isset($user_info['PERSONAL_INFO']['NUMBER OF CHILDREN']))?
		$user_info['PERSONAL_INFO']['NUMBER OF CHILDREN'] : 0;

		if(isset($user_info['USER_NOTES'])) {
			$user['user_notes'] = $user_info['USER_NOTES'];
		}

		if(isset($user_info['USER_FILES'])) {
			$user['user_files'] = $user['reference_id'].'.zip';
		}

		$user['in_db_date'] = time();
		$user['info_source'] = 'web parsing - 1';

		$hydrated['user'] = $user;

		if(isset($user_info['EDUCATION'])) {
			if(is_array($user_info['EDUCATION'])){
				foreach ($user_info['EDUCATION'] as $key => $value) {
					$education[$key]['name'] = $value['School/Academy name'];
					$education[$key]['country'] = $value['Country'];
					$education[$key]['from'] = strtotime(str_replace('.', '/',$value['From']));
					$education[$key]['to'] = strtotime(str_replace('.', '/',$value['To']));
					$education[$key]['diploma'] = $value['Diploma'];
				}
				$hydrated['user_education'] = $education;
			}
		}

		$documents = array();
		if(isset($user_info['CERTIFICATES AND OTHER DOCUMENTS'])) {
			if(is_array($user_info['CERTIFICATES AND OTHER DOCUMENTS'])) {
				foreach ($user_info['CERTIFICATES AND OTHER DOCUMENTS'] as $key => $value) {
					$documents[$key]['type'] = 2;
					$documents[$key]['title'] = $value['TITLE'];
					$documents[$key]['number'] = $value['NUMBER'];
					$documents[$key]['issue_date'] = strtotime(str_replace('.', '/',$value['ISSUED']));
					$documents[$key]['expiry_date'] = strtotime(str_replace('.', '/',$value['VALID']));
					$documents[$key]['issue_place'] = $value['PLACE OF ISSUE'];
				}
			}
		}

		if(isset($user_info['CERTIFICATES AND OTHER DOCUMENTS'])) {
			if(is_array($user_info['PASSPORTS'])) {
				foreach ($user_info['PASSPORTS'] as $key => $value) {
					$i = count($documents);
					$i++;
					$documents[$i]['type'] = 1;
					$documents[$i]['title'] = $value['TITLE'];
					$documents[$i]['number'] = $value['NUMBER'];
					$documents[$i]['expiry_date'] = strtotime(str_replace('.', '/', $value['VALID']));
					$documents[$i]['issue_place'] = $value['PLACE OF ISSUE'];
				}				
			}
		}

		if(!empty($documents)) $hydrated['user_documents'] = $documents;

		if(isset($user_info['PREVIOUS SEA EXPERIENCE (last 5 years)'])) {
			if(is_array($user_info['PREVIOUS SEA EXPERIENCE (last 5 years)'])) {
				foreach ($user_info['PREVIOUS SEA EXPERIENCE (last 5 years)'] as $key => $value) {
					$contracts[$key]['date_from'] = strtotime(str_replace('.', '/', $value['FROM']));
					$contracts[$key]['date_to'] = strtotime(str_replace('.', '/', $value['TO']));
					$contracts[$key]['ship_name'] = $value['M/V NAME'];
					$contracts[$key]['flag'] = $value['FLAG'];
					$contracts[$key]['dwt'] = $value['DWT'];
					$contracts[$key]['bhp'] = $value['BHP'];
					$contracts[$key]['ship_type'] = $value['M/V TYPE'];
					$contracts[$key]['engine'] = $value['ENGINE'];
					$contracts[$key]['rank'] = $value['RANK'];
					$contracts[$key]['company'] = $value['COMPANY'];				
				}
				$hydrated['user_experience'] = $contracts;
			}
		}


		return $hydrated;
	}





}
