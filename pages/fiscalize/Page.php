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
	protected $xTemplate;
	
	/**
	 * The logged user
	 * 
	 * @var \User
	 */
	protected $user;
	public function __construct() {
		\I18n::init ( Conf::$defaultLanguage, __DIR__ . "/" . Conf::$localeDirName );
		$auth = new \Authenticator ();
		$this->user = $auth->getAutenticatedEntity ();
		
		$this->handleRequest ();
		
		$this->xTemplate = new \TemplateLoader ( Conf::getTemplate () );
		
		$this->xTemplate->assign ( "pageParam", Conf::$pageParameterName );
		$this->xTemplate->assign ( "pageId", "fiscalize" );
		
		$this->xTemplate->assign ( "coordLabel", __ ( "gps coordinates" ) );
		$this->xTemplate->assign ( "addressLabel", __ ( "Type the address" ) );
		$this->xTemplate->assign ( "numberLabel", __ ( "Address number" ) );
		$this->xTemplate->assign ( "complementLabel", __ ( "Address complement" ) );
		$this->xTemplate->assign ( "reportImageLabel", __ ( "Take a picture of the wrong thing" ) );
		$this->xTemplate->assign ( "problemLabel", __ ( "Problem description" ) );
		$this->xTemplate->assign ( "solvingLabel", __ ( "Describe how to solve the problem" ) );
		
		$this->xTemplate->assign ( "sendText", __ ( "Send report" ) );
		
		$this->xTemplate->parse ( "main" );
		$this->xTemplate->out ( "main" );
	}
	private function handleRequest(): bool {
		$form = new \Form ();
		
		$form->setType ( \Form::TYPE_POST );
		$form->setTargetObject ( new \Problem () );
		$form->setPathForFileUpload ( Conf::$uploadPath );
		$form->setUploadedFilePrefix ( $this->user->get_id () );
		
		$form->registerField ( "number", FILTER_SANITIZE_STRING );
		$form->registerField ( "address", FILTER_SANITIZE_STRING );
		$form->registerField ( "complement", FILTER_SANITIZE_STRING );
		$form->registerField ( "coordinates", FILTER_SANITIZE_STRING );
		$form->registerField ( "reportImage", FILTER_SANITIZE_ENCODED );
		$form->registerField ( "solvingSuggestion", FILTER_SANITIZE_STRING );
		$form->registerField ( "problemDescription", FILTER_SANITIZE_STRING );
		
		if ($form->generateObject ()) {
			$query = new \DatabaseQuery ();
			$query->setObject ( $form->getObject () );
			$query->setOperationType ( \DatabaseQuery::OPERATION_PUT );
			
			if (\Database::execute ( $query )) {
				return true;
			}
		}
		
		return false;
	}
	public static function isRestricted(): bool {
		return true;
	}
}
?>
