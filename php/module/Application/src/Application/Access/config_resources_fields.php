<?php
$sl = \ServiceLocatorFactory\ServiceLocatorFactory::getInstance();

return array_merge(
	[
		'_user_fields' => null,
		'_experience_fields' => null,
		'_docs_fields' => null,
		'_edu_fields' => null,

		'_stats_fields' => null,
		'_question_fields' => null,
	],
	z_makeResourceMap('_user_fields', $sl->get('UserTable')->getStandartFields()),
	z_makeResourceMap('_experience_fields', $sl->get('ExperienceTable')->getStandartFields()),
	z_makeResourceMap('_docs_fields', $sl->get('DocumentsTable')->getStandartFields()),
	z_makeResourceMap('_edu_fields', $sl->get('EducationTable')->getStandartFields()),

	z_makeResourceMap('_stats_fields', $sl->get('InfoTable')->getStatsFields()),
	z_makeResourceMap('_question_fields', $sl->get('QuestionsTable')->getStandartFields())
	);
