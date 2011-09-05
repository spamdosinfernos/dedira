<?php
require_once  __DIR__ . '/../../Class/Cronograma/CCronograma.php';
require_once __DIR__ . '/../../Class/Core/Template/CXTemplate.php';

class index extends CXTemplate{
	
	/**
	 * Guarda o gerenciador do cronograma
	 * @var CCronograma
	 */
	private $cronograma;
	
	public function __construct(){
		parent::__construct();
		$this->cronograma = new CCronograma();
		$this->cronograma->setDonoDoCronograma();
	}
	
}
new index();
?>