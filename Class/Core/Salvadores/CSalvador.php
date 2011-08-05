<?php
require_once 'ISalvador.php';

class CSalvador implements ISalvador {

	/**
	 * Objeto que se quer salvar
	 * @var mixed
	 */
	private $objParaSalvar;

	public function __construct($objParaSalvar){
		$objParaSalvar = serialize($objParaSalvar);
	}

	public function salvar(){
		

	}

}

?>