<?php

namespace userSignUp;

require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/../../class/log/Log.php';
require_once __DIR__ . '/../../class/page/APage.php';
require_once __DIR__ . '/../../class/template/TemplateLoader.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/protocols/mail/MailSender.php';
require_once __DIR__ . '/../../class/security/PasswordPreparer.php';
require_once __DIR__ . '/../../class/protocols/http/HttpRequest.php';
require_once __DIR__ . '/../../class/security/authentication/drivers/UserAuthenticatorDriver.php';
require_once __DIR__ . '/../../class/security/authentication/Authenticator.php';
/**
 * Register the user on system
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
	
	/**
	 * Stores the user
	 *
	 * @var \User
	 */
	protected $user;
	
	/**
	 * Stores the http requests
	 *
	 * @var HttpRequest
	 */
	protected $httpRequest;
	public function __construct() {
		$this->xTemplate = new \TemplateLoader ( Conf::getTemplate () );
		$this->httpRequest = new \HttpRequest ();
		$this->handleRequest ();
	}
	
	/**
	 * Handles requests
	 *
	 * @return void
	 */
	public function handleRequest() {
		
		// get the page user wants
		$httpRequest = new \HttpRequest ();
		$gotVars = $httpRequest->getGetRequest ();
		$nextPage = isset ( $gotVars ["page"] ) ? $gotVars ["page"] : \Configuration::$mainPageName;
		
		// Default message
		$this->xTemplate->assign ( "message", __ ( "All fields marked with * are mandatory" ) );
		
		// If it is just a user confimation request, activate the user and stops
		// otherwise warns that theres is not such user and stops
		if ($this->isUserConfirmationRequest ()) {
			if ($this->activateUser ( $this->httpRequest->getGetRequest ( "_id" ) [0] )) {
				$this->xTemplate->assign ( "message", sprintf ( __ ( "User %s activated!" ), $this->user->getLogin () ) );
				$this->showMessageGui ();
				return;
			}
			
			$this->xTemplate->assign ( "message", __ ( "Theres no such user on database!" ) );
			$this->showMessageGui ();
			return;
		}
		
		// If nothing was posted so we just show the form and stops
		if (! $this->isUserDataPosted ()) {
			$this->showEditionGui ( $nextPage );
			return;
		}
		
		// Gets the autheticated user if any
		$authenticator = new \Authenticator ();
		$this->user = $authenticator->isAuthenticated () ? $authenticator->getAutenticatedEntity () : null;
		
		// Here we create or updates the user data
		// If it does not exists create a new one
		if (is_null ( $this->user )) {
			if ($this->saveNewUser ()) {
				
				// If we cant send a check email we delete the user
				$email = $this->sendMail ();
				if (empty ( $email )) {
					$this->deleteUser ();
					$this->xTemplate->assign ( "message", __ ( "None of your mail account is valid! Try another mail address!" ) );
					$this->showEditionGui ( $nextPage );
					return;
				}
				
				$this->xTemplate->assign ( "message", __ ( "User created! a mail was sended to your mail box in order to confirm your account: " ) . $email );
				$this->showMessageGui ();
				return;
			} else {
				$this->xTemplate->assign ( "message", __ ( "Fail to create a new user! Remeber: All fields with * are mandatory!" ) );
			}
		} else {
			// Otherwise just updates
			if ($this->updateUser ( $this->user )) {
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
	 * Sends an confirmation mail to user
	 *
	 * @return string - email which receives the confirmation
	 */
	private function sendMail(): string {
		$mailTemplate = new \TemplateLoader ( Conf::getMailTemplate () );
		$mailTemplate->assign ( "hostAddress", Conf::$hostAddress );
		$mailTemplate->assign ( "userId", $this->user->get_id () );
		$mailTemplate->assign ( "message", __ ( "Click here to confirm your account" ) );
		$mailTemplate->parse ( "main" );
		
		\MailSender::setSubject ( __ ( "Confirmation mail" ) );
		\MailSender::setFrom ( Conf::$mailFrom );
		\MailSender::setPort ( Conf::$mailPort );
		\MailSender::setCharset ( Conf::$charset );
		\MailSender::setHost ( Conf::$mailServer );
		\MailSender::setCrypto ( Conf::$mailCryptography );
		\MailSender::setProtocol ( Conf::$mailProtocol );
		\MailSender::setUserName ( Conf::$mailUsername );
		\MailSender::setUserPassword ( Conf::$mailPassword );
		\MailSender::setMessage ( $mailTemplate->text ( "main" ) );
		
		// Tries to send the confirmation to all mails, stops when succeed
		foreach ( $this->user->getArrEmail () as $userMail ) {
			\MailSender::setTo ( $userMail );
			if (\MailSender::sendMail ()) {
				return $userMail;
			}
			
			// If somethig goes wrong log it
			\Log::recordEntry ( \MailSender::getError () );
		}
		
		// All fails!!
		return "";
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
		$this->user = $this->createUserObject ();
		
		// Inserting object
		$query = new \DatabaseQuery ();
		$query->setObject ( $this->user );
		$query->setOperationType ( \DatabaseQuery::OPERATION_PUT );
		
		return \Database::execute ( $query );
	}
	
	/**
	 * Deletes a user
	 *
	 * @return bool
	 */
	private function deleteUser(): bool {
		$cond = new \DatabaseConditions ();
		$cond->addCondition ( \DatabaseConditions::AND, "_id", $this->user->get_id () );
		
		$query = new \DatabaseQuery ();
		$query->setObject ( $this->user );
		$query->setOperationType ( \DatabaseQuery::OPERATION_ERASE );
		$query->setConditions ( $cond );
		return \Database::execute ( $query );
	}
	
	/**
	 * Activates a user
	 *
	 * @return bool
	 */
	private function activateUser(string $userId): bool {
		$cond = new \DatabaseConditions ();
		$cond->addCondition ( \DatabaseConditions::AND, "_id", $userId );
		
		// Retrieves the user
		$query = new \DatabaseQuery ();
		$query->setObject ( new \User () );
		$query->setOperationType ( \DatabaseQuery::OPERATION_GET );
		$query->setConditions ( $cond );
		
		if (! \Database::execute ( $query )) {
			return false;
		}
		
		$res = \Database::getResults ();
		if (! $res->first ()) {
			return false;
		}
		
		$this->user = $res->getRetrivedObject ();
		
		// Updates the user
		$this->user->setActive ( true );
		$query = new \DatabaseQuery ();
		$query->setObject ( $this->user );
		$query->setOperationType ( \DatabaseQuery::OPERATION_UPDATE );
		$query->setConditions ( $cond );
		
		if (! \Database::execute ( $query )) {
			return false;
		}
		
		return true;
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
	private function isUserDataPosted(): bool {
		$postedVars = $this->httpRequest->getPostRequest ();
		
		// Check mandatory data
		if (isset ( $postedVars ["login"] ) && isset ( $postedVars ["password"] ) && isset ( $postedVars ["name"] ) && isset ( $postedVars ["lastName"] ) && isset ( $postedVars ["birthDate"] ) && isset ( $postedVars ["arrEmail"] )) {
			return true;
		}
		
		return false;
	}
	private function isUserConfirmationRequest(): bool {
		$gotVars = $this->httpRequest->getGetRequest ();
		
		// Check mandatory data
		if (isset ( $gotVars ["_id"] )) {
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