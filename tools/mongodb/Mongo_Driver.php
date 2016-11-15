<?php

namespace MongoDB\Driver;

final class Command {
	
	/**
	 *
	 * @param
	 *        	$document
	 */
	final public function __construct($document) {
	}
	public function __wakeup() {
	}
}
final class Cursor implements Traversable {
	final private function __construct() {
	}
	
	/**
	 *
	 * @param
	 *        	$typemap
	 */
	final public function setTypeMap(array $typemap) {
	}
	final public function toArray() {
	}
	final public function getId() {
	}
	final public function getServer() {
	}
	final public function isDead() {
	}
	public function __wakeup() {
	}
}
final class CursorId {
	final private function __construct() {
	}
	final public function __toString() {
	}
	public function __wakeup() {
	}
}
final class Manager {
	
	/**
	 *
	 * @param $uri [optional]        	
	 * @param $options [optional]        	
	 * @param $driverOptions [optional]        	
	 */
	final public function __construct($uri = nullarray, $options = nullarray, $driverOptions = null) {
	}
	
	/**
	 *
	 * @param
	 *        	$db
	 * @param MongoDB\Driver\Command $command        	
	 * @param MongoDB\Driver\ReadPreference $readPreference
	 *        	[optional]
	 */
	final public function executeCommand($db, MongoDB\Driver\Command $command, MongoDB\Driver\ReadPreference $readPreference = null) {
	}
	
	/**
	 *
	 * @param
	 *        	$namespace
	 * @param MongoDB\Driver\Query $query        	
	 * @param MongoDB\Driver\ReadPreference $readPreference
	 *        	[optional]
	 */
	final public function executeQuery($namespace, MongoDB\Driver\Query $query, MongoDB\Driver\ReadPreference $readPreference = null) {
	}
	
	/**
	 *
	 * @param
	 *        	$namespace
	 * @param MongoDB\Driver\BulkWrite $bulk        	
	 * @param MongoDB\Driver\WriteConcern $writeConcern
	 *        	[optional]
	 */
	final public function executeBulkWrite($namespace, MongoDB\Driver\BulkWrite $bulk, MongoDB\Driver\WriteConcern $writeConcern = null) {
	}
	final public function getReadConcern() {
	}
	final public function getReadPreference() {
	}
	final public function getServers() {
	}
	final public function getWriteConcern() {
	}
	
	/**
	 *
	 * @param MongoDB\Driver\ReadPreference $readPreference        	
	 */
	final public function selectServer(MongoDB\Driver\ReadPreference $readPreference = null) {
	}
	public function __wakeup() {
	}
}
final class Query {
	
	/**
	 *
	 * @param
	 *        	$filter
	 * @param $options [optional]        	
	 */
	final public function __construct($filterarray, $options = null) {
	}
	public function __wakeup() {
	}
}
final class ReadConcern implements MongoDB\BSON\Serializable, MongoDB\BSON\Type {
	const LOCAL = "local";
	const MAJORITY = "majority";
	const LINEARIZABLE = "linearizable";
	
	/**
	 *
	 * @param $level [optional]        	
	 */
	final public function __construct($level = null) {
	}
	final public function getLevel() {
	}
	final public function bsonSerialize() {
	}
}
final class ReadPreference implements MongoDB\BSON\Serializable, MongoDB\BSON\Type {
	const RP_PRIMARY = 1;
	const RP_PRIMARY_PREFERRED = 5;
	const RP_SECONDARY = 2;
	const RP_SECONDARY_PREFERRED = 6;
	const RP_NEAREST = 10;
	
	/**
	 *
	 * @param
	 *        	$mode
	 * @param $tagSets [optional]        	
	 * @param $options [optional]        	
	 */
	final public function __construct($modearray, $tagSets = nullarray, $options = null) {
	}
	final public function getMaxStalenessMS() {
	}
	final public function getMode() {
	}
	final public function getTagSets() {
	}
	final public function bsonSerialize() {
	}
}
final class Server {
	const TYPE_UNKNOWN = 0;
	const TYPE_STANDALONE = 1;
	const TYPE_MONGOS = 2;
	const TYPE_POSSIBLE_PRIMARY = 3;
	const TYPE_RS_PRIMARY = 4;
	const TYPE_RS_SECONDARY = 5;
	const TYPE_RS_ARBITER = 6;
	const TYPE_RS_OTHER = 7;
	const TYPE_RS_GHOST = 8;
	final private function __construct() {
	}
	
