<?php
require_once __DIR__ . '/../log/Log.php';
require_once __DIR__ . '/../protocols/http/HttpRequest.php';
require_once __DIR__ . '/../configuration/Configuration.php';
require_once __DIR__ . '/../security/authentication/Authenticator.php';

/**
 * Handles the pages
 * @author ensismoebius
 */
class PageLoader {

	private static $httpRequest;

	private static $nextSeed;

	/**
	 * Executes the specified page
	 * @param string $pageId
	 * @return boolean
	 */
	public static function loadPage($pageId = null): bool {
		self::$httpRequest = new HttpRequest ();

		try {
			$pageId = self::loadPageAndValidatePageId ( $pageId );
		}
		catch ( Exception $error ) {
			Log::recordEntry ( "There is not such page" );
			return false;
		}
		// it MUST implement the APage abstraction!
		if (! is_subclass_of ( "$pageId\\Page", "APage" )) {
			Log::recordEntry ( "The page MUST implement the APage abstraction!" );
			return false;
		}

		// Executes the page!!!!
		$class = new ReflectionClass ( "$pageId\\Page" );
		$class->newInstance ( null );
		return true;
	}

	/**
	 * Return the page id, if no page is
	 * specified than return the main page
	 * @return string
	 */
	private static function loadPageAndValidatePageId($pageId = null) {
		$auth = new Authenticator ();

		// If no page id was informed retrieves one
		if (is_null ( $pageId )) {
			$pageId = self::$httpRequest->getGetRequest ( Configuration::$pageParameterName ) [0];
			$pageId = is_null ( $pageId ) ? Configuration::$mainPageName : $pageId;
		}

		// Checks if the page exists throws an exception
		if (! file_exists ( Configuration::$pagesDirectory . DIRECTORY_SEPARATOR . $pageId . DIRECTORY_SEPARATOR . Configuration::$defaultPageFileName )) {throw new Exception ( "There is not such page" );}

		// Loads the page
		require_once Configuration::$pagesDirectory . DIRECTORY_SEPARATOR . $pageId . DIRECTORY_SEPARATOR . Configuration::$defaultPageFileName;

		// If page is restricted we have to be authenticated to use it
		if (self::isRestrictedPage ( $pageId )) {

			if ($auth->isAuthenticated () && self::providedSeedIsValid ()) {
				// Generates and stores the next seed for further verification
				$_SESSION ["seed"] = $_SESSION ["nextseed"] = self::getNextSeed ();

				return $pageId;
			}

			// Generates and stores the next seed for further verification
			$_SESSION ["seed"] = $_SESSION ["nextseed"] = self::getNextSeed ();

			// Otherwise go to authentication page
			$pageId = Configuration::$authenticationPageName;
			require_once Configuration::$pagesDirectory . DIRECTORY_SEPARATOR . $pageId . DIRECTORY_SEPARATOR . Configuration::$defaultPageFileName;
		}

		// If is a open page, just open it
		return $pageId;
	}

	/**
	 * Generates a random seed with avoid man(or woman)-in-the-middle
	 * attacks
	 * @return number
	 */
	private static function getNextSeed() {
		return rand ();
	}

	/**
	 * The "seed" are used to ensure that no men(or woman)-in-the-middle
	 * attack happens, this is made by generating a new seed every time
	 * a request is made
	 * @return bool
	 */
	private static function providedSeedIsValid(): bool {
		return isset ( $_SESSION ["seed"] ) && $_SESSION ["nextseed"] == $_SESSION ["seed"];
	}

	/**
	 * Is the page restricted?
	 * @param string $pageId
	 * @return bool
	 */
	private static function isRestrictedPage($pageId): bool {
		$restricted = true;
		eval ( "\$restricted =  $pageId\\Page::isRestricted();" );
		return $restricted;
	}
}
?>
