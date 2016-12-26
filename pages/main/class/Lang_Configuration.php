<?php

namespace main;

require_once __DIR__ . '/Conf.php';
final class Lang_Configuration {
	public static function getDescriptions( $descriptionId ){
		$languages = array ();
		
		// Português Brasil
		$languages ["pt_BR"] = array (
				0 => "Olá" 
		);
		
		// English United States
		$languages ["en_US"] = array (
				0 => "Hello" 
		);
		
		return $languages [Conf::getSelectedLanguage ()] [$descriptionId];
	}
}
?>