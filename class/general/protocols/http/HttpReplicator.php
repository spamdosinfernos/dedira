<?php
require_once __DIR__ . '/../../../../class/general/security/Shield.php';
require_once 'HttpRequest.php';

/**
 *
 */
class HttpReplicator{

	/**
	 * Respostas retornadas pelas url's que receberam a replicação da requisição
	 * @var array:string
	 */
	private $arrResponses;

	/**
	 * A requisição http
	 * @var HttpRequest
	 */
	private $httpRequest;

	/**
	 * Arranjo com os cabelhos que não devem ser replicados
	 * Isso é necessário pois replicar alguns cabeçalhos 
	 * pode gerar erros na exibição de caracteres
	 * @var array:string
	 */
	private $arrDoNotReplicate;

	public function __construct(){
		$this->arrDoNotReplicate = array("ACCEPT-ENCODING");
		$this->httpRequest = new HttpRequest();
	}

	/**
	 * Retorna a string correspondente a requisição que está sendo replicada
	 * @return string
	 */
	public function getHttpRequest(){
		return $this->httpRequest->getRawHttpRequest();
	}

	/**
	 * Recupera as respostas retornadas pelas url's que receberam a replicação da requisição
	 * @return array:string
	 */
	public function getArrResponses(){
		return $this->arrResponses;
	}

	/**
	 * Replica a requisição para a url
	 * A url deve ser endereço acessível via http
	 * @param string $url
	 */
	public function replicateTo($url){

		//Recupera os dados da requisição http
		$httpBody = $this->httpRequest->getRawHttpRequestBody();
		$arrHeaders = $this->httpRequest->getArrRetrievedHeaders();

		//Constrói o arranjo de cabeçalhos requeridos pelo curl
		$arrCurlHeaders = array();
		foreach ($arrHeaders as $index => $variable) {

			//Pula os cabeçalhos que não devem ser replicados
			if(in_array($index, $this->arrDoNotReplicate)) continue;

			//Gera os cabeçalhos para o curl
			$arrCurlHeaders[] = $index . ": " . $variable;
		}

		$result = $this->sendHttpRequest($url, $httpBody, $arrCurlHeaders);

		//Guarda no arranjo de respostas
		$this->arrResponses[] = $result;
	}

	/**
	 * Manda a requisção http
	 * @param string $url - Url de destino
	 * @param string $httpBody - Informação a ser enviada
	 * @param string $arrCurlHeaders - Cabeçalhos da informaçãop a ser enviada
	 * @return string
	 */
	private function sendHttpRequest($url, $httpBody, $arrCurlHeaders){

		try{
			
			
			/*
			 * Estou usando o Curl do perl em vez do php.
			 * 
			 * Porquê não usar as funções Curl nativas do php? Você deve estar se perguntando...
			 * Bom... Apesar das funções Curl do php fazerem a mesma coisa, por algum, motivo
			 * as requisições estavam extremamente lentas (cerca de 10 segundos por requisição).
			 * 
			 * Já as funções Curl do perl não levam sequer 1 segundo para fazer a requisão.
			 */
			$perl = new Perl();
			$perl->eval('use WWW::Curl::Easy;');

			//Postador e recuperador dos dados
			$perl->eval('our $curl = WWW::Curl::Easy->new;');

			//Guarda a resposta do servidor
			$perl->eval('our $serverResponse = \'\';');
				
			$perl->eval('our $url = \'' . $url . '\'');

			//Gera o cabeçalho
			$perl->eval('our @header = (\'' . join("','", $arrCurlHeaders) . '\');');
				
			$perl->eval('our $httpBody = \'' . $httpBody . '\';');

			//O Curl precisa de um ponteiro para mandar as respostas
			$perl->eval('open(our $fileb, ">", \\$serverResponse);');

			//Prepara a requisição
			$perl->eval('$curl->setopt(CURLOPT_FOLLOWLOCATION,1);');
			$perl->eval('$curl->setopt(CURLOPT_RETURNTRANSFER,1);');
			$perl->eval('$curl->setopt(CURLOPT_TIMEOUT,60);');
			$perl->eval('$curl->setopt(CURLOPT_CONNECTTIMEOUT,60);');
			$perl->eval('$curl->setopt(CURLOPT_URL,$url);');
			$perl->eval('$curl->setopt(CURLOPT_HTTPHEADER,\@headers);');
			$perl->eval('$curl->setopt(CURLOPT_WRITEDATA,$fileb);');

			//Algumas opções só são setadas se houver algo para postar
			if($httpBody != ""){
				$perl->eval('$curl->setopt(CURLOPT_POST,1);');
				$perl->eval('$curl->setopt(CURLOPT_POSTFIELDS,$httpBody);');
			}

			//Faz a requisição ao servidor
			$perl->eval('my $retcode = $curl->perform;');

			$result = $perl->eval('return $serverResponse');
			
		}catch (Exception $e){
			throw new Exception("Falha ao replicar conteúdo http: " . $e->getMessage());
		}

		if($result === false) throw new Exception("Falha ao replicar conteúdo http");

		return $result;
	}
}
?>