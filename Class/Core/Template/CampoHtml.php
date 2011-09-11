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

		if(!in_array($tipo,array(self::CONST_TIPO_INT, self::CONST_TIPO_FLOAT, self::CONST_TIPO_ARRAY,	self::CONST_TIPO_STRING, self::CONST_TIPO_DATETIME))


		$this->tipo = $tipo;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}

	public function getEditavel()
	{
		return $this->editavel;
	}

	public function setEditavel($editavel){
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
		$this->requerido = $requerido;
	}

	public function getValorPadrao(){
		return $this->valorPadrao;
	}

	public function setValorPadrao($valorPadrao){
		$this->valorPadrao = $valorPadrao;
	}
}
?>