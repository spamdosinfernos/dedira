<?php
class CCore{

	/**
	 * Id do usuário logado, esta propriedade deve ser alterada exclusivamente
	 * pelas classes CCore e por classes que implementem a interface IPessoa.
	 * @var string
	 */
	//private $idDoUsuario;

	private $idDaSessao;

	const CONST_NIVEL_ACESSO_ADMINISTRADOR = 0;

	const CONST_NIVEL_ACESSO_MILITANTE_ORGANICO = 1;

	const CONST_NIVEL_ACESSO_MILITANTE_DE_APOIO = 2;

	const CONST_NIVEL_ACESSO_MILITANTE = 3;

	const CONST_NIVEL_ACESSO_PESSOA = 4;

	protected function __construct(){
		$this->idDaSessao == '';
	}

	protected function SetIdDoUsuario($idDoUsuario){

		$this->iniciarSessao();

		$_SESSION['informacoesDoUsuario']['id'] = $idDoUsuario;
	}

	protected function GetIdDoUsuario(){
		return $_SESSION['informacoesDoUsuario']['id'];
	}

	protected function autenticarUsuario($login, $senha){

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
		//TODO IMPLEMENTAR
		return "54g5fd4g5fd4";
	}
	/**
	 * Retorna uma chave identificando a sessão do sistema
	 * @return string
	 */
	protected function iniciarSessao($idDoUsuario){

		if($this->idDaSessao != '') return;

		session_start();
		session_regenerate_id();
		$this->idDaSessao = session_id();
		$_SESSION['informacoesDoUsuario']['id'] = $idDoUsuario;
	}

	/**
	 * Finaliza sessão atual
	 */
	protected function finalizarSessao(){
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

	private function paraArray($obj){

		$arrSerial = array();

		if(is_object($obj)){
			$reflection = new ReflectionObject($obj);
			$arrPropriedades = $reflection->getProperties();
		}else{
			$arrPropriedades = $obj;
		}

		foreach ($arrPropriedades as $indice => $propriedade) {

			$modificador = "";
			$nomeDaPropriedade = "";
			$valorDaPropriedade = "";
				
			if(is_object($obj)){
				$nomeDaPropriedade = $propriedade->getName();
				$modificador = $propriedade->getModifiers();
				//Muda o valor do valor... hehe...
				$valorDaPropriedade = $obj->$nomeDaPropriedade;
			}

			if(is_object($valorDaPropriedade)){
				$arrSerial[$nomeDaPropriedade][$modificador] = $this->paraArray($valorDaPropriedade);
				continue;
			}

			if(is_array($valorDaPropriedade)){
				//PAREI AQUI
				$arrSerial[$nomeDaPropriedade][$modificador] = $this->paraArray($valorDaPropriedade);
				continue;
			}

			if($modificador == ""){
				$arrSerial[$indice] = $propriedade;
			}else{
				$arrSerial[$nomeDaPropriedade][$modificador] = $obj->$nomeDaPropriedade;
			}

		}

		if(is_object($obj))	$arrSerial["CLASSNAME"] = get_class($obj);
		return $arrSerial;
	}
}
?>