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
		
		$this->createMenu ();
		
		$this->xTemplate->parse ( "main" );
		$this->xTemplate->out ( "main" );
	}
	public static function isRestricted(): bool {
		return true;
	}
	public function createMenu() {
		$this->createSuggestionsMenuEntry ();
		$this->createPrioritiesMapMenuEntry();
		$this->createRankingMenuEntry();
		$this->createRateMenuEntry();
		$this->createRulesMenuEntry();
		$this->createSuggestionsMenuEntry();
		$this->createVoteMenuEntry();
	}
	public function createSuggestionsMenuEntry() {
		$this->xTemplate->assign ( "menuText", __ ( "Suggestions" ) );
		$this->xTemplate->assign ( "menuAddress", "index.php?page=suggestions" );
		$this->xTemplate->assign ( "updatesAmount", 0 );
		$this->xTemplate->parse ( "main.menu" );
	}
	public function createRateMenuEntry() {
		$this->xTemplate->assign ( "menuText", __ ( "Rate" ) );
		$this->xTemplate->assign ( "menuAddress", "index.php?page=rate" );
		$this->xTemplate->assign ( "updatesAmount", 0 );
		$this->xTemplate->parse ( "main.menu" );
	}
	public function createVoteMenuEntry() {
		$this->xTemplate->assign ( "menuText", __ ( "Vote" ) );
		$this->xTemplate->assign ( "menuAddress", "index.php?page=vote" );
		$this->xTemplate->assign ( "updatesAmount", 0 );
		$this->xTemplate->parse ( "main.menu" );
	}
	public function createRankingMenuEntry() {
		$this->xTemplate->assign ( "menuText", __ ( "Ranking" ) );
		$this->xTemplate->assign ( "menuAddress", "index.php?page=ranking" );
		$this->xTemplate->assign ( "updatesAmount", 0 );
		$this->xTemplate->parse ( "main.menu" );
	}
	public function createRulesMenuEntry() {
		$this->xTemplate->assign ( "menuText", __ ( "Rules" ) );
		$this->xTemplate->assign ( "menuAddress", "index.php?page=rules" );
		$this->xTemplate->assign ( "updatesAmount", "" );
		$this->xTemplate->parse ( "main.menu" );
	}
	public function createPrioritiesMapMenuEntry() {
		$this->xTemplate->assign ( "menuText", __ ( "Priorities map" ) );
		$this->xTemplate->assign ( "menuAddress", "index.php?page=prioritiesMap" );
		$this->xTemplate->assign ( "updatesAmount", 0 );
		$this->xTemplate->parse ( "main.menu" );
	}
}
?>
