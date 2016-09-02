<?php
require_once __DIR__ . '/../../../language/class/general/configuration/Lang_Configuration.php';
class Configuration {
	
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
	const CONST_DB_LOGIN = "";
	
	/**
	 * Senha do banco de dados
	 * 
	 * @var string
	 */
	const CONST_DB_PASSWORD = "";
	
	/**
	 * Nome dabBase de dados
	 * 
	 * @var string
	 */
	const CONST_DB_NAME = "milisystem";
	
	/**
	 * Endereço do servidor de banco de dados
	 * 
	 * @var string
	 */
	const CONST_DB_HOST_ADDRESS = "127.0.0.1";
	
	/**
	 * Protocolo do servidor de banco de dados
	 * 
	 * @var string
	 */
	const CONST_DB_HOST_PROTOCOL = "http";
	
	/**
	 * Porta np servidor de banco de dados
	 * 
	 * @var int
	 */
	const CONST_DB_PORT = 5984;
	
	/**
	 * Formato da data no arquivo de log
	 * 
	 * @var string
	 */
	const CONST_DATE_FORMAT = "Y-m-d H:i:s";
	
	/**
	 * Indica o nome do módulo que está em ação
	 * 
	 * @var string
	 */
	const CONST_QUERY_STRING_MODULE_NAME_VAR_NAME = "module";
	
	/**
	 * Nome da classe que inicia a execução do módulo de usuário
	 * 
	 * @var string
	 */
	const CONST_USER_MODULE_STARTER_CLASS_NAME = "module";
	
	/**
	 * Indica o caminho do diretório raiz do sistema
	 * 
	 * @var string
	 */
	private static $systemRootDirectory;
	
	/**
	 * Mensagem exibida quando o sistema pede autenticação
	 * 
	 * @return string
	 */
	static public function getAuthMessage() {
		return Lang_Configuration::getDescriptions ( 1 );
	}
	static public function getSystemRootDirectory() {
		if (self::$systemRootDirectory == "") {
			self::$systemRootDirectory = realpath ( dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." );
		}
		return self::$systemRootDirectory;
	}
	static public function getUserModuleStarterFileName() {
		return self::CONST_USER_MODULE_STARTER_CLASS_NAME . self::DEFAULT_EXECUTABLE_FILE_EXTENSION;
	}
	static public function getUserModuleStarterClassName() {
		return self::CONST_USER_MODULE_STARTER_CLASS_NAME;
	}
	static public function getUserModuleDiretory() {
		return self::getSystemRootDirectory () . DIRECTORY_SEPARATOR . "modules";
	}
	static public function getUserModuleTestDiretory() {
		return self::getSystemRootDirectory () . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "teste";
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
	static public function getTemplatesDirectory() {
		return self::getSystemRootDirectory () . "/template/";
	}
	static public function getSelectedLanguage() {
		return "pt-br";
	}
}
?>