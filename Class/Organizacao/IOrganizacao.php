<?php
interface IOrganizacao{

	/**
	 * Tipo da organização social
	 * @example Camponesa, Sindical, Estudantil, etc
	 * @var CTipoOrganizacao
	 */
	private $tipo;

	private $nome;

	private $descricao;

	private $arrContatos;

	private $arrFocosDeAtuacao;

	/**
	 * SubOrganizações podem ser interpretadas como as frentes
	 * @var Array : IOrganizacao
	 */
	private $arrSubOrganizacoes;

	/**
	 * Informa a organização pai da frente se for "null" é uma organização raíz
	 * @var COrganizacaoPolitica
	 */
	private $organizacaoPai;

	public function __construct();

	public function setOrganizacaoPai(COrganizacaoPolitica $organizacaoPai);

	public function getOrgnaizacaoPai();

}