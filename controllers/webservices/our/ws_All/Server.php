<?php
require_once __DIR__ . '/../../../../class/ws_All/WsAllConf.php';
require_once __DIR__ . '/../../../../class/general/filesystem/File.php';

/**
 * Gerencia todos os pedidos de dados por entidades externas via webservice.
 */
class Server{

	/**
	 * Guarda a id da aplicação
	 * @var string
	 */
	private $serviceId;

	public function __construct(){

		//Se a id da aplicação não for informada retorna uma página vazia
		if(!isset($_GET[WsAllConf::CONST_WEBSERVICE_ID_QUERY_STRING_VAR])) return;

		//Recupera a id do serviço
		$this->serviceId = $_GET[WsAllConf::CONST_WEBSERVICE_ID_QUERY_STRING_VAR];

		try{
			//Recupera o objeto que vai manipular e responder a requisição
			$webservice = $this->getWebserviceObject();

			//Verifica se a instância recuperada é do tipo esperado
			if(!is_a($webservice, "AWebservice")) throw new Exception("A classe que processa o webservice deve extender: AWebservice");
			
			//Se não houver requisição retorna o descritor
			if(!$webservice->thereIsRequest()){
				if($webservice->hasDescriptor()){
					print $webservice->getDescriptor();
				}
				//Se não houver descritor retorna vazio
				return;
			}

			//Manipula a requisição e dá a resposta
			$webservice->handleRequest();

		}catch (Exception $e){
			throw new SoapFault("Falha: " . $e->getMessage(), $e->getCode());
		}
	}

	/**
	 * Recupera um instância da classe que implementa a interface AWebservice
	 * @return AWebservice
	 */
	private function getWebserviceObject(){

		//Inicializa a variável que guardará o objeto
		$appObject = null;

		$serviceName = WsAllConf::getServiceName($this->serviceId);

		//Nome do arquivo da classe requerida em maiúsculas
		$classFile = new File(
		WsAllConf::getServicesDirectoryPath() .
		DIRECTORY_SEPARATOR . $serviceName .
		DIRECTORY_SEPARATOR . WsAllConf::CONST_WEBSERVICE_CLASS_NAME
		);

		//Solicita a importação do código correspondente a classe
		require_once $classFile->getFilePath();

		//Recupera o nome da classe a ser instânciada
		$className = $classFile->getFileNameWithoutExtension();

		//Instância o objeto que vai processar a requisição do webservice
		return new $className($this->serviceId);
	}
}

new Server();
?>