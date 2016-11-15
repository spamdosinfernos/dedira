<?php

namespace MongoDB\BSON;

interface Type {
}
interface Serializable extends MongoDB\BSON\Type {
	abstract public function bsonSerialize() {
	}
}
interface Unserializable {
	
	/**
	 *
	 * @param
	 *        	$data
	 */
	abstract public function bsonUnserialize(array $data) {
	}
}
interface Persistable extends MongoDB\BSON\Unserializable, MongoDB\BSON\Serializable, MongoDB\BSON\Type {
	
	/**
	 *
	 * @param
	 *        	$data
	 */
	abstract public function bsonUnserialize(array $data) {
	}
	abstract public function bsonSerialize() {
	}
}
final class Binary implements MongoDB\BSON\Type, Serializable {
	const TYPE_GENERIC = 0;
	const TYPE_FUNCTION = 1;
	const TYPE_OLD_BINARY = 2;
	const TYPE_OLD_UUID = 3;
	const TYPE_UUID = 4;
	const TYPE_MD5 = 5;
	const TYPE_USER_DEFINED = 128;
	
	/**
	 *
	 * @param
	 *        	$data
	 * @param
	 *        	$type
	 */
	final public function __construct($data, $type) {
	}
	
	/**
	 *
	 * @param
	 *        	$properties
	 */
	public static function __set_state(array $properties) {
	}
	final public function __toString() {
	}
	final public function serialize() {
	}
	
	/**
	 *
	 * @param
	 *        	$serialized
	 */
	final public function unserialize($serialized) {
	}
	final public function getData() {
	}
	final public function getType() {
	}
}
final class Decimal128 implements MongoDB\BSON\Type, Serializable {
	
	/**
	 *
	 * @param
	 *        	$value
	 */
	final public function __construct($value) {
	}
	
	/**
	 *
	 * @param
	 *        	$properties
	 */
	public static function __set_state(array $properties) {
	}
	final public function __toString() {
	}
	final public function serialize() {
	}
	
	/**
	 *
	 * @param
	 *        	$serialized
	 */
	final public function unserialize($serialized) {
	}
}
final class Javascript implements MongoDB\BSON\Type, Serializable {
	
	/**
	 *
	 * @param
	 *        	$javascript
	 * @param $scope [optional]        	
	 */
	final public function __construct($javascript, $scope = null) {
	}
	
	/**
	 *
	 * @param
	 *        	$properties
	 */
	public static function __set_state(array $properties) {
	}
	final public function __toString() {
	}
	final public function serialize() {
	}
	
	/**
	 *
	 * @param
	 *        	$serialized
	 */
	final public function unserialize($serialized) {
	}
	final public function getCode() {
	}
	final public function getScope() {
	}
}
final class MaxKey implements MongoDB\BSON\Type, Serializable {
	
	/**
	 *
	 * @param
	 *        	$properties
	 */
	public static function __set_state(array $properties) {
	}
	final public function serialize() {
	}
	
	/**
	 *
	 * @param
	 *        	$serialized
	 */
	final public function unserialize($serialized) {
	}
}
final class MinKey implements MongoDB\BSON\Type, Serializable {
	
	/**
	 *
	 * @param
	 *        	$properties
	 */
	public static function __set_state(array $properties) {
	}
	final public function serialize() {
	}
	
	/**
	 *
	 * @param
	 *        	$serialized
	 */
	final public function unserialize($serialized) {
	}
}
final class ObjectID implements MongoDB\BSON\Type, Serializable {
	
	/**
	 *
	 * @param $id [optional]        	
	 */
	final public function __construct($id = null) {
	}
	final public function getTimestamp() {
	}
	
	/**
	 *
	 * @param
	 *        	$properties
	 */
	public static function __set_state(array $properties) {
	}
	final public function __toString() {
	}
	final public function serialize() {
	}
	
	/**
	 *
	 * @param
	 *        	$serialized
	 */
	final public function unserialize($serialized) {
	}
}
final class Regex implements MongoDB\BSON\Type, Serializable {
	
	/**
	 *
	 * @param
	 *        	$pattern
	 * @param
	 *        	$flags
	 */
	final public function __construct($pattern, $flags) {
	}
	
	/**
	 *
	 * @param
	 *        	$properties
	 */
	public static function __set_state(array $properties) {
	}
	final public function __toString() {
	}
	final public function serialize() {
	}
	
	/**
	 *
	 * @param
	 *        	$serialized
	 */
	final public function unserialize($serialized) {
	}
	final public function getPattern() {
	}
	final public function getFlags() {
	}
}
final class Timestamp implements MongoDB\BSON\Type, Serializable {
	
	/**
	 *
	 * @param
	 *        	$increment
	 * @param
	 *        	$timestamp
	 */
	final public function __construct($increment, $timestamp) {
	}
	
	/**
	 *
	 * @param
	 *        	$properties
	 */
	public static function __set_state(array $properties) {
	}
	final public function __toString() {
	}
	final public function serialize() {
	}
	
	/**
	 *
	 * @param
	 *        	$serialized
	 */
	final public function unserialize($serialized) {
	}
}
final class UTCDateTime implements MongoDB\BSON\Type, Serializable {
	
	/**
	 *
	 * @param
	 *        	$milliseconds
	 */
	final public function __construct($milliseconds) {
	}
	
	/**
	 *
	 * @param
	 *        	$properties
	 */
	public static function __set_state(array $properties) {
	}
	final public function __toString() {
	}
	final public function serialize() {
	}
	
	/**
	 *
	 * @param
	 *        	$serialized
	 */
	final public function unserialize($serialized) {
	}
	final public function toDateTime() {
	}
}

/**
 *
 * @param
 *        	$value
 */
function fromPHP($value) {
}

/**
 *
 * @param
 *        	$bson
 */
function toPHP($bson) {
}

/**
 *
 * @param
 *        	$bson
 */
function toJSON($bson) {
}

/**
 *
 * @param
 *        	$json
 */
function fromJSON($json) {
}

define ( 'MONGODB_VERSION', "1.2.0-dev" );
define ( 'MONGODB_STABILITY', "devel" );
?>
