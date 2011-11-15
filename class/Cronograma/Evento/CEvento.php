<?php
require_once __DIR__ .'/IEvento.php';
require_once __DIR__ .'/../../Core/BaseDeDados/CDocumentoDaBase.php';

/**
 * Define um evento no cronograma da organização, toda classe 
 * que determina um evento que deve entrar no cronograma
 * deve estender esta classe
 * @author andre
 *
 */
class CEvento extends CDocumentoDaBase implements IEvento{

	/**
	 * Data de fim do evento
	 * @var Datetime
	 */
	protected $dataFim;

	/**
	 * Data de início do evento
	 * @var Datetime
	 */
	protected $dataInicio;

	/**
	 * Observação
	 * @var string
	 */
	protected $observacoes;

	/**
	 * Contatos do evento
	 * @var array : IPessoa
	 */
	protected $arrMaisContatos;

	/**
	 * Endereços do locais onde serão realizados os eventos
	 * @var array : string
	 */
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

	/**
	 * Guarda o tipo de recorrência
	 */
	protected $tipoDeRecorrencia;

	/**
	 * Quantidade de recorrências (-1 para recorrências eternas)
	 * @var int
	 */
	protected $qtdeDeRecorrencia;

	/**
	 * Indica quando o sistema deve mostrar um lembrete
	 * @var DateTime
	 */
	protected $dataDeLembrete;
	
	/**
	 * Indica se o evento é particular
	 * @var boolean
	 */
	protected $particular;

	/*
	 * Tipos de recorrências possíveis para um evento
	 */
	const CONST_RECORRENCIA_NAO = -1;

	const CONST_RECORRENCIA_DIA = 0;
	const CONST_RECORRENCIA_SEMANA = 1;
	const CONST_RECORRENCIA_MES = 2;
	const CONST_RECORRENCIA_ANO = 3;

	const CONST_RECORRENCIA_BIMESTRAL = 4;
	const CONST_RECORRENCIA_TRIMESTRE = 5;
	const CONST_RECORRENCIA_SEMESTRE = 6;

	const CONST_RECORRENCIA_DOMINGO = 7;
	const CONST_RECORRENCIA_SEGUNDA = 8;
	const CONST_RECORRENCIA_TERCA = 9;
	const CONST_RECORRENCIA_QUARTA = 10;
	const CONST_RECORRENCIA_QUINTA = 11;
	const CONST_RECORRENCIA_SEXTA = 12;
	const CONST_RECORRENCIA_SABADO = 13;

	public function __construct(){
		parent::__construct();
		$this->setNomeDaBaseDeDados(Configuration::CONST_DB_PEOPLE_NAME);
	}

	public function setDataInicio(DateTime $dataInicio){
		$this->dataInicio = $dataInicio;
	}

	public function setDataFim(DateTime $dataFim){
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
	
	public function getParticular(){
	    return $this->particular;
	}

	public function setParticular($particular){
	    $this->particular = $particular;
	}

	public function getArrPessoasOuOrganizacoesPromotoras(){
		return $this->arrPessoasOuOrganizacoesPromotoras;
	}

	public function getArrIdsDosDocumentosRelacionados(){
		return $this->arrIdsDosDocumentosRelacionados;
	}

	public function getTipoDeRecorrencia(){
		return $this->tipoDeRecorrencia;
	}

	public function setTipoDeRecorrencia($tipoDeRecorrencia){

		//Verifica se o tipo de recorrência é válida
		$valida = in_array(
		$tipoDeRecorrencia,
		array(
		self::CONST_RECORRENCIA_NAO,
		self::CONST_RECORRENCIA_DIA,
		self::CONST_RECORRENCIA_SEMANA,
		self::CONST_RECORRENCIA_MES,
		self::CONST_RECORRENCIA_ANO,
		self::CONST_RECORRENCIA_BIMESTRAL,
		self::CONST_RECORRENCIA_TRIMESTRE,
		self::CONST_RECORRENCIA_SEMESTRE,
		self::CONST_RECORRENCIA_DOMINGO,
		self::CONST_RECORRENCIA_SEGUNDA,
		self::CONST_RECORRENCIA_TERCA,
		self::CONST_RECORRENCIA_QUARTA,
		self::CONST_RECORRENCIA_QUINTA,
		self::CONST_RECORRENCIA_SEXTA,
		self::CONST_RECORRENCIA_SABADO
		)
		);

		if(!$valida) throw new Exception("text - O tipo de recorrência informada é inválida");

		$this->tipoDeRecorrencia = $tipoDeRecorrencia;
	}

	public function getQtdeDeRecorrencia(){
		return $this->qtdeDeRecorrencia;
	}

	public function setQtdeDeRecorrencia($qtdeDeRecorrencia){
		$this->qtdeDeRecorrencia = $qtdeDeRecorrencia;
	}

	public function getDataDeLembrete(){
		return $this->dataDeLembrete;
	}

	public function setDataDeLembrete(DateTime $dataDeLembrete){
		$this->dataDeLembrete = $dataDeLembrete;
	}
}
?>