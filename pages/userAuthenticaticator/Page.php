<?php

namespace userAuthenticaticator;

require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/../../class/log/Log.php';
require_once __DIR__ . '/../../class/page/Page.php';
require_once __DIR__ . '/../../class/page/APage.php';
require_once __DIR__ . '/../../class/template/TemplateLoader.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/internationalization/i18n.php';
require_once __DIR__ . '/../../class/security/PasswordPreparer.php';
require_once __DIR__ . '/../../class/protocols/http/HttpRequest.php';
require_once __DIR__ . '/../../class/page/notification/Notification.php';
require_once __DIR__ . '/../../class/security/authentication/Authenticator.php';
require_once __DIR__ . '/../../class/security/authentication/drivers/UserAuthenticatorDriver.php';
/**
 * Authenticates the user on system and loads the main page
 *
 * @author André Furlan
 */
class Page extends \APage {
	
	/**
	 * Gerencia os templates
	 *
	 * @var XTemplate
	 */
	protected $xTemplate;
	const NEXT_PAGE_VAR_NAME = "nextPage";
	const FAIL_AUTHENTICATION_VAR_NAME = "failAuth";
	public function __construct() {
		$this->xTemplate = new \TemplateLoader ( Conf::getTemplate () );
		\I18n::init ( Conf::$defaultLanguage, __DIR__ . "/" . Conf::$localeDirName );
		parent::__construct ();
	}
	protected function generateHTML($object): string {
		$arrInfo = $object->getArrMoreInfomation ();
		$nextPage = $arrInfo [self::NEXT_PAGE_VAR_NAME];
		$failToAuthenticate = isset ( $arrInfo [self::FAIL_AUTHENTICATION_VAR_NAME] ) ? $arrInfo [self::FAIL_AUTHENTICATION_VAR_NAME] : false;
		
		$this->xTemplate->assign ( "systemMessage", $this->getTitle ( $failToAuthenticate ) );
		$this->xTemplate->assign ( "signUpMessage", __ ( "Or signup!" ) );
		$this->xTemplate->assign ( "login", __ ( "E-mail or login" ) );
		$this->xTemplate->assign ( "password", __ ( "Password" ) );
		$this->xTemplate->assign ( "nextPage", $nextPage );
		$this->xTemplate->parse ( "main" );
		return $this->xTemplate->text ( "main" );
	}
	
	/**
	 * Handles authentication request If the authenticantion is successful keep executing the system
	 * otherwise show the authentication screen
	 *
	 * @return void
	 */
	protected function handleRequest() {
		
		// Already athenticated: continues
		$authenticator = new \Authenticator ();
		if ($authenticator->isAuthenticated ()) return;
		
		// get login and password if any
		$httpRequest = new \HttpRequest ();
		$postedVars = $httpRequest->getPostRequest ();
		
		// get the page user wants
		$gotVars = $httpRequest->getGetRequest ();
		$nextPage = isset ( $gotVars ["page"] ) ? $gotVars ["page"] : \Configuration::$mainPageName;
		
		// Creates a notification that will be returned
		$notification = new \Notification ();
		$notification->addInformation ( self::NEXT_PAGE_VAR_NAME, $nextPage );
		
		// Verifies the nullables
		if (! isset ( $postedVars ["login"] ) || ! isset ( $postedVars ["password"] )) {
			return $notification;
		}
		
		// Creates the user to authenticate
		$user = new \User ();
		$user->setLogin ( $postedVars ["login"] );
		$user->setPassword ( \PasswordPreparer::messItUp ( $postedVars ["password"] ) );
		
		// Authenticate
		$authenticator->setAuthenticationRules ( new \UserAuthenticatorDriver ( $user ) );
		if ($authenticator->authenticate ()) {
			$ret = \Page::loadPage ( \Configuration::$mainPageName );
			
			// Crashes if, for some reason, we cant load the main page
			if (! $ret) {
				\Log::recordEntry ( __ ( "Something very wrong happens: Fail to load the main page!" ), true );
				exit ( 0 );
			}
		}
		
		// Fail to authenticate!! informing it on notification
		$notification->addInformation ( self::FAIL_AUTHENTICATION_VAR_NAME, true );
		return $notification;
	}
	public function getTitle(bool $failToAuthenticate) {
		return $failToAuthenticate ? __ ( "Login or password incorrect, or your account are inactive" ) : __ ( "Please, type your login and password" );
	}
	public static function isRestricted(): bool {
		return false;
	}
}
?>