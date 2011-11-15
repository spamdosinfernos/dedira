<?php
require_once __DIR__ . '/CXTemplate.php';
require_once __DIR__ . '/../Exception/CUserException.php';
require_once __DIR__ . '/../Configuration/Template/CPageElementConf.php';

class CPageElement{

	/*
	 * Valores aceitos para o tipo de campo
	 */
	const CONST_TIPO_INT = "int";
	const CONST_TIPO_FLOAT = "float";
	const CONST_TIPO_ARRAY = "array";
	const CONST_TIPO_SENHA = "password";
	const CONST_TIPO_STRING = "string";
	const CONST_TIPO_BOOLEANO = "boolean";
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
	const CONST_EDITAR_COMO_CHECKBOX = "editarComoCheckBox";
	const CONST_EDITAR_COMO_LIST_BOX = "editarComoListBox";

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
	private $editarComo;

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
	 * Identificação da consulta alimentadora da listbox
	 * @var string
	 */
	private $alimentador;

	/**
	 * Indica se o campo é multilinha
	 * @var boolean
	 */
	private $multilinha;

	/**
	 * Aponta para a função que seta os dados na propriedade do campo
	 * @var string
	 */
	private $setter;

	private $getter;


	private $xTemplate;

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
		$this->editarComo = self::CONST_EDITAR_IGNORAR;

		/**
		 * Os campos são, por padrão de apenas uma linha
		 */
		$this->multilinha = false;

