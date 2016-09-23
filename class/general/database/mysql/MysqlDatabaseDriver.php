<?php
require __DIR__ . '/../interfaces/IDatabaseDriver.php';
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
			$dsn = Configuration::CONST_DB_HOST_PROTOCOL . ":host=" . Configuration::CONST_DB_HOST_ADDRESS . ";dbname=" . Configuration::CONST_DB_NAME;
			$this->conexao = new PDO ( $dsn, Configuration::CONST_DB_LOGIN, Configuration::CONST_DB_PASSWORD );
			$this->conexao->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$this->conexao->setAttribute ( PDO::ATTR_PERSISTENT, true );
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
		
		$this->result = null;
		
		try {
			$this->conexao->beginTransaction ();
			
			// The results
			$res = $this->conexao->prepare ( $query->getGeneratedQuery () );
			$res->execute ();
			
			// Sets the results
			$this->result = new MysqlDatabaseRequestedData ();
			$this->result->setData ( $res->fetchAll () );
		} catch ( PDOException $e ) {
			$this->connection->rollBack ();
			return false;
		}
		$this->connection->commit ();
		
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