<?php
require_once 'IDatabaseConditions.php';
/**
 * Patterns for a query
 * 
 * @author ensismoebius
 *        
 */
interface IDatabaseQuery {
	const OPERATION_GET = 0;
	const OPERATION_ERASE = 1;
	const OPERATION_INSERT = 2;
	const OPERATION_UPDATE = 3;
	
	/**
	 * Sets the query parameters
	 * 
	 * @param IDatabaseConditions $c        	
	 */
	public function setConditions(IDatabaseConditions $c);
	
	/**
	 * Gets the query parameters
	 * 
	 * @return IDatabaseConditions
	 *
	 */
	public function getConditions(): IDatabaseConditions;
	
	/**
	 * OPERATION_GET, OPERATION_ERASE, OPERATION_INSERT, OPERATION_UPDATE
	 *
	 * @param
	 *        	one of the above constants
	 */
	public function setOperationType($type);
	
	/**
	 * Generates the query for the specific database
	 * @return mixed
	 */
	public function getGeneratedQuery();
}
?>