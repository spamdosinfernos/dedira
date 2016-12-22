<?php

namespace userAuthenticaticator;

require_once __DIR__ . '/Conf.php';
final class Lang_Configuration {
	public static function getDescriptions($descriptionId) {
		$languages = array ();
		
		// Português Brasil
		$languages ["pt_BR"] = array (
				0 => "Por favor, digite login e senha",
				1 => "Algo de muito errado aconteceu: Falha ao carregar módulo principal!",
				2 => "Login ou senha incorretos, ou sua conta pode estar inativa."
		);
		
		// English United States
		$languages ["en_US"] = array (
				0 => "Please, type your login and passaword",
				1 => "Something very wrong happens: Fail to load the main module!",
				2 => "Login or password incorrect, or your account are inactive."
		);
		
		return $languages [Conf::getSelectedLanguage ()] [$descriptionId];
	}
}
?>