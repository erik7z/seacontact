<?php

return [
	// Application resources rules

	['allow','guest','application\controller\index'],
	['allow','guest','application\controller\news'],
	['allow','guest','application\controller\logbook.index'],
	['allow','guest','application\controller\logbook.view'],
	['allow','guest','application\controller\vacancies.index'],
	['allow','guest','application\controller\vacancies.view'],
	['allow','guest','application\controller\vacancies.toggle-subscribe'],
	['allow','guest','application\controller\vacancies.toggle-report'],
	['allow','guest','application\controller\questions.toggle-subscribe'],
	['allow','guest','application\controller\tags'],
	['allow','guest','application\controller\companiesdb.index'],
	['allow','guest','application\controller\seamansdb.index'],
	['allow','guest','application\controller\seamansdb.unlock-user-info'],
	['allow','guest','application\controller\questions.index'],
	['allow','guest','application\controller\questions.view'],
	['allow','guest','application\controller\questions.ask'],
	['allow','guest','application\controller\questions.answer'],
	
	['allow','guest','userinfo\controller'],
	['allow','guest','companyinfo\controller'],
	['allow','guest','admin\controller\auth'],
	['allow','guest','my\controller\comments.get'],
	['allow','guest','my\controller\comments.view'],

	['allow','user','my\controller\settings'],
	['allow','user','my\controller\index'],
	['allow','user','my\controller\contacts'],
	['allow','user','my\controller\messages'],
	['allow','user','my\controller\cv', null, 'MyPropertyAssertion'],
	['allow','user','my\controller\like'],
	['allow','user','my\controller\pics.upload-image'],


	['allow',['user', 'company_basic'],'application\controller\social.parse-vk'],
	['allow',['user', 'company_basic'],'application\controller\social.parse-fb'],
	['allow',['user', 'company_basic'],'application\controller\notifications.index'],
	['allow',['user', 'company_basic'],'application\controller\questions.ask-submit'],
	['allow',['user', 'company_basic'],'application\controller\questions.answer-submit'],
	['allow',['user', 'company_basic'],'application\controller\questions.answer-edit', null, 'MyPropertyAssertion'],
	['allow',['user', 'company_basic'],'application\controller\questions.answer-delete', null, 'MyPropertyAssertion'],
	['allow',['user', 'company_basic'],'application\controller\questions.answer-change-rating', null, 'NotMyPropertyAssertion'],
	['allow',['user', 'company_basic'],'application\controller\questions.toggle-accept', null, 'CommentOrResourceAuthorAssertion'],
	['allow',['user', 'company_basic'],'application\controller\questions.edit', null, 'MyPropertyAssertion'],
	['allow',['user', 'company_basic'],'application\controller\questions.delete', null, 'MyPropertyAssertion'],
	['allow',['user', 'company_basic'],'application\controller\questions.change-rating', null, 'NotMyPropertyAssertion'],

	['allow','user','application\controller\logbook.add'],
	['allow','user','application\controller\logbook.edit', null, 'MyPropertyAssertion'],
	['allow','user','application\controller\logbook.delete', null, 'MyPropertyAssertion'],
	['allow','user','my\controller\comments.edit', null, 'MyPropertyAssertion'],
	['allow','user','my\controller\comments.delete', null, 'CommentOrResourceAuthorAssertion'],
	['allow','user','my\controller\comments.change-rating', null, 'NotMyPropertyAssertion'],

	['deny','user','application\controller\seamansdb'],

	['deny','company_unknown','application\controller\vacancies.toggle-subscribe'],
	['deny','company_unknown','application\controller\vacancies.toggle-report'],

	['allow','company_unknown','companyinfo\controller'],
	['allow','company_unknown','application\controller\logbook.add'],
	['allow','company_unknown','my\controller\pics.upload-image'],
	['allow','company_unknown','application\controller\logbook.edit', null, 'MyPropertyAssertion'],
	['allow','company_unknown','application\controller\logbook.delete', null, 'MyPropertyAssertion'],
	['allow','company_unknown','my\controller\index'],
	['allow','company_unknown','my\controller\like'],

	['allow','company_unknown','my\controller\cv.index'],
	['allow','company_unknown','my\controller\cv.set-avatar'],
	['allow','company_unknown','my\controller\cv.delete-avatar'],
	['allow','company_unknown','company\controller\index'],
	['allow','company_unknown','company\controller\profile'],

	['allow','company_basic','company\controller\index'],
	['allow','company_basic','my\controller\comments.edit', 'MyPropertyAssertion'],
	['allow','company_basic','my\controller\comments.delete', 'CommentOrResourceAuthorAssertion'],
	['allow','company_basic','application\controller\seamansdb.company-db'],
	['allow','company_basic','application\controller\seamansdb.user-cv-notes'],
	['allow','company_basic','application\controller\seamansdb.notes-call-fail'],
	['allow','company_basic','application\controller\seamansdb.notes-ask-salary'],
	['allow','company_basic','application\controller\seamansdb.notes-ask-position'],
	['allow','company_basic','application\controller\seamansdb.notes-readiness'],
	['allow','company_basic','application\controller\seamansdb.delete-cv-note', null, 'MyPropertyAssertion'],

	['allow','company_basic','application\controller\vacancies.company'],
	['allow','company_basic','application\controller\vacancies.add'],
	['allow','company_basic','application\controller\vacancies.edit', null, 'MyPropertyAssertion'],
	['allow','company_basic','application\controller\vacancies.toggle-active', null, 'MyPropertyAssertion'],
	['allow','company_basic','application\controller\vacancies.delete', null, 'MyPropertyAssertion'],

	['allow','company_basic','my\controller\settings'],
	['allow','company_basic','my\controller\messages'],

	['allow',['author', 'company_premium'],'application\controller\social.add-vk-public'],

	['allow',['sc_office', 'company_sc', 'sc_user'],'application\controller\social'],

	['allow',['sc_office', 'company_sc'],'my\controller\like'],
	['allow',['sc_office', 'company_sc'],'my\controller\comments.delete'],
	['allow',['sc_office', 'company_sc'],'application\controller\seamansdb'],
	['allow',['sc_office', 'company_sc'],'application\controller\logbook.edit'],
	['allow',['sc_office', 'company_sc'],'application\controller\logbook.delete'],
	['allow',['sc_office', 'company_sc'],'application\controller\seamansdb.user-cv-notes'],
	['allow',['sc_office', 'company_sc'],'application\controller\seamansdb.delete-cv-note'],
	['allow',['sc_office', 'company_sc'],'application\controller\vacancies'],
	['allow',['sc_office', 'company_sc'],'application\controller\questions'],

];