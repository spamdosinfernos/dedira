<?php
require_once __DIR__ . '/CMensagensDeErro.php';

class CConfiguracao extends CMensagensDeErro {

	/**
	 * Usuário do banco de dados
	 * @var string
	 */
	const CONST_BD_USUARIO = "";

	/**
	 * Senha do banco de dados
	 * @var string
	 */
	const CONST_BD_SENHA = "";

	/**
	 * Base de dados usada para os eventos
	 * @var string
	 */
	const CONST_BD_NOME_EVENTOS = "eventos";


	/**
	 * Base de dados usada para as pessoas
	 * @var string
	 */
	const CONST_BD_NOME_PESSOAS = "pessoas";

	/**
	 * Endereço do servidor de banco de dados
	 * @var string
	 */
	const CONST_BD_ENDERECO_DO_HOST = "127.0.0.1";

	/**
	 * Protocolo do servidor de banco de dados
	 * @var string
	 */
	const CONST_BD_PROTOCOLO_DO_HOST = "http";

	/**
	 * Porta np servidor de banco de dados
	 * @var int
	 */
	const CONST_BD_PORTA = 5984;

	/**
	 * Formato da data no arquivo de log
	 * @var string
	 */
	const CONST_LOG_FORMATO_DA_DATA = "Y-m-d H:i:s";

	/**
	 * Indica o caminho do diretório raiz do sistema
	 * @var string
	 */
	static private $diretorioRaizDoSistema;

	static public function getDiretorioRaizDoSistema(){

		if(self::$diretorioRaizDoSistema == ""){
			self::$diretorioRaizDoSistema = dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "..";
		}
		return self::$diretorioRaizDoSistema;
	}

	/**
	 * O caminho do log não pode ser modificado pois os erros podem
	 * ocorrer e devem ser logados antes mesmo das configurações
	 * serem carregadas, sendo assim o caminho do log é definido
	 * no próprio código
	 * @return string
	 */
	static public function getCaminhoDoLog(){
		return self::getDiretorioRaizDoSistema() . DIRECTORY_SEPARATOR . "log" . DIRECTORY_SEPARATOR . "sistema.log";
	}
}

?>