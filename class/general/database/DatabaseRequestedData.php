<?php
/**
 * Simple database data reader
 * @author ensismoebius
 *
 */
class DatabaseRequestedData {
	
	/**
	 * The data returned
	 *
	 * @var array
	 */
	private $arrData;
	
	/**
	 * The dataset pointer
	 *
	 * @var int
	 */
	private $pointer = - 1;
	
	/**
	 * Sets the data to be returned
	 *
	 * @param array $data        	
	 */
	public function setData(array $data) {
		$this->arrData = $data;
	}
	
	/**
	 * Gets the amount of objects returned
	 * @return int
	 */
	public function getObjectsAffected(): int {
		return count ( $this->arrData );
	}
	
	/**
	 * Gets the current object
	 * @return object
	 */
	public function getRetrivedObject() {
		return $this->arrData [$this->pointer];
	}
	
	/**
	 * Go to next object if exists
	 * @return bool
	 */
	public function next(): bool {
		if ($this->pointer < count ( $this->arrData ) - 1) {
			$this->pointer ++;
			return true;
		}
		return false;
	}
	
	/**
	 * Go to next object if exists
	 * @return bool
	 */
	public function first(): bool {
		if (count ( $this->arrData ) > 0) {
			$this->pointer = 0;
			return true;
		}
		return false;
	}
	
	/**
	 * Go to previous object if exists
	 * @return bool
	 */
	public function previous(): bool {
		if (count ( $this->arrData ) > 0 && $this->pointer > 0) {
			$this->pointer --;
			return true;
		}
		return false;
	}
}
?>