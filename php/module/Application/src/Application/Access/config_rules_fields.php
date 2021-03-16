<?php 
return [
	// Fields Resources
	['allow',null,[
		'_stats_fields',
		'_question_fields',
		'_user_fields\id',
		'_user_fields\name',
		'_user_fields\login',
		'_user_fields\avatar',
		'_user_fields\cv_avatar',
		'_experience_fields\dwt',
		'_experience_fields\grt',
		'_experience_fields\ship_type',
		]],
	['allow','user',[
		'_user_fields',
		'_experience_fields',
		'_docs_fields',
		'_edu_fields',
		], null, 'MyPropertyAssertion'],
	['allow','user',[
		'_user_fields\name',
		'_user_fields\surname',
		'_user_fields\full_name'
		], null, 'FriendsOrPartnersAssertion'],
	['allow','user','_experience_fields', null, 'FriendsAssertion'],
	['allow','company_basic',[
		'_user_fields',
		'_experience_fields',
		'_docs_fields',
		'_edu_fields',
		],null, 'CompanyDbAssertion'],
	['allow',['sc_office'],[
		'_user_fields\name',
		'_user_fields\surname',
		'_user_fields\full_name'
		]],
	['allow',['sc_secretary', 'sc_crewing_manager', 'sc_co_founder', 'sc_director'],[
		'_user_fields',
		'_experience_fields',
		'_docs_fields',
		'_edu_fields',
		]],
];