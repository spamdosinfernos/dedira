<?php
require_once __DIR__ . '/../protocols/http/HttpRequest.php';
require_once __DIR__ . '/../configuration/Configuration.php';
require_once __DIR__ . '/../security/authentication/Authenticator.php';
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
	public static function loadModule($moduleId = null): bool {
		$moduleId = is_null($moduleId) ? self::getModuleId () : $moduleId;
		
		// Se o arquivo index.php do módulo não existe para aqui
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
		$auth = new Authenticator ();
		$httpRequest = new HttpRequest ();
		
		$moduleId = $httpRequest->getGetRequest ( Configuration::MODULE_VAR_NAME ) [0];
		
		if ($auth->isAuthenticated ()) {
			return is_null ( $moduleId ) ? Configuration::MAIN_MODULE_NAME : $moduleId;
		}
		
		if ($moduleId != Configuration::SIGNUP_MODULE_NAME) {
			return Configuration::AUTHENTICATION_MODULE_NAME;
		}
		
		return $moduleId;
	}
}
?>
