<?php
require_once  __DIR__ . '../../Class/Cronograma/CCronograma.php';

class index {
	
	/**
	 * Guarda o gerenciador do cronograma
	 * @var CCronograma
	 */
	private $cronograma;
	
	public function __construct(){
		$this->cronograma = new CCronograma();
	}
	
}
new index();
?>