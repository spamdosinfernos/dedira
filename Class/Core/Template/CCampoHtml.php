<?php
class CampoHtml{

	/*
	 * Valores aceitos para o tipo de campo
	 */
	const CONST_TIPO_INT = "int";
	const CONST_TIPO_FLOAT = "float";
	const CONST_TIPO_ARRAY = "array";
	const CONST_TIPO_SENHA = "password";
	const CONST_TIPO_STRING = "string";
	const CONST_TIPO_DATETIME = "datetime";

	/*
	 * Valore aceitos para tipo de edição
	 */
	const CONST_EDIT_SIM = "editSim";
	const CONST_EDIT_NAO = "editNao";
	const CONST_EDIT_IGNORAR = "editIgnorar";
	const CONST_EDIT_ESCONDER = "editEsconder";

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
	 * @var string
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

	/**
	 * Indica se o campo é multilinha
	 * @var boolean
	 */
	private $multilinha;

	public function __construct(){
		//Setando os valores padrões de cada campo

		//Sempre é uma string por padrão padrão pois este tipo é um dos mais flexíveis
		$this->tipo = self::CONST_TIPO_STRING;

		//Evita muitos campos vazios nos dados
		$this->requerido = true;

		/*
		 * Evita que informações inúteis sejam exibidas por engano, 
		 * pois é necessário explicitar a propriedade que será exposta
		 */
		$this->editavel = self::CONST_EDIT_IGNORAR;
	}

	public function getTipo(){
		return $this->tipo;
	}

	public function setTipo($tipo){

		//Se o tipo informado for inválido lança um excessão
		if(!in_array($tipo,array(self::CONST_TIPO_SENHA, self::CONST_TIPO_INT, self::CONST_TIPO_FLOAT, self::CONST_TIPO_ARRAY,	self::CONST_TIPO_STRING, self::CONST_TIPO_DATETIME))){
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
		if(!in_array($editavel,array(self::CONST_EDIT_SIM, self::CONST_EDIT_NAO, self::CONST_EDIT_IGNORAR,	self::CONST_EDIT_ESCONDER))){
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

	public function getMultilinha(){
		return $this->multilinha;
	}

	public function setMultilinha($multilinha){

		//Se o valor informado for inválido lança um excessão
		if(!is_bool($multilinha)){
			throw new CUserException(
			CConfiguracao::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_TEXTO,
			CConfiguracao::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_COD,
			$multilinha
			);
		}

		$this->multilinha = $multilinha;
	}

	public function getHtml(){

		//O campo deve ter uma descrição
		if($this->getDescricao() == "") throw new CUserException(CConfiguracao::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_TEXTO, CConfiguracao::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_COD, "O campo 'descrição' não pode ser vazio.");
		//O campo deve ter um nome
		if($this->getNome() == "") throw new CUserException(CConfiguracao::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_TEXTO, CConfiguracao::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_COD, "O campo 'nome' não pode ser vazio.");


		$xTemplate = new CXTemplate(CConfiguracao::getDiretorioDosTemplates() . DIRECTORY_SEPARATOR . "Class" . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "Template" . DIRECTORY_SEPARATOR . "CCampoHTML.html");

		//Seta as propriedades do campo
		switch ($this->getEditavel()){
			case self::CONST_EDIT_IGNORAR: return;
			case self::CONST_EDIT_ESCONDER:
				$xTemplate->assign("valorPadrao", $this->getValorPadrao());
				$xTemplate->assign("nome", $this->getNome());
				$xTemplate->parse("editEsconder");
			case self::CONST_EDIT_NAO:
				$xTemplate->assign("valorPadrao", $this->getValorPadrao());
				$xTemplate->assign("descricao", $this->getDescricao());
				$xTemplate->parse("editNao");
			case self::CONST_EDIT_SIM:
				$xTemplate->assign("valorPadrao", $this->getValorPadrao());
				$xTemplate->assign("descricao", $this->getDescricao());
				$xTemplate->assign("nome", $this->getNome());
				$xTemplate->parse($this->getMultilinha() ? "multilinhaSim" : "multilinhaNao");
				$xTemplate->parse("editNao");
		}

		//TODO usar estas propriedades para fazer uma validação via javascript, a verificação dos dados postados deve ser feita via php
		$xTemplate->assign("tipo", $this->getTipo());
		$xTemplate->assign("requerido", $this->getRequerido());

		//Gera o html
		$xTemplate->parse("CCampoHTML");

		//Retorna o html
		return $xTemplate->text();
	}
}
?>