<?php
require_once __DIR__ . '/../configuration/Configuration.php';

/**
 * Define as operações básicas no banco de dados, não pode ser instânciada,
 * as operações no banco de dados serão feitas pelas classes Database e 
 * StorableObject
 *
 * @author tatupheba
 *
 */
abstract class CouchDB {

	private $user;

	private $password;

	private $response;

	private $headerResponse;

	const CONST_GET_OPERATION = "GET";
	const CONST_PUT_OPERATION = "PUT";
	const CONST_DEL_OPERATION = "DELETE";
	const CONST_POST_OPERATION = "POST";

	protected function getRequestResponse(){
		return json_decode($this->response);
	}

	protected function getQueryStatus(){
		return json_decode($this->headerResponse);
	}

	protected function loadAllDocuments() {
		$this->send(self::CONST_GET_OPERATION, '/_all_docs');
	}

	protected function loadItem($id) {
		$this->send(self::CONST_GET_OPERATION, '/'.$id);
	}

	/**
	 * Gera a requisição para o CouchDb
	 * @param string $requestType
	 * @param string $url
	 * @param mixed $information
	 * @return string
	 */
	private function generateRequest($url, $requestType, $documentId = null, $information = null, $rev = null) {

		if($rev != null && $requestType != self::CONST_DEL_OPERATION){
			if(is_object($information)){
				$information->_rev = $rev;
			}

			$information["_rev"] = $rev;
		}

		/*
		 * Se a id do documento for diferente de nulo e a operação 
		 * estiver errôneamente setada como POST, troca para PUT
		 */
		if($documentId != "" && $requestType == self::CONST_POST_OPERATION){
			$requestType = self::CONST_PUT_OPERATION;
		}

		$information = is_null($information) ? null : json_encode($information);

		$documentId = is_null($documentId) ? null : urlencode($documentId);

		$urlCompleta = $documentId == "" ? $url : $url . "/" . $documentId;

		if($requestType == self::CONST_DEL_OPERATION && $rev != ""){
			$urlCompleta = $urlCompleta . "?rev=" . $rev;
		}

		$req = "{$requestType} {$urlCompleta} HTTP/1.0\r\nHost: " . Configuration::CONST_DB_HOST_ADDRESS . "\r\n";

		if(Configuration::CONST_DB_LOGIN){
			$req .= 'Authorization: Basic ' . base64_encode(Configuration::CONST_DB_LOGIN . ':' . Configuration::CONST_DB_PASSWORD) . "\r\n";
		}

		if(!is_null($information)) {
			$req .= 'Content-Length: '.strlen($information)."\r\n";
			$req .= 'Content-Type: application/json'."\r\n\r\n";
			$req .= $information."\r\n";
		} else {
			$req .= "\r\n";
		}

		return $req;
	}

	protected function send($requestType, $url, $documentId = null, $information = null, $rev = null){

		$request = $this->generateRequest($url, $requestType, $documentId, $information, $rev);

		$ponteiro = fsockopen(Configuration::CONST_DB_HOST_ADDRESS, Configuration::CONST_DB_PORT, $errno, $errstr);

		fwrite($ponteiro, $request);

		$response = "";

		while(!feof($ponteiro)) {
			$response .= fgets($ponteiro);
		}

		list($this->headerResponse, $this->response) = explode("\r\n\r\n", $response);
	}

}
?>