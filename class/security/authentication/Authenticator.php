<?php
require_once __DIR__ . '/../../log/Log.php';
class Authenticator {
	
	/**
	 * Regras de autenticação
	 *
	 * @var IAuthenticationRules
	 */
	private $authenticatorDriver;
	
	/**
	 * Incia o autenticado de usuários no sistema
	 *
	 * @param IAuthenticationRules $authenticationRules        	
	 */
	public function __construct(IAuthenticationRules $authenticationRules = null) {
		$this->authenticatorDriver = $authenticationRules;
	}
	
	/**
	 * Informa se o usário está autenticado no sistema
	 *
	 * @return boolean
	 */
	public function isAuthenticated() {
		if (! isset ( $_SESSION )) session_start ();
		return isset ( $_SESSION ['authData'] ['autenticatedEntity'] );
	}
	
	/**
	 * Desautentica o usuário do sistema
	 */
	public function unauthenticate() {
		if (! isset ( $_SESSION )) {
			session_start ();
		}
		unset ( $_SESSION );
		@session_destroy ();
	}
	
	/**
	 * Autentica o usuário no sistema
	 *
	 * @return boolean
	 */
	public function authenticate(): bool {
		try {
			// Caso a verificação esteja ok, verifica se o usuário e senha são válidos
			if ($this->authenticatorDriver->checkAuthenticationData ()) {
				
				// Se tudo deu certo incia a sessão e atribui o id do usuário
				@session_destroy ();
				session_start ();
				session_regenerate_id ();
				
				$_SESSION ['authData'] ['autenticatedEntity'] = serialize ( $this->authenticatorDriver->getAutenticatedEntity () );
				return true;
			}
		} catch ( Exception $e ) {
			Log::recordEntry ( $e->getMessage () );
			throw new Exception ( $e->getMessage () );
		}
		// login ou senha inválidos
		return false;
	}
	public function setAuthenticationRules(IAuthenticationRules $authenticatiorDriver) {
		$this->authenticatorDriver = $authenticatiorDriver;
	}
	public function getAutenticatedEntity() {
		if (! isset ( $_SESSION )) session_start ();
		return unserialize ( $_SESSION ['authData'] ['autenticatedEntity'] );
	}
}
?>