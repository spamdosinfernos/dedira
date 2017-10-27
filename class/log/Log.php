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
	const FIELD_SEPARATOR = "\t";
	
	/**
	 * Saves the log entries
	 */
	public static function recordEntry(string $message, $exposeMessage = false) {
		if (trim ( $message ) == "")
			return;
		
		if ($exposeMessage)
			echo $message;
		
		$message = date ( "Y-m-d H:i:s" ) . self::FIELD_SEPARATOR . $message . PHP_EOL;
		
		try {
			if (! is_file ( Configuration::$logFilePath )) {
				echo "Fatal error!! The log system are not working! Theres no log file. CREATE ONE!";
				exit ( 3 );
			}
			
			// Tryes to write the log file
			$ret = file_put_contents ( Configuration::$logFilePath, $message . self::generateCallTrace (), FILE_APPEND );
			
			if (is_bool ( $ret )) {
				echo "Fatal error!! The log system are not working! Theres no permission to write the log file.";
				exit ( 3 );
			}
		} catch ( Exception $error ) {
			echo "Fatal error!! The log system are not working!";
			exit ( 1 );
		}
	}
	
	/**
	 * Generates a backtrace, helpfull for debugging purposes.
	 * Thanks to jurchiks101 from php.net foruns!
	 *
	 * @author jurchiks101 at gmail dot com
	 * @return string
	 */
	private static function generateCallTrace(): string {
		$e = new Exception ();
		$trace = explode ( "\n", $e->getTraceAsString () );
		
		// reverse array to make steps line up chronologically
		$trace = array_reverse ( $trace );
		
		// Removing uselless data
		array_shift ( $trace ); // remove {main}
		array_pop ( $trace ); // remove call to this method
		array_pop ( $trace ); // remove call to "recordEntry" method
		
		$length = count ( $trace );
		$result = array ();
		
		for($i = 0; $i < $length; $i ++) {
			$result [] = ($i + 1) . ')' . substr ( $trace [$i], strpos ( $trace [$i], ' ' ) );
		}
		
		return "\t" . implode ( "\n\t", $result ) . PHP_EOL;
	}
}
?>