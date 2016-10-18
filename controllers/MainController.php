<?php
require_once __DIR__ . '/language/Lang_MainController.php';
require_once __DIR__ . '/../class/general/module/Module.php';
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
		
		// Loads the authenticator module
		// If not authenticated the program stops here
		require_once __DIR__ . '/../modules/userAuthenticaticator/Module.php';
		
		Module::loadModule ();
		Database::disconnect ();
	}
}
new MainController ();
?>