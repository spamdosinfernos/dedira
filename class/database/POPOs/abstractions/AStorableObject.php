<?php
/**
 * All POPOs MUST extends that abstraction in order 
 * to do a proper storing and reading operations
 * @author André Furlan
 */
abstract class AStorableObject {
	
	/**
	 * Means an unitary change
	 *
	 * @var integer
	 */
	const UNITARY = 12356;
	
	/**
	 * Means an adding to a collection
	 *
	 * @var integer
	 */
	const COLLECTION_ADD = 56356;
	
	/**
	 * Means a removing from a collection
	 *
	 * @var integer
	 */
	const COLLECTION_REMOVE = 46300;
	
	/**
	 * id
	 *
	 * @var mixed
	 */
	protected $_id;
	
	/**
	 * Stores the changes
	 *
	 * @var array
	 */
	private $arrChanges;
	
	/**
	 * Gets the id
	 *
	 * @return string
	 */
	public function get_id() : string {
		return $this->_id;
	}
	
	/**
	 * Sets the id
	 * WTF?? Id is a string???
	 * Yes you MUST use a hexadecimal number as the id!
	 * It avoids problems with really large numbers
	 *
	 * @param string $id        	
	 */
	public function set_id(string $id) {
		if (! ctype_xdigit ( $id )) throw new Exception ( "The id must be an hex number" );
		
		$this->_id = $id;
		$this->AddChange ( "_id", $id );
		return $this;
	}
	
	/**
	 * Returns if the object has changed
	 *
	 * @return bool
	 */
	public function hasChanges(): bool {
		return count ( $this->arrChanges ) > 0;
	}
	
	/**
	 * Informs what has changed
	 *
	 * @param string $propertyName        	
	 * @param mixed $newValue        	
	 * @param int $changeType
	 *        	allowed values: UNITARY or COLLECTION_ADD or COLLECTION_REMOVE
	 */
	protected function AddChange(string $propertyName, $newValue, $changeType = self::UNITARY) {
		if ($changeType == self::UNITARY) {
			$this->arrChanges [$changeType] [$propertyName] = $newValue;
			return;
		}
		
		$this->arrChanges [$changeType] [$propertyName] [] = $newValue;
	}
	
	/**
	 * Get the changes
	 *
	 * @return array
	 */
	public function getArrChanges(): array {
		return $this->arrChanges;
	}
}
?>