<?php
require_once 'IEvento.php';
require_once '../../Core/BaseDeDados/CDocumentoDaBase.php';

class CEvento extends CDocumentoDaBase implements IEvento{

	protected $dataFim;

	protected $dataInicio;

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

	public function __construct(){
		parent::__construct();
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