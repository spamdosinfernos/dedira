<?php
require_once __DIR__ . '/../class/log/Log.php';
require_once __DIR__ . '/language/Lang_Controller.php';
require_once __DIR__ . '/../class/module/Module.php';
require_once __DIR__ . '/../class/security/Shield.php';
require_once __DIR__ . '/../class/database/Database.php';
require_once __DIR__ . '/../class/configuration/Configuration.php';

/**
 * Manages all requests and loads the correponding module
 *
 * @author André Furlan
 */
class Controller {
	public function __construct() {
		Shield::treatTextFromForm ();
		Database::init ( Configuration::getDatabaseDriver () );
		
		if (! Database::connect ()) {
			Log::recordEntry ( Lang_Controller::getDescriptions ( 0 ), true );
			return;
		}
		
		// Loads the authenticator module
		// If not authenticated the program stops here
		require_once __DIR__ . '/../modules/userAuthenticaticator/Module.php';
		
		Module::loadModule ();
		Database::disconnect ();
	}
}
new Controller ();
?>