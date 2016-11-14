<?php

namespace userValidator;

require_once __DIR__ . '/Conf.php';
// TODO all language classes must load languages from a file
final class Lang_Configuration {
	public static function getDescriptions($descriptionId) {
		$languages = array ();
		
		// Português Brasil
		$languages ["pt-br"] = array (
				0 => "O usuário validado com sucesso!",
				1 => "O usuário não pode ser validado pois não está cadastrado",
				2 => "Entrar no sistema"
		);
		
		// English United States
		$languages ["en-us"] = array (
				0 => "User validated!",
				1 => "Theres no such user in database",
				2 => "Login"
		);
		
		return $languages [Conf::getSelectedLanguage ()] [$descriptionId];
	}
}
?>