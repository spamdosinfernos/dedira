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
		$sourceProperties = $destinationReflection->getProperties ();
		
		Caster::retriveParentProperties ( $destinationReflection, $sourceProperties );
		
		// translate!
		foreach ( $sourceProperties as $sourceProperty ) {
			
			// Only updates the existing properties in the destiny
			if (! $sourceReflection->hasProperty ( $sourceProperty->getName () )) {
				continue;
			}
			
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
			$propDest = $destinationReflection->getProperty ( $name );
			$propDest->setAccessible ( true );
			$propDest->setValue ( $destination, $value );
		}
		
		// Returns the translated object!
		return $destination;
	}
	
	/**
	 * Retrieve all parent properties if any
	 * @param ReflectionClass $obj
	 * @param array $sourceProperties - the list of properties to add
	 */
	private static function retriveParentProperties(ReflectionClass $obj, array &$sourceProperties) {
		if ($parentClass = $obj->getParentClass ()) {
			
			//Recursing
			Caster::retriveParentProperties ( $parentClass, $sourceProperties );
			$sourceProperties = array_merge ( $parentClass->getProperties (), $sourceProperties );
		}
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