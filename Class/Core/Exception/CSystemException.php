<?php 
class CSystemException extends CException{
	
	public function __construct($message, $code, $infoExtra = null){
		
		$infoExtra .= 
		"\nDados da sessão: " . serialize($_SESSION) . 
		"\nDados do COOKIE: " . serialize($_COOKIE) . 
		"\nDados do POST: " . serialize($_POST) .
		"\nDados do GET: " . serialize($_GET) .
		"\nDados do ENV: " . serialize($_ENV) . 
		  
		
		parent:: __construct($message, $code, $infoExtra = null);
	}
}




?>