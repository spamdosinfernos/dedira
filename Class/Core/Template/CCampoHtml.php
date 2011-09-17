<?php
require_once __DIR__ . '/CXTemplate.php';

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
	 * Valores aceitos para tipo de edição, também
	 * é o nome dos blocos no template que definem 
	 * os tipos de campos a serem renderizados 
	 */
	const CONST_EDITAR_IGNORAR = "ignorar";
	const CONST_EDITAR_COMO_TEXTO = "editarComoTexto";
	const CONST_EDITAR_COMO_SENHA = "editarComoSenha";
	const CONST_EDITAR_APENAS_MOSTRAR = "apenasMostrar";
	const CONST_EDITAR_COMO_ESCONDIDO = "editarEscondido";
	const CONST_EDITAR_COMO_CHECKBOX = "editarComoChekBox"; //TODO Implementar no template e no código
	const CONST_EDITAR_COMO_COMBO_BOX = "editarComoComboBox"; //TODO Implementar no template e no código 
	
	/*
	 * Bloco principal do template do campo a ser gerado
	 */
	const CONST_BLOCO_PRINCIPAL = "CCampoHTML";

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
	private $valorInicial;

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
		$this->editavel = self::CONST_EDITAR_IGNORAR;
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
		if(!in_array($editavel,array(self::CONST_EDITAR_COMO_SENHA, self::CONST_EDITAR_COMO_TEXTO, self::CONST_EDITAR_APENAS_MOSTRAR, self::CONST_EDITAR_IGNORAR,	self::CONST_EDITAR_COMO_ESCONDIDO))){
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
		
		//Caso receba texto, é necessário traduzir o texto para para booleano 
		$requerido = $requerido == "true" ? true : $requerido;
		$requerido = $requerido == "false" ? false : $requerido;

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

	public function getValorInicial(){
		return $this->valorInicial;
	}

	public function setValorInicial($valorInicial){
		$this->valorInicial = $valorInicial;
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


		$xTemplate = new CXTemplate(CConfiguracao::getDiretorioDosTemplates() . "Class" . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "Template" . DIRECTORY_SEPARATOR . "CCampoHTML.html");

		//Seta as propriedades do campo
		switch ($this->getEditavel()){
			case self::CONST_EDITAR_IGNORAR: return;
			case self::CONST_EDITAR_COMO_ESCONDIDO:
				$xTemplate->assign("valorInicial", $this->getValorInicial());
				$xTemplate->assign("nome", $this->getNome());
				$xTemplate->parse("CCampoHTML." . self::CONST_EDITAR_COMO_ESCONDIDO);
				break;
			case self::CONST_EDITAR_APENAS_MOSTRAR:
				$xTemplate->assign("valorInicial", $this->getValorInicial());
				$xTemplate->assign("descricao", $this->getDescricao());
				$xTemplate->parse(self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_APENAS_MOSTRAR);
				break;
			case self::CONST_EDITAR_COMO_TEXTO || self::CONST_EDITAR_COMO_SENHA:
				$xTemplate->assign("valorInicial", $this->getValorInicial());
				$xTemplate->assign("descricao", $this->getDescricao());
				$xTemplate->assign("nome", $this->getNome());
				
				if($this->getEditavel() == self::CONST_EDITAR_COMO_SENHA){
					$xTemplate->parse(self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO . "." . self::CONST_EDITAR_COMO_SENHA);
					$xTemplate->parse(self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO);
					break;					
				}
				
				$xTemplate->parse($this->getMultilinha() ? self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO . ".multilinhaSim" : self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO . ".multilinhaNao");
				$xTemplate->parse(self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO);
				break;
		}

		//TODO usar estas propriedades para fazer uma validação via javascript, a verificação dos dados postados deve ser feita via php
		//$xTemplate->assign("tipo", $this->getTipo());
		//$xTemplate->assign("requerido", $this->getRequerido());

		//Gera o html
		$xTemplate->parse(self::CONST_BLOCO_PRINCIPAL);

		//Retorna o html
		return trim($xTemplate->text(self::CONST_BLOCO_PRINCIPAL));
	}
}
?>