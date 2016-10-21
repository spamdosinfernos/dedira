<?php

namespace main;

require_once __DIR__ . '/MainConf.php';
final class Lang_Configuration {
	public static function getDescriptions( $descriptionId ){
		$languages = array ();
		
		// Português Brasil
		$languages ["pt-br"] = array (
				0 => "Olá" 
		);
		
		// English United States
		$languages ["en-us"] = array (
				0 => "Hello" 
		);
		
		return $languages [MainConf::getSelectedLanguage ()] [$descriptionId];
	}
}
?>