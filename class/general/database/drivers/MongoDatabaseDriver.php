<?php
require_once __DIR__ . '/../../log/Log.php';

require_once __DIR__ . '/../DatabaseConditions.php';
require_once __DIR__ . '/../DatabaseRequestedData.php';
require_once __DIR__ . '/../interfaces/IDatabaseDriver.php';
require_once __DIR__ . '/../../configuration/Configuration.php';

require_once __DIR__ . '/../../variable/Caster.php';
require_once __DIR__ . '/../../variable/ClassPropertyPublicizator.php';
/**
 *
 * @author ensismoebius
 */
class MongoDatabaseDriver implements IDatabaseDriver {
	
	/**
	 * The database connection
	 * 
	 * @var MongoDB\Driver\Manager
	 */
	private $connection;
	
	/**
	 * Guarda resultado da consulta
	 * 
	 * @var DatabaseRequestedData
	 */
	private $result;
	
	/**
	 * Stores the query that will be executed
	 * 
	 * @var DatabaseQuery
	 */
	private $query;
	
	/**
	 * Stores the entity name manipulated in query
	 * 
	 * @var string
	 */
	private $entityName;
	
	/**
	 *
	 * @var MongoDB\Driver\WriteConcern
	 */
	private $writeConcern;
	public function __construct(){
		$this->result = new DatabaseRequestedData ();
		$this->entityName = "";
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseDriver::connect()
	 */
	public function connect(): bool{
		
		// Construct a write concern
		$this->writeConcern = new MongoDB\Driver\WriteConcern ( 
				// Guarantee that writes are acknowledged by a majority of our nodes
				MongoDB\Driver\WriteConcern::MAJORITY, 
				// But only wait 1000ms because we have an application to run!
				1000 );
		
		try {
			$url = Configuration::DB_HOST_PROTOCOL . "://" . Configuration::DB_HOST_ADDRESS . ":" . Configuration::DB_PORT;
			$this->connection = new MongoDB\Driver\Manager ( $url );
			
			// Execute an connection test, it may or may not throw an exception
			$stats = new MongoDB\Driver\Command ( [ 
					"dbstats" => 1 
			] );
			$this->connection->executeCommand ( "testdb", $stats );
			
			// If nothing goes wrong so everything goes well ;)
			return true;
		} catch ( MongoDB\Driver\Exception\Exception $e ) {
			Log::recordEntry ( $e->getMessage () );
			return false;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseDriver::disconnect()
	 */
	public function disconnect(): bool{
		// For some reason looks like we cant close the connection
		return true;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseDriver::execute()
	 */
	public function execute( DatabaseQuery $query ): bool{
		$this->query = $query;
		
		if (is_null ( $this->connection )) {
			// TODO Create a log entry but keep the echo
			echo "Connect to database!";
			return false;
		}
		
		$reflection = new ReflectionClass ( $query->getObject () );
		$this->entityName = $reflection->getName ();
		
		return $this->executeQuery ();
	}
	
	/**
	 */
	public function getResults(): DatabaseRequestedData{
		return $this->result;
	}
	
	/**
	 * Generates the query string
	 */
	private function executeQuery(): bool{
		switch ($this->query->getOperationType ()) {
			case DatabaseQuery::OPERATION_GET :
				return $this->doRead ();
			case DatabaseQuery::OPERATION_PUT :
				return $this->doInsert ();
			case DatabaseQuery::OPERATION_UPDATE :
				return $this->doUpdate ();
			case DatabaseQuery::OPERATION_ERASE :
				return $this->doDelete ();
			default :
				Log::recordEntry ( "Unsuported operation" );
				return false;
		}
	}
	
	/**
	 * Generates the update query
	 * 
	 * @return string
	 */
	private function doUpdate(): string{
		
		// Create a bulk write object and add our update operation
		$bulk = new MongoDB\Driver\BulkWrite ();
		
		// FIXME implement collections addition and removes, this part is not working!
		$bulk->update ( $this->buildFilters (), $this->buildModifiers (), [ 
				'multi' => true,
				'upsert' => false 
		] );
		
		// Retrieves the name of collection to insert
		$collection = Configuration::DB_NAME . "." . $this->entityName;
		
		try {
			$this->connection->executeBulkWrite ( $collection, $bulk, $this->writeConcern );
			return true;
		} catch ( MongoDB\Driver\Exception\Exception $e ) {
			Log::recordEntry ( $e->getMessage () );
		}
		return false;
	}
	
	/**
	 * Generates the insert query
	 * 
	 * @return string
	 */
	private function doInsert(): bool{
		
		// Create a bulk write object and add our insert operation
		$bulk = new MongoDB\Driver\BulkWrite ();
		
		// To insert we need turn all properties as public
		$bulk->insert ( ClassPropertyPublicizator::publicizise ( $this->query->getObject () ) );
		
		// Retrieves the name of collection to insert
		$collection = Configuration::DB_NAME . "." . $this->entityName;
		
		try {
			$this->connection->executeBulkWrite ( $collection, $bulk, $this->writeConcern );
			return true;
		} catch ( MongoDB\Driver\Exception\Exception $e ) {
			Log::recordEntry ( $e->getMessage () );
		}
		return false;
	}
	
	/**
	 * Generates the delete query
	 * 
	 * @return string
	 */
	private function doDelete(): string{
		// Create a bulk write object and add our update operation
		$bulk = new MongoDB\Driver\BulkWrite ();
		
		$bulk->delete ( $this->buildFilters (), array (
				'multi' => true 
		) );
		
		// Retrieves the name of collection to insert
		$collection = Configuration::DB_NAME . "." . $this->entityName;
		
		try {
			$this->connection->executeBulkWrite ( $collection, $bulk, $this->writeConcern );
			return true;
		} catch ( MongoDB\Driver\Exception\Exception $e ) {
			Log::recordEntry ( $e->getMessage () );
		}
		return false;
	}
	
	/**
	 * Generates the select query
	 * 
	 * @return string
	 */
	private function doRead(): bool{
		$query = new MongoDB\Driver\Query ( $this->buildFilters () );
		try {
			
			$cursor = $this->connection->executeQuery ( Configuration::DB_NAME . "." . $this->entityName, $query );
			
			// Stores all matched documents
			$result = array ();
			foreach ( $cursor as $document ) {
				$result [] = Caster::classToClassCast ( $document, $this->entityName );
			}
			
			$this->result->setData ( $result );
			
			return true;
		} catch ( MongoDB\Driver\Exception\Exception $e ) {
			Log::recordEntry ( $e->getMessage () );
		}
		return false;
	}
	
	/**
	 * Build the modifiers for updates
	 * 
	 * @return array
	 */
	protected function buildModifiers(): array{
		$arrChanges = $this->query->getObject ()->getArrChanges ();
		
		$adders = array ();
		$setters = array ();
		$removers = array ();
		
		$parans = array ();
		
		foreach ( $arrChanges as $changeType => $arrFieldValues ) {
			
			if ($changeType == AStorableObject::UNITARY) {
				
				foreach ( $arrFieldValues as $key => $value ) {
					$setters ['$set']->$key = $value;
				}
				continue;
			}
			
			if ($changeType == AStorableObject::COLLECTION_ADD) {
				foreach ( $arrFieldValues as $key => $value ) {
					$adders['$push'][$key]['$each'] = $value;
				}
				continue;
			}
			
			if ($changeType == AStorableObject::COLLECTION_REMOVE) {
				foreach ( $arrFieldValues as $key => $value ) {
					$removers ['$pop'] ['$each']->$key = $value;
				}
				continue;
			}
		}
		
		return $adders;
	}
	/**
	 * Builds the filter clause
	 * 
	 * @return array
	 */
	protected function buildFilters(): array{
		
		// Filter for documents
		$arrFilter = array ();
		
		// Creates the filter
		foreach ( $this->query->getConditions ()->getTokens () as $type => $arrToken ) {
			
			foreach ( $arrToken as $field => $value ) {
				
				switch ($type) {
					case DatabaseConditions::AND :
						$arrFilter [$field] = $value;
						continue;
					case DatabaseConditions::AND_LIKE :
						$arrFilter [$field] = new MongoDB\BSON\Regex ( ".*" . $value . ".*" );
						continue;
					case DatabaseConditions::OR :
						$arrFilter ['$or'] [] = array (
								$field => $value 
						);
						continue;
					case DatabaseConditions::OR_LIKE :
						$arrFilter ['$or'] [] = array (
								$field => new MongoDB\BSON\Regex ( ".*" . $value . ".*" ) 
						);
						continue;
				}
			}
		}
		
		return $arrFilter;
	}
}
?>