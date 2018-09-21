<?php

/**
 * Manages the database query limits
 * @author ensismoebius
 */
class DatabaseLimit {
	public const UPPER_LIMIT = 0;
	public const LOWER_LIMIT = 1;

	/**
	 * Stores the database query limits
	 * @var array
	 */
	private $arrLimits;

	/**
	 * @param int $type
	 * @param int $value
	 * @throws Exception
	 */
	public function addLimit(int $type, int $value) {
		switch ($type) {
			case self::UPPER_LIMIT :
			case self::LOWER_LIMIT :
				$this->$arrLimits [$type] = $value;
				break;

			default :
				throw new Exception ( "Invalid limit" );
		}
	}
}