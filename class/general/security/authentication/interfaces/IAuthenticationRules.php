<?php
/**
 * Define o padrão para classes que devem fazer a verificação de login e senha de usuário
 */
interface IAuthenticationRules{
	
	/**
	 * Seta o login do usuário
	 * @return string
	 */ 
	public function setUser(User $user);
	
	/**
	 * Verifica se o usuário e senha são válidos
	 * @return boolean true : válidos | false : inválidos
	 * @see setUser
	 */
	public function checkAuthenticationData() : bool;
	
	/**
	 * Retorna a id do usuário dentro da sessão
	 * Execute com sucesso "checkAuthenticationData()" 
	 * antes de usar isso.
	 * @return string | int
	 */
	public function getAutenticatedEntity();
	
}
?>