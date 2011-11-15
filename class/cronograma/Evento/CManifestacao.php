<?php
require_once __DIR__ . '/Evento.php';
require_once __DIR__ . '/../../Organizacao/OrganizacaoSocial.php';

class Manifestacao extends Evento{
	
	protected $reinvindicacao;
	
	/**
	 * Movimentos promovedores
	 * @var array : OrganizacaoSocial
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

	public function addMovimentoPromovedor(OrganizacaoSocial $movimentoPromovedor){
	    $this->arrMovimentosPromovedor[] = $movimentoPromovedor;
	}
}