<?php
interface IDatabaseConditions {
	const OR = 0;
	const AND = 1;
	const LIKE = 2;
	
	/**
	 * Adds the conditions to a query
	 * 
	 * @param mixed $type        	
	 * @param string $name        	
	 * @param mixed $value        	
	 */
	public function addCondition($type, string $name, $value);
	
	/**
	 * Gets the conditions
	 * 
	 * @return array
	 */
	public function getConditions(): array;
}
?>