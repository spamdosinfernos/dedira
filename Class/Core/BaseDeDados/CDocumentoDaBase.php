<?php
require_once '../../Core/CCore.php';
require_once '../../Core/BaseDeDados/CBaseDeDados.php';
require_once '../../Core/Configuracao/CConfiguracao.php';

/**
 * Todos os documentos que se quiser salvar deve extender esta classe
 * 
 * @author andre
 *
 */
class CDocumentoDaBase extends CCore{
	
	protected $id;
	
	protected $rev;

	private $operadorDeBancoDeDados;

	public function __construct(){
		parent::__construct();
		$this->operadorDeBancoDeDados = new CBaseDeDados();
		$this->operadorDeBancoDeDados->selecionarBaseDeDados(CConfiguracao::CONST_BD_NOME_EVENTOS);
	}
	
	public function setId($id){
		$this->id = $id;
	}

	public function setRev($rev){
		$this->rev = $rev;
	}
	
	public function getId(){
		return $this->id;
	}

	public function getRev(){
		return $this->rev;
	}

	/**
	 * Carrega um evento da base dada sua identificação
	 */
	public function carregar(){

		if($this->id == "") return false;

		if(!$this->operadorDeBancoDeDados->carregarInformacao($this->id)) return false;

		$objInfo = $this->operadorDeBancoDeDados->getResposta();

		foreach ($objInfo as $propriedade => $valor) {

			if(is_null($valor) || $propriedade == "CLASSNAME") continue;
			
			if($propriedade == "_id"){
				$this->id = $valor;
				continue;
			}
			
			if($propriedade == "_rev"){
				$this->rev = $valor;
				continue;
			}

			if(is_string($valor)) $expressao = "\$this->$propriedade = '$valor';";

			if(is_numeric($valor)) $expressao = "\$this->$propriedade = $valor;";

			if(is_object($valor) || is_array($valor)){
				$valor = serialize($valor);
				$expressao = "\$this->$propriedade = unserialize('$valor');";
			}

			eval($expressao);
		}

		return true;
	}

	/**
	 * Salva o evento
	 */
	public function salvar(){

		if($this->id == ""){
			$ok = $this->operadorDeBancoDeDados->inserirInformacao("", $this->toArray());
		}else{
			$ok = $this->operadorDeBancoDeDados->atualizarInformacao($this->id, $this->rev, $this->toArray());
		}

		if($ok){
			$resposta = $this->operadorDeBancoDeDados->getResposta();
			$this->id = $resposta->id;
			$this->rev = $resposta->rev;
			return;
		}

		throw new Exception("text - Falha ao salvar evento.");
	}

	/**
	 * Apaga o evento
	 */
	public function apagar(){
		if($this->id == ""){
			throw new Exception("texto - Falha ao apagar evento: O evento não tem uma identificação.");
		}else{
			$this->operadorDeBancoDeDados->apagarInformacao($this->id, $this->rev);

			$resposta = $this->operadorDeBancoDeDados->getResposta();

		}
	}
}
?>