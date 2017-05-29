<?php
require_once __DIR__ . '/../abstractions/AStorableObject.php';

/**
 * Cost types may or may not have subTypes
 * 
 * @author ensismoebius
 *        
 */
class CostType extends AStorableObject {
	
	/**
	 *
	 * @var CostType
	 */
	private $type;
	
	/**
	 *
	 * @return CostType
	 */
	public function getType(): CostType {
		return $this->type;
	}
	
	/**
	 *
	 * @param CostType $type        	
	 */
	public function setType(CostType $type) {
		$this->AddChange ( "type", $type );
		$this->type = $type;
		return $this;
	}
}