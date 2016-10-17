<?php
class Caster {
	/**
	 * Class casting
	 *
	 * @param object $destination        	
	 * @param object $sourceObject        	
	 * @return object
	 */
	public static function classToClassCast($sourceObject, $destination) {
		
		// Creates the final object base
		if (is_string ( $destination )) {
			$destination = new $destination ();
		}
		
		// Read the both objects
		$sourceReflection = new ReflectionObject ( $sourceObject );
		$destinationReflection = new ReflectionObject ( $destination );
		
		// Get the properties to be translated
		$sourceProperties = $sourceReflection->getProperties ();
		
		// translate!
		foreach ( $sourceProperties as $sourceProperty ) {
			
			// Get the name and values to be translated
			$sourceProperty->setAccessible ( true );
			$name = $sourceProperty->getName ();
			$value = $sourceProperty->getValue ( $sourceObject );
			
			// If the property is an object translate it!
			if (is_object ( $value ) && isset ( $value->class )) {
				$value = self::classToClassCast ( $value, $value->class );
			}
			
			// If the property is an array of objects translate it!
			if (is_array ( $value ) && isset ( $value [0]->class )) {
				foreach ( $value as &$object ) {
					$object = self::classToClassCast ( $object, $object->class );
				}
			}
			
			// Set the corresponding values
			if ($destinationReflection->hasProperty ( $name )) {
				$propDest = $destinationReflection->getProperty ( $name );
				$propDest->setAccessible ( true );
				$propDest->setValue ( $destination, $value );
			} else {
				$destination->$name = $value;
			}
		}
		
		// Returns the translated object!
		return $destination;
	}
	
	/**
	 * Array casting
	 *
	 * @param object $destination        	
	 * @param array $sourceArray        	
	 * @return object
	 */
	public static function arrayToClassCast($sourceArray, $destination) {
		$object = json_decode ( json_encode ( $sourceArray ), false );
		return Caster::classToClassCast ( $object, $destination );
	}
}