<?php
require_once 'Class/Core/Configuracao/CConfiguracao.php';

require_once 'Class/Cronograma/Evento/CManifestacao.php';
require_once 'Class/Cronograma/Evento/CEncontro.php';
require_once 'Class/Cronograma/Evento/CReuniao.php';
require_once 'Class/Cronograma/Evento/CEvento.php';

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