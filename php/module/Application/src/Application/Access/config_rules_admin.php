<?php 

return [

	// Admin Resources

	['allow',['sc_office', 'company_sc'],'admin\controller\index'],
	['allow','sc_office','admin\controller\messages'],
	['allow','sc_office','admin\controller\vacancies'],
	['allow','sc_content_manager','admin\controller\content'],

	['allow','sc_office','admin\controller\tasks.index'],
	['allow','sc_office','admin\controller\tasks.view'],
	['allow','sc_office','admin\controller\tasks.add-activity'],
	['allow','sc_office','admin\controller\tasks.close'],

	['allow','sc_cv_parser','admin\controller\index'],
	['allow','sc_cv_parser','admin\controller\userdb.add-user'],
	['allow','sc_cv_parser','admin\controller\userdb.add-company'],
	['allow','sc_cv_parser','admin\controller\userdb.check-user'],
	['allow','sc_cv_parser','admin\controller\userdb.check-company'],
	['allow','sc_cv_parser','admin\controller\userdb.user'],
	['allow','sc_cv_parser','admin\controller\userdb.company'],
	['allow','sc_cv_parser','admin\controller\userdb.user-cv-notes'],
	['allow','sc_cv_parser','application\controller\seamansdb.user-cv-notes'],
	['allow','sc_cv_parser','application\controller\seamansdb.notes-cv-updated'],
	['allow','sc_cv_parser','application\controller\seamansdb.delete-cv-note', null, 'MyPropertyAssertion'],

	['allow','sc_cv_parser','admin\controller\userdb.notes-cv-updated'],
	['allow','sc_cv_parser','admin\controller\userdb.delete-cv-note'],
	['allow','sc_cv_parser','admin\controller\userdb.user-experience'],
	['allow','sc_cv_parser','admin\controller\userdb.user-documents'],
	['allow','sc_cv_parser','admin\controller\mailbox'],
	['allow','sc_cv_parser','admin\controller\mailbox.view'],
	['allow','sc_cv_parser','admin\controller\mailbox.new-mail'],


	['allow',['sc_secretary', 'sc_content_manager','sc_crewing_manager'],'admin\controller\userdb'],
	['allow',['sc_secretary', 'sc_content_manager','sc_crewing_manager'],'admin\controller\mailbox'],

	['allow',['sc_director', 'sc_co_founder'],'admin\controller'],


	['allow','sc_director','_admin_\delete\user'],
	['allow','sc_director','_admin_\delete\vacancy'],
	['allow','sc_director','_admin_\access\assign_types'],
	['allow','sc_director','_admin_\access\assign_roles'],

	['deny','sc_cv_parser','admin\controller\mailbox.index'],
	['deny',['sc_secretary', 'sc_content_manager','sc_crewing_manager', 'sc_co_founder', 'sc_director'],['admin\controller\mailbox.add-account', 'admin\controller\mailbox.edit-account']],

	['deny',['sc_director', 'sc_co_founder'],'_admin_\access\assign_roles\admin'],
	['deny',['sc_director', 'sc_co_founder'],'_admin_\access\assign_roles\sc_director'],
	['deny',['sc_director', 'sc_co_founder'],'_admin_\access\assign_roles\sc_co_founder'],
	['deny',['sc_director', 'sc_co_founder'],'_admin_\access\assign_roles\company_sc'],
	['deny',['sc_director', 'sc_co_founder'],'admin\controller\access'],

	['allow','admin']
];