<?php
require_once '../Core/Configuracao/CConfiguracao.php';

require_once 'Evento/CManifestacao.php';
require_once 'Evento/CEncontro.php';
require_once 'Evento/CReuniao.php';
require_once 'Evento/CEvento.php';

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
		$this->donoDoCronograma = $donoDoCronograma;
	}
	
}
?>