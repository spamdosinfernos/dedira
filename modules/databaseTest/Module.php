<?php

namespace databaseTest;

require_once __DIR__ . '/../../class/general/database/Database.php';
require_once __DIR__ . '/../../class/general/variable/JSONGenerator.php';
require_once __DIR__ . '/../../class/general/database/DatabaseQuery.php';
require_once __DIR__ . '/../../class/general/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/general/security/PasswordPreparer.php';
require_once __DIR__ . '/../../class/general/database/DatabaseConditions.php';
require_once __DIR__ . '/../../class/general/database/drivers/MongoDatabaseDriver.php';
require_once __DIR__ . '/../../class/general/database/interfaces/IDatabaseDriver.php';
class Module {
	public function __construct() {
		// Initilizing the database
		\Database::init ( new \MongoDatabaseDriver () );
		\Database::connect ();
		
		// $user = new \User ();
		// $user->setId ( microtime ( true ) );
		// $user->setSex ( "fem" );
		// $user->setLogin ( "jac" );
		// $user->setPassword ( \PasswordPreparer::messItUp ( "1234" ) );
		// $user->setName ( "Jac Meire" );
		// $user->setArrEmail ( array (
		// "jac.meire@hotmail.com"
		// ) );
		
		// $query = new \DatabaseQuery ();
		// $query->setObject ( $user );
		// $query->setOperationType ( \DatabaseQuery::OPERATION_PUT );
		// \Database::execute ( $query );
		
		// Retrieving objects
		$c = new \DatabaseConditions ();
		$c->addCondition ( \DatabaseConditions::AND, "id", 1476809019.954783 );
		$c->addCondition ( \DatabaseConditions::OR, "active", true );
		$c->addCondition ( \DatabaseConditions::AND_LIKE, "login", "jac" );
		$c->addCondition ( \DatabaseConditions::OR_LIKE, "login", "jac" );
		// $c->addCondition ( DatabaseConditions::OR, "login", "uga" );
		$query = new \DatabaseQuery ();
		$query->setConditions ( $c );
		$query->setObject ( new \User () );
		$query->setOperationType ( \DatabaseQuery::OPERATION_GET );
		\Database::execute ( $query );
		$res = \Database::getResults ();
		while ( $res->next () ) {
			echo $res->getRetrivedObject ()->getLogin ();
		}
		
		// // Updating objects
		// $c2 = new DatabaseConditions ();
		// $c2->addCondition ( DatabaseConditions::AND, "id", 1 );
		// $user->setLogin ( "João" );
		// $query3 = new DatabaseQuery ();
		// $query3->setConditions ( $c2 );
		// $query3->setObject ( $user );
		// $query3->setOperationType ( DatabaseQuery::OPERATION_UPDATE );
		// Database::execute ( $query3 );
		
		// // Deleting objects
		// $c3 = new DatabaseConditions ();
		// $c3->addCondition ( DatabaseConditions::AND, "id", 1 );
		// $query4 = new DatabaseQuery ();
		// $query4->setConditions ( $c3 );
		// $query4->setObject ( $user );
		// $query4->setOperationType ( DatabaseQuery::OPERATION_ERASE );
		// Database::execute ( $query4 );
	}
}
new Module ();
?>