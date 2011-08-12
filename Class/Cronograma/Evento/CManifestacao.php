<?php
require_once 'CEvento.php';
require_once '../../Organizacao/COrganizacaoSocial.php';

class CManifestacao extends CEvento{
	
	protected $reinvindicacao;
	
	/**
	 * Movimentos promovedores
	 * @var array : COrganizacaoSocial
	 */
	protected $arrMovimentosPromovedor;

	public function getReinvindicacao(){
	    return $this->reinvindicacao;
	}

	public function setReinvindicacao($reinvindicacao){
	    $this->reinvindicacao = $reinvindicacao;
	}

	public function getArrMovimentosPromovedor(){
	    return $this->arrMovimentosPromovedor;
	}

	public function addMovimentoPromovedor(COrganizacaoSocial $movimentoPromovedor){
	    $this->arrMovimentosPromovedor[] = $movimentoPromovedor;
	}
}