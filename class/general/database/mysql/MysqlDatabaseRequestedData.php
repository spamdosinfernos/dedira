<?php
require_once __DIR__ . '/../interfaces/IDatabaseRequestedData.php';
class MysqlDatabaseRequestedData implements IDatabaseRequestedData {
	
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
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseRequestedData::getRowsAffected()
	 */
	public function getRowsAffected(): int {
		return count ( $this->arrData );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseRequestedData::getRetrivedObject()
	 */
	public function getRetrivedObject() {
		return $this->arrData [$this->pointer];
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseRequestedData::next()
	 */
	public function next(): bool {
		if ($this->pointer < count ( $this->arrData ) - 1) {
			$this->pointer ++;
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseRequestedData::first()
	 */
	public function first(): bool {
		if (count ( $this->arrData ) > 0) {
			$this->pointer = 0;
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseRequestedData::previous()
	 */
	public function previous(): bool {
		if (count ( $this->arrData ) > 0 && $this->pointer > 0) {
			$this->pointer--;
			return true;
		}
		return false;
	}
}
?>