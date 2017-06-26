<?php
/**
 * Publicitize all properties and creates an 
 * additional field that informs the class name
 * @author ensismoebius
 *
 */
class ClassPropertyPublicizator {
	/**
	 *
	 * @var array
	 */
	private $arrSpecialPublicizators;
	
	/**
	 * Is a special type?
	 *
	 * @param mixed $variable        	
	 * @return bool
	 */
	private function isConvertable($variable): bool {
		// Testing if variable is a special type
		foreach ( $this->arrSpecialPublicizators as $publicizators ) {
			if (is_a ( $variable, $publicizators->getSpecialType () )) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Convert the special types if applicable
	 *
	 * @param mixed $variable        	
	 * @return mixed
	 */
	private function convert($variable) {
		// Testing if variable is a special type
		foreach ( $this->arrSpecialPublicizators as $publicizators ) {
			if (is_a ( $variable, $publicizators->getSpecialType () )) {
				$variable = $publicizators->convert ( $variable );
			}
		}
		return $variable;
	}
	/**
	 * If you have some special types like Datetime or anothe one to
	 * publicizise you need to specify some custom publicizators
	 *
	 * @param ISpecialTypesPublicizator $specialPublicizator        	
	 */
	public function addSpecialTypePublicizator(ISpecialTypesPublicizator $specialPublicizator) {
		$this->arrSpecialPublicizators [] = $specialPublicizator;
	}
	
	/**
	 * Creates an generic object with all properties as public
	 * or an array if it is the case
	 *
	 * @param object $variable        	
	 * @return mixed
	 */
	public function publicizise($variable) {
		
		if(is_null($variable)) return null;
		
		// If the informed variable is NOT an object we must prepare this in another ways
		if (! is_object ( $variable )) {
			
			if (is_array ( $variable )) {
				return $this->publiciziseArray ( $variable );
			}
			
			return $this->convert ( $variable );
		}
		
		// Publicizing an object
		// Used to read the properties including the private ones
		$reflection = new ReflectionClass ( $variable );
		
		// A genereric object that will represents the public object
		$obj = new stdClass ();
		
		// Creates an additional field that informs the class name
		$obj->class = $reflection->getName ();
		
		// Reading the properties
		foreach ( $reflection->getProperties () as $property ) {
			$property->setAccessible ( true );
			
			$value = $property->getValue ( $variable );
			
			// If is a special type, convert it and go on
			if ($this->isConvertable ( $value )) {
				$value = $this->convert ( $value );
				eval ( '$obj->' . $property->getName () . '=$value;' );
				continue;
			}
			
			// May happens that we have an array of objects
			if (is_array ( $value )) {
				$value = $this->publiciziseArray ( $value );
			}
			
			// If item is an object create a generic object of it
			if (is_object ( $value )) {
				$value = $this->publicizise ( $value );
			}
			
			// Formatting the command that will gererate the property
			if (is_string ( $value ) && ! is_numeric ( $value )) {
				$value = "" . $value . "";
			}
			// Creates the property
			eval ( '$obj->' . $property->getName () . '=$value;' );
		}
		
		return $obj;
	}
	
	/**
	 * Creates a public array
	 *
	 * @param array $array        	
	 * @return array
	 */
	private function publiciziseArray(array &$array): array {
		
		// We must check every single item
		foreach ( $array as &$item ) {
			if (! is_object ( $item )) {
				continue;
			}
			
			// If the array item is an object create a generic object of it
			$item = $this->publicizise ( $item );
		}
		
		return $array;
	}
}