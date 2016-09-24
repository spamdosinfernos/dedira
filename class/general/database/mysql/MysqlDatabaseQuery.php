<?php
require_once __DIR__ . '/../interfaces/IDatabaseQuery.php';
require_once __DIR__ . '/../interfaces/IDatabaseConditions.php';
/**
 *
 * @author ensismoebius
 *        
 */
class MysqlDatabaseQuery implements IDatabaseQuery {
	
	/**
	 * Holds the operation code
	 *
	 * @var unknown
	 */
	private $operation;
	
	/**
	 * Holds the conditions
	 *
	 * @var IDatabaseConditions
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
	 * {@inheritdoc}
	 *
	 * @see IDatabaseQuery::setConditions()
	 */
	public function setConditions(IDatabaseConditions $c) {
		$this->conditions = $c;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseQuery::getConditions()
	 */
	public function getConditions(): IDatabaseConditions {
		return $this->conditions;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseQuery::setOperationType()
	 */
	public function setOperationType(int $type) {
		switch ($type) {
			case IDatabaseQuery::OPERATION_GET :
			case IDatabaseQuery::OPERATION_ERASE :
			case IDatabaseQuery::OPERATION_INSERT :
			case IDatabaseQuery::OPERATION_UPDATE :
				$this->operation = $type;
				break;
			default :
				throw new Exception ( "Unsuported operation" );
				return;
		}
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseQuery::getOperationType()
	 */
	public function getOperationType() {
		return $this->operation;
	}

	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseQuery::getGeneratedQuery()
	 */
	public function getGeneratedQuery(): string {
		switch ($this->operation) {
			case IDatabaseQuery::OPERATION_GET :
				return $this->generateSelect ();
			case IDatabaseQuery::OPERATION_ERASE :
				break;
			case IDatabaseQuery::OPERATION_INSERT :
				break;
			case IDatabaseQuery::OPERATION_UPDATE :
				break;
			default :
				throw new Exception ( "Unsuported operation" );
				return;
		}
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseQuery::setObject()
	 */
	public function setObject($object) {
		$this->object = $object;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @return object
	 * @see IDatabaseQuery::getObject()
	 */
	public function getObject() {
		return $this->object;
	}
	
	/**
	 * Generates the select query
	 *
	 * @return string
	 */
	private function generateSelect(): string {
		$reflection = new ReflectionClass ( $this->object );
		
		$tableName = $reflection->getName ();
		
		// Standard query
		$sql = "select * from $tableName";
		
		// Builds the where clause
		if (count ( $this->conditions->getConditions () ) > 0) {
			
			// If is the first condition do not put the logical operations
			$firstCondition = true;
			
			$sql .= " where ";
			
			// The conditions are a bidimensional array, we must do a double loop
			foreach ( $this->conditions->getConditions () as $type => $arrParameters ) {
				
				foreach ( $arrParameters as $param => $value ) {
					// If is the first condition do not put the logical operations
					if ($firstCondition) {
						$sql .= $param . "='" . $value . "'";
						$firstCondition = false;
						continue;
					}
					
					switch ($type) {
						case IDatabaseConditions::AND :
							$sql .= " AND " . $param . "='" . $value . "' ";
							break;
						case IDatabaseConditions::AND_LIKE :
							$sql .= " AND " . $param . " LIKE '%" . $value . "%' ";
							break;
						case IDatabaseConditions::AND_LIKE :
							$sql .= " OR " . $param . " LIKE '%" . $value . "%' ";
							break;
						case IDatabaseConditions::OR :
							$sql .= " OR " . $param . "='" . $value . "' ";
					}
				}
			}
		}
		return $sql;
	}
}
?>