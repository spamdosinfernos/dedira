<?php
require_once __DIR__ . '/../../../../class/general/security/Shield.php';

/**
 * Replica e recupera a requisição http além de modificá-la.
 * Baseada na classe "http_request" baixada em http://www.fijiwebdesign.com/
 */
class HttpRequest {

	/**
	 * Cabeçalhos adicionais não prefixados com "HTTP_" na variável $_SERVER
	 * @var array:string
	 */
	private $arrAditionalHeaders = array('CONTENT_TYPE', 'CONTENT_LENGTH');

	private $arrRetrievedHeaders;

	private $httpProtocol;

	private $httpMethod;

	/**
	 * Guarda o corpo da requisição bruta do protocolo http
	 * @var string
	 */
	private $rawHttpRequestBody;

	/**
	 * Guarda o cabeçalho da requisição bruta do protocolo http
	 * @var string
	 */
	private $rawHttpRequestHeader;

	/**
	 * Guarda os dados enviados via POST
	 * @var array : mixed
	 */
	private $postRequest;

	/**
	 * Guarda os dados enviados via GET
	 * @var array : mixed
	 */
	private $getRequest;

	public function __construct($arrAditionalHeaders = null){
		Shield::treatTextFromForm();
		$this->retrieveHeaders($arrAditionalHeaders);

		//Recupera o corpo da requisição no get
		$this->getRequest = $_GET;

		//Recupera o corpo da requisição no post
		$this->postRequest = $_POST;

		//Recupera o corpo da requisição http
		$this->rawHttpRequestBody = trim(@file_get_contents('php://input')) != "" ? trim(@file_get_contents('php://input')) : @$GLOBALS['HTTP_RAW_POST_DATA'];

		if($this->rawHttpRequestBody == "") $this->rawHttpRequestBody = null;
	}

	/**
	 * Recupera os cabeçalhos http
	 * @param array:string - nomes dos cabeçalhos adicionais a serem recuperados 
	 */
	private function retrieveHeaders($arrAditionalHeaders = false) {

		if (!is_null($arrAditionalHeaders)) {
			$this->arrAditionalHeaders = array_merge($this->arrAditionalHeaders, $arrAditionalHeaders);
		}

		if (isset($_SERVER['HTTP_METHOD'])) {
			$this->httpMethod = $_SERVER['HTTP_METHOD'];
			unset($_SERVER['HTTP_METHOD']);
		} else {
			$this->httpMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : false;
		}

		$this->httpProtocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : false;
		$this->arrRetrievedHeaders = array();


		$this->arrRetrievedHeaders["EXPECT"] = null;

		foreach($_SERVER as $i=>$val) {
			if (strpos($i, 'HTTP_') === 0 || in_array($i, $this->arrAditionalHeaders)) {
				$name = str_replace(array('HTTP_', '_'), array('', '-'), $i);
				$this->arrRetrievedHeaders[$name] = $val;
			}
		}
	}

	/**
	 * Retorna a requisição Http bruta
	 * @param boolean $refresh - atualizar informações
	 */
	public function getRawHttpRequestBody() {
		return $this->rawHttpRequestBody;
	}

	/**
	 * Retorna a requisição Http bruta
	 * @param boolean $refresh - atualizar informações
	 */
	public function getRawHttpRequestHeader($refresh = false) {

		/*
		 * Se a requisição bruta já estiver em memória retorna a mesma
		 * senão recupera ela e a retorna
		 */
		if (isset($this->rawHttpRequestHeader) && !$refresh) {
			return $this->rawHttpRequestHeader;
		}

		$this->rawHttpRequestHeader = "{$this->httpMethod}\r\n";

		foreach($this->arrRetrievedHeaders as $i=>$header) {
			$this->rawHttpRequestHeader .= "$i: $header\r\n";
		}

		return $this->rawHttpRequestHeader;
	}

	public function getRawHttpRequest($refresh = false){
		return $this->getRawHttpRequestHeader($refresh) . "\r\n" . $this->getRawHttpRequestBody();
	}

	public function getArrAditionalHeaders(){
		return $this->arrAditionalHeaders;
	}

	public function getRetrievedHeader($name){
		$name = strtoupper($name);
		return isset($this->arrRetrievedHeaders[$name]) ? $this->arrRetrievedHeaders[$name] : null;
	}

	public function getHttpProtocol(){
		return $this->httpProtocol;
	}

	public function getHttpMethod(){
		return $this->httpMethod;
	}

	public function getHttpBody(){
		return $this->rawHttpRequestBody;
	}

	public function setHttpBody($rawHttpRequestBody){
		$this->rawHttpRequestBody = $rawHttpRequestBody;
	}

	public function getArrRetrievedHeaders(){
		return $this->arrRetrievedHeaders;
	}

	public function getPostRequest(){
		return $this->postRequest;
	}

	/**
	 * Recupera os dados da requisição GET informe $queryStringVarName
	 * para pegar um dado específico ou nada para recuperar todas as variáveis
	 * @param string $queryStringVarName
	 * @return array : string
	 */
	public function getGetRequest($queryStringVarName = null){

		if(is_null($queryStringVarName)) return $this->getRequest;

		return isset($this->getRequest[$queryStringVarName]) ? array($this->getRequest[$queryStringVarName]) : null;
	}
}
?>
