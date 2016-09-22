<?php
/**
 * Patterns for a query
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
	 * @param IDatabaseConditions $c
	 */
	private function setArrConditions(IDatabaseConditions $cond);
	
	/**
	 * Gets the query parameters
	 * @return array(IDatabaseConditions)
	 * 
	 */
	private function getArrConditions() : array;
	
	/**
	 * Gets the requested data
	 * @return array(IDatabaseRequestedData)
	 */
	private function getArrRequestedData() : array;
	
	/**
	 * OPERATION_GET, OPERATION_ERASE, OPERATION_INSERT, OPERATION_UPDATE
	 * 
	 * @param one of the above constants
	 */
	private function setOperationType($type);
	
	
}
?>