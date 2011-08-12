<?php
require_once 'CEvento.php';

class CReuniao extends CEvento{

	/**
	 * @var string
	 */
	protected $pauta;

	/**
	 * Pessoas da organização que devm participar da reunião 
	 * @var Array : CMilitanteOrganico
	 * @var Array : CAdministrador
	 */
	protected $arrIntegrantes;

	/**
	 *
	 * Ouvintes e outras pessoas
	 * @var Array : CPessoa
	 * @var Array : CMilitanteDeApoio
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