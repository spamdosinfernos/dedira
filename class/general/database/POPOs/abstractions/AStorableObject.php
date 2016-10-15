<?php
/**
 * 
 *
 * @author André Furlan
 *        
 */
abstract class AStorableObject {
	
	/**
	 * Stores the changes
	 *
	 * @var array
	 */
	private $arrChanges;
	
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
	 */
	protected function AddChange(string $propertyName, $newValue) {
		$this->arrChanges [$propertyName] = $newValue;
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