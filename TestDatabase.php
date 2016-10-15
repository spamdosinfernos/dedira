<?php
require_once __DIR__ . '/class/general/user/User.php';
require_once __DIR__ . '/class/general/database/Database.php';
require_once __DIR__ . '/class/general/variable/JSONGenerator.php';
require_once __DIR__ . '/class/general/database/DatabaseQuery.php';
require_once __DIR__ . '/class/general/database/DatabaseConditions.php';
require_once __DIR__ . '/class/general/database/drivers/MongoDatabaseDriver.php';
require_once __DIR__ . '/class/general/database/interfaces/IDatabaseDriver.php';
class MyObserver1 implements SplObserver {
	public function update(SplSubject $subject) {
		echo __CLASS__ . ' - ' . $subject->getName ();
	}
}
class MyObserver2 implements SplObserver {
	public function update(SplSubject $subject) {
		echo __CLASS__ . ' - ' . $subject->getName ();
	}
}
class MySubject implements SplSubject {
	private $_observers;
	private $_name;
	public function __construct($name) {
		$this->_observers = new SplObjectStorage ();
		$this->_name = $name;
	}
	public function attach(SplObserver $observer) {
		$this->_observers->attach ( $observer );
	}
	public function detach(SplObserver $observer) {
		$this->_observers->detach ( $observer );
	}
	public function notify() {
		foreach ( $this->_observers as $observer ) {
			$observer->update ( $this );
		}
	}
	public function getName() {
		return $this->_name;
	}
}

$observer1 = new MyObserver1 ();
$observer2 = new MyObserver2 ();

$subject = new MySubject ( "test" );

$subject->attach ( $observer1 );
$subject->attach ( $observer2 );
$subject->notify ();
class TestDatabase {
	public function __construct() {
		// Initilizing the database
		Database::init ( new MongoDatabaseDriver () );
		
		$user2 = new User ();
		$user2->setName ( "fsadfsaf" );
		$user2->setLogin ( "11111" );
		$user2->setArrEmail ( array (
				"teste@gmail.com",
				"uga@ig.com.br",
				"e@a.b.c" 
		) );
		
		$user3 = new User ();
		$user3->setName ( "eee" );
		$user3->setLogin ( "222" );
		
		// Recording objects
		$user = new User ();
		$user->setId ( 1 );
		$user->setLogin ( "andre" );
		$user->setPassword ( "1234" );
		$user->setSex ( $user2 );
		$user->setPassword ( array (
				$user2,
				$user3 
		) );
		
		$query2 = new DatabaseQuery ();
		$query2->setObject ( $user );
		$query2->setOperationType ( DatabaseQuery::OPERATION_PUT );
		Database::execute ( $query2 );
		
		// Retrieving objects
		$c = new DatabaseConditions ();
		$c->addCondition ( DatabaseConditions::AND, "id", 1 );
		$c->addCondition ( DatabaseConditions::OR, "active", true );
		$c->addCondition ( DatabaseConditions::AND_LIKE, "login", "nd" );
		$c->addCondition ( DatabaseConditions::OR_LIKE, "login", "an" );
		// $c->addCondition ( DatabaseConditions::OR, "login", "uga" );
		$query = new DatabaseQuery ();
		$query->setConditions ( $c );
		$query->setObject ( new User () );
		$query->setOperationType ( DatabaseQuery::OPERATION_GET );
		Database::execute ( $query );
		$res = Database::getResults ();
		while ( $res->next () ) {
			echo $res->getRetrivedObject ()->getLogin ();
		}
		
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

?>