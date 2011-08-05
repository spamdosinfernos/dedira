<?php
require_once 'Class/Core/Log/CLog.php';

/**
 * Responsável pelo tratamento e registros dos erros no sistema
 * @author tatupheba
 */
class CException extends Exception {

	public function __construct($message, $code, $infoExtra = null){

		new CLog("$message - $code: $infoExtra");
		parent::__construct($message, $code);
	}

}

?>