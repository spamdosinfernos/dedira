<?php
require_once __DIR__ . '/../Core/Configuracao/CConfiguracao.php';

require_once __DIR__ . '/Evento/CManifestacao.php';
require_once __DIR__ . '/Evento/CEncontro.php';
require_once __DIR__ . '/Evento/CReuniao.php';
require_once __DIR__ . '/Evento/CEvento.php';

class CCronograma extends CCore{
	
	/**
	 * Pessoa para a qual será exibido o cronograma 
	 * @var IPessoa
	 */
	private $donoDoCronograma;
	
	/**
	 * Eventos da organização que deverão estar 
	 * ou ser ordenados e organizados 
	 * @var Array : IEvento
	 */
	private $arrEventos;
	
	public function __construct(IPessoa $donoDoCronograma){
		$this->setDonoDoCronograma($donoDoCronograma);
	}
	
	public function getDonoDoCronograma(){
	    return $this->donoDoCronograma;
	}

	public function setDonoDoCronograma(IPessoa $donoDoCronograma){
	    $this->donoDoCronograma = $donoDoCronograma;
	}

	public function getArrEventos(){
	    return $this->arrEventos;
	}

	public function setArrEventos($arrEventos){
	    $this->arrEventos = $arrEventos;
	}
	
	public function AddEvento(IEvento $evento){
	    $this->arrEventos[] = $evento;
	}
}
?>