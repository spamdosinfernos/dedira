<?php

namespace main;

require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/../../class/page/APage.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/security/authentication/Authenticator.php';
class Page extends \APage {
	public function __construct() {
		parent::__construct ( Conf::getTemplateFolder (), __DIR__ );
	}
	public function generateHTML($dataObject): string {
		$this->template->assign ( "wellcomeMessage", __ ( "Hello" ) );
		$this->template->assign ( "userName", $dataObject->getName () . " " . $dataObject->getLastName () );
		
		$this->createMenu ();
		
		return $this->template->render ( Conf::getTemplateFile () );
	}
	public function handleRequest(): object {
		$auth = new \Authenticator ();
		return $auth->getAutenticatedEntity ();
	}
	public static function isRestricted(): bool {
		return true;
	}
	public function createMenu() {
		$this->createPrioritiesMapMenuEntry ();
		$this->createSuggestionsMenuEntry ();
		$this->createRankingMenuEntry ();
		$this->createRateMenuEntry ();
		$this->createRulesMenuEntry ();
		$this->createSuggestionsMenuEntry ();
		$this->createVoteMenuEntry ();
		$this->test ();
	}
	public function createSuggestionsMenuEntry() {
		$this->template->assign ( "menuText", __ ( "Suggestions" ) );
		$this->template->assign ( "menuAddress", "index.php?page=suggestions" );
		$this->template->assign ( "updatesAmount", 0 );
	}
	public function createRateMenuEntry() {
		$this->template->assign ( "menuText", __ ( "Rate" ) );
		$this->template->assign ( "menuAddress", "index.php?page=rate" );
		$this->template->assign ( "updatesAmount", 0 );
	}
	public function createVoteMenuEntry() {
		$this->template->assign ( "menuText", __ ( "Vote" ) );
		$this->template->assign ( "menuAddress", "index.php?page=vote" );
		$this->template->assign ( "updatesAmount", 0 );
	}
	public function createRankingMenuEntry() {
		$this->template->assign ( "menuText", __ ( "Ranking" ) );
		$this->template->assign ( "menuAddress", "index.php?page=ranking" );
		$this->template->assign ( "updatesAmount", 0 );
	}
	public function createRulesMenuEntry() {
		$this->template->assign ( "menuText", __ ( "Rules" ) );
		$this->template->assign ( "menuAddress", "index.php?page=rules" );
		$this->template->assign ( "updatesAmount", 0 );
	}
	public function createPrioritiesMapMenuEntry() {
		$this->template->assign ( "menuText", __ ( "Priorities map" ) );
		$this->template->assign ( "menuAddress", "index.php?page=prioritiesMap" );
		$this->template->assign ( "updatesAmount", 0 );
	}
	public function test() {
		$this->template->assign ( "menuText", __ ( "test" ) );
		$this->template->assign ( "menuAddress", "index.php?page=userSignUp" );
		$this->template->assign ( "updatesAmount", 0 );
	}
	protected function setup(): bool {
		return true;
	}
}
?>
