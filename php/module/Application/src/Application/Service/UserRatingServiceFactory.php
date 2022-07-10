<?php
namespace Application\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserRatingServiceFactory implements FactoryInterface 
{

	private $info;
	private $sl;

	CONST MAX_EXP_POINTS = 25;
	CONST MAX_DOC_POINTS = 25;
	CONST MAX_PROFILE_POINTS = 25;
	CONST MAX_ACTIVITY_POINTS = 25;

	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$this->sl = $serviceLocator;
		return $this;
	}

	public function getUserRating($user_id = null) {
		$this->info = $this->sl->get('InfoTable')->getUserStats($user_id);

		if (!$this->info) throw new \Application\Exception\Exception("User with such id not found", 1);
		
		$profile_points = $this->getProfilePoints();
		$experience_points = $this->getExperiencePoints();
		$documents_points = $this->getDocumentsPoints();
		$activity_points = $this->getActivityPoints();
		$result = [
			'user_id' => $user_id,
			'total' => $experience_points + $documents_points + $profile_points + $activity_points,
			'profile' => $profile_points,
			'experience' => $experience_points,
			'documents' => $documents_points,
			'activity' => $activity_points,
			'max_exp_points' => self::MAX_EXP_POINTS,
			'max_doc_points' => self::MAX_DOC_POINTS,
			'max_profile_points' => self::MAX_PROFILE_POINTS,
			'max_activity_points' => self::MAX_ACTIVITY_POINTS,
		];

		return $result;
	}


	public function getExperiencePoints()
	{
		try {
			$points  = $this->info->contracts * 5;
			if($points > self::MAX_EXP_POINTS) $points = self::MAX_EXP_POINTS;
			if($points > 20) {
				//if last contract more than one year ago, reducing exp_points
				if(time() - $this->info->last_contract > 31104000) $points -= 10;
			}
		} catch (\Exception $e) {
			$points = 0;
		}
		return $points;
	}

	public function getDocumentsPoints()
	{
		try {
			$points  = $this->info->docs * 2;
			if($points > self::MAX_DOC_POINTS) $points = self::MAX_DOC_POINTS;
		} catch (\Exception $e) {
			$points = 0;
		}
		return $points;
	}

	public function getProfilePoints()
	{
		try {
			if($this->info->type == \Application\Model\UserTable::TYPE_USER) {
				$fields = [
				'name',
				'surname',
				'avatar',
				'cv_avatar',
				'desired_rank',
				'minimum_salary',
				'readiness_date',
				'visa_usa',
				'visa_shenghen',
				'dob',
				'nationality',
				'home_city',
				'contact_email',
				'contact_mobile',
				'contact_phone',
				'english_knowledge',
				];
			} else if($this->info->type == \Application\Model\UserTable::TYPE_COMPANY) {
				$fields = [
				'avatar',
				'home_country',
				'home_city',
				'home_address',
				'contact_email',
				'contact_email_2',
				'contact_mobile',
				'contact_mobile_2',
				'contact_phone',
				'contact_phone_2',
				'info_website',
				'company_name',
				'company_description',
				'company_license',
				];
			} else {
				$fields = [
				'email',
				'info_website',
				];
			}

			$count_fields = count($fields);
			$coeff = round(self::MAX_PROFILE_POINTS / $count_fields, 1);
			$points = 0;
			for ($i=0; $i < $count_fields; $i++) { 
				if(isset($this->info[$fields[$i]]) && $this->info[$fields[$i]]) $points++;
			}
			$points = $points * $coeff;
			if($points > self::MAX_PROFILE_POINTS) $points = self::MAX_PROFILE_POINTS;
		} catch (\Exception $e) {
			$points = 0;
		}
		return $points;
	}

	public function getActivityPoints()
	{
		try {
			$points = $this->info->posts * 5;
			if($points > self::MAX_ACTIVITY_POINTS) $points = self::MAX_ACTIVITY_POINTS;
			if($points > 20) {
				//if last post more than one week ago, reducing points
				if(time() - $this->info->last_post > 604800) $points -= 15;
			}
		} catch (\Exception $e) {
			$points = 0;
		}
		return $points;
	}




}