<?php
require_once 'CDocumento.php';

class CAta extends CDocumento{
	
	/**
	 * Participantes na reunião em que foi feita a ata
	 * @var Array : IPessoa
	 */
	protected $arrPartcipantesDaReuniao;
	
	/**
	 * Deliberações constantes na ata
	 * @var Array : string
	 */
	protected $arrDeliberacoes;

	public function getArrPartcipantesDaReuniao(){
	    return $this->arrPartcipantesDaReuniao;
	}

	public function addPartcipantesDaReuniao(IPessoa $participanteDaReuniao){
	    $this->arrPartcipantesDaReuniao[] = $participanteDaReuniao;
	}

	public function getArrDeliberacoes(){
	    return $this->arrDeliberacoes;
	}

	public function addDeliberacoes($deliberacao){
	    $this->arrDeliberacoes[] = $deliberacao;
	}
}

?>