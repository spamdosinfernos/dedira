<?php
/**
 * Define o padrão para classes que devem fazer a verificação de login e senha de usuário
 */
interface IAuthenticationRules {
	
	/**
	 * Sets the entity that will be authenticated
	 */
	public function setEntity($user);
	
	/**
	 * Authenticate
	 * 
	 * @return bool
	 */
	public function checkAuthenticationData(): bool;
	
	/**
	 * Gets the authenticated entity
	 * 
	 * @return object
	 */
	public function getAutenticatedEntity();
}
?>