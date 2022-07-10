<?php

return [
	'users' => [
		'guest' => null,
		'unreg'=> 'guest',
		'user'=> 'unreg',
		'author'=> 'user',
		'sc_user'=> 'user',
		],
	'companies' => [
		'sc_company'=> 'unreg',
		'company_unknown'=> 'unreg',
		'company_basic'=> 'company_unknown',
		'company_test'=> 'company_basic',
		'company_premium'=> 'company_basic',
		'company_sc'=> 'company_premium',
		],
	'office' => [
		'sc_cv_parser'=> 'user',
		'sc_office'=> 'user',
		'sc_secretary'=> 'sc_office',
		'sc_content_manager'=> 'sc_office',
		'sc_crewing_manager'=> 'sc_office',
		'sc_co_founder'=> 'sc_office',
		'sc_director'=> 'sc_co_founder',
	],
	'admins' => [
		'admin' => null,
	]


];