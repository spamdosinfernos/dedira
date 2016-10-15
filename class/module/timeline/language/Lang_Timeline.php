<?php
require_once __DIR__ . '/../Timeline.php';
final class Lang_Timeline {
	public static function getDescriptions($descriptionId) {
		
		// Português Brasil
		$languages ["pt-br"] = array (
				Timeline::CONST_ERROR_1 => "CONST_ERROR_1 - O dono deve ser informado",
				Timeline::CONST_ERROR_2 => "CONST_ERROR_2 - A data inicial deve ser informada",
				Timeline::CONST_ERROR_3 => "CONST_ERROR_3 - A data final deve ser informada" 
		);
		
		// English United States
		$languages ["en-us"] = array (
				Timeline::CONST_ERROR_1 => "CONST_ERROR_1 - Owner must be informed",
				Timeline::CONST_ERROR_2 => "CONST_ERROR_2 - Inicial date must be informed",
				Timeline::CONST_ERROR_3 => "CONST_ERROR_3 - Final date must be informed" 
		);
		
		return $languages [Configuration::getSelectedLanguage ()] [$descriptionId];
	}
}
?>