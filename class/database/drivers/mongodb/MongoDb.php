<?php
require __DIR__ . '/../../../../lib/vendor/autoload.php';

require_once __DIR__ . '/../../DatabaseConditions.php';
require_once __DIR__ . '/../../DatabaseRequestedData.php';
require_once __DIR__ . '/../../interfaces/IDatabaseDriver.php';

require_once __DIR__ . '/../../../log/Log.php';
require_once __DIR__ . '/../../../variable/Caster.php';
require_once __DIR__ . '/../../../configuration/Configuration.php';
require_once __DIR__ . '/../../../variable/ClassPropertyPublicizator.php';

require_once __DIR__ . '/DatetimeToMongoDatePublicizator.php';
require_once __DIR__ . '/MongoDateToDatetimePublicizator.php';
require_once __DIR__ . '/MongoObjectIdPublicizatorToSimpleID.php';
require_once __DIR__ . '/MongoBSONArrayToArrayPublicizator.php';

/**
 * @author ensismoebius
 */
class MongoDb implements IDatabaseDriver {

	/**
	 * The database connection
	 * @var MongoDB\Client
	 */
	private $connection;

	/**
	 * Guarda resultado da consulta
	 * @var DatabaseRequestedData
	 */
	private $result;

	/**
	 * Stores the query that will be executed
	 * @var DatabaseQuery
	 */
	private $query;

	/**
	 * Stores the entity name manipulated in query
	 * @var string
	 */
	private $entityName;

	/**
	 * Publicitize all properties
	 * @var ClassPropertyPublicizator
	 */
	private $classPublicizator;

