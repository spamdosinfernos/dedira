<?php
class CUserException extends CException{

	public function __construct($message, $code, $infoExtra = null){
		parent:: __construct($message, $code, $infoExtra = null);
	}
}
?>