<?php
require_once __DIR__ . '/../class/log/Log.php';
require_once __DIR__ . '/../class/page/PageLoader.php';
require_once __DIR__ . '/../class/security/Shield.php';

require_once __DIR__ . '/../class/database/Database.php';
require_once __DIR__ . '/../class/configuration/Configuration.php';

require_once __DIR__ . '/../class/internationalization/i18n.php';

/**
 * Manages all requests and loads the correponding page
 * @author André Furlan
 */
class Controller {

	public function __construct() {
		Configuration::init ();

		I18n::init ( Configuration::$defaultLanguage, __DIR__ . "/" . Configuration::$localeDirName );

		Shield::treatTextFromForm ();
		Database::init ( Configuration::$databaseDriver );

		if (! Database::connect ( Configuration::$databaseHostProtocol, Configuration::$databaseHostAddress, Configuration::$databasePort )) {
			Log::recordEntry ( __ ( "The system can't connect to database" ), true );
			return;
		}

		if (! PageLoader::loadPage ()) {
			Log::recordEntry ( __ ( "Fail on load the page!" ), true );
		}

		Database::disconnect ();
	}
}
new Controller ();
?>