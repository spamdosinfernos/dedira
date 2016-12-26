<?php

namespace userAuthenticaticator;

require_once __DIR__ . '/../../class/log/Log.php';
require_once __DIR__ . '/class/Lang_Configuration.php';
require_once __DIR__ . '/../../class/page/Page.php';
require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/../../class/template/TemplateLoader.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/security/PasswordPreparer.php';
require_once __DIR__ . '/../../class/protocols/http/HttpRequest.php';
require_once __DIR__ . '/../../class/module/IPage.php';
require_once __DIR__ . '/../../class/security/authentication/drivers/UserAuthenticatorDriver.php';
require_once __DIR__ . '/../../class/security/authentication/Authenticator.php';
/**
 * Authenticates the user on system and loads the main module
 *
 * @author André Furlan
 */
class Page implements \IPage {
	
	/**
	 * Gerencia os templates
	 *
	 * @var XTemplate
	 */
	protected $xTemplate;
	public function __construct() {
		$this->xTemplate = new \TemplateLoader ( Conf::getAutenticationRequestTemplate () );
		
		$this->handleRequest ();
	}
	
	/**
	 * Handles authentication request If the authenticantion is successful keep executing the system
	 * otherwise show the authentication screen
	 *
	 * @return void
	 */
	public function handleRequest() {
		
		// Already athenticated: continues
		$authenticator = new \Authenticator ();
		if ($authenticator->isAuthenticated ())
			return;
			
			// get login and password if any
		$httpRequest = new \HttpRequest ();
		$postedVars = $httpRequest->getPostRequest ();
		
		// get the module user wants
		$gotVars = $httpRequest->getGetRequest ();
		$nextModule = isset ( $gotVars ["module"] ) ? $gotVars ["module"] : \Configuration::MAIN_PAGE_NAME;
		
		// Verifies the nullables
		if (! isset ( $postedVars ["login"] ) || ! isset ( $postedVars ["password"] )) {
			$this->showGui ( $nextModule );
			exit ( 0 );
		}
		
		// Creates the user to authenticate
		$user = new \User ();
		$user->setLogin ( $postedVars ["login"] );
		$user->setPassword ( \PasswordPreparer::messItUp ( $postedVars ["password"] ) );
		
		// Authenticate
		$authenticator->setAuthenticationRules ( new \UserAuthenticatorDriver ( $user ) );
		if ($authenticator->authenticate ()) {
			$ret = \Module::loadPage ( \Configuration::MAIN_PAGE_NAME );
			
			// Crashes if, for some reason, we cant load the main module
			if (! $ret) {
				\Log::recordEntry ( Lang_Configuration::getDescriptions ( 2 ), true );
				exit ( 0 );
			}
			
			return;
		}
		
		$this->showGui ( $nextModule, true );
		exit ( 0 );
	}
	private function showGui(string $nextModule, bool $failToAuthenticate = false) {
		$this->xTemplate->assign ( "systemMessage", $this->getTitle ( $failToAuthenticate ) );
		$this->xTemplate->assign ( "nextModule", $nextModule );
		
		// Mostra o bloco principal
		$this->xTemplate->parse ( "main" );
		$this->xTemplate->out ( "main" );
	}
	public function getTitle(bool $failToAuthenticate) {
		return $failToAuthenticate ? Lang_Configuration::getDescriptions ( 2 ) : Lang_Configuration::getDescriptions ( 0 );
	}
	public static function isRestricted(): bool {
		return false;
	}
}
?>