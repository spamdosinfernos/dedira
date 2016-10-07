<?php
/**
 *
 * @author ensismoebius
 *        
 */
class DatabaseQuery {
	const OPERATION_GET = 0;
	const OPERATION_PUT = 1;
	const OPERATION_ERASE = 2;
	const OPERATION_UPDATE = 3;
	
	/**
	 * Holds the operation code
	 *
	 * @var int
	 */
	private $operation;
	
	/**
	 * Holds the conditions
	 *
	 * @var DatabaseConditions
	 */
	private $conditions;
	
	/**
	 * Holds the object involved in query
	 *
	 * @var mixed
	 */
	private $object;
	
	/**
	 *
	 * @param DatabaseConditions $c        	
	 */
	public function setConditions(DatabaseConditions $c) {
		$this->conditions = $c;
	}
	
	/**
	 * Returns the conditions
	 *
	 * @return DatabaseConditions
	 */
	public function getConditions(): DatabaseConditions {
		return $this->conditions;
	}
	
	/**
	 * Sets the query type it must be one of the constants:
	 *
	 * @param DatabaseQuery::OPERATION_GET $type        	
	 * @param DatabaseQuery::OPERATION_PUT $type        	
	 * @param DatabaseQuery::OPERATION_ERASE $type        	
	 * @param DatabaseQuery::OPERATION_UPDATE $type        	
	 * @throws Exception
	 */
	public function setOperationType(int $type) {
		switch ($type) {
			case DatabaseQuery::OPERATION_GET :
			case DatabaseQuery::OPERATION_PUT :
			case DatabaseQuery::OPERATION_ERASE :
			case DatabaseQuery::OPERATION_UPDATE :
				$this->operation = $type;
				break;
			default :
				throw new Exception ( "Unsuported operation" );
				return;
		}
	}
	
	/**
	 * Gets the operation type
	 *
	 * @return int
	 */
	public function getOperationType(): int {
		return $this->operation;
	}
	
	/**
	 * Sets the object involved in operation
	 *
	 * @param object $object        	
	 */
	public function setObject($object) {
		
		
		
		$this->object = $object;
	}
	
	/**
	 * Returns the object involved in operation
	 *
	 * @return object
	 */
	public function getObject() {
		return $this->object;
	}
}
?>