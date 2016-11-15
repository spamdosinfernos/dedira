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
		try {
			$moduleId = self::loadModuleAndValidateModuleId ( $moduleId );
		} catch ( Exception $error ) {
			Log::recordEntry ( "There is not such module" );
			return false;
		}
		// Even the module has the "isRestricted()" method
		// it MUST implement the IModule interface!
		if (! in_array ( "IModule", class_implements ( "$moduleId\\Module" ) )) {
			Log::recordEntry ( "The module MUST implement the IModule interface!" );
			return false;
		}
		
		// Executes the module!!!!
		$class = new ReflectionClass ( "$moduleId\\Module" );
		$class->newInstance ( null );
		return true;
	}
	
	/**
	 * Return the module id, if no module is
	 * specified than return the main module
	 *
	 * @return string
	 */
	private static function loadModuleAndValidateModuleId($moduleId = null) {
		$auth = new Authenticator ();
		$httpRequest = new HttpRequest ();
		
		// If no module id was informed retrieves one
		if (is_null ( $moduleId )) {
			$moduleId = $httpRequest->getGetRequest ( Configuration::MODULE_VAR_NAME ) [0];
			$moduleId = is_null ( $moduleId ) ? Configuration::MAIN_MODULE_NAME : $moduleId;
		}
		
		// Checks if the module exists if no returns the authentication module
		if (! file_exists ( Configuration::getModuleDiretory () . DIRECTORY_SEPARATOR . $moduleId . DIRECTORY_SEPARATOR . Configuration::getUserModuleStarterFileName () )) {
			throw new Exception ( "There is not such module" );
		}
		
		// Loads the module
		require_once Configuration::getModuleDiretory () . DIRECTORY_SEPARATOR . $moduleId . DIRECTORY_SEPARATOR . Configuration::getUserModuleStarterFileName ();
		
		// If module is restricted we have to be authenticated to use it
		if (self::isRestrictedModule ( $moduleId )) {
			if ($auth->isAuthenticated ()) return $moduleId;
			
			// Otherwise go to authentication module
			$moduleId = Configuration::AUTHENTICATION_MODULE_NAME;
			require_once Configuration::getModuleDiretory () . DIRECTORY_SEPARATOR . $moduleId . DIRECTORY_SEPARATOR . Configuration::getUserModuleStarterFileName ();
		}
		
		// If is a open module, just open it
		return $moduleId;
	}
	
	/**
	 * Is the module restricted?
	 *
	 * @param string $moduleId        	
	 * @return bool
	 */
	private static function isRestrictedModule($moduleId): bool {
		$restricted = true;
		eval ( "\$restricted =  $moduleId\\Module::isRestricted();" );
		return $restricted;
	}
}
?>
