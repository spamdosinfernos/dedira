<?php
require_once __DIR__ . '/../DatabaseConditions.php';
require_once __DIR__ . '/../DatabaseRequestedData.php';
require_once __DIR__ . '/../interfaces/IDatabaseDriver.php';
require_once __DIR__ . '/../../configuration/Configuration.php';

require_once __DIR__ . '/../../variable/Caster.php';
require_once __DIR__ . '/../../variable/ClassPropertyPublicizator.php';
/**
 *
 * @author ensismoebius
 *        
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
	 *
	 * @var MongoDB\Driver\WriteConcern
	 */
	private $writeConcern;
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseDriver::connect()
	 */
	public function connect(): bool {
		
		// Construct a write concern
		$this->writeConcern = new MongoDB\Driver\WriteConcern ( 
				// Guarantee that writes are acknowledged by a majority of our nodes
				MongoDB\Driver\WriteConcern::MAJORITY, 
				// But only wait 1000ms because we have an application to run!
				1000 );
		
		try {
			$url = Configuration::CONST_DB_HOST_PROTOCOL . "://" . Configuration::CONST_DB_HOST_ADDRESS . ":" . Configuration::CONST_DB_PORT;
			$this->connection = new MongoDB\Driver\Manager ( $url );
			return true;
		} catch ( MongoDB\Driver\Exception\Exception $e ) {
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
	public function disconnect(): bool {
		// For some reason looks like we cant close the connection
		return true;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseDriver::execute()
	 */
	public function execute(DatabaseQuery $query): bool {
		$this->query = $query;
		
		if (! $this->connect ())
			return false;
		
		return $this->executeQuery ();
	}
	
	/**
	 */
	public function getResults(): DatabaseRequestedData {
		return $this->result;
	}
	
	/**
	 * Generates the query string
	 */
	private function executeQuery(): bool {
		switch ($this->query->getOperationType ()) {
			case DatabaseQuery::OPERATION_GET :
				return $this->doRead ();
			case DatabaseQuery::OPERATION_PUT :
				return $this->doInsert ();
			case DatabaseQuery::OPERATION_UPDATE :
				return $this->generateUpdate ();
			case DatabaseQuery::OPERATION_ERASE :
				return $this->generateDelete ();
			default :
				// TODO otherwise record a log
				throw new Exception ( "Unsuported operation" );
				return "";
		}
	}
	
	/**
	 * Generates the update query
	 *
	 * @return string
	 */
	private function generateUpdate(): string {
		$sets = array ();
		
		$reflection = new ReflectionClass ( $this->query->getObject () );
		
		foreach ( $reflection->getMethods ( ReflectionMethod::IS_PUBLIC ) as $method ) {
			
			// We just want the getters
			if ($method->isConstructor () || $method->getNumberOfParameters () > 0) {
				continue;
			}
			
			// Invoke getter
			$value = $method->invoke ( $this->query->getObject () );
			
			// Just put non empty fields in update
			if ($value == "") {
				continue;
			}
			
			// If value is boolean put it without quotes
			$value = is_bool ( $value ) ? "true" : "'$value'";
			
			// extract property name
			$field = strtolower ( str_ireplace ( "get", "", $method->getName () ) );
			
			$sets [] = "$field = $value";
		}
		
		return "update " . $this->getEntityName () . " set " . implode ( ",", $sets ) . $this->buildConditions ();
	}
	
	/**
	 * Generates the insert query
	 *
	 * @return string
	 */
	private function doInsert(): bool {
		
		// Create a bulk write object and add our insert operation
		$bulk = new MongoDB\Driver\BulkWrite ();
		
		// To insert we need turn all properties as public
		$bulk->insert ( ClassPropertyPublicizator::publicizise ( $this->query->getObject () ) );
		
		// Retrieves the name of collection to insert
		$reflection = new ReflectionClass ( $this->query->getObject () );
		$collection = Configuration::CONST_DB_NAME . "." . $reflection->getName ();
		
		try {
			/*
			 * Specify the full namespace as the first argument, followed by the bulk
			 * write object and an optional write concern. MongoDB\Driver\WriteResult is
			 * returned on success; otherwise, an exception is thrown.
			 */
			$this->connection->executeBulkWrite ( $collection, $bulk, $this->writeConcern );
			return true;
		} catch ( MongoDB\Driver\Exception\Exception $e ) {
			// TODO otherwise record a log
			echo $e->getMessage (), "\n";
		}
		return false;
	}
	
	/**
	 * Generates the delete query
	 *
	 * @return string
	 */
	private function generateDelete(): string {
		return "delete from " . $this->getEntityName () . $this->buildConditions ();
	}
	
	/**
	 * Generates the select query
	 *
	 * @return string
	 */
	private function doRead(): bool {
		
		// Filter for documents
		$arrFilter = array ();
		
		// Retrieves the class name for document casting
		$reflection = new ReflectionClass ( $this->query->getObject () );
		$className = $reflection->getName ();
		
		// Prepare the caster
		$caster = new Caster ();
		
		// Creates the filter
		foreach ( $this->query->getConditions ()->getTokens () as $type => $arrToken ) {
			
			switch ($type) {
				case DatabaseConditions::AND :
					;
					break;
				
				case DatabaseConditions::AND_LIKE :
					;
					break;
				
				case DatabaseConditions::OR :
					;
					break;
				
				case DatabaseConditions::OR_LIKE :
					;
					break;
				default :
					;
					break;
			}
			
			foreach ( $arrToken as $key => $value ) {
				
				$arrFilter;
			}
		}
		
		$query = new MongoDB\Driver\Query ( $arrFilter );
		
		try {
			
			$cursor = $this->connection->executeQuery ( Configuration::CONST_DB_NAME . "." . $className, $query );
			/*
			 * Specify the full namespace as the first argument, followe'd by the query
			 * object and an optional read preference. MongoDB\Driver\Cursor is returned
			 * success; otherwise, an exception is thrown.
			 */
			
			// Stores all matched documents
			foreach ( $cursor as $document ) {
				$this->result [] = $caster->classToClassCast ( $document, $className );
			}
			
			return true;
		} catch ( MongoDB\Driver\Exception\Exception $e ) {
			// TODO otherwise record a log
			echo $e->getMessage (), "\n";
		}
		return false;
	}
	
	/**
	 * Returns the table name
	 *
	 * @return string
	 */
	private function getEntityName() {
		$reflection = new ReflectionClass ( $this->query->getObject () );
		return $reflection->getName ();
	}
	
	/**
	 * Builds the where clause
	 *
	 * @return string
	 */
	private function buildConditions(): string {
		$sql = "";
		
		if (count ( $this->query->getConditions () ) > 0) {
			
			// If is the first condition do not put the logical operations
			$firstCondition = true;
			
			$sql .= " where ";
			
			// The conditions are a bidimensional array, we must do a double loop
			foreach ( $this->query->getConditions ()->getTokens () as $type => $arrParameters ) {
				
				foreach ( $arrParameters as $param => $value ) {
					// If is the first condition do not put the logical operations
					if ($firstCondition) {
						$sql .= $param . "='" . $value . "'";
						$firstCondition = false;
						continue 1;
					}
					
					switch ($type) {
						case DatabaseConditions::AND :
							$sql .= " AND " . $param . "='" . $value . "' ";
							break;
						case DatabaseConditions::AND_LIKE :
							$sql .= " AND " . $param . " LIKE '%" . $value . "%' ";
							break;
						case DatabaseConditions::OR_LIKE :
							$sql .= " OR " . $param . " LIKE '%" . $value . "%' ";
							break;
						case DatabaseConditions::OR :
							$sql .= " OR " . $param . "='" . $value . "' ";
					}
				}
			}
		}
		
		return $sql;
	}
}
?>