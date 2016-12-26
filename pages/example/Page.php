<?php
namespace example;

require_once __DIR__ . '/../../class/module/IPage.php';
require_once __DIR__ . '/../../class/internationalization/i18n.php';

class Page implements \IPage{
	public function __construct() {
		
		\I18n::init ( Configuration::getSelectedLanguage (), __DIR__ . "/" . Configuration::LOCALE_DIR_NAME );
		
		echo __("My example module!!!!!!!!!!<br>");
		
		$user = $_SESSION['authData'] ['autenticatedEntity'];
		
		echo __("Hello ") . $user->getLogin();
	}
	
	public static function isRestricted(): bool {
		return true;
	}
}
?>
