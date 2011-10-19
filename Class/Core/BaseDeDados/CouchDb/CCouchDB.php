<?php
require_once __DIR__ . '/../../CCore.php';
require_once __DIR__ . '/../../Configuracao/CConfiguracao.php';

class CCouchDB extends CCore{

	private $usuario;

	private $senha;

	private $resposta;

	private $cabecalhosDaResposta;

	const CONST_OPERACAO_GET = "GET";
	const CONST_OPERACAO_PUT = "PUT";
	const CONST_OPERACAO_DEL = "DELETE";
	const CONST_OPERACAO_POST = "POST";

	protected function __construct(){
		parent::__construct();
	}

	protected function getResultadoDaConsulta(){
		return json_decode($this->resposta);
	}

	protected function getStatusDaConsulta(){
		return json_decode($this->cabecalhosDaResposta);
	}

	protected function load_all_docs() {
		$this->enviar(self::CONST_OPERACAO_GET, '/_all_docs');
	}

	protected function carregarItem($id) {
		$this->enviar(self::CONST_OPERACAO_GET, '/'.$id);
	}

	/**
	 * Gera a requisição para o CouchDb
	 * @param string $tipoDeRequisicao
	 * @param string $url
	 * @param mixed $informacao
	 * @return string
	 */
	private function gerarRequisicao($url, $tipoDeRequisicao, $idDoDocumento = null, $informacao = null, $rev = null) {

		if($rev != null && $tipoDeRequisicao != self::CONST_OPERACAO_DEL){
			if(is_object($informacao)){
				$informacao->_rev = $rev;
			}

			$informacao["_rev"] = $rev;
		}

		/*
		 * Se a id do documento for diferente de nulo e a operação 
		 * estiver errôneamente setada como POST, troca para PUT
		 */
		if($idDoDocumento != "" && $tipoDeRequisicao == self::CONST_OPERACAO_POST){
			$tipoDeRequisicao = self::CONST_OPERACAO_PUT;
		}

		$informacao = is_null($informacao) ? null : json_encode($informacao);

		$idDoDocumento = is_null($idDoDocumento) ? null : urlencode($idDoDocumento);

		$urlCompleta = $idDoDocumento == "" ? $url : $url . "/" . $idDoDocumento;

		if($tipoDeRequisicao == self::CONST_OPERACAO_DEL && $rev != ""){
			$urlCompleta = $urlCompleta . "?rev=" . $rev;
		}

		$req = "{$tipoDeRequisicao} {$urlCompleta} HTTP/1.0\r\nHost: " . CConfiguracao::CONST_BD_ENDERECO_DO_HOST . "\r\n";

		if(CConfiguracao::CONST_BD_USUARIO){
			$req .= 'Authorization: Basic ' . base64_encode(CConfiguracao::CONST_BD_USUARIO . ':' . CConfiguracao::CONST_BD_SENHA) . "\r\n";
		}

		if(!is_null($informacao)) {
			$req .= 'Content-Length: '.strlen($informacao)."\r\n";
			$req .= 'Content-Type: application/json'."\r\n\r\n";
			$req .= $informacao."\r\n";
		} else {
			$req .= "\r\n";
		}

		return $req;
	}

	protected function enviar($tipoDeRequisicao, $url, $idDoDocumento = null, $informacao = null, $rev = null){

		$request = $this->gerarRequisicao($url, $tipoDeRequisicao, $idDoDocumento, $informacao, $rev);

		$ponteiro = fsockopen(CConfiguracao::CONST_BD_ENDERECO_DO_HOST, CConfiguracao::CONST_BD_PORTA, $errno, $errstr);

		fwrite($ponteiro, $request);

		$resposta = "";

		while(!feof($ponteiro)) {
			$resposta .= fgets($ponteiro);
		}

		list($this->cabecalhosDaResposta, $this->resposta) = explode("\r\n\r\n", $resposta);
	}

}
?>