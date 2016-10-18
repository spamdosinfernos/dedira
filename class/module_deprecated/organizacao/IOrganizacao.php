<?php
interface IOrganizacao {
	public function getTipo();
	public function setTipo(TipoOrganizacao $tipo);
	public function getNome();
	public function setNome($nome);
	public function getDescricao();
	public function setDescricao($descricao);
	public function getArrContatos();
	public function setArrContatos($arrContatos);
	public function getArrFocosDeAtuacao();
	public function setArrFocosDeAtuacao($arrFocosDeAtuacao);
	public function getArrSubOrganizacoes();
	public function setArrSubOrganizacoes($arrSubOrganizacoes);
	public function getOrganizacaoPai();
	public function setOrganizacaoPai(OrganizacaoPolitica $organizacaoPai);
}