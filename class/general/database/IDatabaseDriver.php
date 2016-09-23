<?php
/**
 * Paterns for a data base driver
 * @author André Furlan
 *
 */
interface IDatabaseDriver {
	
	/**
	 * The database connection
	 */
	private $connection;
	
	/**
	 * The result store
	 */
	private $results;
	
	/**
	 * Connects to database
	 */
	public function connect();
	
	/**
	 * Disconnect from database
	 */
	public function disconnect();
	
	/**
	 * The query that must be executed
	 *
	 * @param IDatabaseQuery $query        	
	 */
	public function execute(IDatabaseQuery $query);
	
	/**
	 * Return the results
	 */
	public function getResults();
}
?>