		$this->xTemplate = new CXTemplate(CPageElementConf::getTemplateFile());
		$this->xTemplate->reset(self::CONST_BLOCO_PRINCIPAL);

	}

	public function getTipo(){
		return $this->tipo;
	}

	public function setTipo($tipo){

		//Se o tipo informado for inválido lança um excessão
		if(!in_array($tipo,array(self::CONST_TIPO_BOOLEANO, self::CONST_TIPO_SENHA, self::CONST_TIPO_INT, self::CONST_TIPO_FLOAT, self::CONST_TIPO_ARRAY,	self::CONST_TIPO_STRING, self::CONST_TIPO_DATETIME))){
			throw new CUserException(
			Configuration::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_TEXTO,
			Configuration::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_COD,
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

	public function getEditarComo(){
		return $this->editarComo;
	}

	public function setEditarComo($editarComo){

		$arrValoresAceitos = array(
		self::CONST_EDITAR_IGNORAR,
		self::CONST_EDITAR_COMO_SENHA,
		self::CONST_EDITAR_COMO_TEXTO,
		self::CONST_EDITAR_COMO_CHECKBOX,
		self::CONST_EDITAR_COMO_LIST_BOX,
		self::CONST_EDITAR_APENAS_MOSTRAR,
		self::CONST_EDITAR_COMO_ESCONDIDO
		);

		//Se o valor informado for inválido lança um excessão
		if(!in_array($editarComo, $arrValoresAceitos)){
			throw new CUserException(
			Configuration::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_TEXTO,
			Configuration::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_COD,
			$editarComo
			);
		}

		$this->editarComo = $editarComo;
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
			Configuration::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_TEXTO,
			Configuration::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_COD,
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
			Configuration::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_TEXTO,
			Configuration::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_COD,
			$multilinha
			);
		}

		$this->multilinha = $multilinha;
	}

	public function getHtml(){

		//O campo deve ter uma descrição
		if($this->getDescricao() == "") throw new CUserException(Configuration::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_TEXTO, Configuration::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_COD, "O campo 'descrição' não pode ser vazio.");
		//O campo deve ter um nome
		if($this->getNome() == "") throw new CUserException(Configuration::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_TEXTO, Configuration::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_COD, "O campo 'nome' não pode ser vazio.");

		//Seta as propriedades do campo
		switch ($this->getEditarComo()){
			case self::CONST_EDITAR_IGNORAR: return;
			case self::CONST_EDITAR_APENAS_MOSTRAR:	$this->gerarApenasVisualizacao(); break;
			case self::CONST_EDITAR_COMO_ESCONDIDO:	$this->gerarCampoEscondido(); break;
			case self::CONST_EDITAR_COMO_TEXTO: $this->gerarCampoDeTexto();	break;
			case self::CONST_EDITAR_COMO_SENHA:	$this->geraCampoDeSenha(); break;
			case self::CONST_EDITAR_COMO_CHECKBOX: $this->geraCheckBox(); break;
			case self::CONST_EDITAR_COMO_LIST_BOX: $this->geraListBox(); break;
		}

		//TODO usar estas propriedades para fazer uma validação via javascript, a verificação dos dados postados deve ser feita via php
		//$this->xTemplate->assign("tipo", $this->getTipo());
		//$this->xTemplate->assign("requerido", $this->getRequerido());

		//Gera o html
		$this->xTemplate->parse(self::CONST_BLOCO_PRINCIPAL);

		//Retorna o html
		return trim($this->xTemplate->text(self::CONST_BLOCO_PRINCIPAL));
	}

	/**
	 * Recupera os dados de uma lista de alimentação.
	 * A lista de alimentação é usada para preencher 
	 * dados em combox e list boxes
	 * @param string $nomeDaListaDealimentacao
	 * @return array : index => string
	 */
	private function getListaDeAlimentacao($nomeDaListaDealimentacao){
		//TODO implementar
		$arrRetorno = array(
		"valor00" => "descricaoDaOpcao 00" . $nomeDaListaDealimentacao,
		"valor01" => "descricaoDaOpcao 01" . $nomeDaListaDealimentacao,
		"valor02" => "descricaoDaOpcao 02" . $nomeDaListaDealimentacao,
		"valor03" => "descricaoDaOpcao 03" . $nomeDaListaDealimentacao
		);

		return $arrRetorno;
	}

	public function getAlimentador(){
		return $this->alimentador;
	}

	public function setAlimentador($alimentador){
		//Se o valor informado for inválido lança um excessão
		if(!is_string($alimentador)){
			throw new CUserException(
			Configuration::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_TEXTO,
			Configuration::CONST_ERR_FALHA_AO_SETAR_PROPRIEDADE_VALOR_INVALIDO_COD,
			$alimentador
			);
		}

		$this->alimentador = $alimentador;
	}
	private function gerarCampoEscondido(){
		$this->xTemplate->assign("valorInicial", $this->getValorInicial());
		$this->xTemplate->assign("nome", $this->getNome());
		$this->xTemplate->parse("CCampoHTML." . self::CONST_EDITAR_COMO_ESCONDIDO);
	}

	private function gerarApenasVisualizacao(){
		$this->xTemplate->assign("valorInicial", $this->getValorInicial());
		$this->xTemplate->assign("descricao", $this->getDescricao());
		$this->xTemplate->parse(self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_APENAS_MOSTRAR);
	}

	private function gerarCampoDeTexto(){
		$this->xTemplate->assign("valorInicial", $this->getValorInicial());
		$this->xTemplate->assign("descricao", $this->getDescricao());
		$this->xTemplate->assign("nome", $this->getNome());
		$this->xTemplate->parse($this->getMultilinha() ? self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO . ".multilinhaSim" : self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO . ".multilinhaNao");
		$this->xTemplate->parse(self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO);
	}

	private function geraCampoDeSenha(){
		$this->xTemplate->assign("valorInicial", $this->getValorInicial());
		$this->xTemplate->assign("descricao", $this->getDescricao());
		$this->xTemplate->assign("nome", $this->getNome());
		$this->xTemplate->parse(self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO . "." . self::CONST_EDITAR_COMO_SENHA);
		$this->xTemplate->parse(self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO);
	}

	private function geraListBox(){

		$this->xTemplate->assign("nome", $this->getNome());
		$this->xTemplate->assign("descricao", $this->getDescricao());

		//Recupera as opções disponíveis do list bo
		$arrAlimentador = $this->getListaDeAlimentacao($this->getAlimentador());
		//Recupera as opções já selecionadas
		$mixedValorInicial = $this->getValorInicial();

		//Constrói a list box
		foreach ($arrAlimentador as $valorDaOpcao => $descricaoDaOpcao) {

			$this->xTemplate->assign("valorDaOpcao", $valorDaOpcao);
			$this->xTemplate->assign("descricaoDaOpcao", $descricaoDaOpcao);

			//Marca as opções selecionadas (Comparo apenas os valores, não as descrições)
			foreach ($mixedValorInicial as $valorDaOpcaoIncial => $descricaoDaOpcaoInicial) {
				if($valorDaOpcaoIncial == $valorDaOpcao){
					$this->xTemplate->assign("selected", "selected=\"selected\"");
					break;
				}
			}

			$this->xTemplate->parse(self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO . "." . self::CONST_EDITAR_COMO_LIST_BOX . ".opcaoDoListBox");
			$this->xTemplate->assign("selected", "");
		}

		$this->xTemplate->parse(self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO . "." . self::CONST_EDITAR_COMO_LIST_BOX);
		$this->xTemplate->parse(self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO);
	}

	private function geraCheckBox(){

		$this->xTemplate->assign("nome", $this->getNome());

		//Recupera as opções disponíveis do list bo
		$arrAlimentador = $this->getListaDeAlimentacao($this->getAlimentador());

		/*
		 * Recupera as opções já selecionadas caso seja um array de opções
		 * ou informa se o checkbox está ou não marcado (true ou false)
		 */
		$mixedValorInicial = $this->getValorInicial();

		//As vezes podemos necessitar gerar um único checkbox
		if(is_bool($mixedValorInicial)){
			$this->xTemplate->assign("valorDaOpcao", true);
			$this->xTemplate->assign("descricaoDaOpcao", $this->getDescricao());
			$this->xTemplate->assign("checked", $mixedValorInicial ? "checked=\"checked\"" : "");
			$this->xTemplate->parse(self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO . "." . self::CONST_EDITAR_COMO_CHECKBOX . ".checkBox");
			$this->xTemplate->parse(self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO . "." . self::CONST_EDITAR_COMO_CHECKBOX);
			$this->xTemplate->parse(self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO);
			return;
		}

		//Constrói a lista de checkboxes
		$this->xTemplate->assign("descricao", $this->getDescricao());

		foreach ($arrAlimentador as $valorDaOpcao => $descricaoDaOpcao) {

			$this->xTemplate->assign("valorDaOpcao", $valorDaOpcao);
			$this->xTemplate->assign("descricaoDaOpcao", $descricaoDaOpcao);

			//Marca as opções selecionadas (Comparo apenas os valores, não as descrições)
			foreach ($mixedValorInicial as $valorDaOpcaoIncial => $descricaoDaOpcaoInicial) {
				if($valorDaOpcaoIncial == $valorDaOpcao){
					$this->xTemplate->assign("checked", "checked=\"checked\"");
					break;
				}
			}

			$this->xTemplate->parse(self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO . "." . self::CONST_EDITAR_COMO_CHECKBOX . ".checkBox");
			$this->xTemplate->assign("checked", "");
		}

		$this->xTemplate->parse(self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO . "." . self::CONST_EDITAR_COMO_CHECKBOX);
		$this->xTemplate->parse(self::CONST_BLOCO_PRINCIPAL . "." . self::CONST_EDITAR_COMO_TEXTO);
	}
}
?>