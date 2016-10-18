<?php
final class Lang_MainController {
	public static function getDescriptions($descriptionId) {
		
		// Português Brasil
		$languages ["pt-br"] = array (
				0 => "O sistema não pode conectar ao banco de dados." 
		);
		
		// English United States
		$languages ["en-us"] = array (
				0 => "The system can't connect to database." 
		);
		
		return $languages [Configuration::getSelectedLanguage ()] [$descriptionId];
	}
}
?>