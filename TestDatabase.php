<?php
require_once __DIR__ . '/class/general/user/User.php';
require_once __DIR__ . '/class/general/database/Database.php';
require_once __DIR__ . '/class/general/database/DatabaseQuery.php';
require_once __DIR__ . '/class/general/database/DatabaseConditions.php';
require_once __DIR__ . '/class/general/database/drivers/MysqlDatabaseDriver.php';
require_once __DIR__ . '/class/general/database/interfaces/IDatabaseDriver.php';
class TestDatabase {
	public function __construct() {
		// Initilizing the database
		Database::init ( new MysqlDatabaseDriver () );
		
		// Retrieving objects
		$c = new DatabaseConditions ();
		$c->addCondition ( DatabaseConditions::AND, "id", 1 );
		$c->addCondition ( DatabaseConditions::OR, "login", "uga" );
		$query = new DatabaseQuery ();
		$query->setConditions ( $c );
		$query->setObject ( new User () );
		$query->setOperationType ( DatabaseQuery::OPERATION_GET );
		Database::execute ( $query );
		$res = Database::getResults ();
		while ( $res->next () ) {
			echo $res->getRetrivedObject ()->getLogin ();
		}
		
		// Recording objects
		$user = new User ();
		$user->setLogin ( "andre" );
		$user->setPassword ( "1234" );
		$query2 = new DatabaseQuery ();
		$query2->setObject ( $user );
		$query2->setOperationType ( DatabaseQuery::OPERATION_PUT );
		Database::execute ( $query2 );
		
		// Updating objects
		$c2 = new DatabaseConditions ();
		$c2->addCondition ( DatabaseConditions::AND, "id", 1 );
		$user->setLogin ( "João" );
		$query3 = new DatabaseQuery ();
		$query3->setConditions ( $c2 );
		$query3->setObject ( $user );
		$query3->setOperationType ( DatabaseQuery::OPERATION_UPDATE );
		Database::execute ( $query3 );
		
		// Deleting objects
		$c3 = new DatabaseConditions ();
		$c3->addCondition ( DatabaseConditions::AND, "id", 10 );
		$query4 = new DatabaseQuery ();
		$query4->setConditions ( $c3 );
		$query4->setObject ( $user );
		$query4->setOperationType ( DatabaseQuery::OPERATION_ERASE );
		Database::execute ( $query4 );
	}
}

try {
	
	$mng = new MongoDB\Driver\Manager ( "mongodb://localhost:27017" );
	
	$stats = new MongoDB\Driver\Command ( [ 
			"dbstats" => 1 
	] );
	$res = $mng->executeCommand ( "testdb", $stats );
	
	$stats = current ( $res->toArray () );
	
	print_r ( $stats );
} catch ( MongoDB\Driver\Exception\Exception $e ) {
	
	$filename = basename ( __FILE__ );
	
	echo "The $filename script has experienced an error.\n";
	echo "It failed with the following exception:\n";
	
	echo "Exception:", $e->getMessage (), "\n";
	echo "In file:", $e->getFile (), "\n";
	echo "On line:", $e->getLine (), "\n";
}

?>