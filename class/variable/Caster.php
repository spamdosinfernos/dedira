<?php

class Caster {

	/**
	 * Class casting
	 * @param object $destination
	 * @param object $sourceObject
	 * @return object
	 */
	public static function classToClassCast($sourceObject, string $destination) {

		// Returns the translated object!
		return unserialize ( sprintf ( 'O:%d:"%s"%s', strlen ( $destination ), $destination, strstr ( strstr ( serialize ( $sourceObject ), '"' ), ':' ) ) );
	}

	/**
	 * Retrieve all parent properties if any
	 * @param ReflectionClass $obj
	 * @param array $sourceProperties
	 *        	- the list of properties to add
	 */
	private static function retriveParentProperties(ReflectionClass $obj, array &$sourceProperties) {
		if ($parentClass = $obj->getParentClass ()) {

			// Recursing
			Caster::retriveParentProperties ( $parentClass, $sourceProperties );
			$sourceProperties = array_merge ( $parentClass->getProperties (), $sourceProperties );
		}
	}

	/**
	 * Array casting
	 * @param object $destination
	 * @param array $sourceArray
	 * @return object
	 */
	public static function arrayToClassCast($sourceArray, $destination) {
		$object = json_decode ( json_encode ( $sourceArray, 4 ), false );
		return Caster::classToClassCast ( $object, $destination );
	}
}