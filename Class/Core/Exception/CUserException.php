<?php
/**
 * Dá conta das exceções geradas pelo usuário (campos de formulário mal digitados, 
 * operações não permitidas, login inválido, etc.).
 * @author tatupheba
 */
class CUserException extends CException{

	public function __construct($message, $code, $infoExtra = null){
		parent:: __construct($message, $code, $infoExtra = null);
	}
}
?>