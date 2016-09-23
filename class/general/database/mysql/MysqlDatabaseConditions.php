<?php
require_once __DIR__ . '/../interfaces/IDatabaseConditions.php';
/**
 *
 * @author ensismoebius
 *        
 */
class MysqlDatabaseConditions implements IDatabaseConditions {
	
	/**
	 * Holds the conditions
	 *
	 * @var array
	 */
	private $arrConditions;
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseConditions::addCondition()
	 */
	public function addCondition($type, $name, $value) {
		switch ($type) {
			case IDatabaseConditions::AND :
			case IDatabaseConditions::LIKE :
			case IDatabaseConditions::OR :
				$this->arrConditions [$type] [$name] = $value;
				break;
			
			default :
				throw new Exception ( "Invalid condition" );
		}
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseConditions::getConditions()
	 */
	public function getConditions(): array {
		return $this->arrConditions;
	}
}
?>