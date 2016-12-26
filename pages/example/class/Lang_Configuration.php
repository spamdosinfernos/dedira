<?php
namespace rulesEditor;
require_once __DIR__ . '/Conf.php';
final class Lang_Configuration {
	public static function getDescriptions( $descriptionId ){
		$languages = array ();
		
		// Português Brasil
		$languages ["pt_BR"] = array (
				0 => "Mensagem", 
				1 => "Mensagem2",
		);
		
		// English United States
		$languages ["en_US"] = array (
				0 => "Message",
				1 => "Message2"
		);
		
		return $languages [Conf::getSelectedLanguage ()] [$descriptionId];
	}
}
?>
