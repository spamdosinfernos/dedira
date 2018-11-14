<?php

namespace userAuthenticaticator;

require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/../../class/log/Log.php';
require_once __DIR__ . '/../../class/page/APage.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/security/PasswordPreparer.php';
require_once __DIR__ . '/../../class/protocols/http/HttpRequest.php';
require_once __DIR__ . '/../../class/page/notification/SystemNotification.php';
require_once __DIR__ . '/../../class/security/authentication/Authenticator.php';
require_once __DIR__ . '/../../class/security/authentication/drivers/UserAuthenticatorDriver.php';

/**
 * Authenticates the user on system and loads the main page
 *
 * @author André Furlan
 */
class Page extends \APage {

	const NEXT_PAGE_VAR_NAME = "nextPage";

	const FAIL_AUTHENTICATION_VAR_NAME = "failAuth";

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \APage::setup()
	 */
	protected function setup(): bool {
		return true;
	}

	/**
	 * Handles authentication request If the authenticantion is successful keep executing the system
	 * otherwise show the authentication screen
	 *
	 * @return void
	 */
	protected function handleRequest(): \SystemNotification {

		// Creates a notification that will be returned
		$notification = new \SystemNotification ();

		// Already athenticated: continues
		$authenticator = new \Authenticator ();

		if ($authenticator->isAuthenticated ()) {
			$notification->setType ( \SystemNotification::SUCCESS );
			return $notification;
		}

		// get login and password if any
		$httpRequest = new \HttpRequest ();
		$postedVars = $httpRequest->getPostRequest ();

		// get the page user wants
		$gotVars = $httpRequest->getGetRequest ();
		$nextPage = isset ( $gotVars ["page"] ) ? $gotVars ["page"] : \Configuration::$mainPageName;

		// Creates a notification that will be returned
		$notification->addInformation ( self::NEXT_PAGE_VAR_NAME, $nextPage );

		// Verifies the nullables
		if (! isset ( $postedVars ["login"] ) || ! isset ( $postedVars ["password"] )) {
			$notification->setType ( \SystemNotification::FAIL );
			return $notification;
		}

		// Creates the user to authenticate
		$user = new \User ();
		$user->setLogin ( $postedVars ["login"] );
		$user->setPassword ( \PasswordPreparer::messItUp ( $postedVars ["password"] ) );

		// Authenticate
		$authenticator->setAuthenticationRules ( new \UserAuthenticatorDriver ( $user ) );
		if ($authenticator->authenticate ()) {
			$ret = \PageLoader::loadPage ( $nextPage );

			// Crashes if, for some reason, we cant load the main page
			if (! $ret) {
				\Log::recordEntry ( _ ( "Something very wrong happens: Fail to load the page!" ), true );
				exit ( 0 );
			}
		}

		// Success on authentication!!
		return $notification;
	}

	protected static function isRestricted(): bool {
		return false;
	}

	protected function generateTemplateData($data): array {

		// the "template" property comes from APage class
		return array (
				"signUpMessage" => _ ( "Or signup!" ),
				"login" => _ ( "E-mail or login" ),
				"password" => _ ( "Password" )
		);
	}

	protected function returnTemplateFile(\SystemNotification $data): string {
		return Conf::getTemplateFile ();
	}

	protected function returnTemplateFolder(): string {
		return Conf::getTemplateFolder ();
	}

	protected function returnCurrentDir(): string {
		return __DIR__;
	}
}
?>