<?php
require_once __DIR__ . '/../configuration/Configuration.php';
/**
 * Handles the modules
 * 
 * @author ensismoebius
 *        
 */
class Module {
	/**
	 * Carrega e executa o módulo expecificado
	 *
	 * @param string $moduleId        	
	 * @return boolean
	 */
	public static function loadModule(): bool {
		$moduleId = self::getModuleId ();
		
		// Se o arquivo index.php do módulo não existe pára aqui
		if (! file_exists ( Configuration::getModuleDiretory () . DIRECTORY_SEPARATOR . $moduleId . DIRECTORY_SEPARATOR . Configuration::getUserModuleStarterFileName () )) return false;
		
		// Carrega o módulo
		require_once Configuration::getModuleDiretory () . DIRECTORY_SEPARATOR . $moduleId . DIRECTORY_SEPARATOR . Configuration::getUserModuleStarterFileName ();
		
		// Se chegou até aqui deu tudo certo
		return true;
	}
	
	/**
	 * Return the module id, if no module is
	 * specified than return the main module
	 *
	 * @return string
	 */
	public static function getModuleId() {
		$httpRequest = new HttpRequest ();
		$moduleId = $httpRequest->getGetRequest ( Configuration::QUERY_STRING_MODULE_NAME_VAR_NAME ) [0];
		return is_null ( $moduleId ) ? Configuration::MAIN_MODULE_NAME : $moduleId;
	}
}
?>
