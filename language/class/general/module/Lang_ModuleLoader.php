<?php
final class Lang_ModuleLoader{
	
	public static function getDescriptions($descriptionId){

		//Português Brasil
		$languages["pt-br"] = array(
		1 => "O módulo informado não é válido."
		);

		//English United States
		$languages["en-us"] = array(
		1 => "The informed module is inválid."
		);

		return $languages[Configuration::getSelectedLanguage()][$descriptionId];
	}
}
?>