	/**
	 *
	 * @param
	 *        	$db
	 * @param MongoDB\Driver\Command $command        	
	 * @param MongoDB\Driver\ReadPreference $readPreference
	 *        	[optional]
	 */
	final public function executeCommand($db, MongoDB\Driver\Command $command, MongoDB\Driver\ReadPreference $readPreference = null) {
	}
	
	/**
	 *
	 * @param
	 *        	$namespace
	 * @param MongoDB\Driver\Query $zquery        	
	 * @param MongoDB\Driver\ReadPreference $readPreference
	 *        	[optional]
	 */
	final public function executeQuery($namespace, MongoDB\Driver\Query $query, MongoDB\Driver\ReadPreference $readPreference = null) {
	}
	
	/**
	 *
	 * @param
	 *        	$namespace
	 * @param MongoDB\Driver\BulkWrite $zbulk        	
	 * @param MongoDB\Driver\WriteConcern $writeConcern
	 *        	[optional]
	 */
	final public function executeBulkWrite($namespace, MongoDB\Driver\BulkWrite $bulk, MongoDB\Driver\WriteConcern $writeConcern = null) {
	}
	final public function getHost() {
	}
	final public function getTags() {
	}
	final public function getInfo() {
	}
	final public function getLatency() {
	}
	final public function getPort() {
	}
	final public function getType() {
	}
	final public function isPrimary() {
	}
	final public function isSecondary() {
	}
	final public function isArbiter() {
	}
	final public function isHidden() {
	}
	final public function isPassive() {
	}
	public function __wakeup() {
	}
}
final class BulkWrite implements Countable {
	
	/**
	 *
	 * @param $options [optional]        	
	 */
	final public function __construct(array $options = null) {
	}
	
	/**
	 *
	 * @param
	 *        	$document
	 */
	final public function insert($document) {
	}
	
	/**
	 *
	 * @param
	 *        	$query
	 * @param
	 *        	$newObj
	 * @param $updateOptions [optional]        	
	 */
	final public function update($query, $newObjarray, $updateOptions = null) {
	}
	
	/**
	 *
	 * @param
	 *        	$query
	 * @param $deleteOptions [optional]        	
	 */
	final public function delete($queryarray, $deleteOptions = null) {
	}
	final public function count() {
	}
	public function __wakeup() {
	}
}
final class WriteConcern implements MongoDB\BSON\Serializable, MongoDB\BSON\Type {
	const MAJORITY = "majority";
	
	/**
	 *
	 * @param
	 *        	$w
	 * @param $wtimeout [optional]        	
	 * @param $journal [optional]        	
	 */
	final public function __construct($w, $wtimeout = null, $journal = null) {
	}
	final public function getW() {
	}
	final public function getWtimeout() {
	}
	final public function getJournal() {
	}
	final public function bsonSerialize() {
	}
}
final class WriteConcernError {
	final private function __construct() {
	}
	final public function getCode() {
	}
	final public function getInfo() {
	}
	final public function getMessage() {
	}
	public function __wakeup() {
	}
}
final class WriteError {
	final private function __construct() {
	}
	final public function getCode() {
	}
	final public function getIndex() {
	}
	final public function getMessage() {
	}
	final public function getInfo() {
	}
	public function __wakeup() {
	}
}
final class WriteResult {
	final private function __construct() {
	}
	final public function getInsertedCount() {
	}
	final public function getMatchedCount() {
	}
	final public function getModifiedCount() {
	}
	final public function getDeletedCount() {
	}
	final public function getUpsertedCount() {
	}
	final public function getServer() {
	}
	final public function getUpsertedIds() {
	}
	final public function getWriteConcernError() {
	}
	final public function getWriteErrors() {
	}
	final public function isAcknowledged() {
	}
	public function __wakeup() {
	}
}
?>
