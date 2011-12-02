<?php
final class Lang_Configuration{
	
	public static function getDescriptions($descriptionId){

		//Português Brasil
		$languages["pt-br"] = array(
		1 => "Para usar o sistema você precisa se autenticar.."
		);

		//English United States
		$languages["en-us"] = array(
		1 => "You need autentication to use the system."
		);

		return $languages[Configuration::getSelectedLanguage()][$descriptionId];
	}
}
?>