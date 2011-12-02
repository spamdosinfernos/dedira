<?php
require_once __DIR__ . '/Documento.php';

class Ata extends Documento{
	
	/**
	 * Participantes na reunião em que foi feita a ata
	 * @var Array : IPerson
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

	public function addPartcipantesDaReuniao(IPerson $participanteDaReuniao){
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