<?php
require_once '../general/variable/ClassPropertyPublicizator.php';

/**
 * Generates a JSON expression
 *
 * @author ensismoebius
 *        
 */
class JSONGenerator {
	/**
	 * Creates a json expression from an object
	 *
	 * @param mixed $object        	
	 * @param boolean $pretty        	
	 * @return string
	 */
	// FIXME not perfect some structures can not be converted
	public static function objectToJson($object, $pretty = false): string {
		
		// Returns the json
		if ($pretty) {
			return json_encode ( ClassPropertyPublicizator::publicizise ( $object ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT );
		}
		return json_encode ( ClassPropertyPublicizator::publicizise ( $object ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK );
	}
}
