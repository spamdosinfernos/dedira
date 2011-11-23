<?php
require_once  __DIR__ . '/../../Class/Cronograma/Cronograma.php';
require_once __DIR__ . '/../../Class/general/template/CXTemplate.php';

class index extends CXTemplate{
	
	/**
	 * Guarda o gerenciador do cronograma
	 * @var Cronograma
	 */
	private $cronograma;
	
	public function __construct(){
		parent::__construct();
		$this->cronograma = new Cronograma();
		$this->cronograma->setTimelineOwner();
	}
	
}
new index();
?>