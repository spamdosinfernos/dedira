<?php
class Problem {
	private $number;
	private $address;
	private $complement;
	private $coordinates;
	private $reportImage;
	private $solvingSuggestion;
	private $problemDescription;
	
	/**
	 * number
	 *
	 * @return int
	 */
	public function getNumber() {
		return $this->number;
	}
	
	/**
	 * number
	 *
	 * @param string $number        	
	 * @return Problem
	 */
	public function setNumber(string $number): Problem {
		$this->number = $number;
		return $this;
	}
	
	/**
	 * address
	 *
	 * @return string
	 */
	public function getAddress() {
		return $this->address;
	}
	
	/**
	 * address
	 *
	 * @param string $address        	
	 * @return Problem
	 */
	public function setAddress(string $address): Problem {
		$this->address = $address;
		return $this;
	}
	
	/**
	 * complement
	 *
	 * @return string
	 */
	public function getComplement() {
		return $this->complement;
	}
	
	/**
	 * complement
	 *
	 * @param string $complement        	
	 * @return Problem
	 */
	public function setComplement(string $complement): Problem {
		$this->complement = $complement;
		return $this;
	}
	
	/**
	 * coordinates
	 *
	 * @return string
	 */
	public function getCoordinates() {
		return $this->coordinates;
	}
	
	/**
	 * coordinates
	 *
	 * @param string $coordinates        	
	 * @return Problem
	 */
	public function setCoordinates(string $coordinates): Problem {
		$this->coordinates = $coordinates;
		return $this;
	}
	
	/**
	 * reportImage
	 *
	 * @return mixed
	 */
	public function getReportImage() {
		return $this->reportImage;
	}
	
	/**
	 * reportImage
	 *
	 * @param mixed $reportImage        	
	 * @return Problem
	 */
	public function setReportImage($reportImage): Problem {
		$this->reportImage = $reportImage;
		return $this;
	}
	
	/**
	 * solvingSuggestion
	 *
	 * @return string
	 */
	public function getSolvingSuggestion() {
		return $this->solvingSuggestion;
	}
	
	/**
	 * solvingSuggestion
	 *
	 * @param string $solvingSuggestion        	
	 * @return Problem
	 */
	public function setSolvingSuggestion(string $solvingSuggestion): Problem {
		$this->solvingSuggestion = $solvingSuggestion;
		return $this;
	}
	
	/**
	 * problemDescription
	 *
	 * @return string
	 */
	public function getProblemDescription() {
		return $this->problemDescription;
	}
	
	/**
	 * problemDescription
	 *
	 * @param string $problemDescription        	
	 * @return Problem
	 */
	public function setProblemDescription(string $problemDescription): Problem {
		$this->problemDescription = $problemDescription;
		return $this;
	}
}
?>