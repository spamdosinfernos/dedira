<?php

namespace databaseTest;

require_once __DIR__ . '/../../class/module/IModule.php';
require_once __DIR__ . '/../../class/database/Database.php';
require_once __DIR__ . '/../../class/variable/JSONGenerator.php';
require_once __DIR__ . '/../../class/database/DatabaseQuery.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/security/PasswordPreparer.php';
require_once __DIR__ . '/../../class/database/DatabaseConditions.php';
require_once __DIR__ . '/../../class/database/drivers/MongoDatabaseDriver.php';
require_once __DIR__ . '/../../class/database/interfaces/IDatabaseDriver.php';
class Module implements \IModule {
	public function __construct() {
		// Initilizing the database
		\Database::init ( new \MongoDatabaseDriver () );
		\Database::connect ();
		
		$user = new \User ();
		$user->set_Id ( 1 );
		$user->setSex ( "masc" );
		$user->setLogin ( "root" );
		$user->setPassword ( \PasswordPreparer::messItUp ( "1234" ) );
		$user->setName ( "André Furlan" );
		$user->setArrEmail ( array (
				"ensismoebius@gmail.com" 
		) );
		$query = new \DatabaseQuery ();
		$query->setObject ( $user );
		$query->setOperationType ( \DatabaseQuery::OPERATION_PUT );
		\Database::execute ( $query );
		
		// Retrieving objects
		$c = new \DatabaseConditions ();
		$c->addCondition ( \DatabaseConditions::AND, "_id", 1 );
		$c->addCondition ( \DatabaseConditions::OR, "active", true );
		$c->addCondition ( \DatabaseConditions::AND_LIKE, "login", "oo" );
		$c->addCondition ( \DatabaseConditions::OR_LIKE, "login", "oo" );
		$c->addCondition ( \DatabaseConditions::OR, "sex", "masc" );
		$query = new \DatabaseQuery ();
		$query->setConditions ( $c );
		$query->setObject ( new \User () );
		$query->setOperationType ( \DatabaseQuery::OPERATION_GET );
		\Database::execute ( $query );
		$res = \Database::getResults ();
		while ( $res->next () ) {
			echo $res->getRetrivedObject ()->getLogin ();
		}
		
		// Updating objects
		$user = new \User ();
		$c2 = new \DatabaseConditions ();
		$c2->addCondition ( \DatabaseConditions::AND, "_id", 1 );
		$user->setLogin ( "root" );
		$user->setName ( "root" );
		$user->addTelephone ( 99999 );
		$user->addTelephone ( 9999945 );
		$query3 = new \DatabaseQuery ();
		$query3->setConditions ( $c2 );
		$query3->setObject ( $user );
		$query3->setOperationType ( \DatabaseQuery::OPERATION_UPDATE );
		\Database::execute ( $query3 );
		
		$user = new \User ();
		$c2 = new \DatabaseConditions ();
		$c2->addCondition ( \DatabaseConditions::AND, "_id", 1 );
		$user->removeTelephone ( 99999 );
		$user->removeTelephone ( 99999 );
		$query3 = new \DatabaseQuery ();
		$query3->setConditions ( $c2 );
		$query3->setObject ( $user );
		$query3->setOperationType ( \DatabaseQuery::OPERATION_UPDATE );
		\Database::execute ( $query3 );
		
		// Deleting objects
		$user = new \User ();
		$c3 = new \DatabaseConditions ();
		$c3->addCondition ( \DatabaseConditions::AND, "_id", 1 );
		$query4 = new \DatabaseQuery ();
		$query4->setConditions ( $c3 );
		$query4->setObject ( $user );
		$query4->setOperationType ( \DatabaseQuery::OPERATION_ERASE );
		\Database::execute ( $query4 );
	}
	public static function isRestricted(): bool {
		return true;
	}
}
new Module ();
?>