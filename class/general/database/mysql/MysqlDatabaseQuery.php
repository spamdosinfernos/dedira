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
				return $this->generateDelete ();
				break;
			case IDatabaseQuery::OPERATION_INSERT :
				return $this->generateInsert ();
				break;
			case IDatabaseQuery::OPERATION_UPDATE :
				return $this->generateUpdate ();
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
	 * Generates the update query
	 *
	 * @return string
	 */
	private function generateUpdate(): string {
		$sets = array ();
		
		foreach ( $this->object as $field => $value ) {
			$sets [] = "$field = $value";
		}
		
		return "update " . $this->getTableName () . "set " . implode ( ",", $sets ) . $this->buildConditions ();
	}
	
	/**
	 * Generates the insert query
	 *
	 * @return string
	 */
	private function generateInsert(): string {
		$fields = array ();
		$values = array ();
		
		$reflection = new ReflectionClass ( $this->object );
		
		foreach ( $reflection->getMethods ( ReflectionMethod::IS_PUBLIC ) as $method ) {
			
			// We just want the getters
			if ($method->isConstructor () || $method->getNumberOfParameters () > 0)
				continue;
			
			$value = $method->invoke ( $this->object );
			
			// Just put non empty fields in insert
			if ($value == "")
				continue;
			
			$values [] = is_bool ( $value ) ? "true" : "'$value'";
			$fields [] = strtolower ( str_ireplace ( "get", "", $method->getName () ) );
		}
		
		return "insert into " . $this->getTableName () . "(" . implode ( ",", $fields ) . ")values(" . implode ( ",", $values ) . ")";
	}
	
	/**
	 * Generates the delete query
	 *
	 * @return string
	 */
	private function generateDelete(): string {
		return "delete from " . $this->getTableName () . $this->buildConditions ();
	}
	
	/**
	 * Generates the select query
	 *
	 * @return string
	 */
	private function generateSelect(): string {
		return "select * from " . $this->getTableName () . $this->buildConditions ();
	}
	
	/**
	 * Returns the table name
	 *
	 * @return string
	 */
	private function getTableName() {
		$reflection = new ReflectionClass ( $this->object );
		
		return $reflection->getName ();
	}
	
	/**
	 * Builds the where clause
	 *
	 * @return string
	 */
	private function buildConditions(): string {
		$sql = "";
		
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