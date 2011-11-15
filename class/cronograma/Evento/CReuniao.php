<?php
require_once __DIR__ . '/Evento.php';

class Reuniao extends Evento{

	/**
	 * @var string
	 */
	protected $pauta;

	/**
	 * Pessoas da organização que devm participar da reunião 
	 * @var Array : MilitanteOrganico
	 * @var Array : Administrador
	 */
	protected $arrIntegrantes;

	/**
	 *
	 * Ouvintes e outras pessoas
	 * @var Array : Pessoa
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