<?php
class CampoHtml{

	/*
	 * Valores aceitos para o tipo de campo
	 */
	const CONST_TIPO_INT = "int";
	const CONST_TIPO_FLOAT = "foat";
	const CONST_TIPO_ARRAY = "array";
	const CONST_TIPO_STRING = "string";
	const CONST_TIPO_DATETIME = "datetime";

	/**
	 * Tipo do campo
	 * @var string : int, float, string, datetime, array
	 */
	private $tipo;

	/**
	 * @var string
	 */
	private $nome;

	/**
	 * @var boolean
	 */
	private $editavel;

	/**
	 * @var string
	 */
	private $descricao;

	/**
	 * @var boolean
	 */
	private $requerido;

	/**
	 * @var string | numeric
	 */
	private $valorPadrao;


	public function getTipo(){
		return $this->tipo;
	}

	public function setTipo($tipo){

		//Se o tipo informado for inválido lança um excessão
		if(!in_array($tipo,array(self::CONST_TIPO_INT, self::CONST_TIPO_FLOAT, self::CONST_TIPO_ARRAY,	self::CONST_TIPO_STRING, self::CONST_TIPO_DATETIME))){
			throw new CUserException(
			CConfiguracao::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_TEXTO,
			CConfiguracao::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_COD,
			$tipo
			);
		}

		$this->tipo = $tipo;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}

	public function getEditavel(){
		return $this->editavel;
	}

	public function setEditavel($editavel){

		//Se o valor informado for inválido lança um excessão
		if(!is_bool($editavel)){
			throw new CUserException(
			CConfiguracao::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_TEXTO,
			CConfiguracao::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_COD,
			$editavel
			);
		}

		$this->editavel = $editavel;
	}

	public function getDescricao(){
		return $this->descricao;
	}

	public function setDescricao($descricao){
		$this->descricao = $descricao;
	}

	public function getRequerido(){
		return $this->requerido;
	}

	public function setRequerido($requerido){

		//Se o valor informado for inválido lança um excessão
		if(!is_bool($requerido)){
			throw new CUserException(
			CConfiguracao::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_TEXTO,
			CConfiguracao::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_COD,
			$requerido
			);
		}

		$this->requerido = $requerido;
	}

	public function getValorPadrao(){
		return $this->valorPadrao;
	}

	public function setValorPadrao($valorPadrao){
		$this->valorPadrao = $valorPadrao;
	}
	
	public function getHtml(){

		//TODO preciso fazer com esta parte retorne um html completo para que eu possa seguir fazendo o gerador de páginas
		$xtemplate = new CXTemplate(CConfiguracao::getDiretorioDosTemplates() . DIRECTORY_SEPARATOR . "Class" . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "Template" . DIRECTORY_SEPARATOR . "CCampoHTML.html");
	}
}
?>