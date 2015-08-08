<?php
require_once __DIR__ . '/../log/Log.php';

/**
 * Responsável pelo tratamento e registros dos erros no sistema
 * @author André Furlan
 */
class Exception extends Exception {

	/**
	 * Gera a excessão
	 * @param string $message
	 * @param int $code
	 * @param string $infoExtra
	 */
	public function __construct($message, $code, $infoExtra = null){
		
		//Mata o programa se a excessão não form informada corretamente
		if($message == "" || $code == "") die ("Uma excessão deve ter, necessáriamente uma mensagem é um código");

		new Log("$message - $code: $infoExtra	" . $this->getFile() . "	" . $this->getLine() . "	" . $this->getTraceAsString());
		parent::__construct($message, $code);
	}

}
?>