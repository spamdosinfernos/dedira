<?php

namespace userSignUp;

require_once __DIR__ . '/class/UserSignUpConf.php';
require_once __DIR__ . '/class/Lang_Configuration.php';
require_once __DIR__ . '/../../class/template/TemplateLoader.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/security/PasswordPreparer.php';
require_once __DIR__ . '/../../class/protocols/http/HttpRequest.php';
require_once __DIR__ . '/../../class/security/authentication/drivers/UserAuthenticatorDriver.php';
require_once __DIR__ . '/../../class/security/authentication/Authenticator.php';
/**
 * Register the user on system
 *
 * @author André Furlan
 */
class Module {
	
	/**
	 * Gerencia os templates
	 *
	 * @var XTemplate
	 */
	protected $xTemplate;
	public function __construct() {
		$this->xTemplate = new \TemplateLoader ( UserSignUpConf::getAutenticationRequestTemplate () );
		
		$this->handleRequest ();
	}
	
	/**
	 * Handles authentication request If the authenticantion is successful keep executing the system
	 * otherwise show the authentication screen
	 *
	 * @return void|boolean
	 */
	public function handleRequest() {
		
		// get the module user wants
		$httpRequest = new \HttpRequest ();
		$gotVars = $httpRequest->getGetRequest ();
		$nextModule = isset ( $gotVars ["module"] ) ? $gotVars ["module"] : \Configuration::MAIN_MODULE_NAME;
		
		if (! $this->checkMandatoryFields ()) {
			$this->showGui ( $nextModule );
			return;
		}
		
		// Gets the user id
		$authenticator = new \Authenticator ();
		$user = $authenticator->isAuthenticated () ? $authenticator->getAutenticatedEntity () : null;
		
		// If it does not exists create a new one
		if (is_null ( $user )) {
			if ($this->createNewUser ()) {
				$this->xTemplate->assign ( "message", Lang_Configuration::getDescriptions ( 16 ) );
			} else {
				$this->xTemplate->assign ( "message", Lang_Configuration::getDescriptions ( 17 ) );
			}
		} else {
			// Otherwise just updates
			if ($this->updateUser ( $user )) {
				$this->xTemplate->assign ( "message", Lang_Configuration::getDescriptions ( 16 ) );
			} else {
				$this->xTemplate->assign ( "message", Lang_Configuration::getDescriptions ( 17 ) );
			}
		}
		
		$this->showGui ( $nextModule );
	}
	
	// TODO Finish it!
	private function updateUser(\User $user) {
		$httpRequest = new \HttpRequest ();
		$postedVars = $httpRequest->getPostRequest ();
		
		// Creates the user to authenticate
		$user->setLogin ( $postedVars ["login"] );
		$user->setPassword ( \PasswordPreparer::messItUp ( $postedVars ["password"] ) );
		$user->setActive ( $postedVars ["active"] );
	}
	
	// TODO Finish it!
	private function createNewUser() {
		$httpRequest = new \HttpRequest ();
		$postedVars = $httpRequest->getPostRequest ();
		
		// Creates the user to authenticate
		$user = new \User ();
		$user->setLogin ( $postedVars ["login"] );
		$user->setPassword ( \PasswordPreparer::messItUp ( $postedVars ["password"] ) );
		$user->setActive ( $postedVars ["active"] );
	}
	private function checkMandatoryFields(): bool {
		$httpRequest = new \HttpRequest ();
		$postedVars = $httpRequest->getPostRequest ();
		
		// Verifies the nullables
		if (! isset ( $postedVars ["login"] ) || ! isset ( $postedVars ["password"] ) || ! isset ( $postedVars ["active"] ) || ! isset ( $postedVars ["name"] ) || ! isset ( $postedVars ["lastName"] ) || ! isset ( $postedVars ["sex"] ) || ! isset ( $postedVars ["birthDate"] ) || ! isset ( $postedVars ["arrEmail"] )) {
			return false;
		}
		
		return true;
	}
	private function showGui(string $nextModule) {
		$this->xTemplate->assign ( "tittle", Lang_Configuration::getDescriptions ( 0 ) );
		$this->xTemplate->assign ( "lblActive", Lang_Configuration::getDescriptions ( 1 ) );
		$this->xTemplate->assign ( "lblLogin", Lang_Configuration::getDescriptions ( 2 ) );
		$this->xTemplate->assign ( "lblPassword", Lang_Configuration::getDescriptions ( 3 ) );
		$this->xTemplate->assign ( "lblName", Lang_Configuration::getDescriptions ( 4 ) );
		$this->xTemplate->assign ( "lblLastName", Lang_Configuration::getDescriptions ( 5 ) );
		$this->xTemplate->assign ( "lblBirthdate", Lang_Configuration::getDescriptions ( 7 ) );
		$this->xTemplate->assign ( "lblEmail", Lang_Configuration::getDescriptions ( 8 ) );
		$this->xTemplate->assign ( "lblTelephone", Lang_Configuration::getDescriptions ( 9 ) );
		
		$this->xTemplate->assign ( "sendText", Lang_Configuration::getDescriptions ( 10 ) );
		
		$this->xTemplate->assign ( "lblSex", Lang_Configuration::getDescriptions ( 6 ) );
		
		$this->xTemplate->assign ( "sexText", Lang_Configuration::getDescriptions ( 13 ) );
		$this->xTemplate->assign ( "sexValue", \User::SEX_IRRELEVANT );
		$this->xTemplate->parse ( "main.comboSex" );
		$this->xTemplate->assign ( "sexText", Lang_Configuration::getDescriptions ( 11 ) );
		$this->xTemplate->assign ( "sexValue", \User::SEX_BOTH );
		$this->xTemplate->parse ( "main.comboSex" );
		$this->xTemplate->assign ( "sexText", Lang_Configuration::getDescriptions ( 12 ) );
		$this->xTemplate->assign ( "sexValue", \User::SEX_FEMALE );
		$this->xTemplate->parse ( "main.comboSex" );
		$this->xTemplate->assign ( "sexText", Lang_Configuration::getDescriptions ( 14 ) );
		$this->xTemplate->assign ( "sexValue", \User::SEX_MALE );
		$this->xTemplate->parse ( "main.comboSex" );
		
		$this->xTemplate->assign ( "warning", Lang_Configuration::getDescriptions ( 15 ) );
		
		$this->xTemplate->assign ( "nextModule", $nextModule );
		
		$this->xTemplate->parse ( "main" );
		$this->xTemplate->out ( "main" );
	}
}
new Module ();
?>