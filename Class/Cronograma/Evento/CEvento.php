<?php
require_once 'IEvento.php';
require_once 'Class/Core/CCore.php';
require_once 'Class/Core/BaseDeDados/CBaseDeDados.php';

class CEvento extends CCore implements IEvento{

	protected $id;

	protected $rev;

	protected $dataInicio;

	protected $dataFim;

	protected $observacoes;

	protected $arrMaisContatos;

	protected $arrEnderecosDosLocais;

	/**
	 * Lista das identificações dos documentos relacionados
	 * @var int
	 */
	protected $arrIdsDosDocumentosRelacionados;

	/**
	 * Pessoas ou organizações promotoras do evento 
	 * @var CPessoa
	 * @var CMilitante
	 * @var IOrganizacao
	 */
	protected $arrPessoasOuOrganizacoesPromotoras;

	private $operadorDeBancoDeDados;

	public function __construct(){
		parent::__construct();

		$this->operadorDeBancoDeDados = new CBaseDeDados();
		$this->operadorDeBancoDeDados->selecionarBaseDeDados(CConfiguracao::CONST_BD_NOME_EVENTOS);
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

	/**
	 * Carrega um evento da base dada sua identificação
	 */
	public function carregar(){

		if($this->id == "") return false;

		if(!$this->operadorDeBancoDeDados->carregarInformacao($this->id)) return false;

		$objInfo = $this->operadorDeBancoDeDados->getResposta();

		foreach ($objInfo as $propriedade => $valor) {

			if(is_null($valor) || $propriedade == "CLASSNAME") continue;

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

	public function setId($id){
		$this->id = $id;
	}

	public function setRev($rev){
		$this->rev = $rev;
	}

	public function setDataInicio($dataInicio){
		$this->dataInicio = $dataInicio;
	}

	public function setDataFim($dataFim){
		$this->dataFim = $dataFim;
	}

	public function setObservacoes($observacoes){
		$this->observacoes = $observacoes;
	}

	public function setArrMaisContatos($arrMaisContatos){
		$this->arrMaisContatos = $arrMaisContatos;
	}

	public function setArrEnderecosDosLocais($arrEnderecosDosLocais){
		$this->arrEnderecosDosLocais = $arrEnderecosDosLocais;
	}

	public function setArrPessoasOuOrganizacoesPromotoras($arrPessoasOuOrganizacoesPromotoras){
		$this->arrPessoasOuOrganizacoesPromotoras = $arrPessoasOuOrganizacoesPromotoras;
	}

	public function setArrIdsDosDocumentosRelacionados($arrIdsDosDocumentosRelacionados){
		$this->arrIdsDosDocumentosRelacionados = $arrIdsDosDocumentosRelacionados;
	}

	public function getId(){
		return $this->id;
	}

	public function getRev(){
		return $this->rev;
	}

	public function getDataInicio(){
		return $this->dataInicio;
	}

	public function getDataFim(){
		return $this->dataFim;
	}

	public function getObservacoes(){
		return $this->observacoes;
	}

	public function getArrMaisContatos(){
		return $this->arrMaisContatos;
	}

	public function getArrEnderecosDosLocais(){
		return $this->arrEnderecosDosLocais;
	}

	public function getArrPessoasOuOrganizacoesPromotoras(){
		return $this->arrPessoasOuOrganizacoesPromotoras;
	}

	public function getArrIdsDosDocumentosRelacionados(){
		return $this->arrIdsDosDocumentosRelacionados;
	}
}
?>