<?php
/**
 * Define o padrão para classes que devem fazer a verificação de login e password de usuário
 */
interface IAuthenticationRules{
	
	/**
	 * Seta o login do usuário
	 * @return string
	 */ 
	public function setLogin($login);
	
	/**
	 * Seta a password do usuário
	 * @return string
	 */
	public function setPassword($password);
	
	/**
	 * Verifica se o usuário e password são válidos
	 * @return boolean true : válidos | false : inválidos
	 * @see setLogin
	 * @see setPassword
	 */
	public function verifyUserAndPassword();
	
	/**
	 * Retorna a id do usuário dentro da sessão
	 * Execute com sucesso "verifyUserAndPassword()" 
	 * antes de usar isso.
	 * @return string | int
	 */
	public function getAutenticationId();
	
}
?>