<?php
require_once 'CErrorMessages.php';

class Configuration extends CErrorMessages {

	/**
	 * Usuário do banco de dados
	 * @var string
	 */
	const CONST_DB_LOGIN = "";

	/**
	 * Senha do banco de dados
	 * @var string
	 */
	const CONST_DB_PASSWORD = "";

	/**
	 * Base de dados usada para os eventos
	 * @var string
	 */
	const CONST_DB_EVENTS_NAME = "eventos";


	/**
	 * Base de dados usada para as pessoas
	 * @var string
	 */
	const CONST_DB_PEOPLE_NAME = "pessoas";

	/**
	 * Endereço do servidor de banco de dados
	 * @var string
	 */
	const CONST_DB_HOST_ADDRESS = "127.0.0.1";

	/**
	 * Protocolo do servidor de banco de dados
	 * @var string
	 */
	const CONST_DB_HOST_PROTOCOL = "http";

	/**
	 * Porta np servidor de banco de dados
	 * @var int
	 */
	const CONST_DB_PORT = 5984;
	
	/**
	 * Mensagem exibida quando o sistema pede autenticação
	 * @var string
	 */
	const CONST_AUTH_MESSAGE = "Autentique-se no sistema";

	/**
	 * Formato da data no arquivo de log
	 * @var string
	 */
	const CONST_LOG_DATE_FORMAT = "Y-m-d H:i:s";
	
	/**
	 * Indica o caminho do diretório raiz do sistema
	 * @var string
	 */
	static private $systemRootDirectory;

	static public function getSystemRootDirectory(){

		if(self::$systemRootDirectory == ""){
			self::$systemRootDirectory = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "..");
		}
		return self::$systemRootDirectory;
	}
	
	static public function getUserModuleDiretory(){
		return self::getSystemRootDirectory() . DIRECTORY_SEPARATOR . "Modulos";
	}
	
	static public function getUserModuleTestDiretory(){
		return self::getSystemRootDirectory() . DIRECTORY_SEPARATOR . "Modulos" . DIRECTORY_SEPARATOR . "teste";
	}

	/**
	 * O caminho do log não pode ser modificado pois os erros podem
	 * ocorrer e devem ser gravados antes mesmo das configurações
	 * serem carregadas, sendo assim o caminho do log é definido
	 * no próprio código
	 * @return string
	 */
	static public function getLogFilePath(){
		return self::getSystemRootDirectory() . DIRECTORY_SEPARATOR . "log" . DIRECTORY_SEPARATOR . "sistema.log";
	}
	
	/**
	 * Retorna o intervalo de tempo padrão que o sistema adota
	 * @return string - (um sinal [+ ou -] mais um número indicando a quantidade)
	 */
	static public function getDefaultTimeInterval(){
		return array("+1");
	}
	
		/**
	 * Retorna o tipo intervalo de tempo padrão que o sistema adota
	 * @return string - (year = ano, month = mês, day = dia, hour = hora, minute = minuto, segundo = second) 
	 */
	static public function getDefaultTimeIntervalType(){
		return "month";
	}
	
	static public function getTemplatesDirectory(){
		return self::getSystemRootDirectory() . DIRECTORY_SEPARATOR . "Template" . DIRECTORY_SEPARATOR;
	}
	
}
?>