<?php

/**
 * Generates a JSON expression
 * @author ensismoebius
 *
 */
class JSONGenerator {
	/**
	 * Creates a json expression from an object
	 *
	 * @param mixed $object        	
	 * @return string
	 */
	public static function objectToJson($object): string {
		
		// Returns the json
		return json_encode ( self::createJSONable ( $object ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT );
	}
	
	/**
	 * Creates an generic object with all properties as public
	 * or an array if it is the case
	 *
	 * @param object $variable        	
	 * @return mixed
	 */
	private static function createJSONable($variable) {
		if (! is_object ( $variable )) {
			
			if (is_array ( $variable )) {
				return self::convertArray ( $variable );
			}
			
			return $variable;
		}
		
		// Used to read the properties including the private ones
		$reflection = new ReflectionClass ( $variable );
		
		// A genereric object that will turn on json
		$obj = new stdClass ();
		
		// Reading the properties
		foreach ( $reflection->getProperties () as $property ) {
			$property->setAccessible ( true );
			
			$value = $property->getValue ( $variable );
			
			// May happens that we have an array of objects
			if (is_array ( $value )) {
				$value = self::convertArray ( $value );
			}
			
			// If item is an object create a generic object of it
			if (is_object ( $value )) {
				$value = self::createJSONable ( $value );
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
	private static function convertArray(array &$array): array {
		
		// We must check every single item
		foreach ( $array as &$item ) {
			if (! is_object ( $item )) {
				continue;
			}
			
			// If the array item is an object create a generic object of it
			$item = self::createJSONable ( $item );
		}
		
		return $array;
	}
}
