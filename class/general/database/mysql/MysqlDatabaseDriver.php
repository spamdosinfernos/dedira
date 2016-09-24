<?php
require_once __DIR__ . '/../interfaces/IDatabaseDriver.php';
require_once __DIR__ . '/../../configuration/Configuration.php';
require_once __DIR__ . '/../../configuration/Configuration.php';
require_once __DIR__ . '/../mysql/MysqlDatabaseRequestedData.php';
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
	public function execute(IDatabaseQuery $query): bool {
		if (! $this->connect ())
			return false;
		
		try {
			$this->connection->beginTransaction ();
			
			// The results
			$res = $this->connection->prepare ( $query->getGeneratedQuery () );
			$res->execute ();
			
			// Retrieves the object data to extract the class name
			$reflection = new ReflectionClass ( $query->getObject () );
			
			$this->result = new MysqlDatabaseRequestedData ();
			
			// Only populates the results if the operation is data retrieving
			if ($query->getOperationType () == IDatabaseQuery::OPERATION_GET) {
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
	public function getResults(): IDatabaseRequestedData {
		return $this->result;
	}
}
?>