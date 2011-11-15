<?php
interface IDocumento{

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