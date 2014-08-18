<?php
final class Lang_MainController{
	
	public static function getDescriptions($descriptionId){

		//Português Brasil
		$languages["pt-br"] = array(
		1 => "O sistema não pode encontrar o identificação do módulo.",
		2 => "Módulo inválido."
		);

		//English United States
		$languages["en-us"] = array(
		1 => "The system can't find the module id.",
		2 => "Invalid module."
		);

		return $languages[Configuration::getSelectedLanguage()][$descriptionId];
	}
}
?>