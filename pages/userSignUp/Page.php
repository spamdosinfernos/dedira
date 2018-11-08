<?php

namespace userSignUp;

require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/../../class/log/Log.php';
require_once __DIR__ . '/../../class/page/APage.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/protocols/mail/MailSender.php';
require_once __DIR__ . '/../../class/security/PasswordPreparer.php';
require_once __DIR__ . '/../../class/protocols/http/HttpRequest.php';
require_once __DIR__ . '/../../class/page/notification/SystemNotification.php';
require_once __DIR__ . '/../../class/security/authentication/drivers/UserAuthenticatorDriver.php';
require_once __DIR__ . '/../../class/security/authentication/Authenticator.php';

/**
 * Register the user on system
 * @author André Furlan
 */
class Page extends \APage {

	/**
	 * Controls when generate the HTML for mail or when not
	 * @var bool
	 */
	private $sendMail;

	/**
	 * Stores the user
	 * @var \User
	 */
	protected $user;

	/**
	 * Stores the http requests
	 * @var \HttpRequest
	 */
	protected $httpRequest;

	/**
	 * Stores the nofication
	 * @var \SystemNotification
	 */
	protected $notification;

	/**
	 * {@inheritdoc}
	 *
	 * @see \APage::setup()
	 */
	public function setup(): bool {
		$this->sendMail = false;
		$this->httpRequest = new \HttpRequest ();
		$this->notification = new \SystemNotification ();
		return is_null ( $this->httpRequest ) || is_null ( $this->notification ) ? false : true;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \APage::handleRequest()
	 */
	public function handleRequest(): \SystemNotification {

		// If it is just a user confimation request, activate the user and stops
		// otherwise warns that theres is not such user and stops
		if ($this->isUserConfirmationRequest ()) {
			if ($this->activateUser ( $this->httpRequest->getGetRequest ( "_id" ) [0] )) {
				$this->notification->setType ( \SystemNotification::SUCCESS );
				return $this->notification->setMessage ( sprintf ( _ ( "User %s activated!" ), $this->user->getLogin () ) );
			}

			// FAIL!!
			$this->notification->setType ( \SystemNotification::FAIL );
			return $this->notification->setMessage ( _ ( "Theres no such user on database!" ) );
		}

		// If nothing was posted so we just show the form and stops
		if (! $this->isUserDataPosted ()) {return $this->notification;}

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

					$this->notification->setType ( \SystemNotification::FAIL );
					return $this->notification->setMessage ( _ ( "None of your mail account is valid! Try another mail address!" ) );
				}

				$this->notification->setType ( \SystemNotification::SUCCESS );
				return $this->notification->setMessage ( _ ( "User created! a mail was sended to your mail box in order to confirm your account: " ) . $email );
			} else {

				$this->notification->setType ( \SystemNotification::FAIL );
				return $this->notification->setMessage ( _ ( "Fail to create a new user! Remeber: All fields with * are mandatory!" ) );
			}
		} else {
			// Otherwise just updates
			if ($this->updateUser ( $this->user )) {
				$this->notification->setType ( \SystemNotification::SUCCESS );
				return $this->notification->setMessage ( _ ( "User updated!" ) );
			} else {
				$this->notification->setType ( \SystemNotification::SUCCESS );
				return $this->notification->setMessage ( _ ( "Fail to update user! Remeber: All fields with * are mandatory!" ) );
			}
		}
	}

	/**
	 * Sends an confirmation mail to user
	 * @return string - email which receives the confirmation
	 */
	private function sendMail(): string {
		$this->sendMail = true;

		\MailSender::setSubject ( _ ( "Confirmation mail" ) );
		\MailSender::setFrom ( Conf::$mailFrom );
		\MailSender::setPort ( Conf::$mailPort );
		\MailSender::setCharset ( Conf::$charset );
		\MailSender::setHost ( Conf::$mailServer );
		\MailSender::setCrypto ( Conf::$mailCryptography );
		\MailSender::setProtocol ( Conf::$mailProtocol );
		\MailSender::setUserName ( Conf::$mailUsername );
		\MailSender::setUserPassword ( Conf::$mailPassword );
		\MailSender::setMessage ( $this->generateOutput ( new \SystemNotification () ) );

		// Tries to send the confirmation to all mails, stops when succeed
		foreach ( $this->user->getArrEmail () as $userMail ) {
			\MailSender::setTo ( $userMail );
			if (\MailSender::sendMail ()) {
				$this->sendMail = false;
				return $userMail;
			}

			// If somethig goes wrong log it
			\Log::recordEntry ( \MailSender::getError () );
		}

		// All fails!!
		$this->sendMail = false;
		return "";
	}

	/**
	 * Updates a user
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
	 * @return bool
	 */
	private function saveNewUser(): string {
		$this->user = $this->createUserObject ();

		// Inserting object
		$query = new \DatabaseQuery ();
		$query->setObject ( $this->user );
		$query->setOperationType ( \DatabaseQuery::OPERATION_PUT );

		if (\Database::execute ( $query )) {
			$result = \Database::getResults ();
			$result->first ();

			$this->user->set_id ( (string)$result->getRetrivedObject () );
			return true;
		}

		return false;
	}

	/**
	 * Deletes a user
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

		if (! \Database::execute ( $query )) {return false;}

		$res = \Database::getResults ();
		if (! $res->first ()) {return false;}

		$this->user = $res->getRetrivedObject ();

		// Updates the user
		$this->user->setActive ( true );
		$query->setObject ( $this->user );
		$query->setOperationType ( \DatabaseQuery::OPERATION_UPDATE );
		$query->setConditions ( $cond );

		if (! \Database::execute ( $query )) {return false;}

		return true;
	}

	/**
	 * Creates a user object using previous data or not
	 * @param \User $user
	 * @return \User
	 */
	private function createUserObject(\User $user = null): \User {
		$postedVars = $this->httpRequest->getPostRequest ();

		// If no user is informed creates a new one
		$user = is_null ( $user ) ? new \User () : $user;

		// The user only will be valid after a validation
		$user->setActive ( false );

		// Creates the user to authenticate
		// $user->set_id ( dechex ( microtime ( true ) ) );
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
		if (isset ( $postedVars ["login"] ) && isset ( $postedVars ["password"] ) && isset ( $postedVars ["name"] ) && isset ( $postedVars ["lastName"] ) && isset ( $postedVars ["birthDate"] ) && isset ( $postedVars ["arrEmail"] )) {return true;}

		return false;
	}

	private function isUserConfirmationRequest(): bool {
		$gotVars = $this->httpRequest->getGetRequest ();

		// Check mandatory data
		if (isset ( $gotVars ["_id"] )) {return true;}

		return false;
	}

	public static function isRestricted(): bool {
		return false;
	}

	protected function generateTemplateData(\SystemNotification $systemNotification): array {
		$data = array ();

		if ($this->sendMail) {
			$data ["userId"] = $this->user->get_id ();
			$data ["hostAddress"] = Conf::$hostAddress;
			$data ["message"] = _ ( "Click here to confirm your account" );
			return $data;
		}

		$data ["tittle"] = _ ( "User sign up" );
		$data ["lblActive"] = _ ( "Active user" );
		$data ["lblLogin"] = _ ( "Login" );
		$data ["lblPassword"] = _ ( "Password" );
		$data ["lblName"] = _ ( "Name" );
		$data ["lblLastName"] = _ ( "Last name" );

		$data ["lblBirthday"] = _ ( "Birth day" );
		$data ["lblBirthmonth"] = _ ( "Birth month" );
		$data ["lblBirthyear"] = _ ( "Birth year" );
		$data ["lblBirthDate"] = _ ( "Birthdate" );

		$data ["lblEmail"] = _ ( "Email (going to be used for validation)" );
		$data ["lblTelephone"] = _ ( "Telephone" );

		$data ["sendText"] = _ ( "Send" );

		$data ["lblSex"] = _ ( "Sex" );

		$data ["sexValues"] [] = array (
				"value" => \User::SEX_IRRELEVANT,
				"text" => _ ( "Irrelevant" )
		);

		$data ["sexValues"] [] = array (
				"value" => \User::SEX_BOTH,
				"text" => _ ( "Both" )
		);

		$data ["sexValues"] [] = array (
				"value" => \User::SEX_MALE,
				"text" => _ ( "Male" )
		);

		$data ["sexValues"] [] = array (
				"value" => \User::SEX_FEMALE,
				"text" => _ ( "Female" )
		);

		return $data;
	}

	protected function returnCurrentDir(): string {
		return __DIR__;
	}

	protected function returnTemplateFile(\SystemNotification $data): string {
		return $this->sendMail ? Conf::getMailTemplateFile () : Conf::getTemplateFile ();
	}

	protected function returnTemplateFolder(): string {
		return Conf::getTemplateFolder ();
	}
}
?>