<?php

namespace fiscalize;

require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/../../class/form/Form.php';
require_once __DIR__ . '/../../class/page/IPage.php';
require_once __DIR__ . '/../../class/database/Database.php';
require_once __DIR__ . '/../../class/database/DatabaseQuery.php';
require_once __DIR__ . '/../../class/template/TemplateLoader.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/internationalization/i18n.php';
require_once __DIR__ . '/../../class/database/POPOs/problem/Problem.php';
require_once __DIR__ . '/../../class/security/authentication/Authenticator.php';
class Page implements \IPage {
	
	/**
	 * Gerencia os templates
	 *
	 * @var XTemplate
	 */
	protected $template;
	
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
		\I18n::init ( Conf::$defaultLanguage, __DIR__ . "/" . Conf::$localeDirName );
		$auth = new \Authenticator ();
		$this->user = $auth->getAutenticatedEntity ();
		
		$this->template = new \TemplateLoader ( Conf::getTemplate () );
		
		$this->handleRequest ();
		
		$this->template->assign ( "pageId", "fiscalize" );
		$this->template->assign ( "sendText", __ ( "Send report" ) );
		$this->template->assign ( "pageParam", Conf::$pageParameterName );
		$this->template->parse ( "main" );
		$this->template->out ( "main" );
	}
	private function showMessage(string $message) {
		$this->template->assign ( "messageLabel", $message );
		$this->template->parse ( "main.messages" );
	}
	private function createForm() {
		$this->template->assign ( "coordLabel", __ ( "GPS coordinates" ) );
		$this->template->assign ( "addressLabel", __ ( "Type the address" ) );
		$this->template->assign ( "numberLabel", __ ( "Address number" ) );
		$this->template->assign ( "complementLabel", __ ( "Address complement" ) );
		$this->template->assign ( "reportImageLabel", __ ( "Take a picture of the wrong thing" ) );
		$this->template->assign ( "problemLabel", __ ( "Problem description please be polite" ) );
		$this->template->assign ( "solvingLabel", __ ( "Describe how to solve the problem" ) );
	}
	
	/**
	 * Handles the request if any
	 */
	private function handleRequest() {
		
		// Creating the object generator
		$form = new \Form ();
		$form->setType ( \Form::TYPE_POST );
		$form->setTargetObject ( new \Problem () );
		$form->setPathForFileUpload ( Conf::$uploadPath );
		$form->setUploadedFilePrefix ( $this->user->get_id () );
		
		// Registering filds for validation
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
				$this->showMessage ( __ ( "Incorrect data! Send it again." ) );
				return;
			case \Form::NO_REQUEST_DETECTED :
				$this->createForm ();
				return;
		}
		
		// Its ok, lets try to record the problem on database
		$query = new \DatabaseQuery ();
		$query->setObject ( $form->getObject () );
		$query->setOperationType ( \DatabaseQuery::OPERATION_PUT );
		
		if (! \Database::execute ( $query )) {
			$this->showMessage ( __ ( "Fail to record the problem! Try again later." ) );
			return;
		}
		
		// Yay!! Everithing worked!
		$this->showMessage ( __ ( "Problem succefully reported! Thank you!" ) );
		$this->createForm ();
	}
	public static function isRestricted(): bool {
		return true;
	}
}
?>
