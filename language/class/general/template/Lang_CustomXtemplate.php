<?php
require_once __DIR__ . '/../CustomXtemplate.php';

final class Lang_CustomXtemplate{

	public static function getDescriptions($descriptionId){

		//Português Brasil
		$languages["pt-br"] = array(
		Event::CONST_ERROR_1 => "CONST_ERROR_1 - O arquivo de template não existe!"
		);

		//English United States
		$languages["en-us"] = array(
		Event::CONST_ERROR_1 => "CONST_ERROR_1 - The template file does not exist!"
		);

		return $languages[Configuration::getSelectedLanguage()][$descriptionId];
	}
}
?>