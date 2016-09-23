<?php
/**
 * A static class that manages the database
 *
 * @author André Furlan
 *        
 */
class Database {
	
	/**
	 * The database driver
	 *
	 * @var IDatabaseDriver
	 */
	private static $driver;
	
	/**
	 * Initializes the database
	 *
	 * @param IDatabaseDriver $driver        	
	 */
	public static function init(IDatabaseDriver $driver) {
		self::$driver = $driver;
	}
	
	/**
	 * Connects to database
	 *
	 * @see IDatabaseDriver::connect()
	 */
	public static function connect(): bool {
		return self::$driver->connect ();
	}
	
	/**
	 * Disconnect from database
	 *
	 * @see IDatabaseDriver::disconnect()
	 */
	public static function disconnect(): bool {
		return self::$driver->disconnect ();
	}
	
	/**
	 * Execute a query
	 *
	 * @see IDatabaseDriver::execute()
	 */
	public static function execute(IDatabaseQuery $query): bool {
		return self::$driver->execute ( $query );
	}
	
	/**
	 * Retrive results
	 *
	 * {@inheritdoc}
	 *
	 * @see IDatabaseDriver::getResults()
	 */
	public static function getResults(): IDatabaseRequestedData {
		return self::$driver->getResults ();
	}
}
?>