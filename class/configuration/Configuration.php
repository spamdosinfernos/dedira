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
	
	/**
	 * The host address
	 * @var string
	 */
	const HOST_ADDRESS = "http://localhost/MiliSystem";
	
	/**
	 * Default cryptography when sending something using email
	 *
	 * @var string
	 */
	const MAIL_CRYPTO = "tls";
	
	/**
	 * Default server port
	 *
	 * @var int
	 */
	const MAIL_PORT = 587;
	
	/**
	 * Default password when sending something using email
	 *
	 * @var string
	 */
	const MAIL_PASSWORD = "tatu7172";
	
	/**
	 * Default email protocol
	 *
	 * @var string
	 */
	const MAIL_PROTOCOL = "smtp";
	
	/**
	 * Default username when sending something using email
	 *
	 * @var string
	 */
	const MAIL_USERNAME = "ensismoebius@gmail.com";
	
	/**
	 * Default email when sending something using email
	 *
	 * @var string
	 */
	const MAIL_SERVER = "smtp.gmail.com";
	
	/**
	 * Default email when sending something
	 *
	 * @var string
	 */
	const MAIL = "libertas@libertas.org";
	
	/**
	 * Default name for translantions file
	 *
	 * @var string
	 */
	const LOCALE_DIR_NAME = "lang";
	
	/**
	 * The main page name, it should be loaded after authentication
	 *
	 * @var string
	 */
	const MAIN_PAGE_NAME = "main";
	
	/**
	 * The authentication page name, it should be loaded before authentication
	 *
	 * @var string
	 */
	const AUTHENTICATION_PAGE_NAME = "userAuthenticaticator";
	
	/**
	 * The file name for the page
	 *
	 * @var string
	 */
	const PAGE_VAR_NAME = "page";
	
	/**
	 * Extensão padrão do arquivo executável no sistema
	 *
	 * @var string
	 */
	const DEFAULT_EXECUTABLE_FILE_EXTENSION = "php";
	
	/**
	 * Usuário do banco de dados
	 *
	 * @var string
	 */
	const DB_LOGIN = "root";
	
	/**
	 * Senha do banco de dados
	 *
	 * @var string
	 */
	const DB_PASSWORD = "1234";
	
	/**
	 * Nome dabBase de dados
	 *
	 * @var string
	 */
	const DB_NAME = "milisystem";
	
	/**
	 * Endereço do servidor de banco de dados
	 *
	 * @var string
	 */
	const DB_HOST_ADDRESS = "127.0.0.1";
	
	/**
	 * Protocolo do servidor de banco de dados
	 *
	 * @var string
	 */
	const DB_HOST_PROTOCOL = "mongodb";
	
	/**
	 * Porta np servidor de banco de dados
	 *
	 * @var int
	 */
	const DB_PORT = 27017;
	
	/**
	 * Formato da data no arquivo de log
	 *
	 * @var string
	 */
	const DATE_FORMAT = "Y-m-d H:i:s";
	
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