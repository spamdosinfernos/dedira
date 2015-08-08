<?php
require_once 'Exception.php';

/**
 * Dá conta das exceções geradas pelo usuário (campos de formulário mal digitados, 
 * operações não permitidas, login inválido, etc.).
 * @author André Furlan
 */
class UserException extends Exception{

	/**
	 * Gera a excessão de usuário
	 * @param string $message
	 * @param int $code
	 * @param string $infoExtra
	 */
	public function __construct($message, $code, $infoExtra = null){
		parent:: __construct($message, $code, $infoExtra = null);
	}
}
?>