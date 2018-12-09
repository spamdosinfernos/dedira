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
	 * Informa se o usuário está autenticado no sistema
	 *
	 * @return bool
	 */
	public function isAuthenticated(): bool {
		if (! isset ( $_SESSION ))
			session_start ();

		if (! \SessionSeed::providedSeedIsValid ()) {
			$this->unauthenticate();
			return false;
		}

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
				if (! isset ( $_SESSION )) {
					session_start ();
				}

				session_regenerate_id ();

				// The "seed" its an important mecanism witch prevents man (or woman)
				// in the middle attacks because everytime an request is sent we must
				// we must check if sent seed its equal the one stored on session, if
				// it matches the request can go ahead, otherwise send the user to
				// authentication screen, here we generate the first seed

				SessionSeed::genNextSeed ();
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
		if (! isset ( $_SESSION ))
			session_start ();
		return unserialize ( $_SESSION ['authData'] ['autenticatedEntity'] );
	}
}
?>