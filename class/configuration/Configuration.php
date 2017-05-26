<?php
require_once __DIR__ . '/../database/interfaces/IDatabaseDriver.php';
require_once __DIR__ . '/../database/drivers/mongodb/MongoDb.php';

/**
 * Centraliza todas as configurações do sistema
 *
 * @author André Furlan
 *        
 */
class Configuration {
	
	/**
	 * System default charset
	 *
	 * @var string
	 */
	public static $charset;
	
	/**
	 * The host address
	 *
	 * @var string
	 */
	public static $hostAddress;
	
	/**
	 * Default cryptography when sending something using email
	 *
	 * @var string
	 */
	public static $mailCryptography;
	
	/**
	 * Default server port
	 *
	 * @var int
	 */
	public static $mailPort;
	
	/**
	 * Default password when sending something using email
	 *
	 * @var string
	 */
	public static $mailPassword;
	
	/**
	 * Default email protocol
	 *
	 * @var string
	 */
	public static $mailProtocol;
	
	/**
	 * Default username when sending something using email
	 *
	 * @var string
	 */
	public static $mailUsername;
	
	/**
	 * Default email when sending something using email
	 *
	 * @var string
	 */
	public static $mailServer;
	
	/**
	 * Default email when sending something
	 *
	 * @var string
	 */
	public static $mailFrom;
	
	/**
	 * Default name for translantions file
	 *
	 * @var string
	 */
	public static $localeDirName;
	
	/**
	 * The main page name, it should be loaded after authentication
	 *
	 * @var string
	 */
	public static $mainPageName;
	
	/**
	 * The authentication page name, it should be loaded before authentication
	 *
	 * @var string
	 */
	public static $authenticationPageName;
	
	/**
	 * The file name for the page
	 *
	 * @var string
	 */
	public static $pageParameterName;
	
	/**
	 * Usuário do banco de dados
	 *
	 * @var string
	 */
	public static $databaseUsername;
	
	/**
	 * Senha do banco de dados
	 *
	 * @var string
	 */
	public static $databasePassword;
	
	/**
	 * Nome dabBase de dados
	 *
	 * @var string
	 */
	public static $databaseNAme;
	
	/**
	 * Endereço do servidor de banco de dados
	 *
	 * @var string
	 */
	public static $databaseHostAddress;
	
	/**
	 * Protocolo do servidor de banco de dados
	 *
	 * @var string
	 */
	public static $databaseHostProtocol;
	
	/**
	 * Porta np servidor de banco de dados
	 *
	 * @var int
	 */
	public static $databasePort;
	
	/**
	 * Formato da data no arquivo de log
	 *
	 * @var string
	 */
	public static $dateformat;
	
	/**
	 * Path to default css files
	 */
	public static $cssPath;
	
	/**
	 * Indica o caminho do diretório raiz do sistema
	 *
	 * @var string
	 */
	public static $systemRootDirectory;
	
	/**
	 * The default file name for new pages on system
	 * 
	 * @var string
	 */
	public static $defaultPageFileName;
	
	/**
	 * O caminho do log não pode ser modificado pois os erros podem
	 * ocorrer e devem ser gravados antes mesmo das configurações
	 * serem carregadas, sendo assim o caminho do log é definido
	 * no próprio código
	 *
	 * @var string
	 */
	public static $logFilePath;
	
	/**
	 * The default system language
	 * 
	 * @var string
	 */
	public static $defaultLanguage;
	
	/**
	 * Returns the default database driver
	 *
	 * @var IDatabaseDriver
	 */
	public static $databaseDriver;
	
	/**
	 * Initializes the configuration of the system
	 */
	public static function init() {
		
		// Read the ini file
		$arrValues = parse_ini_file ( "./class/configuration/config.ini", true );
		
		// Auto generated configuration values
		self::$logFilePath = self::$systemRootDirectory . DIRECTORY_SEPARATOR . "log" . DIRECTORY_SEPARATOR . "sistema.log";
		self::$systemRootDirectory = realpath ( dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." );
		
		// This is mandatory! if some error occurs stop all!
		self::loadDataBaseDriver ( $arrValues ["databaseDriverName"] );
		
		// Default settings (may be override)
		self::$mainPageName = "main";
		self::$defaultLanguage = "pt_BR";
		self::$pageParameterName = "page";
		self::$defaultPageFileName = "Page.php";
		self::$authenticationPageName = "userAuthenticaticator";
		
		// Read and set the configurations
		$reflection = new ReflectionClass ( "Configuration" );
		$statics = $reflection->getStaticProperties ();
		foreach ( $statics as $name => $value ) {
			
			// If there is no such entry in ini file ignore
			if (! isset ( $arrValues [$name] ))
				continue;
				// Sets the value from ini file
			$reflection->setStaticPropertyValue ( $name, $arrValues [$name] );
		}
	}
	
	/**
	 * Loads the database driver (this is mandatory)
	 *
	 * @param string $driver        	
	 * @return IDatabaseDriver
	 */
	private static function loadDataBaseDriver(string $driver) {
		try {
			require_once self::$systemRootDirectory . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "database" . DIRECTORY_SEPARATOR . "drivers" . DIRECTORY_SEPARATOR . strtolower($driver) . DIRECTORY_SEPARATOR . $driver . ".php";
			$class = new ReflectionClass ( $driver );
			self::$databaseDriver = $class->newInstance ( null );
		} catch ( Exception $e ) {
			echo Log::recordEntry ( "Fail on load the database driver!" . $e->getMessage () );
			exit ( 2 );
		}
	}
}
?>