<?php
require_once __DIR__ . '/ISalvador.php';

class Salvador implements ISalvador {

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