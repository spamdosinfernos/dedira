<?php
namespace example;
require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/../../class/page/APage.php';
require_once __DIR__ . '/../../class/internationalization/i18n.php';

class Page extends \APage{
	public function __construct() {
		
		\I18n::init ( Conf::$defaultLanguage, __DIR__ . "/" . Conf::$localeDirName );
		
		echo __("My example page!!!!!!!!!!<br>");
		
		$user = $_SESSION['authData'] ['autenticatedEntity'];
		
		echo __("Hello ") . $user->getLogin();
	}
	
	public static function isRestricted(): bool {
		return true;
	}
}
?>
