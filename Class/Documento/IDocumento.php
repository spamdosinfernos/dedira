<?php
interface IDocumento{

	public function getRev();

	public function setRev($id);

	public function getId();

	public function setId($id);

	public function getAutor();

	public function setAutor($autor);

	public function getConteudo();

	public function setConteudo($conteudo);

	public function getDataDeCriacao();

	public function setDataDeCriacao($dataDeCriacao);

	public function getNomeDoDocumento();

	public function setNomeDoDocumento($nomeDoDocumento);

	public function getDataDeModificacao();

	public function setDataDeModificacao($dataDeModificacao);
}
?>