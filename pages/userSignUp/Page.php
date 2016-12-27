<?php

namespace userSignUp;

require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/../../class/page/IPage.php';
require_once __DIR__ . '/../../class/template/TemplateLoader.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/security/PasswordPreparer.php';
require_once __DIR__ . '/../../class/internationalization/i18n.php';
require_once __DIR__ . '/../../class/protocols/http/HttpRequest.php';
require_once __DIR__ . '/../../class/security/authentication/drivers/UserAuthenticatorDriver.php';
require_once __DIR__ . '/../../class/security/authentication/Authenticator.php';
/**
 * Register the user on system
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
		\I18n::init ( Conf::getSelectedLanguage (), __DIR__ . "/" . Conf::LOCALE_DIR_NAME );
		$this->xTemplate = new \TemplateLoader ( Conf::getTemplate () );
		$this->handleRequest ();
	}
	
	/**
	 * Handles authentication request If the authenticantion is successful keep executing the system
	 * otherwise show the authentication screen
	 *
	 * @return void|boolean
	 */
	public function handleRequest() {
		
		// get the page user wants
		$httpRequest = new \HttpRequest ();
		$gotVars = $httpRequest->getGetRequest ();
		$nextPage = isset ( $gotVars ["page"] ) ? $gotVars ["page"] : \Configuration::MAIN_PAGE_NAME;
		
		// Default message
		$this->xTemplate->assign ( "message", __ ( "All fields marked with * are mandatory" ) );
		
		if (! $this->checkMandatoryFields ()) {
			$this->showEditionGui ( $nextPage );
			return;
		}
		
		// Gets the user id
		$authenticator = new \Authenticator ();
		$user = $authenticator->isAuthenticated () ? $authenticator->getAutenticatedEntity () : null;
		
		// If it does not exists create a new one
		if (is_null ( $user )) {
			if ($this->saveNewUser ()) {
				$this->xTemplate->assign ( "message", __ ( "User created a mail was sended to your mail box in order to confirm your account." ) );
				$this->showMessageGui ();
				return;
			} else {
				$this->xTemplate->assign ( "message", __ ( "Fail to create a new user! Remeber: All fields with * are mandatory!" ) );
			}
		} else {
			// Otherwise just updates
			if ($this->updateUser ( $user )) {
				$this->xTemplate->assign ( "message", __ ( "User updated!" ) );
				$this->showMessageGui ();
				return;
			} else {
				$this->xTemplate->assign ( "message", __ ( "Fail to update user! Remeber: All fields with * are mandatory!" ) );
			}
		}
		
		$this->showEditionGui ( $nextPage );
	}
	
	/**
	 * Shows the message GUI
	 */
	private function showMessageGui() {
		$this->xTemplate->parse ( "main.message" );
		$this->xTemplate->parse ( "main" );
		$this->xTemplate->out ( "main" );
	}
	
	/**
	 * Updates a user
	 *
	 * @param \User $user        	
	 * @return bool
	 */
	private function updateUser(\User $user): bool {
		$user = $this->createUserObject ( $user );
		
		// Updating object
		$c = new \DatabaseConditions ();
		$c->addCondition ( \DatabaseConditions::AND, "id", $user->get_id () );
		
		$query = new \DatabaseQuery ();
		$query->setConditions ( $c );
		$query->setObject ( $user );
		$query->setOperationType ( \DatabaseQuery::OPERATION_UPDATE );
		
		return \Database::execute ( $query );
	}
	
	/**
	 * Create a new user
	 *
	 * @return bool
	 */
	private function saveNewUser(): bool {
		$user = $this->createUserObject ();
		
		// Inserting object
		$query = new \DatabaseQuery ();
		$query->setObject ( $user );
		$query->setOperationType ( \DatabaseQuery::OPERATION_PUT );
		
		return \Database::execute ( $query );
	}
	
	/**
	 * Creates a user object using previous data or not
	 *
	 * @param \User $user        	
	 * @return \User
	 */
	private function createUserObject(\User $user = null): \User {
		$httpRequest = new \HttpRequest ();
		$postedVars = $httpRequest->getPostRequest ();
		
		// If no user is informed creates a new one
		$user = is_null ( $user ) ? new \User () : $user;
		
		// The user modifications only will be valid after a validation
		$user->setActive ( false );
		
		// Creates the user to authenticate
		$user->set_id ( dechex ( microtime ( true ) ) );
		$user->setSex ( $postedVars ["sex"] );
		$user->setName ( $postedVars ["name"] );
		$user->setLogin ( $postedVars ["login"] );
		$user->setLastName ( $postedVars ["lastName"] );
		$user->setArrEmail ( $postedVars ["arrEmail"] );
		$user->setBirthDate ( new \DateTime ( $postedVars ["birthDate"] ["year"] . "-" . $postedVars ["birthDate"] ["month"] . "-" . $postedVars ["birthDate"] ["day"] ) );
		
		$user->setArrTelephone ( $postedVars ["arrTelephone"] );
		$user->setPassword ( \PasswordPreparer::messItUp ( $postedVars ["password"] ) );
		
		return $user;
	}
	private function checkMandatoryFields(): bool {
		$httpRequest = new \HttpRequest ();
		$postedVars = $httpRequest->getPostRequest ();
		
		// Check mandatory fields
		if (isset ( $postedVars ["login"] ) && isset ( $postedVars ["password"] ) && isset ( $postedVars ["name"] ) && isset ( $postedVars ["lastName"] ) && isset ( $postedVars ["birthDate"] ) && isset ( $postedVars ["arrEmail"] )) {
			return true;
		}
		
		return false;
	}
	private function showEditionGui(string $nextPage) {
		$this->xTemplate->assign ( "tittle", __ ( "User sign up" ) );
		$this->xTemplate->assign ( "lblActive", __ ( "Active user" ) );
		$this->xTemplate->assign ( "lblLogin", __ ( "Login" ) );
		$this->xTemplate->assign ( "lblPassword", __ ( "Password" ) );
		$this->xTemplate->assign ( "lblName", __ ( "Name" ) );
		$this->xTemplate->assign ( "lblLastName", __ ( "Last name" ) );
		
		$this->xTemplate->assign ( "lblBirthday", __ ( "Birth day" ) );
		$this->xTemplate->assign ( "lblBirthmonth", __ ( "Birth month" ) );
		$this->xTemplate->assign ( "lblBirthyear", __ ( "Birth year" ) );
		$this->xTemplate->assign ( "lblBirthDate", __ ( "Birthdate" ) );
		
		$this->xTemplate->assign ( "lblEmail", __ ( "Email (going to be used for validation)" ) );
		$this->xTemplate->assign ( "lblTelephone", __ ( "Telephone" ) );
		
		$this->xTemplate->assign ( "sendText", __ ( "Send" ) );
		
		$this->xTemplate->assign ( "lblSex", __ ( "Sex" ) );
		
		$this->xTemplate->assign ( "sexText", __ ( "Irrelevant" ) );
		$this->xTemplate->assign ( "sexValue", \User::SEX_IRRELEVANT );
		$this->xTemplate->parse ( "main.dataEditing.comboSex" );
		$this->xTemplate->assign ( "sexText", __ ( "Both" ) );
		$this->xTemplate->assign ( "sexValue", \User::SEX_BOTH );
		$this->xTemplate->parse ( "main.dataEditing.comboSex" );
		$this->xTemplate->assign ( "sexText", __ ( "Female" ) );
		$this->xTemplate->assign ( "sexValue", \User::SEX_FEMALE );
		$this->xTemplate->parse ( "main.dataEditing.comboSex" );
		$this->xTemplate->assign ( "sexText", __ ( "Male" ) );
		$this->xTemplate->assign ( "sexValue", \User::SEX_MALE );
		$this->xTemplate->parse ( "main.dataEditing.comboSex" );
		
		$this->xTemplate->assign ( "nextPage", $nextPage );
		
		$this->xTemplate->parse ( "main.dataEditing" );
		$this->xTemplate->parse ( "main" );
		$this->xTemplate->out ( "main" );
	}
	public static function isRestricted(): bool {
		return false;
	}
}
?>