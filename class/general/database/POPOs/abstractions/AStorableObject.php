<?php
/**
 * All POPOs MUST extends that abstraction in order 
 * to do a proper storing and reading operations
 * @author André Furlan
 */
abstract class AStorableObject {
	
	/**
	 * id
	 *
	 * @var mixed
	 */
	protected $id;
	
	/**
	 * Stores the changes
	 *
	 * @var array
	 */
	private $arrChanges;
	
	/**
	 * Gets the id
	 *
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Sets the id
	 *
	 * @param mixed $id        	
	 */
	public function setId($id) {
		$this->id = $id;
		$this->AddChange ( "id", $id );
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