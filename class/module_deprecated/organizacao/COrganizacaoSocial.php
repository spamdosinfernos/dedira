<?php
require_once __DIR__ . '/Organizacao.php';
require_once __DIR__ . '/../CorrentesIdeologicas/CorrenteIdeologica.php';
class OrganizacaoSocial extends Organizacao {
	
	/**
	 * Tipo da organização social
	 * 
	 * @example Camponesa, Sindical, Estudantil, etc
	 * @var TipoOrganizacao
	 */
	protected $tipo;
	protected $arrCorrentesIdeologicas;
	public function getTipo() {
		return $this->tipo;
	}
	public function setTipo(TipoOrganizacao $tipo) {
		$this->tipo = $tipo;
	}
	public function getArrCorrentesIdeologicas() {
		return $this->arrCorrentesIdeologicas;
	}
	public function addCorrenteIdeologica(CorrenteIdeologica $correnteIdeologica) {
		$this->arrCorrentesIdeologicas [] = $correnteIdeologica;
	}
}
?>