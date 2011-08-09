<?php
require_once 'IDocumento.php';
require_once '../Core/CCore.php';

class CDocumento extends CCore implements IDocumento{

	protected $id;

	protected $rev;

	protected $autor;

	protected $conteudo;

	protected $dataDeCriacao;

	protected $nomeDoDocumento;

	protected $dataDeModificacao;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function setRev($rev){
		$this->rev = $rev;
	}

	public function getRev(){
		return $this->rev;
	}

	public function getAutor(){
		return $this->autor;
	}

	public function setAutor($autor){
		$this->autor = $autor;
	}

	public function getConteudo(){
		return $this->conteudo;
	}

	public function setConteudo($conteudo){
		$this->conteudo = $conteudo;
	}

	public function getDataDeCriacao(){
		return $this->dataDeCriacao;
	}

	public function setDataDeCriacao($dataDeCriacao){
		$this->dataDeCriacao = $dataDeCriacao;
	}

	public function getNomeDoDocumento(){
		return $this->nomeDoDocumento;
	}

	public function setNomeDoDocumento($nomeDoDocumento){
		$this->nomeDoDocumento = $nomeDoDocumento;
	}

	public function getDataDeModificacao(){
		return $this->dataDeModificacao;
	}

	public function setDataDeModificacao($dataDeModificacao){
		$this->dataDeModificacao = $dataDeModificacao;
	}
}
?>