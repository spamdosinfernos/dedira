<?php
require_once __DIR__ . '/CouchDb/CCouchDB.php';
require_once __DIR__ . '/../../Core/Configuracao/CConfiguracao.php';
/**
 * Responsável pelo gerenciamento do armazenamento dos dados
 * @author andre
 *
 */
class CBaseDeDados extends CCouchDB{

	/**
	 * Indica qual base de dados está selecionada 
	 * @var string
	 */
	private $baseSelecionada;

	private $resposta;

	public function __construct(){
		parent::__construct();
	}

	/**
	 * Criar um nova base de dados
	 * @param string $nomeDaBase
	 * @return boolean
	 */
	public function criarBaseDeDados($nomeDaBase){

		$nomeDaBase = substr($nomeDaBase,-1,1) == "/" ? $nomeDaBase : "/" . $nomeDaBase;

		$this->enviar(self::CONST_OPERACAO_PUT, $nomeDaBase);

		$this->resposta = $this->getResultadoDaConsulta();

		if(!isset($this->resposta->ok) && !$this->resposta->ok) return false;

		$this->selecionarBaseDeDados($nomeDaBase);

		return true;
	}

	/**
	 * Apaga uma base de dados
	 * @param string $nomeDaBase
	 * @return boolean
	 */
	public function apagaBaseDeDados($nomeDaBase){

		$nomeDaBase = substr($nomeDaBase,-1,1) == "/" ? $nomeDaBase : "/" . $nomeDaBase;

		$this->enviar(self::CONST_OPERACAO_DEL, $nomeDaBase);

		$this->resposta = $this->getResultadoDaConsulta();

		if(!isset($this->resposta->ok) && !$this->resposta->ok) return false;

		$this->selecionarBaseDeDados("");

		return true;
	}

	/**
	 * Seleciona a base dados que sofrerá as leituras e escritas
	 * @param string $nomeDaBase
	 */
	public function selecionarBaseDeDados($nomeDaBase){
		$nomeDaBase = substr($nomeDaBase,-1,1) == "/" ? $nomeDaBase : "/" . $nomeDaBase;

		$this->baseSelecionada = $nomeDaBase;
	}

	/**
	 * Insere uma nova informação na base de dados
	 * @param mixed $informacao
	 * @return boolean
	 */
	public function gravarDocumento($idDoDocumento, $informacao){

		if(!is_array($informacao)) throw new Exception("text - A informação fornecida deve ser um arranjo");

		if($idDoDocumento == ""){
			$this->enviar(self::CONST_OPERACAO_POST, $this->baseSelecionada, $idDoDocumento, $informacao);
		}else{
			$this->enviar(self::CONST_OPERACAO_PUT, $this->baseSelecionada, $idDoDocumento, $informacao);
		}

		$this->resposta = $this->getResultadoDaConsulta();

		if(!isset($this->resposta->ok) && !$this->resposta->ok){
			return false;
		}

		return true;
	}

	public function apagarDocumento($idDoDocumento, $revisao){

		$this->enviar(self::CONST_OPERACAO_DEL, $this->baseSelecionada, $idDoDocumento, null, $revisao);

		$this->resposta = $this->getResultadoDaConsulta();

		if(!isset($this->resposta->ok) && !$this->resposta->ok){
			return false;
		}

		return true;
	}

	public function atualizarInformacao($idDoDocumento, $revisao, $informacao){

		$this->enviar(self::CONST_OPERACAO_PUT, $this->baseSelecionada, $idDoDocumento, $informacao, $revisao);

		$this->resposta = $this->getResultadoDaConsulta();

		if(!isset($this->resposta->ok) && !$this->resposta->ok){
			return false;
		}
		return true;
	}

	public function carregarDocumento($idDoDocumento){

		$this->enviar(self::CONST_OPERACAO_GET, $this->baseSelecionada, $idDoDocumento);

		$this->resposta = $this->getResultadoDaConsulta();

		if(isset($this->resposta->error)){
			return false;
		}

		return true;
	}

	/**
	 * Executa a view
	 * @param string $enderecoDaView
	 */
	public function executaView($enderecoDaView){
		$this->enviar(self::CONST_OPERACAO_GET,$enderecoDaView);
		$this->resposta = $this->getResultadoDaConsulta();
	}

	public function carregarTodasAsViews(){

		$arrRespostaFinal = array();

		$this->enviar(self::CONST_OPERACAO_GET, $this->baseSelecionada . "/_all_docs?descending=true&startkey=\"_design0\"&endkey=\"_design\"");

		$this->resposta = $this->getResultadoDaConsulta();

		if(isset($this->resposta->error)){
			return false;
		}

		$arrViews = $this->resposta->rows;

		foreach ($arrViews as $view) {
			$this->enviar(self::CONST_OPERACAO_GET, $this->baseSelecionada . "/" . $view->id);
			$this->resposta = $this->getResultadoDaConsulta();
			if(isset($this->resposta->error)) return false;
				
			$arrRespostaFinal[] = $this->getResultadoDaConsulta();
		}

		$this->resposta = $arrRespostaFinal;
		return true;
	}

	public function getResposta(){
		return $this->resposta;
	}
}
?>