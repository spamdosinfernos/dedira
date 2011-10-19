<?php
require_once __DIR__ . '/Seguranca/CAutenticador.php';

//TODO Apagar!!!!
session_start();
$_SESSION['informacoesDoUsuario']['id'] = 10;

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
		//Deve ser sempre o primeiro comando, pois disto depende todo o sistema
		if(!$this->isSessaoIniciada()){
			session_start();
			//Se necessário solicita a autenticação do usuário
			$this->autenticarUsuario();
		}
	}

	protected function GetIdDoUsuario(){
		return $_SESSION['informacoesDoUsuario']['id'];
	}

	protected function desautenticar(){
		$this->finalizarSessao();
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













	/**
	 * Realiza a autenticação de um usuário no sistema
	 * @return null - Falha na autenticação | string - Id do usuário
	 */
	public function autenticarUsuario(){

		//Se o usuário já estiver autenticado retorna o id da sessão
		if(isset($_SESSION['informacoesDoUsuario']['id'])){
			$this->idDaSessao = session_id();
			return $this->idDaSessao;
		}

		$users = array('tatupheba' => 'tatu7', 'guest' => 'guest');

		if(empty($_SERVER['PHP_AUTH_DIGEST'])){
			header('HTTP/1.1 401 Unauthorized');
			header('WWW-Authenticate: Digest realm="' . CConfiguracao::CONST_AUTH_MENSAGEM . '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5(CConfiguracao::CONST_AUTH_MENSAGEM) . '"');

			die('Autenticação cancelada.');
		}

		if(
		!($data = $this->httpDigestParse($_SERVER['PHP_AUTH_DIGEST'])) ||
		!isset($users[$data['username']])
		) die('Falha na autenticação');

		$A1 = md5($data['username'] . ':' . CConfiguracao::CONST_AUTH_MENSAGEM . ':' . $users[$data['username']]);
		$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
		$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

		if ($data['response'] != $valid_response) die('Falha na autenticação');

		session_regenerate_id();
		$this->idDaSessao = session_id();
		$_SESSION['informacoesDoUsuario']['id'] = $data['username'];

		return $this->idDaSessao;
	}

	// function to parse the http auth header
	private function httpDigestParse($txt){
		// protect against missing data
		$needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
		$data = array();
		$keys = implode('|', array_keys($needed_parts));

		preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

		foreach ($matches as $m) {
			$data[$m[1]] = $m[3] ? $m[3] : $m[4];
			unset($needed_parts[$m[1]]);
		}

		return $needed_parts ? false : $data;
	}
















}
?>