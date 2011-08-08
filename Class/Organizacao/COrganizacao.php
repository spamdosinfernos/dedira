<?php
class COrganizacao extends CCore implements IOrganizacao {

	/**
	 * Tipo da organização social
	 * @example Camponesa, Sindical, Estudantil, etc
	 * @var CTipoOrganizacao
	 */
	protected $tipo;

	protected $nome;

	protected $descricao;

	protected $arrContatos;

	protected $arrFocosDeAtuacao;

	/**
	 * SubOrganizações podem ser interpretadas como as frentes
	 * @var Array : IOrganizacao
	 */
	protected $arrSubOrganizacoes;

	/**
	 * Informa a organização pai da frente se for "null" é uma organização raíz
	 * @var COrganizacaoPolitica
	 */
	protected $organizacaoPai;

	public function __construct(){
		parent::__construct();
		$this->organizacaoPai = null;
	}

	public function setOrganizacaoPai(COrganizacaoPolitica $organizacaoPai){
		$this->organizacaoPai = $organizacaoPai;
	}

	public function getOrgnaizacaoPai(){
		return $this->organizacaoPai;
	}

}