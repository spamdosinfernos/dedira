<?php
require_once __DIR__ . '/../../class/general/security/Shield.php';
require_once __DIR__ . '/../../class/general/database/DataBase.php';
require_once __DIR__ . '/../../class/ws_conexaoDb/WsConexaoDbConf.php';

/**
 * Executa as "stored procedures" necessárias aos vários serviços e sistemas
 *
 * Esta classe tem como objetivo isolar o banco de dados das
 * aplicações externas, ou mesmo, das aplicações internas.
 * Todo programa que quiser acessar algum dado do banco de
 * dados terá que passar por esta classe, ela recuperará e
 * filtrará os dados passando-os de volta ao aplicativo que
 * que requisitou as informações.
 */
class Server{

	/**
	 * Guarda o objeto que gerencia as requisições do webservice
	 * @var SoapServer
	 */
	private $soapServer;

	public function __construct(){

		Shield::treatTextFromForm();

		$httpData = @file_get_contents('php://input') != "" ? trim(@file_get_contents('php://input')) : @$GLOBALS['HTTP_RAW_POST_DATA'];

		file_put_contents("/tmp/httpDataWsConexaoDb.txt", $httpData);

		$this->soapServer = new SoapServer("./server.wsdl");
		$this->soapServer->setObject($this);
		@$this->soapServer->handle($httpData);
	}

	/**
	 * Executa o precedimento chamado
	 * @param string $dbOperationsClassName
	 * @param string $procedureCall
	 * @return array:string
	 */
	public function executeProcedure($dbOperationsClassName, $procedureCall){

		try{
			//Valida a expressão que chama a procedure
			$numOfMatches = preg_match(WsConexaoDbConf::CONST_ACCEPTED_PROCEDURE_CALL_FORMAT, $procedureCall, $matches);

			//Se não for válida lança uma excessão
			if($numOfMatches == 0) throw new Exception("A consulta não está em um formato aceitável use: <procedureId>([param01, param02, ..])");

			//Se a expressão está correta faz a consulta e retorna os resultados
			try{
				$db = new DataBase($this->getDbOperationsDb($dbOperationsClassName));

				//As procedures no banco devem ter o prefixo "sp_"
				$procedureCall = WsConexaoDbConf::CONST_DEFAULT_STORED_PROCEDURE_PREFIX . $procedureCall;
					
				//Chamando a procedure
				if(!$db->execProcedure($procedureCall)) throw new Exception("Não foi possível executar a consulta.");
			}catch (Exception $e){
				throw new Exception("Falha ao executar consulta através do webservice: " . $e->getMessage());
			}

			return $this->generateSoapResponse($db->getFetchArray());

		}catch (Exception $e){
			throw new SoapFault($e->getMessage(),0);
		}
	}

	/**
	 * Retorna o objeto com as operações da base de dados escolhida
	 * @param string $dbOperationsClassName
	 * @return IDataBaseOperations
	 */
	private function getDbOperationsDb($dbOperationsClassName){

		try{

			//Carrega a classe necessária as operações no banco de dados
			require_once WsConexaoDbConf::getDatabaseOperationClassesPath() . DIRECTORY_SEPARATOR .  $dbOperationsClassName . WsConexaoDbConf::CONST_PHP_SCRIPT_EXTENSION;

			//Instancia o objeto
			$object = new $dbOperationsClassName;

			//Verifica se o objeto é do tipo esperado
			if(!is_a($object, "IDataBaseOperations")) throw new Exception("A classe de operações $dbOperationsClassName não é válida!");

			//Retorna o objeto pedido
			return $object;

		}catch (Exception $e){
			throw new SoapFault($e->getMessage(),0);
		}
	}

	/**
	 * Gera o resposta soap
	 * @param array:mixed $arrResults
	 */
	private function generateSoapResponse($arrResults){

		//Inicia resultado final
		$arrSoapResult = array();
		
		foreach ($arrResults as $arrLinha) {

			//Zera o resultado da linha
			$arrLinhas = array();
			foreach ($arrLinha as $index => $coluna) {
				$arrLinhas["campos"][] = array("nome" => $index, "valor" => $coluna);
			}

			//Monta o resultado
			$arrSoapResult[] = $arrLinhas;
		}

		return $arrSoapResult;
	}
}
new Server();
?>