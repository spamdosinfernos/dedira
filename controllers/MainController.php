<?php
require_once __DIR__ . '/language/Lang_MainController.php';
require_once __DIR__ . '/../class/general/security/Shield.php';
require_once __DIR__ . '/../class/general/database/Database.php';
require_once __DIR__ . '/../class/general/configuration/Configuration.php';

/**
 * Esta classe gerencia todas as requisições recebidas pelo site
 * Requisições enviadas a outros arquivos devem ser ignoradas
 *
 * @author André Furlan
 */
class MainController {
	public function __construct() {
		Shield::treatTextFromForm ();
		
		Database::init ( Configuration::getDatabaseDriver () );
		
		if (! Database::connect ()) {
			// TODO create a log entry but keep the echo
			echo "fail to connect";
			return;
		}
		
		// Loads the authenticar module
		// If not authenticated the stops here
		require_once __DIR__ . '/../modules/userAuthenticaticator/Module.php';
		
		// Recupera identificação do módulo atual
		$moduleId = $this->getModuleId ();
		
		// Só pára aqui se na url não houver o nome do módulo
		if (is_null ( $moduleId )) die ( Lang_MainController::getDescriptions ( 1 ) );
		
		// Só pára aqui se o módulo especificado é inválido senão carrega o mesmo
		if (! $this->loadModule ( $moduleId )) die ( Lang_MainController::getDescriptions ( 2 ) );
		
		Database::disconnect ();
	}
	
	/**
	 * Carrega e executa o módulo expecificado
	 *
	 * @param string $moduleId        	
	 * @return boolean
	 */
	private function loadModule(string $moduleId): bool {
		
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
	private function getModuleId() {
		$httpRequest = new HttpRequest ();
		$moduleId = $httpRequest->getGetRequest ( Configuration::CONST_QUERY_STRING_MODULE_NAME_VAR_NAME ) [0];
		return is_null ( $moduleId ) ? Configuration::CONST_MAIN_MODULE_NAME : $moduleId;
	}
}
new MainController ();
?>