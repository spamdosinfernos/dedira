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
		if (is_string ( $destination )) {
			$destination = new $destination ();
		}
		$sourceReflection = new ReflectionObject ( $sourceObject );
		$destinationReflection = new ReflectionObject ( $destination );
		$sourceProperties = $sourceReflection->getProperties ();
		foreach ( $sourceProperties as $sourceProperty ) {
			$sourceProperty->setAccessible ( true );
			$name = $sourceProperty->getName ();
			$value = $sourceProperty->getValue ( $sourceObject );
			if ($destinationReflection->hasProperty ( $name )) {
				$propDest = $destinationReflection->getProperty ( $name );
				$propDest->setAccessible ( true );
				$propDest->setValue ( $destination, $value );
			} else {
				$destination->$name = $value;
			}
		}
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