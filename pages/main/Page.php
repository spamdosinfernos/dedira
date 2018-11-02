<?php

namespace main;

require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/../../class/page/APage.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/security/authentication/Authenticator.php';

class Page extends \APage {

	/**
	 * Pages data
	 * @var array
	 */
	private $arrData;

	public function __construct() {
		parent::__construct ( Conf::getTemplateFolder (), __DIR__ );
	}

	public function handleRequest(): object {
		$auth = new \Authenticator ();
		return $auth->getAutenticatedEntity ();
	}

	public static function isRestricted(): bool {
		return true;
	}

	public function createRateMenuEntry() {
		$i = new MenuItemData ();
		$i->setMenuAddress ( "index.php?page=rate" );
		$i->setMenuText ( __ ( "Rate" ) );
		$i->setUpdatesAmount ( 0 );
		$this->data [] = $i;
	}

	protected function setup(): bool {
		return true;
	}

	public function generateTemplateData($data): array {
		$this->createRateMenuEntry ();

		return $this->arrData;
	}
}
?>
