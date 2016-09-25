<?php
require_once __DIR__ . '/../DatabaseConditions.php';
require_once __DIR__ . '/../DatabaseRequestedData.php';
require_once __DIR__ . '/../interfaces/IDatabaseDriver.php';
require_once __DIR__ . '/../../configuration/Configuration.php';
/**
 *
 * @author ensismoebius
 *        
 */
class MysqlDatabaseDriver implements IDatabaseDriver {
	
	/**
	 * The database connection
	 *
	 * @var PDO
	 */
	private $connection;
	
	/**
	 * Guarda resultado da consulta
	 *
	 * @var IDatabaseRequestedData
	 */
	private $result;
	
	/**
	 * Stores the query that will be executed
	 *
	 * @var IDatabaseQuery
	 */
	private $query;
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseDriver::connect()
	 */
	public function connect(): bool {
		try {
			$dsn = Configuration::CONST_DB_HOST_PROTOCOL . ": host=" . Configuration::CONST_DB_HOST_ADDRESS . ";dbname=" . Configuration::CONST_DB_NAME;
			$this->connection = new PDO ( $dsn, Configuration::CONST_DB_LOGIN, Configuration::CONST_DB_PASSWORD, array (
					PDO::ATTR_PERSISTENT,
					true 
			) );
			$this->connection->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			return true;
		} catch ( Exception $e ) {
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
		$this->connection = null;
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
		
		try {
			$this->connection->beginTransaction ();
			
			// The results
			$res = $this->connection->prepare ( $this->getGeneratedQuery () );
			$res->execute ();
			
			// Retrieves the object data to extract the class name
			$reflection = new ReflectionClass ( $query->getObject () );
			
			$this->result = new DatabaseRequestedData ();
			
			// Only populates the results if the operation is data retrieving
			if ($query->getOperationType () == DatabaseQuery::OPERATION_GET) {
				// Sets the results as an array of objects (PDO rocks!!!!)
				$this->result->setData ( $res->fetchAll ( PDO::FETCH_CLASS, $reflection->getName () ) );
			}
			$this->connection->commit ();
		} catch ( PDOException $e ) {
			$this->connection->rollBack ();
			return false;
		}
		
		$this->disconnect ();
		
		return true;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseDriver::getResults()
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
			case DatabaseQuery::OPERATION_ERASE :
				return $this->generateDelete ();
				break;
			case DatabaseQuery::OPERATION_INSERT :
				return $this->generateInsert ();
				break;
			case DatabaseQuery::OPERATION_UPDATE :
				return $this->generateUpdate ();
				break;
			default :
				throw new Exception ( "Unsuported operation" );
				return;
		}
	}
	
	/**
	 * Generates the update query
	 *
	 * @return string
	 */
	private function generateUpdate(): string {
		$sets = array ();
		
		$reflection = new ReflectionClass ( $this->object );
		
		foreach ( $reflection->getMethods ( ReflectionMethod::IS_PUBLIC ) as $method ) {
			
			// We just want the getters
			if ($method->isConstructor () || $method->getNumberOfParameters () > 0) {
				continue;
			}
			
			// Invoke getter
			$value = $method->invoke ( $this->query->getObject () );
			
			// Just put non empty fields in insert
			if ($value == "") {
				continue;
			}
			
			// extract property name
			$field = strtolower ( str_ireplace ( "get", "", $method->getName () ) );
			
			$sets [] = "$field = $value";
		}
		
		return "update " . $this->getTableName () . "set " . implode ( ",", $sets ) . $this->buildConditions ();
	}
	
	/**
	 * Generates the insert query
	 *
	 * @return string
	 */
	private function generateInsert(): string {
		$fields = array ();
		$values = array ();
		
		$reflection = new ReflectionClass ( $this->object );
		
		foreach ( $reflection->getMethods ( ReflectionMethod::IS_PUBLIC ) as $method ) {
			
			// We just want the getters
			if ($method->isConstructor () || $method->getNumberOfParameters () > 0)
				continue;
			
			$value = $method->invoke ( $this->query->getObject () );
			
			// Just put non empty fields in insert
			if ($value == "")
				continue;
			
			$values [] = is_bool ( $value ) ? "true" : "'$value'";
			$fields [] = strtolower ( str_ireplace ( "get", "", $method->getName () ) );
		}
		
		return "insert into " . $this->getTableName () . "(" . implode ( ",", $fields ) . ")values(" . implode ( ",", $values ) . ")";
	}
	
	/**
	 * Generates the delete query
	 *
	 * @return string
	 */
	private function generateDelete(): string {
		return "delete from " . $this->getTableName () . $this->buildConditions ();
	}
	
	/**
	 * Generates the select query
	 *
	 * @return string
	 */
	private function generateSelect(): string {
		return "select * from " . $this->getTableName () . $this->buildConditions ();
	}
	
	/**
	 * Returns the table name
	 *
	 * @return string
	 */
	private function getTableName() {
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
			foreach ( $this->query->getConditions () as $type => $arrParameters ) {
				
				foreach ( $arrParameters as $param => $value ) {
					// If is the first condition do not put the logical operations
					if ($firstCondition) {
						$sql .= $param . "='" . $value . "'";
						$firstCondition = false;
						continue;
					}
					
					switch ($type) {
						case DatabaseConditions::AND :
							$sql .= " AND " . $param . "='" . $value . "' ";
							break;
						case DatabaseConditions::AND_LIKE :
							$sql .= " AND " . $param . " LIKE '%" . $value . "%' ";
							break;
						case DatabaseConditions::AND_LIKE :
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