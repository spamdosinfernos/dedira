<?php
require_once __DIR__ . '/IDocumento.php';
require_once __DIR__ . '/../../general/database/StorableObject.php';
class Documento extends AStorableObject implements IDocumento {
	protected $autor;
	protected $conteudo;
	protected $dataDeCriacao;
	protected $nomeDoDocumento;
	protected $dataDeModificacao;
	public function getAutor() {
		return $this->autor;
	}
	public function setAutor($autor) {
		$this->autor = $autor;
	}
	public function getConteudo() {
		return $this->conteudo;
	}
	public function setConteudo($conteudo) {
		$this->conteudo = $conteudo;
	}
	public function getDataDeCriacao() {
		return $this->dataDeCriacao;
	}
	public function setDataDeCriacao(DateTime $dataDeCriacao) {
		$this->dataDeCriacao = $dataDeCriacao;
	}
	public function getNomeDoDocumento() {
		return $this->nomeDoDocumento;
	}
	public function setNomeDoDocumento($nomeDoDocumento) {
		$this->nomeDoDocumento = $nomeDoDocumento;
	}
	public function getDataDeModificacao() {
		return $this->dataDeModificacao;
	}
	public function setDataDeModificacao(DateTime $dataDeModificacao) {
		$this->dataDeModificacao = $dataDeModificacao;
	}
}
?>