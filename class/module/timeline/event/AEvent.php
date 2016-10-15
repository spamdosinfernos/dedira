<?php
require_once __DIR__ . '/IEvent.php';
require_once __DIR__ . '/language/Lang_AEvent.php';
require_once __DIR__ . '/../../general/database/StorableObject.php';

/**
 * Define um evento no cronograma da organização, toda classe
 * que determina um evento que deve entrar no cronograma
 * deve estender esta classe.
 *
 * @author André Furlan
 *        
 */
abstract class AEvent extends AStorableObject implements IEvent {
	
	/**
	 * Data de fim do evento
	 * 
	 * @var Datetime
	 */
	protected $finalDate;
	
	/**
	 * Data de início do evento
	 * 
	 * @var Datetime
	 */
	protected $beginDate;
	
	/**
	 * Observação
	 * 
	 * @var string
	 */
	protected $observations;
	
	/**
	 * Contatos do evento
	 * 
	 * @var array : IPerson
	 */
	protected $arrMoreContats;
	
	/**
	 * Endereços do locais onde serão realizados os eventos
	 * 
	 * @var array : string
	 */
	protected $arrPlacesAddresses;
	
	/**
	 * Lista das identificações dos documentos relacionados
	 * 
	 * @var int
	 */
	protected $arrRelatedDocumentsIds;
	
	/**
	 * Pessoas ou organizações promotoras do evento
	 * 
	 * @var IPerson
	 * @var IOrganizacao
	 */
	protected $arrPromoters;
	
	/**
	 * Guarda o tipo de recorrência
	 */
	protected $recurringType;
	
	/**
	 * Quantidade de recorrências (-1 para recorrências eternas)
	 * 
	 * @var int
	 */
	protected $recurringAmount;
	
	/**
	 * Indica quando o sistema deve mostrar um lembrete
	 * 
	 * @var DateTime
	 */
	protected $rememberingDate;
	
	/**
	 * Indica se o evento é particular
	 * 
	 * @var boolean
	 */
	protected $private;
	
	/**
	 * (apenas para uso interno a classe) Guarda um arranjo com todos os códigos de recorrência
	 * 
	 * @var array : int
	 */
	private $arrRecorrencies;
	
	/*
	 * Tipos de recorrências possíveis para um evento
	 */
	const CONST_RECORRENCY_NO = - 1;
	const CONST_RECORRENCY_DAY = 0;
	const CONST_RECORRENCY_WEEK = 1;
	const CONST_RECORRENCY_MONTH = 2;
	const CONST_RECORRENCY_YEAR = 3;
	const CONST_RECORRENCY_BIMESTRAL = 4;
	const CONST_RECORRENCY_TRIMESTRAL = 5;
	const CONST_RECORRENCY_SEMESTRAL = 6;
	const CONST_RECORRENCY_SUNDAY = 7;
	const CONST_RECORRENCY_MONDAY = 8;
	const CONST_RECORRENCY_TUESDAY = 9;
	const CONST_RECORRENCY_WEDNESDAY = 10;
	const CONST_RECORRENCY_THURSDAY = 11;
	const CONST_RECORRENCY_FRIDAY = 12;
	const CONST_RECORRENCY_SATURDAY = 13;
	
	/*
	 * Erro emitidos por esta classe
	 */
	const CONST_ERROR_1 = 1;
	const CONST_ERROR_2 = 2;
	public function getRecorrencyName() {
		return Lang_AEvent::getDescriptions ( $this->recurringType );
	}
	public function setRecurringType($recurringType) {
		
		// Verifica se o tipo de recorrência é válida
		$valid = in_array ( $recurringType, array (
				self::CONST_RECORRENCY_NO,
				self::CONST_RECORRENCY_DAY,
				self::CONST_RECORRENCY_WEEK,
				self::CONST_RECORRENCY_MONTH,
				self::CONST_RECORRENCY_YEAR,
				self::CONST_RECORRENCY_BIMESTRAL,
				self::CONST_RECORRENCY_TRIMESTRAL,
				self::CONST_RECORRENCY_SEMESTRAL,
				self::CONST_RECORRENCY_SUNDAY,
				self::CONST_RECORRENCY_MONDAY,
				self::CONST_RECORRENCY_TUESDAY,
				self::CONST_RECORRENCY_WEDNESDAY,
				self::CONST_RECORRENCY_THURSDAY,
				self::CONST_RECORRENCY_FRIDAY,
				self::CONST_RECORRENCY_SATURDAY 
		) );
		if (! $valid)
			throw new UserException ( Lang_AEvent::getDescriptions ( self::CONST_ERROR_1 ), self::CONST_ERROR_1 );
		
		$this->recurringType = $recurringType;
	}
	public function getFinalDate() {
		return $this->finalDate;
	}
	public function setFinalDate(Datetime $finalDate) {
		$this->finalDate = $finalDate;
	}
	public function getBeginDate() {
		return $this->beginDate;
	}
	public function setBeginDate(Datetime $beginDate) {
		$this->beginDate = $beginDate;
	}
	public function getObservations() {
		return $this->observations;
	}
	public function setObservations($observations) {
		$this->observations = $observations;
	}
	public function getArrMoreContats() {
		return $this->arrMoreContats;
	}
	public function setArrMoreContats($arrMoreContats) {
		$this->arrMoreContats = $arrMoreContats;
	}
	public function getArrPlacesAddresses() {
		return $this->arrPlacesAddresses;
	}
	public function setArrPlacesAddresses($arrPlacesAddresses) {
		$this->arrPlacesAddresses = $arrPlacesAddresses;
	}
	public function getArrRelatedDocumentsIds() {
		return $this->arrRelatedDocumentsIds;
	}
	public function setArrRelatedDocumentsIds($arrRelatedDocumentsIds) {
		$this->arrRelatedDocumentsIds = $arrRelatedDocumentsIds;
	}
	public function getArrPromoters() {
		return $this->arrPromoters;
	}
	public function setArrPromoters($arrPromoters) {
		$this->arrPromoters = $arrPromoters;
	}
	public function getRecurringType() {
		return $this->recurringType;
	}
	public function getRecurringAmount() {
		return $this->recurringAmount;
	}
	public function setRecurringAmount($recurringAmount) {
		$this->recurringAmount = $recurringAmount;
	}
	public function getRememberingDate() {
		return $this->rememberingDate;
	}
	public function setRememberingDate(Datetime $rememberingDate) {
		$this->rememberingDate = $rememberingDate;
	}
	public function IsPrivate() {
		return $this->private;
	}
	public function setPrivate($private) {
		if (! is_bool ( $private ))
			throw new UserException ( Lang_AEvent::getDescriptions ( self::CONST_ERROR_2 ), self::CONST_ERROR_2 );
		
		$this->private = $private;
	}
}
?>