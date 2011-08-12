<?php
require_once './../../Core/CCore.php';
require_once 'ICorrenteIdeologica.php';

class CCorrenteIdeologica extends CCore implements ICorrenteIdeologica{
	
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