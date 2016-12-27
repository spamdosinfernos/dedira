<?php 
interface ISalve{
	
	/**
	 * Objeto que se quer salvar 
	 * @var mixed
	 */
	private $objParaSalvar;
	
	public function __construct($objParaSalvar);
	
	public function salvar();
	
}

?>