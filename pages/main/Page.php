<?php

namespace main;

require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/../../class/page/IPage.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/template/TemplateLoader.php';
require_once __DIR__ . '/../../class/security/authentication/Authenticator.php';
require_once __DIR__ . '/../../class/internationalization/i18n.php';
class Page implements \IPage {
	
	/**
	 * Gerencia os templates
	 *
	 * @var XTemplate
	 */
	protected $xTemplate;
	public function __construct() {
		\I18n::init ( Conf::getSelectedLanguage (), __DIR__ . "/" . Conf::LOCALE_DIR_NAME );
		
		$auth = new \Authenticator ();
		$user = $auth->getAutenticatedEntity ();
		
		$this->xTemplate = new \TemplateLoader ( Conf::getTemplate () );
		$this->xTemplate->assign ( "wellcomeMessage", __ ( "Hello" ) );
		
		$this->xTemplate->assign ( "userName", $user->getName () . " " . $user->getLastName () );
		
		$this->xTemplate->parse ( "main" );
		$this->xTemplate->out ( "main" );
	}
	public static function isRestricted(): bool {
		return true;
	}
}
?>
