<?php
require_once 'CostType.php';
require_once __DIR__ . '/../abstractions/AStorableObject.php';

/**
 * The cost center may be a sigle person up
 * to the whole federation.
 * Its an fundamental
 * part of the system because it will help to
 * control and fiscalize the budget
 *
 * @author ensismoebius
 *        
 */
class CostUnit extends AStorableObject {
	
	/**
	 *
	 * @var string
	 */
	private $name;
	
	/**
	 *
	 * @var string
	 */
	private $address;
	
	/**
	 *
	 * @var CostType
	 */
	private $type;
	
	/**
	 * A cost center may have another subcost centers
	 * like employees, departments, etc.
	 * @var array
	 */
	private $arrCostUnits;
	
	/**
	 * The sum of the entire cost per cicle
	 * The cicle must be defined for example
	 * like a month, week or year
	 * @var double
	 */
	private $totalCostPerCicle;
	
	public function getName(): string {
		return $this->name;
	}
	public function setName(string $name) {
		$this->AddChange ( "name", $name );
		$this->name = $name;
		return $this;
	}
	public function getAddress(): string {
		return $this->address;
	}
	public function setAddress(string $address) {
		$this->AddChange ( "address", $address );
		$this->address = $address;
		return $this;
	}
	public function getType(): CostType {
		return $this->type;
	}
	public function setType(CostType $type) {
		$this->AddChange ( "type", $type );
		$this->type = $type;
		return $this;
	}
	public function getArrCostUnits(): array {
		return $this->arrCostUnits;
	}
	public function setArrCostUnits(array $arrCostUnits) {
		$this->AddChange ( "arrCostUnits", $arrCostUnits );
		$this->arrCostUnits = $arrCostUnits;
		return $this;
	}
	public function addCostUnits(CostType $costUnit) {
		$this->AddChange ( "arrCostUnits", $costUnit, self::COLLECTION_ADD );
		$this->arrCostUnits [] = $costUnit;
		return $this;
	}
	public function getTotalCostPerCicle(): float {
		return $this->totalCostPerCicle;
	}
	public function setTotalCostPerCicle(float $totalCostPerCicle) {
		$this->AddChange ( "totalCostPerCicle", $totalCostPerCicle );
		$this->totalCostPerCicle = $totalCostPerCicle;
		return $this;
	}
}