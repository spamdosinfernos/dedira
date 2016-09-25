<?php
require_once __DIR__ . '/../interfaces/IDatabaseConditions.php';
/**
 *
 * @author ensismoebius
 *        
 */
class DatabaseQuery {
	const OPERATION_GET = 0;
	const OPERATION_ERASE = 1;
	const OPERATION_INSERT = 2;
	const OPERATION_UPDATE = 3;
	
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
}
?>