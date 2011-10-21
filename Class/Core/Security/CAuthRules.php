<?php
require_once __DIR__ . '/IAuthenticationRules.php';

class CAuthRules implements IAuthenticationRules{

	private $login;

	private $password;

	/**
	 * Seta o login do usuário
	 * @return string
	 */
	public function setLogin($login){
		$this->login = $login;
	}

	/**
	 * Seta a password do usuário
	 * @return string
	 */
	public function setPassword($password){
		$this->password = strtolower(md5($password));
	}

	public function verifyUserAndPassword(){
		//TODO Implementar
		return true;
	}

	public function getAutenticationId(){
		return $this->autenticationId;
	}
}
?>