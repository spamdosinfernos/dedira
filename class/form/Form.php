<?php
final class Form {
	
	/**
	 *
	 * @var array
	 */
	private $arrFilters;
	private $className;
	public function setClassName($className) {
		$this->className = $className;
	}
	public function getObject() {
		$this->validateAndSanitize ( $this->arrFilters );
	}
	public function registerField($fieldName, $filterType) {
		$this->arrFilters [$fieldName] = $filterType;
	}
	private function validateAndSanitize(&$arrData) {
		foreach ( $arrData as $fieldName => $filterType ) {
			
			if (is_array ( $data )) {
				$arrData [$index] = self::validateAndSanitize ( $data );
				continue;
			}
			
			$arrData [$index] = self::sanitize ( $data );
		}
		
		return $arrData;
	}
}
?>