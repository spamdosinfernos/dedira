<?php
require_once __DIR__ . '/../interfaces/IDatabaseQuery.php';

class MysqlDatabaseQuery implements IDatabaseQuery{
	/**
	 * {@inheritDoc}
	 * @see IDatabaseQuery::setConditions()
	 */
	public function setConditions(IDatabaseConditions $c) {
		// TODO: Auto-generated method stub

	}

	/**
	 * {@inheritDoc}
	 * @see IDatabaseQuery::getConditions()
	 */
	public function getConditions() {
		// TODO: Auto-generated method stub

	}

	/**
	 * {@inheritDoc}
	 * @see IDatabaseQuery::setOperationType()
	 */
	public function setOperationType($type) {
		// TODO: Auto-generated method stub

	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseQuery::getGeneratedQuery()
	 */
	public function getGeneratedQuery() {
		// TODO: Auto-generated method stub
	}


}
?>