<?php
require_once __DIR__ . '/../database/interfaces/IDatabaseDriver.php';
require_once __DIR__ . '/../database/drivers/mongodb/MongoDatabaseDriver.php';

/**
 * Centraliza todas as configurações do sistema
 *
 * @author André Furlan
 *        
 */
class Configuration {
	
	

	public static function init(): boolean {
		parse_ini_file ( "./config.ini", true );
		
		
		$reflection = new ReflectionClass($this);
		
		$statics = $reflection->getStaticProperties();
		
		foreach ($statics as $staticProperty) {
			$staticProperty-
		}
		
		
		
	}
	
	/**
	 * System default charset
	 * 
	 * @var string
	 */
	private static $charset = "UTF-8";
	
	/**
	 * The host address
	 * 
	 * @var string
	 */
	public static $hostAddress = "http://localhost/MiliSystem";
	
	/**
	 * Default cryptography when sending something using email
	 *
	 * @var string
	 */
	public static $mailCryptography = "tls";
	
	/**
	 * Default server port
	 *
	 * @var int
	 */
	public static $mailPort = 587;
	
	/**
	 * Default password when sending something using email
	 *
	 * @var string
	 */
	public static $mailPassword = "tatu7172";
	
	/**
	 * Default email protocol
	 *
	 * @var string
	 */
	public static $mailProtocol = "smtp";
	
	/**
	 * Default username when sending something using email
	 *
	 * @var string
	 */
	public static $mailUsername = "ensismoebius@gmail.com";
	
	/**
	 * Default email when sending something using email
	 *
	 * @var string
	 */
	public static $mailServer = "smtp.gmail.com";
	
	/**
	 * Default email when sending something
	 *
	 * @var string
	 */
	public static $mailFrom = "libertas@libertas.org";
	
	/**
	 * Default name for translantions file
	 *
	 * @var string
	 */
	public static $localeDirName = "lang";
	
	/**
	 * The main page name, it should be loaded after authentication
	 *
	 * @var string
	 */
	public static $mainPageName = "main";
	
	/**
	 * The authentication page name, it should be loaded before authentication
	 *
	 * @var string
	 */
	public static $authenticationPageName = "userAuthenticaticator";
	
	/**
	 * The file name for the page
	 *
	 * @var string
	 */
	public static $pageFileName = "page";
	
	/**
	 * Usuário do banco de dados
	 *
	 * @var string
	 */
	public static $databaseUsername = "root";
	
	/**
	 * Senha do banco de dados
	 *
	 * @var string
	 */
	public static $databasePassword = "1234";
	
	/**
	 * Nome dabBase de dados
	 *
	 * @var string
	 */
	public static $databaseNAme = "milisystem";
	
	/**
	 * Endereço do servidor de banco de dados
	 *
	 * @var string
	 */
	public static $databaseHostAddress = "127.0.0.1";
	
	/**
	 * Protocolo do servidor de banco de dados
	 *
	 * @var string
	 */
	public static $databaseHostProtocol = "mongodb";
	
	/**
	 * Porta np servidor de banco de dados
	 *
	 * @var int
	 */
	public static $databasePort = 27017;
	
	/**
	 * Formato da data no arquivo de log
	 *
	 * @var string
	 */
	public static $dateformat = "Y-m-d H:i:s";
	
	/**
	 * Path to default css files
	 */
	public static $cssPath = "./lib/purecss/";
	
	/**
	 * Indica o caminho do diretório raiz do sistema
	 *
	 * @var string
	 */
	private static $systemRootDirectory;
	static public function getSystemRootDirectory() {
		if (self::$systemRootDirectory == "") {
			self::$systemRootDirectory = realpath ( dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." );
		}
		return self::$systemRootDirectory;
	}
	static public function getPageFileName() {
		return "Page.php";
	}
	static public function getPagesDiretory() {
		return self::getSystemRootDirectory () . DIRECTORY_SEPARATOR . "pages";
	}
	
	/**
	 * O caminho do log não pode ser modificado pois os erros podem
	 * ocorrer e devem ser gravados antes mesmo das configurações
	 * serem carregadas, sendo assim o caminho do log é definido
	 * no próprio código
	 *
	 * @return string
	 */
	static public function getLogFilePath() {
		return self::getSystemRootDirectory () . DIRECTORY_SEPARATOR . "log" . DIRECTORY_SEPARATOR . "sistema.log";
	}
	
	/**
	 * Retorna o intervalo de tempo padrão que o sistema adota
	 *
	 * @return string - (um sinal [+ ou -] mais um número indicando a quantidade)
	 */
	static public function getDefaultTimeInterval() {
		return array (
				"+1" 
		);
	}
	
	/**
	 * Retorna o tipo intervalo de tempo padrão que o sistema adota
	 *
	 * @return string - (year = ano, month = mês, day = dia, hour = hora, minute = minuto, segundo = second)
	 */
	static public function getDefaultTimeIntervalType() {
		return "month";
	}
	static public function getSelectedLanguage() {
		return "pt_BR";
	}
	
	/**
	 * Returns the default database driver
	 *
	 * @return IDatabaseDriver
	 */
	static public function getDatabaseDriver(): IDatabaseDriver {
		return new MongoDatabaseDriver ();
	}
}
?>