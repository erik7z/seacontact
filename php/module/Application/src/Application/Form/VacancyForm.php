<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class VacancyForm extends EmptyForm 
{

	public function __construct()
	{
		parent::__construct('vacancy');
	}

	public function setup($vacancy_fields, $identity_id, $options = [])
	{

		$submit_text = (isset($options['submit_text'])) ? $options['submit_text'] : $this->translate('Add Vacancy');
		$vacancy_data = (isset($options['vacancy_data'])) ? $options['vacancy_data'] : null;
		$post_vk = (isset($options['post_vk'])) ? $options['post_vk'] : 0;
		$admin_form = (isset($options['admin_form'])) ? $options['admin_form'] : 0;
		$company_id = (isset($options['company_id'])) ? $options['company_id'] : null;
		$for_company_id = (isset($options['for_company_id'])) ? $options['for_company_id'] : null;
		$pics_field = (isset($options['pics_field'])) ? $options['pics_field'] : 1;

		if($vacancy_data) {
			$vacancy_data['time'] = date("d.m.Y H:i", $vacancy_data['time']);
			$vacancy_data['date_join'] =  ($vacancy_data['date_join'] != 0) ? date('Y-m-d', $vacancy_data['date_join']) : '';
			$vacancy_data['contract_unit'] = ($vacancy_data['contract_length'] >= 30)? 30 : 1;
			$vacancy_data['contract_length'] = ($vacancy_data['contract_length'] >= 30)? $vacancy_data['contract_length'] / 30 : $vacancy_data['contract_length'];

			if($vacancy_data['post_vk_time']) $vacancy_fields->get('post_vk')
					->setValueOptions(array(0 => $this->translate('Last Post ').zformatDateTime($vacancy_data['post_vk_time']), 1 => $this->translate('Yes')));
			if($vacancy_data['pics']) $this->addOldPictureFields(json_decode($vacancy_data['pics']));
		}

		if($admin_form) {
			$companies_list = $this->getUsersList($identity_id);
			// d($companies_list->toArray());
			$vacancy_fields->addFromCompany($companies_list);
			if($company_id !== null) $vacancy_fields->get('user')->setValue($company_id);

			$owners_list = $this->getUsersList($identity_id, ['company', 'owner']);
			$vacancy_fields->addForCompany($owners_list);
			if($for_company_id !== null) $vacancy_fields->get('for_company')->setValue($for_company_id);
		}

		if(!$post_vk) $vacancy_fields->remove('post_vk');

		$this->add($vacancy_fields);
		if($pics_field) $this->addPicsField();
		$this->addSubmit($submit_text);

		if($vacancy_data) $this->setData(array('vacancies' => $vacancy_data));

	}

	private function getUsersList($identity_id, $user_type = 'company', $title_field = 'company_name')
	{
		$q_options = ['user_type' => $user_type, '_user_fields' => ['id', $title_field], '_limit' => 0, 'query_only' => 1];
		$query = $this->sl->get('UserTable')->getUsersList($identity_id, [], $q_options);
		$predicat = '_'.md5($query);
		$dump_name = z_generateNameFromMethod(get_class($this).'::'.__FUNCTION__, 0);
		return $this->sl->get('UserTable')->getCachedRequest($query, $dump_name, $predicat, 0);
	}


	public function getInputFilterSpecification()
	{
	 	return $this->getFiltersFromFieldsOptions();
	}


}