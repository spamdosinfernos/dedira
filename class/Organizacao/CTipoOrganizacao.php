<?php
class CTipoOrganizacao{

	protected $nomeDoTipo;

	protected $descricao;

	public function getNomeDoTipo(){
	    return $this->nomeDoTipo;
	}

	public function setNomeDoTipo($nomeDoTipo){
	    $this->nomeDoTipo = $nomeDoTipo;
	}

	public function getDescricao(){
	    return $this->descricao;
	}

	public function setDescricao($descricao){
	    $this->descricao = $descricao;
	}
}

?>