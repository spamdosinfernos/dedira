<?php

namespace fiscalize;

use SystemNotification;

require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/../../class/form/Form.php';
require_once __DIR__ . '/../../class/page/APage.php';
require_once __DIR__ . '/../../class/database/Database.php';
require_once __DIR__ . '/../../class/database/DatabaseQuery.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/database/POPOs/problem/Problem.php';
require_once __DIR__ . '/../../class/page/notification/SystemNotification.php';
require_once __DIR__ . '/../../class/security/keyGenerators/SessionSeed.php';
require_once __DIR__ . '/../../class/security/authentication/Authenticator.php';
class Page extends \APage {

	/**
	 * The logged user
	 *
	 * @var \User
	 */
	protected $user;

	public function returnTemplateFile(SystemNotification $data): string {
		return Conf::getTemplateFile ();
	}

	public function generateTemplateData(SystemNotification $data): array {
		$arrData = array ();

		$arrData ["sendText"] = _ ( "Send report" );
		$arrData ["coordLabel"] = _ ( "GPS coordinates" );
		$arrData ["numberLabel"] = _ ( "Address number" );
		$arrData ["solvingLabel"] = _ ( "Describe how to solve the problem" );
		$arrData ["problemLabel"] = _ ( "Problem description please be polite" );
		$arrData ["addressLabel"] = _ ( "Type the address" );
		$arrData ["complementLabel"] = _ ( "Address complement" );
		$arrData ["reportImageLabel"] = _ ( "Take a picture of the wrong thing" );
		$arrData ["seed"] = \SessionSeed::getSeed ();

		return $arrData;
	}

	public function returnCurrentDir(): string {
		return __DIR__;
	}

	public function setup(): bool {
		try {
			$auth = new \Authenticator ();
			$this->user = $auth->getAutenticatedEntity ();
			return true;
		} catch ( \Exception $e ) {
			\Log::recordEntry ( $e->getMessage () );
			return false;
		}
	}

	public function handleRequest(): SystemNotification {
		// Creating the object generator
		$form = new \Form ();
		$form->setType ( \Form::TYPE_POST );
		$form->setTargetObject ( new \Problem () );
		$form->setPathForFileUpload ( Conf::$uploadPath );
		$form->setUploadedFilePrefix ( $this->user->get_id () );

		// Registering fields for validation
		$form->registerField ( "number", FILTER_SANITIZE_STRING, false );
		$form->registerField ( "address", FILTER_SANITIZE_STRING, false );
		$form->registerField ( "complement", FILTER_SANITIZE_STRING, false );
		$form->registerField ( "coordinates", FILTER_SANITIZE_STRING, false );
		$form->registerField ( "reportImage", FILTER_SANITIZE_ENCODED );
		$form->registerField ( "solvingSuggestion", FILTER_SANITIZE_STRING );
		$form->registerField ( "problemDescription", FILTER_SANITIZE_STRING );

		// Verifying if there is some errors
		switch ($form->generateObject ()) {
			case \Form::BAD_DATA :
				return $this->createSystemNotification ( _ ( "Incorrect data! Send it again. The fields in yellow must be properly filled!" ), $form->getAllInvalidFields () );
			case \Form::NO_REQUEST_DETECTED :
				return $this->createSystemNotification ( _ ( "Provide some information!" ), $form->getAllInvalidFields () );
		}

		// Its ok, lets try to record the problem on database
		$query = new \DatabaseQuery ();
		$query->setObject ( $form->getObject () );
		$query->setOperationType ( \DatabaseQuery::OPERATION_PUT );

		if (! \Database::execute ( $query )) {
			return $this->createSystemNotification ( _ ( "Fail to record the problem! Try again later." ) );
		}

		// Yay!! Everithing worked!
		return $this->createSystemNotification ( _ ( "Problem succefully reported! Thank you!" ) );
	}

	public static function isRestricted(): bool {
		return true;
	}

	public function returnTemplateFolder(): string {
		return Conf::getTemplateFolder ();
	}

	private function createSystemNotification(string $message, array $moreInfo = array()) {
		$notification = new \SystemNotification ();
		$notification->setMessage ( $message );
		$notification->setArrMoreInfomation ( $moreInfo );
		return $notification;
	}
}
?>
