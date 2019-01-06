<?php

/**
 * Manages the database query conditions
 * @author ensismoebius
 */
class DatabaseConditions {
	const OR = 0;
	const AND = 1;
	const OR_LIKE = 2;
	const AND_LIKE = 3;

	/**
	 * Holds the conditions
	 * @var array
	 */
	private $arrConditions = array();

	/**
	 * Adds a condition to query
	 * @param int $type
	 * @param string $name
	 * @param mixed $value
	 * @throws Exception
	 */
	public function addCondition(int $type, string $name, $value) {
		switch ($type) {
			case self::OR :
			case self::AND :
			case self::OR_LIKE :
			case self::AND_LIKE :
				$this->arrConditions [$type] [$name] = $value;
				break;

			default :
				throw new Exception ( "Invalid condition" );
		}
	}

	/**
	 * Return the conditions
	 * @return array
	 */
	public function getTokens(): array {
		return $this->arrConditions;
	}
}
?>