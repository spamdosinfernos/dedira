<?php
require_once __DIR__ . '/security/Authenticator.php';
require_once __DIR__ . '/security/AuthRules.php';

class Core{

	private $arrUserModules;

	private $authenticator;

	const CONST_ACCESS_LEVEL_ROOT = 0;

	const CONST_ACCESS_LEVEL_USER = 3;

	const CONST_ACCESS_LEVEL_NONE = 4;

	protected function __construct(){

		$this->authenticator = new Authenticator(new AuthRules());

		//Deve ser sempre o primeiro comando, pois disto depende todo o sistema
		if(!$this->authenticator->isAuthenticated()){
			$this->authenticator->authenticate();
		}
	}

	protected function getUserId(){
		return $this->authenticator->getUserId();
	}

	protected function unauthenticate(){
		$this->authenticator->unauthenticate();
	}

}
?>