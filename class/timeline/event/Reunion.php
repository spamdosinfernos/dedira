<?php
require_once 'Event.php';

class Reunion extends Event{

	/**
	 * @var string
	 */
	protected $pauta;

	/**
	 * Pessoas da organização que devem participar da reunião 
	 * @var Array : MilitanteOrganico
	 * @var Array : Administrador
	 */
	protected $arrIntegrantes;

	/**
	 *
	 * Ouvintes e outras pessoas
	 * @var Array : Person
	 * @var Array : MilitanteDeApoio
	 */
	protected $arrConvidadosOpcionais;
	
	public function getPauta(){
		return $this->pauta;
	}

	public function setPauta($pauta){
		$this->pauta = $pauta;
	}

	public function getArrIntegrantes(){
		return $this->arrIntegrantes;
	}

	public function setArrIntegrantes($arrIntegrantes){
		$this->arrIntegrantes = $arrIntegrantes;
	}

	public function getArrConvidadosOpcionais(){
		return $this->arrConvidadosOpcionais;
	}

	public function setArrConvidadosOpcionais($arrConvidadosOpcionais){
		$this->arrConvidadosOpcionais = $arrConvidadosOpcionais;
	}
}
?>