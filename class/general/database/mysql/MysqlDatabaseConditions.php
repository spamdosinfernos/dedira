<?php
require_once __DIR__ . '/../interfaces/IDatabaseConditions.php';

class MysqlDatabaseConditions implements IDatabaseConditions {
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseConditions::addCondition()
	 */
	public function addCondition($type, $name, $value) {
		// TODO: Auto-generated method stub
	}
}
?>