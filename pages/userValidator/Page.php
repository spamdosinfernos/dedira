<?php

namespace userValidator;

require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/../../class/log/Log.php';
require_once __DIR__ . '/../../class/page/Page.php';
require_once __DIR__ . '/../../class/page/APage.php';
require_once __DIR__ . '/../../class/template/TemplateLoader.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/internationalization/i18n.php';
require_once __DIR__ . '/../../class/security/PasswordPreparer.php';
require_once __DIR__ . '/../../class/protocols/http/HttpRequest.php';
require_once __DIR__ . '/../../class/security/authentication/drivers/UserAuthenticatorDriver.php';
require_once __DIR__ . '/../../class/security/authentication/Authenticator.php';

/**
 * Validates an existing user
 *
 * @author André Furlan
 */
class Page extends \APage{
	
	/**
	 * Manages os templates
	 *
	 * @var XTemplate
	 */
	protected $xTemplate;
	public function __construct() {
		\I18n::init ( Conf::$defaultLanguage, __DIR__ . "/" . Conf::$localeDirName );
		$this->xTemplate = new \TemplateLoader ( Conf::getTemplate() );
		$this->handleRequest ();
	}
	
	/**
	 * Handles validation request If the validation is successful redirects to login page
	 *
	 * @return void
	 */
	public function handleRequest() {
		
		// get the user hash
		$httpRequest = new \HttpRequest ();
		$gotVars = $httpRequest->getGetRequest ();
		$userId = isset ( $gotVars ["user"] ) ? $gotVars ["user"] : null;
		
		// If there is no user id, just stop and inform the user
		if (is_null ( $userId )) {
			$this->showGui ( false );
		}
		
		// Creates the user to validade
		$user = new \User ();
		$user->setActive ( true );
		
		$c = new \DatabaseConditions ();
		$c->addCondition ( \DatabaseConditions::AND, "_id", $userId );
		
		$query = new \DatabaseQuery ();
		$query->setConditions ( $c );
		$query->setObject ( $user );
		$query->setOperationType ( \DatabaseQuery::OPERATION_UPDATE );
		
		// If something goes wrong informs the user
		if (! \Database::execute ( $query )) {
			$this->showGui ( false );
			\Log::recordEntry ( __("Fail on validating the user id: ") .  $userId );
			return;
		}
		
		$this->showGui ( true );
	}
	private function showGui(bool $validated) {
		$this->xTemplate->assign ( "systemMessage", $this->getTitle ( $validated ) );
		$this->xTemplate->assign ( "nextPage", \Configuration::$mainPageName );
		$this->xTemplate->assign ( "mainPageMessage", __( "Login" ) );
		
		// Mostra o bloco principal
		$this->xTemplate->parse ( "main" );
		$this->xTemplate->out ( "main" );
	}
	public function getTitle(bool $validated) {
		return $validated ? __( "User validated" ) : __( "Theres no such user in database" );
	}
	public static function isRestricted(): bool {
		return false;
	}
}
?>