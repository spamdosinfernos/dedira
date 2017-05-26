<?php
require_once __DIR__ . '/../Timeline.php';
final class Lang_Timeline {
	public static function getDescriptions($descriptionId) {
		
		// Português Brasil
		$languages ["pt_BR"] = array (
				Timeline::ERROR_1 => "ERROR_1 - O dono deve ser informado",
				Timeline::ERROR_2 => "ERROR_2 - A data inicial deve ser informada",
				Timeline::ERROR_3 => "ERROR_3 - A data final deve ser informada" 
		);
		
		// English United States
		$languages ["en_US"] = array (
				Timeline::ERROR_1 => "ERROR_1 - Owner must be informed",
				Timeline::ERROR_2 => "ERROR_2 - Inicial date must be informed",
				Timeline::ERROR_3 => "ERROR_3 - Final date must be informed" 
		);
		
		return $languages [Configuration::$defaultLanguage] [$descriptionId];
	}
}
?>