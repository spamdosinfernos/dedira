<?php
require_once __DIR__ . '/../../../../class/general/protocols/http/HttpReplicator.php';
require_once __DIR__ . '/../../../../class/general/configuration/Configuration.php';
require_once __DIR__ . '/../../../../class/ws_All/WsAllConf.php';

/**
 * Esta classe redireciona as requisições de webservice para o webservice ws_All
 * É apenas uma camada de redirecionamento para as requisições http
 */
class Server{

	public function __construct(){

		$replicator = new HttpReplicator();

		//Id do serviço acessado
		$serviceVar = WsAllConf::CONST_WEBSERVICE_ID_QUERY_STRING_VAR;
		$serviceId = isset($_GET[$serviceVar]) ? $_GET[$serviceVar] : null;

		//A id do serviço é obrigatória!
		if(is_null($serviceId)) throw new Exception("Id do serviço não especificado! Use " . WsAllConf::CONST_WEBSERVICE_ID_QUERY_STRING_VAR . " para informá-lo");

		//Replica a requisição para o endereço do ws_All
		$replicator->replicateTo(WsAllConf::getWebserviceRequestProcessorURL() . "?" . $serviceVar . "=" . $serviceId);
		$arrResponses = $replicator->getArrResponses();

		//Exibe o resultado
		header("Content-type: text/xml");
		print $this->getModifiedWebServiceDescritor($arrResponses[0]);
	}

	private function getModifiedWebServiceDescritor($wsdl){

		$url = $this->getThisFileUrl();

		/**
		 * Substitui o endereço de destino da requisição pela url deste arquivo
		 * assim a requisição vem direto para este arquivo em vez de ir para 
		 * outro lugar qualquer
		 */
		preg_match('/<soap:address location="(.*)"/i', $wsdl, $matches);
		return trim(isset($matches[1]) ? str_replace($matches[1],$url,$wsdl) : $wsdl);
	}

	/**
	 * Retorna o endereço do arquivo que processa as requisições do webservice
	 * @return string
	 */
	private function getThisFileUrl(){

		//Constrói o caminho relativo do site no servidor web
		$docRoot = $_SERVER['DOCUMENT_ROOT'];
		$filePath = __FILE__;
		$url = str_ireplace($docRoot,"",$filePath);
		$url = "http://" . $_SERVER['SERVER_ADDR'] . $url . "?" . $_SERVER['QUERY_STRING'];

		return $url;
	}
}
/*
$GLOBALS["HTTP_RAW_POST_DATA"] = <<<XML
<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"><soap:Body><getConfiguracoes xmlns="vokiWebService"><versao>5</versao><sistemaOperacional>Linux</sistemaOperacional></getConfiguracoes></soap:Body></soap:Envelope>
XML;

$_GET[WsAllConf::CONST_WEBSERVICE_ID_QUERY_STRING_VAR] = 10;
*/
new Server();
?>