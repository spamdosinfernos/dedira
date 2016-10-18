<?php
require_once __DIR__ . '/../configuration/Configuration.php';
/**
 * Records log
 */
class Log {
	
	/**
	 *
	 * @var string
	 */
	const CONST_FIELD_SEPARATOR = "\t";
	
	/**
	 * Saves the log entries
	 */
	public static function recordEntry(string $message, $exposeMessage = false) {
		if (trim ( $message ) == "") return;
		
		if ($exposeMessage) echo $message;
		
		$logFilePath = Configuration::getLogFilePath ();
		$message = date ( "Y-m-d H:i:s" ) . self::CONST_FIELD_SEPARATOR . $message . PHP_EOL;
		
		try {
			if (is_file ( $logFilePath )) {
				file_put_contents ( $logFilePath, $message, FILE_APPEND );
			}
		} catch ( Exception $error ) {
			echo "Fatal error!! The log system are not working!";
			exit ( 1 );
		}
	}
}
?>