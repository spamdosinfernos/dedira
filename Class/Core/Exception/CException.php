<?php
require_once __DIR__ . '/../Log/CLog.php';

/**
 * Responsável pelo tratamento e registros dos erros no sistema
 * @author tatupheba
 */
class CException extends Exception {

	public function __construct($message, $code, $infoExtra = null){
		
		//Mata o programa se a excessão não form informada corretamente
		if($message == "" || $code == "") die ("Uma excessão deve ter, necessáriamente uma mensagem é um código");

		new CLog("$message - $code: $infoExtra	" . $this->getFile() . "	" . $this->getLine() . "	" . $this->getTraceAsString());
		parent::__construct($message, $code);
	}

}

?>