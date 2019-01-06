<?php

namespace main;

require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/../../class/page/APage.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/security/authentication/Authenticator.php';
class Page extends \APage {

	/**
	 * Pages data
	 *
	 * @var array
	 */
	private $arrData;

	public function handleRequest(): \SystemNotification {
		$auth = new \Authenticator ();

		$sn = new \SystemNotification ();
		$sn->addInformation ( 0, $auth->getAutenticatedEntity () );
		return $sn;
	}

	public static function isRestricted(): bool {
		return true;
	}

	protected function setup(): bool {
		return true;
	}

	public function generateTemplateData(\SystemNotification $data): array {
		return array();
	}

	protected function returnTemplateFile(\SystemNotification $data): string {
		return Conf::getTemplateFile ();
	}

	protected function returnTemplateFolder(): string {
		return Conf::getTemplateFolder ();
	}

	protected function returnCurrentDir(): string {
		return __DIR__;
	}
}
?>
