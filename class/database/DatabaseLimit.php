<?php

/**
 * Manages the database query limits
 * @author ensismoebius
 */
class DatabaseLimit {

	/**
	 * Stores the database query limits
	 * @var int
	 */
	private $limit;

	/**
	 * @return number
	 */
	public function getLimit() {
		return $this->limit;
	}

	/**
	 * @param number $limit
	 */
	public function setLimit($limit) {
		$this->limit = $limit;
	}
}