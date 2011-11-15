<?php
require_once __DIR__ . '/../Log/CLog.php';

class CAuthenticator{

	/**
	 * Regras de autenticação
	 * @var IAuthenticationRules
	 */
	private $authenticationRules;

	/**
	 * Id do usuário na sessão
	 * @var int | string
	 */
	private $authenticationId;

	/**
	 * Incia o autenticado de usuários no sistema
	 * @param IAuthenticationRules $authenticationRules
	 */
	public function __construct(IAuthenticationRules $authenticationRules = null){
		$this->authenticationRules = $authenticationRules;
	}

	/**
	 * Retorna a id do usuário na sessão
	 * @return int | string
	 */
	public function getUserId(){
		if(!isset($_SESSION)) session_start();
		return $_SESSION['userData']['userId'];
	}

	/**
	 * Informa se o usário está autenticado no sistema
	 * @return boolean
	 */
	public function isAuthenticated(){
		if(!isset($_SESSION)) session_start();
		return isset($_SESSION['userData']['userId']);
	}

	/**
	 * Desautentica o usuário do sistema
	 */
	public function unauthenticate(){
		if(!isset($_SESSION)) session_start();
		unset($_SESSION);
		@session_destroy();
	}

	/**
	 * Autentica o usuário no sistema
	 * @return boolean
	 */
	public function authenticate(){

		//TODO Apagar!!!!
		session_start();
		session_regenerate_id();
		$_SESSION['userData']['userId'] = 1;

		try{
			//Verificando se a verificação de usuário e password retorna o tipo de valor esperado (booleano)
			$isValid = $this->authenticationRules->verifyUserAndPassword();
			if(!is_bool($isValid)) throw new Exception("O procedimento 'verifyUserAndPassword' deve retornar um valor booleano.");

			//Caso a verificação esteja ok, verifica se o usuário e password são válidos
			if($isValid){

				//Se o usuário e password são válidos recupera a id do usuário na sessão
				$authenticationId = $this->authenticationRules->getAutenticationId();
				if(!(is_numeric($authenticationId) || is_string($authenticationId))) throw new Exception("O procedimento 'getAutenticationId' deve retornar uma string ou um número.");

				@session_destroy();
				//Se tudo deu certo incia a sessão e atribui o id do usuário
				session_start();
				session_regenerate_id();
				$_SESSION['userData']['userId'] = $authenticationId;

				$this->authenticationId = $authenticationId;

				return true;
			}

		}catch(Exception $e){
			new Log($e->getMessage());
			throw new Exception($e->getMessage());
		}
		//login ou password inválidos
		return false;
	}

	public function setAuthenticationRules(IAuthenticationRules $authenticationRules){
		$this->authenticationRules = $authenticationRules;
	}

	/**
	 * Verifica se houve uma solicitação de autenticação na página
	 */
	public function isAuthenticationRequested(){
		return isset($_POST["login"]) && isset($_POST["password"]);
	}

	public function getAuthenticationId(){
		return $this->authenticationId;
	}
}
?>