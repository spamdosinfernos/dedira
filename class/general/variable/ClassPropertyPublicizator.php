<?php
/**
 * Publicitize all properties
 * @author ensismoebius
 *
 */
class ClassPropertyPublicizator {
	/**
	 * Creates an generic object with all properties as public
	 * or an array if it is the case
	 *
	 * @param object $variable        	
	 * @return mixed
	 */
	public static function publicizise($variable) {
		
		// If the informed variable is NOT an object we must prepare this in another ways
		if (! is_object ( $variable )) {
			
			if (is_array ( $variable )) {
				return self::publiciziseArray ( $variable );
			}
			
			return $variable;
		}
		
		// Used to read the properties including the private ones
		$reflection = new ReflectionClass ( $variable );
		
		// A genereric object that will turn on json
		$obj = new stdClass ();
		
		// Creates an additional field that informs the class name
		$obj->class = $reflection->getName ();
		
		// Reading the properties
		foreach ( $reflection->getProperties () as $property ) {
			$property->setAccessible ( true );
			
			$value = $property->getValue ( $variable );
			
			// May happens that we have an array of objects
			if (is_array ( $value )) {
				$value = self::publiciziseArray ( $value );
			}
			
			// If item is an object create a generic object of it
			if (is_object ( $value )) {
				$value = self::publicizise ( $value );
			}
			
			// Formatting the command that will gererate the property
			if (is_string ( $value ) && ! is_numeric ( $value )) {
				$value = "'" . $value . "'";
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
	private static function publiciziseArray(array &$array): array {
		
		// We must check every single item
		foreach ( $array as &$item ) {
			if (! is_object ( $item )) {
				continue;
			}
			
			// If the array item is an object create a generic object of it
			$item = self::publicizise ( $item );
		}
		
		return $array;
	}
}