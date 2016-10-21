<?php
namespace userAuthenticaticator;

require_once __DIR__ . '/UserAuthenticaticatorConf.php';
final class Lang_Configuration {
	public static function getDescriptions( $descriptionId ){
		$languages = array ();
		
		// Português Brasil
		$languages ["pt-br"] = array (
				0 => "Por favor, digite login e senha" 
		);
		
		// English United States
		$languages ["en-us"] = array (
				0 => "Please, type your login and passaword" 
		);
		
		return $languages [UserAuthenticaticatorConf::getSelectedLanguage ()] [$descriptionId];
	}
}
?>