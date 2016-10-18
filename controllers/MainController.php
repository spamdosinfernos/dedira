<?php
require_once __DIR__ . '/../class/general/log/Log.php';
require_once __DIR__ . '/language/Lang_MainController.php';
require_once __DIR__ . '/../class/general/module/Module.php';
require_once __DIR__ . '/../class/general/security/Shield.php';
require_once __DIR__ . '/../class/general/database/Database.php';
require_once __DIR__ . '/../class/general/configuration/Configuration.php';

/**
 * Manages all requests and loads the correponding module
 *
 * @author André Furlan
 */
class MainController {
	public function __construct() {
		Shield::treatTextFromForm ();
		Database::init ( Configuration::getDatabaseDriver () );
		
		if (! Database::connect ()) {
			Log::recordEntry ( Lang_MainController::getDescriptions ( 0 ), true );
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