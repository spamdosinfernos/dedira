<?php

namespace userAuthenticaticator;

require_once __DIR__ . '/UserAuthenticaticatorConf.php';
final class Lang_Configuration {
	public static function getDescriptions($descriptionId) {
		$languages = array ();
		
		// Português Brasil
		$languages ["pt-br"] = array (
				0 => "Por favor, digite login e senha",
				1 => "Algo de muito errado aconteceu: Falha ao carregar módulo principal!" 
		);
		
		// English United States
		$languages ["en-us"] = array (
				0 => "Please, type your login and passaword",
				1 => "Something very wrong happens: Fail to load the main module!" 
		);
		
		return $languages [UserAuthenticaticatorConf::getSelectedLanguage ()] [$descriptionId];
	}
}
?>