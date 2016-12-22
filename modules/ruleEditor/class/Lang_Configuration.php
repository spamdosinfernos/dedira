<?php

namespace ruleEditor;

final class Lang_Configuration {
	public static function getDescriptions($descriptionId) {
		$languages = array ();
		
		// Português Brasil
		$languages ["pt_BR"] = array (
				0 => "Digite uma proposta de regra",
				1 => "Enviar",
				2 => "Nova regra inserida!"
		);
		
		// English United States
		$languages ["en_US"] = array (
				0 => "Write a rule proposal",
				1 => "Send",
				2 => "New rule added!"
		);
		return $languages [Conf::getSelectedLanguage ()] [$descriptionId];
	}
}
?>