<?php
require_once __DIR__ . '/../log/Log.php';
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
		$moduleId = is_null ( $moduleId ) ? self::getModuleId () : $moduleId;
		
		// Checks if the module exists
		if (! file_exists ( Configuration::getModuleDiretory () . DIRECTORY_SEPARATOR . $moduleId . DIRECTORY_SEPARATOR . Configuration::getUserModuleStarterFileName () )) return false;
		
		// Load module
		require_once Configuration::getModuleDiretory () . DIRECTORY_SEPARATOR . $moduleId . DIRECTORY_SEPARATOR . Configuration::getUserModuleStarterFileName ();
		
		// Even the module has the "isRestricted()" method
		// it MUST implement the IModule interface!
		if (! in_array ( "IModule", class_implements ( "$moduleId\\Module" ) )) {
			Log::recordEntry ( "The module MUST implement the IModule interface!" );
			throw new Exception ( "The module MUST implement the IModule interface!" );
			exit ( 1 );
		}
		
		// Executes the module!!!!
		eval ( "new $moduleId\\Module();" );
		
		return true;
	}
	
	/**
	 * Return the module id, if no module is
	 * specified than return the main module
	 *
	 * @return string
	 */
	private static function getModuleId() {
		$auth = new Authenticator ();
		$httpRequest = new HttpRequest ();
		
		$moduleId = $httpRequest->getGetRequest ( Configuration::MODULE_VAR_NAME ) [0];
		$moduleId = is_null ( $moduleId ) ? Configuration::MAIN_MODULE_NAME : $moduleId;
		
		// TODO Delete SIGNUP_MODULE_NAME ?
		if (self::isARestrictedModule ( $moduleId )) {
			if ($auth->isAuthenticated ()) return $moduleId;
			
			return Configuration::AUTHENTICATION_MODULE_NAME;
		}
		
		return $moduleId;
	}
	
	/**
	 * Is the module restricted?
	 * 
	 * @param string $moduleId        	
	 * @return bool
	 */
	private static function isARestrictedModule($moduleId): bool {
		require_once Configuration::getModuleDiretory () . DIRECTORY_SEPARATOR . $moduleId . DIRECTORY_SEPARATOR . Configuration::getUserModuleStarterFileName ();
		
		$restricted = true;
		eval ( "\$restricted =  $moduleId\\Module::isRestricted();" );
		return $restricted;
	}
}
?>
