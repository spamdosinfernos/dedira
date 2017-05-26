<?php
require_once __DIR__ . '/../../log/Log.php';
require_once __DIR__ . '/../DatabaseConditions.php';
require_once __DIR__ . '/../DatabaseRequestedData.php';
require_once __DIR__ . '/../interfaces/IDatabaseDriver.php';
require_once __DIR__ . '/../../configuration/Configuration.php';
/**
 *
 * @author ensismoebius
 *        
 */
class MysqlDb implements IDatabaseDriver {
	
	/**
	 * The database connection
	 *
	 * @var PDO
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
	 */
	public function connect(): bool {
		try {
			$dsn = Configuration::$databaseHostProtocol . ": host=" . Configuration::$databaseHostAddress . ";dbname=" . Configuration::$databaseNAme;
			$this->connection = new PDO ( $dsn, Configuration::$databaseUsername, Configuration::$databasePassword, array (
					PDO::ATTR_PERSISTENT,
					true 
			) );
			$this->connection->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			return true;
		} catch ( Exception $e ) {
			Log::recordEntry ( $e->getMessage () );
			return false;
		}
		return false;
	}
	
	/**
	 */
	public function disconnect(): bool {
		$this->connection = null;
		return true;
	}
	
	/**
	 */
	public function execute(DatabaseQuery $query): bool {
		$this->query = $query;
		
		if (! $this->connect ()) return false;
		
		try {
			$this->connection->beginTransaction ();
			
			// The results
			$res = $this->connection->prepare ( $this->getGeneratedQuery () );
			$res->execute ();
			
			$this->result = new DatabaseRequestedData ();
			
			// Only populates the results if the operation is data retrieving
			if ($query->getOperationType () == DatabaseQuery::OPERATION_GET) {
				// Sets the results as an array of objects (PDO rocks!!!!)
				$this->result->setData ( $res->fetchAll ( PDO::FETCH_CLASS, $this->getEntityName () ) );
			}
			$this->connection->commit ();
		} catch ( PDOException $e ) {
			$this->connection->rollBack ();
			Log::recordEntry ( $e->getMessage () );
			return false;
		}
		
		$this->disconnect ();
		
		return true;
	}
	
	/**
	 */
	public function getResults(): DatabaseRequestedData {
		return $this->result;
	}
	
	/**
	 * Generates the query string
	 */
	private function getGeneratedQuery(): string {
		switch ($this->query->getOperationType ()) {
			case DatabaseQuery::OPERATION_GET :
				return $this->generateSelect ();
			case DatabaseQuery::OPERATION_PUT :
				return $this->generateInsert ();
			case DatabaseQuery::OPERATION_UPDATE :
				return $this->generateUpdate ();
			case DatabaseQuery::OPERATION_ERASE :
				return $this->generateDelete ();
			default :
				Log::recordEntry ( "Unsuported operation" );
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
	private function generateInsert(): string {
		$fields = array ();
		$values = array ();
		
		$reflection = new ReflectionClass ( $this->query->getObject () );
		
		foreach ( $reflection->getMethods ( ReflectionMethod::IS_PUBLIC ) as $method ) {
			
			// We just want the getters
			if ($method->isConstructor () || $method->getNumberOfParameters () > 0) continue;
			
			$value = $method->invoke ( $this->query->getObject () );
			
			// Just put non empty fields in insert
			if ($value == "") continue;
			
			$values [] = is_bool ( $value ) ? "true" : "'$value'";
			$fields [] = strtolower ( str_ireplace ( "get", "", $method->getName () ) );
		}
		
		return "insert into " . $this->getEntityName () . "(" . implode ( ",", $fields ) . ")values(" . implode ( ",", $values ) . ")";
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
	private function generateSelect(): string {
		return "select * from " . $this->getEntityName () . $this->buildConditions ();
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
			foreach ( $this->query->getConditions ()->getConditions () as $type => $arrParameters ) {
				
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