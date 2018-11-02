<?php
// declare(strict_types = 1);
require_once '../../lib/vendor/autoload.php';
require_once '../../class/database/Database.php';
require_once '../../class/database/DatabaseQuery.php';
require_once '../../class/database/drivers/mongodb/MongoDb.php';

require_once '../../class/database/POPOs/user/User.php';

use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase {

	/**
	 * @var Database
	 */
	private $connection;

	public function __construct() {
		Configuration::init ();
		$this->connection = new Database ();
	}

	public function testMongoDbConnection(): void {
		$this->connection->init ( new MongoDb () );
		$this->assertEquals ( true, $this->connection->connect ( Configuration::$databaseHostProtocol, Configuration::$databaseHostAddress, Configuration::$databasePort ) );
	}

	public function testMongoDbInsert(): void {
		$user = new User ();
		$user->setActive ( false );
		$user->setName ( "Teste user" );

		$query = new DatabaseQuery ();
		$query->setObject ( $user );
		$query->setOperationType ( DatabaseQuery::OPERATION_PUT );

		$this->assertEquals ( true, $this->connection->execute ( $query ) );
	}
}