	public function __construct() {
		$this->classPublicizator = new ClassPropertyPublicizator ();
		$this->classPublicizator->addSpecialTypePublicizator ( new DatetimeToMongoDatePublicizator () );
		$this->classPublicizator->addSpecialTypePublicizator ( new MongoBSONArrayToArrayPublicizator () );
		$this->classPublicizator->addSpecialTypePublicizator ( new MongoObjectIdPublicizatorToSimpleID () );

		$this->result = new DatabaseRequestedData ();
		$this->entityName = "";
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see IDatabaseDriver::connect()
	 */
	public function connect(string $databaseHostProtocol, string $databaseHostAddress, int $databasePort, string $user, string $password): bool {
		try {
			$url = "$databaseHostProtocol://$databaseHostAddress:$databasePort";
			$this->connection = new MongoDB\Client ( $url );

			// If nothing went wrong so everything went well ;)
			return true;
		}
		catch ( MongoDB\Exception\Exception $e ) {
			Log::recordEntry ( $e->getMessage () );
			return false;
		}
		return false;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see IDatabaseDriver::disconnect()
	 */
	public function disconnect(): bool {
		// For some reason looks like we cant close the connection
		return true;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see IDatabaseDriver::execute()
	 */
	public function execute(DatabaseQuery $query): bool {
		$this->query = $query;

		if (is_null ( $this->connection )) {
			Log::recordEntry ( "Not connected to database!" );
			return false;
		}

		$reflection = new ReflectionClass ( $query->getObject () );
		$this->entityName = $reflection->getName ();

		return $this->executeQuery ();
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see IDatabaseDriver::getResults()
	 */
	public function getResults(): DatabaseRequestedData {
		return $this->result;
	}

	/**
	 * Generates the query string
	 * @return bool
	 */
	private function executeQuery(): bool {
		switch ($this->query->getOperationType ()) {
			case DatabaseQuery::OPERATION_GET :
				return $this->doRead ();
			case DatabaseQuery::OPERATION_PUT :
				return $this->doInsert ();
			case DatabaseQuery::OPERATION_UPDATE :
				return $this->doUpdate ();
			case DatabaseQuery::OPERATION_ERASE :
				return $this->doDelete ();
			default :
				Log::recordEntry ( "Unsuported operation" );
				return false;
		}
	}

	/**
	 * Generates the update query
	 * @return string
	 */
	private function doUpdate(): bool {
		try {
			$collection = $this->connection->selectCollection ( Configuration::$databaseNAme, $this->entityName );
			$updateResult = $collection->updateMany ( $this->buildFilters (), $this->buildModifiers (), [ 
					'multi' => true,
					'upsert' => false
			] );

			$this->result->setData ( array (
					$updateResult->isAcknowledged()
			) );
			return true;
		}
		catch ( MongoDB\Exception\Exception $e ) {
			Log::recordEntry ( $e->getMessage () );
		}

		$this->result->setData ( array () );
		return false;
	}

	/**
	 * Generates the insert query
	 * @return string
	 */
	private function doInsert(): bool {
		try {
			// Retrieves the collection to insert
			$collection = $this->connection->{Configuration::$databaseNAme}->{$this->entityName};

			// Inserts the data
			$insertResult = $collection->insertMany ( array (
					$this->classPublicizator->publicizise ( $this->query->getObject () )
			) );

			$this->result->setData ( array (
					( string ) $insertResult->getInsertedIds () [0]
			) );
			return true;
		}
		catch ( MongoDB\Exception\Exception $e ) {
			Log::recordEntry ( $e->getMessage () );
		}

		$this->result->setData ( array () );
		return false;
	}

	/**
	 * Generates the delete query
	 * @return string
	 */
	private function doDelete(): bool {
		try {
			// Retrieves the collection to delete
			$collection = $this->connection->{Configuration::$databaseNAme}->{$this->entityName};

			// Inserts the data
			$deleteResult = $collection->deleteMany ( $this->buildFilters () );

			$this->result->setData ( array (
					$deleteResult->getDeletedCount ()
			) );
			return true;
		}
		catch ( MongoDB\Exception\Exception $e ) {
			Log::recordEntry ( $e->getMessage () );
		}

		$this->result->setData ( array (
				0
		) );
		return false;
	}

	/**
	 * Generates the select query
	 * @return string
	 */
	private function doRead(): bool {

		// Retrieves the collection to delete
		$collection = $this->connection->{Configuration::$databaseNAme}->{$this->entityName};

		try {

			$options = array ();
			if ($this->query->getLimit () > 0) {
				$options ["limit"] = $this->query->getLimit ();
			}

			$cursor = $collection->find ( $this->buildFilters (), $options );

			// Stores all matched documents
			$result = array ();
			foreach ( $cursor as $document ) {
				// Even when all attributes are public we use the publicizator
				// to convert some mongo types to native types

				$document = ( object ) $document->getArrayCopy ();

				$document = Caster::classToClassCast ( $document, $this->entityName );
				$document = $this->classPublicizator->publicizise ( $document );
				$document = Caster::classToClassCast ( $document, $this->entityName );

				$result [] = $document;
			}

			$this->result->setData ( $result );

			return true;
		}
		catch ( MongoDB\Exception\Exception $e ) {
			Log::recordEntry ( $e->getMessage () );
		}
		return false;
	}

	/**
	 * Build the modifiers for updates
	 * @return array
	 */
	protected function buildModifiers(): array {
		$arrChanges = $this->query->getObject ()->getArrChanges ();

		$adders = array ();
		$setters = array ();
		$removers = array ();

		foreach ( $arrChanges as $changeType => $arrFieldValues ) {

			if ($changeType == AStorableObject::UNITARY) {

				foreach ( $arrFieldValues as $key => $value ) {

					if (is_a ( $value, "DateTime" )) {
						$value = $this->datetimeConverter->convert ( $value );
					}

					@$setters ['$set']->$key = $value;
				}
				continue;
			}

			if ($changeType == AStorableObject::COLLECTION_ADD) {
				foreach ( $arrFieldValues as $key => $arrValues ) {
					foreach ( $arrValues as $value ) {

						if (is_a ( $value, "DateTime" )) {
							$value = $this->datetimeConverter->convert ( $value );
						}

						$adders ['$addToSet']->$key ['$each'] [] = $value;
					}
				}
				continue;
			}

			if ($changeType == AStorableObject::COLLECTION_REMOVE) {
				foreach ( $arrFieldValues as $key => $arrValues ) {
					foreach ( $arrValues as $value ) {

						if (is_a ( $value, "DateTime" )) {
							$value = $this->datetimeConverter->convert ( $value );
						}

						$removers ['$pull']->$key ['$in'] [] = $value;
					}
				}
				continue;
			}
		}

		return array_merge ( $setters, $adders, $removers );
	}

	/**
	 * Builds the filter clause
	 * @return array
	 */
	protected function buildFilters(): array {

		// Filter for documents
		$arrFilter = array ();

		// Creates the filter
		foreach ( $this->query->getConditions ()->getTokens () as $type => $arrToken ) {

			foreach ( $arrToken as $field => $value ) {

				if ($field == "_id") {
					$value = new MongoDB\BSON\ObjectID ( strtolower ( $value ) );
				}

				if (is_a ( $value, "DateTime" )) {
					$value = $this->datetimeConverter->convert ( $value );
				}

				if (is_numeric ( $value )) {
					if (is_int ( $value )) $value = intval ( $value );
					if (is_float ( $value )) $value = floatval ( $value );
				}

				switch ($type) {
					case DatabaseConditions::AND :
						$arrFilter [$field] = $value;
						continue;
					case DatabaseConditions::AND_LIKE :
						$arrFilter [$field] = ".*" . $value . ".*";
						continue;
					case DatabaseConditions::OR :
						$arrFilter ['$or'] [] = array (
								$field => $value
						);
						continue;
					case DatabaseConditions::OR_LIKE :
						$arrFilter ['$or'] [] = array (
								$field => ".*" . $value . ".*"
						);
						continue;
				}
			}
		}

		return $arrFilter;
	}
}
?>