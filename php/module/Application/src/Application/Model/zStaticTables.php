<?php
namespace Application\Model;


abstract class zStaticTables
{

	public static $userTable;
	public static $userContactsTable;
	public static $userExperienceTable;
	public static $companyUsersTable;
	public static $uploadsTable;

	public static function getUserTable() {
		if(!self::$userTable) {
			self::$userTable = new UserTable;
		}
		return self::$userTable;
	}

	public static function getUserContactsTable() {
		if(!self::$userContactsTable) {
			self::$userContactsTable = new UserContactsTable;
		}
		return self::$userContactsTable;
	}

	public static function getUserExperienceTable() {
		if(!self::$userExperienceTable) {
			self::$userExperienceTable = new UserExperienceTable;
		}
		return self::$userExperienceTable;
	}

	public static function getCompanyUsersTable() {
		if(!self::$companyUsersTable) {
			self::$companyUsersTable = new CompanyUsersTable;
		}
		return self::$companyUsersTable;
	}
	
	public static function getUploadsTable() {
		if(!self::$uploadsTable) {
			self::$uploadsTable = new UserUploadsTable;
		}
		return self::$uploadsTable;
	}

}



