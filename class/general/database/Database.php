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
	 */
	private static $driver;
	
	/**
	 * Initializes the database
	 */
	public static function init(IDatabaseDriver $driver) {
		self::$driver = $driver;
	}
	
	/**
	 * Connects to database
	 */
	public static function connect(): bool {
		return self::$driver->connect ();
	}
	
	/**
	 * Disconnect from database
	 */
	public static function disconnect(): bool {
		return self::$driver->disconnect ();
	}
	
	/**
	 * Execute a query
	 */
	public static function execute(DatabaseQuery $query): bool {
		return self::$driver->execute ( $query );
	}
	
	/**
	 * Retrive results
	 */
	public static function getResults(): DatabaseRequestedData {
		return self::$driver->getResults ();
	}
}
?>