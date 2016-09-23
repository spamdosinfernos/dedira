<?php
require_once 'IDatabaseQuery.php';
require_once 'IDatabaseRequestedData.php';
/**
 * Paterns for a data base driver
 * 
 * @author André Furlan
 *        
 */
interface IDatabaseDriver {
	
	/**
	 * Connects to database
	 * 
	 * @return bool
	 */
	public function connect(): bool;
	
	/**
	 * Disconnect from database
	 * 
	 * @return bool
	 */
	public function disconnect(): bool;
	
	/**
	 * The query that must be executed
	 *
	 * @param IDatabaseQuery $query        	
	 * @return bool
	 */
	public function execute(IDatabaseQuery $query): bool;
	
	/**
	 * Return the results
	 * 
	 * @return IDatabaseRequestedData
	 */
	public function getResults(): IDatabaseRequestedData;
}
?>