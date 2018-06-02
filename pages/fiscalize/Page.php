<?php

namespace fiscalize;

require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/../../class/form/Form.php';
require_once __DIR__ . '/../../class/page/APage.php';
require_once __DIR__ . '/../../class/database/Database.php';
require_once __DIR__ . '/../../class/database/DatabaseQuery.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/database/POPOs/problem/Problem.php';
require_once __DIR__ . '/../../class/page/notification/SystemNotification.php';
require_once __DIR__ . '/../../class/security/authentication/Authenticator.php';
class Page extends \APage {
	
	/**
	 * The logged user
	 *
	 * @var \User
	 */
	protected $user;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct ( Conf::getTemplate (), __DIR__ );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \APage::setup()
	 */
	protected function setup(): bool {
		$auth = new \Authenticator ();
		$this->user = $auth->getAutenticatedEntity ();
		return true;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \APage::generateOutput()
	 */
	protected function generateOutput($object): string {
		
		// the "template" property comes from APage class
		$this->template->assign ( "cssPath", Conf::$cssPath );
		
		// Creates the form
		$this->template->assign ( "coordLabel", __ ( "GPS coordinates" ) );
		$this->template->assign ( "addressLabel", __ ( "Type the address" ) );
		$this->template->assign ( "numberLabel", __ ( "Address number" ) );
		$this->template->assign ( "complementLabel", __ ( "Address complement" ) );
		$this->template->assign ( "reportImageLabel", __ ( "Take a picture of the wrong thing" ) );
		$this->template->assign ( "problemLabel", __ ( "Problem description please be polite" ) );
		$this->template->assign ( "solvingLabel", __ ( "Describe how to solve the problem" ) );
		$this->template->assign ( "pageId", "fiscalize" );
		$this->template->assign ( "sendText", __ ( "Send report" ) );
		$this->template->assign ( "pageParam", Conf::$pageParameterName );
		$this->template->parse ( "main" );
		return $this->template->text ( "main" );
	}
	public static function isRestricted(): bool {
		return true;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \APage::handleRequest()
	 */
	protected function handleRequest() {
		
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
				return $this->createSystemNotification ( __ ( "Incorrect data! Send it again. The fields in yellow must be properly filled!" ), $form->getAllInvalidFields () );
			case \Form::NO_REQUEST_DETECTED :
				return;
		}
		
		// Its ok, lets try to record the problem on database
		$query = new \DatabaseQuery ();
		$query->setObject ( $form->getObject () );
		$query->setOperationType ( \DatabaseQuery::OPERATION_PUT );
		
		if (! \Database::execute ( $query )) {
			return $this->createSystemNotification ( __ ( "Fail to record the problem! Try again later." ) );
		}
		
		// Yay!! Everithing worked!
		return $this->createSystemNotification ( __ ( "Problem succefully reported! Thank you!" ) );
	}
	private function createSystemNotification(string $message, array $moreInfo = array()) {
		$notification = new \SystemNotification ();
		$notification->setMessage ( $message );
		$notification->setArrMoreInfomation ( $moreInfo );
		return $notification;
	}
}
?>
