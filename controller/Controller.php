<?php
require_once __DIR__ . '/../class/log/Log.php';
require_once __DIR__ . '/language/Lang_Controller.php';
require_once __DIR__ . '/../class/module/Module.php';
require_once __DIR__ . '/../class/security/Shield.php';
require_once __DIR__ . '/../class/database/drivers/mongodb/GridFs.php';
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
		
		if(!Module::loadModule ()){
			Log::recordEntry(Lang_Controller::getDescriptions ( 1 ), true );
		}
		
		Database::disconnect ();
	}
}
new Controller ();
?>