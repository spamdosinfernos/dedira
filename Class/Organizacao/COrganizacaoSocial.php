<?php
require_once 'COrganizacao.php';
require_once '../../CorrentesIdeologicas/CCorrenteIdeologica.php';

class COrganizacaoSocial extends COrganizacao{

	/**
	 * Tipo da organização social
	 * @example Camponesa, Sindical, Estudantil, etc
	 * @var CTipoOrganizacao
	 */
	protected $tipo;

	protected $arrCorrentesIdeologicas;

	public function getTipo(){
	    return $this->tipo;
	}

	public function setTipo(CTipoOrganizacao $tipo){
	    $this->tipo = $tipo;
	}

	public function getArrCorrentesIdeologicas(){
	    return $this->arrCorrentesIdeologicas;
	}

	public function addCorrenteIdeologica(CCorrenteIdeologica $correnteIdeologica){
	    $this->arrCorrentesIdeologicas[] = $correnteIdeologica;
	}
}
?>