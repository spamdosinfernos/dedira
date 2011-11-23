<?php
require_once __DIR__ . '/../core/Core.php';
require_once __DIR__ . '/ICorrenteIdeologica.php';

class CorrenteIdeologica  implements ICorrenteIdeologica{
	
	protected $nome;
	
	protected $descricao;
	
	protected $arrBibliografia;

	public function getNome(){
	    return $this->nome;
	}

	public function setNome($nome){
	    $this->nome = $nome;
	}

	public function getDescricao(){
	    return $this->descricao;
	}

	public function setDescricao($descricao){
	    $this->descricao = $descricao;
	}

	public function getArrBibliografia(){
	    return $this->arrBibliografia;
	}

	public function setArrBibliografia($arrBibliografia){
	    $this->arrBibliografia = $arrBibliografia;
	}
}

?>