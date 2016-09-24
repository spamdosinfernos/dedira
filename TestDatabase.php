<?php
require_once __DIR__ . '/class/general/user/User.php';
require_once __DIR__ . '/class/general/variable/Caster.php';
require_once __DIR__ . '/class/general/database/Database.php';
require_once __DIR__ . '/class/general/database/mysql/MysqlDatabaseQuery.php';
require_once __DIR__ . '/class/general/database/interfaces/IDatabaseQuery.php';
require_once __DIR__ . '/class/general/database/mysql/MysqlDatabaseDriver.php';
require_once __DIR__ . '/class/general/database/interfaces/IDatabaseDriver.php';
require_once __DIR__ . '/class/general/database/mysql/MysqlDatabaseConditions.php';
require_once __DIR__ . '/class/general/database/interfaces/IDatabaseConditions.php';
class TestDatabase {
	public function __construct() {
		$c = new MysqlDatabaseConditions ();
		$c->addCondition ( IDatabaseConditions::AND, "id", 1 );
		$c->addCondition ( IDatabaseConditions::OR, "login", "uga" );
		
		$query = new MysqlDatabaseQuery ();
		$query->setConditions ( $c );
		$query->setObject ( new User () );
		$query->setOperationType ( IDatabaseQuery::OPERATION_GET );
		
		Database::init ( new MysqlDatabaseDriver () );
		Database::execute ( $query );
		
		$res = Database::getResults ();
		while ( $res->next () ) {
			echo $res->getRetrivedObject ()->getLogin ();
		}
	}
}
?>