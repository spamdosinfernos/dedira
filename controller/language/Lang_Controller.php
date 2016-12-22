<?php
final class Lang_Controller {
	public static function getDescriptions($descriptionId) {
		
		// Português Brasil
		$languages ["pt_BR"] = array (
				0 => "O sistema não pode conectar ao banco de dados.",
				1 => "Falha ao carregar módulo!"
		);
		
		// English United States
		$languages ["en_US"] = array (
				0 => "The system can't connect to database.", 
				1 => "Fail on load a module!"
		);
		
		return $languages [Configuration::getSelectedLanguage ()] [$descriptionId];
	}
}
?>