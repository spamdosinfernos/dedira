<?php
/**
 * Grava o log de solicitações
 * e retornos, o arquivo de log será
 * gravado no mesmo diretório do arquivo
 * que instanciar esta classe. Um outro
 * local pode ser especificado no
 * construtor do objeto.
 */
Class LogDeRequisicoesDoCartao{

	/**
	 * 
	 * Guarda a solcitação
	 * @var mixed
	 */
	private $solcitacao;

	/**
	 * 
	 * Guarda o retorno
	 * @var mixed
	 */
	private $retorno;

	/**
	 * Guarda a data de geração do log
	 * esta data é gerada quando o objeto
	 * é construído
	 * @var datetime
	 */
	private $data;

	/**
	 * 
	 * Armazena o local de salvamento do log
	 * o local padrão é no próprio diretório
	 * @var string
	 */
	private $localDeArmazenamento;

	const CONST_SEPARADOR_DE_CAMPOS = "\t";

	/**
	 * 
	 * Construtor
	 * @param string $localDeArmazenamento
	 */
	public function __construct($localDeArmazenamento = "./"){
		$this->localDeArmazenamento = $localDeArmazenamento;
		$this->data = date("Y-m-d H:i:s");
	}

	/**
	 * 
	 * seta a solicitação
	 * @param mixed $solcitacao
	 */
	public function setSolcitacao($solcitacao){
		$this->solcitacao = $this->trataParametro($solcitacao);
	}

	/**
	 * 
	 * seta o retorno
	 * @param mixed $retorno
	 */
	public function setRetorno($retorno){
		$this->retorno = $this->trataParametro($retorno);
	}

	/**
	 * 
	 * Grava o log no local especificado
	 */
	public function gravarLog(){
		$filehandle = fopen("CartaoLog.log", 'a');
		fwrite($filehandle, $this->data . self::CONST_SEPARADOR_DE_CAMPOS . $this->solcitacao . self::CONST_SEPARADOR_DE_CAMPOS . $this->retorno . "\n");
		fclose($filehandle);
	}

	/**
	 * 
	 * Transforma os parâmetros passados em string
	 * por enquanto transforma array em string 
	 * @param mixed $parametro
	 */
	private function trataParametro($parametro){
		//Se for um arranjo junta o mesmo
		//numa string concatenda com pipe
		if (is_array($parametro)){
			return join("|", $parametro);
		}
		return $parametro;
	}
}
?>