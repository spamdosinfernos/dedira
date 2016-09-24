<?php
/**
 * @author ensismoebius
 *
 */
interface IDatabaseRequestedData {
	
	/**
	 * Get the number of rows affected by the operation
	 *
	 * @return int
	 */
	public function getRowsAffected(): int;
	
	/**
	 * Get current object from the list
	 * 
	 * @return mixed
	 */
	public function getRetrivedObject();
	
	/**
	 * Go to the next object, if there is no one returns false
	 *
	 * @return bool
	 */
	public function next(): bool;
	
	/**
	 * Go to the first object, if there is no one returns false
	 *
	 * @return bool
	 */
	public function first(): bool;
	
	/**
	 * Go to the previous object, if there is no one returns false
	 *
	 * @return bool
	 */
	public function previous(): bool;
}
?>