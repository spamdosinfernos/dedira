<?php
require_once __DIR__ . '/Seguranca/CAutenticador.php';

class CCore{

	/**
	 * Id do usuário logado, esta propriedade deve ser alterada exclusivamente
	 * pelas classes CCore e por classes que implementem a interface IPessoa.
	 * @var string
	 */
	private $idDoUsuario;

	private $idDaSessao;

	private $arrUserModules;

	const CONST_NIVEL_ACESSO_ADMINISTRADOR = 0;

	const CONST_NIVEL_ACESSO_MILITANTE_ORGANICO = 1;

	const CONST_NIVEL_ACESSO_MILITANTE_DE_APOIO = 2;

	const CONST_NIVEL_ACESSO_MILITANTE = 3;

	const CONST_NIVEL_ACESSO_PESSOA = 4;

	protected function __construct(){

		if($this->isSessaoIniciada()) return;

		//Achar alguma forma de solicitar usuário e senha para o navegador
		$this->autenticarUsuario();
	}

	protected function getRootClassName(){
		return __CLASS__;
	}

	protected function SetIdDoUsuario($idDoUsuario){

		$this->iniciarSessao();

		$_SESSION['informacoesDoUsuario']['id'] = $idDoUsuario;
	}

	protected function GetIdDoUsuario(){
		return $_SESSION['informacoesDoUsuario']['id'];
	}

	protected function autenticarUsuario($login = null, $senha = null){

		$idDoUsuario = $this->validarUsuarioESenha($login, $senha);

		if($idDoUsuario){
			$this->iniciarSessao($idDoUsuario);
			return true;
		}

		return false;
	}

	protected function desautenticar(){
		$this->finalizarSessao();
	}

	/**
	 * Valida o usuário e senha do usuário
	 * Se tudo der certo retorna a id do usuário senão retorna FALSE
	 * @param string $login
	 * @param string $senha
	 * @return string: Autenticado com sucesso | FALSE: Falha na autenticação
	 */
	private function validarUsuarioESenha($login, $senha){
		//TODO Implementar de forma descente esta parte, aqui tem ser retornado a id do usuário ou FALSE
		new CAutenticador();
	}
	/**
	 * Retorna uma chave identificando a sessão do sistema
	 * @return string
	 */
	protected function iniciarSessao($idDoUsuario = null){

		if($this->idDaSessao != '') return;

		session_start();
		session_regenerate_id();
		$this->idDaSessao = session_id();
		$_SESSION['informacoesDoUsuario']['id'] = $idDoUsuario;
	}

	private function isSessaoIniciada(){
		return isset($_SESSION['informacoesDoUsuario']['id']);
	}

	/**
	 * Finaliza sessão atual
	 */
	protected function finalizarSessao(){
		unset($_SERVER['PHP_AUTH_DIGEST']);
		session_destroy();
		$this->idDaSessao = '';
	}

	/**
	 * Transforma as propriedades do objeto em um arranjo
	 * @return array:mixed
	 */
	protected function toArray(){
		return $this->paraArray($this);
	}

	/**
	 * Transforma as propriedades do objeto ou array em um arranjo
	 * @return array:mixed
	 */
	private function paraArray($info){

		$arrSerial = array();

		//Se a informação for um objeto 
		if(is_object($info)){
			//As propriedades são as propriedades do objeto
			$reflection = new ReflectionObject($info);
			$arrPropriedades = $reflection->getProperties();
		}else{
			//Senão as propridades são os itens do arranjo
			$arrPropriedades = $info;
		}

		//Construindo a estrutura que será salva
		foreach ($arrPropriedades as $indice => $propriedade) {

			//Visibilidade da propriedade (caso o item varrido seja um objeto)
			$visibilidade = "";
			//Nome da propriedade
			$nomeDaPropriedade = "";
			//Valor da propriedade
			$valorDaPropriedade = "";

			//Recupera os dados da propridade do objeto
			if(is_object($info)){
				$nomeDaPropriedade = $propriedade->getName();
				$visibilidade = $propriedade->getModifiers();
				$valorDaPropriedade = $info->$nomeDaPropriedade;

				//Correção de bug aparente: As vezes a visibilidade fica em 4096 sendo que o máximo é 102
				$visibilidade = $visibilidade > ReflectionMethod::IS_PRIVATE ? ReflectionMethod::IS_PUBLIC : $visibilidade;
			}

			//Se o valor da propriedade é um objeto, chama recursivamente o método
			if(is_object($valorDaPropriedade)){
				$arrSerial[$nomeDaPropriedade][$visibilidade] = $this->paraArray($valorDaPropriedade);
				continue;
			}

			//Se o valor da propriedade é um array, chama recursivamente o método
			if(is_array($valorDaPropriedade)){
				$arrSerial[$nomeDaPropriedade][$visibilidade] = $this->paraArray($valorDaPropriedade);
				continue;
			}

			if($visibilidade == ""){
				//Quando a visibilidade é vazia, isso significa que a propriedade é o item de um array
				$arrSerial[$indice] = $propriedade;
			}else{
				//Quando a visibilidade não é vazia, isso significa que é uma propriedade de um objeto
				$arrSerial[$nomeDaPropriedade][$visibilidade] = $info->$nomeDaPropriedade;
			}
		}

		//Se a informação tratada for um objeto, adiciona o nome da classe na estrura a ser salva
		if(is_object($info)) $arrSerial["CLASSNAME"] = get_class($info);

		return $arrSerial;
	}
}
?>