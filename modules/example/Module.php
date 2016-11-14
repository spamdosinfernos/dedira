<?php
namespace example;

require_once __DIR__ . '/../../class/module/IModule.php';

class Module implements \IModule{
	public function __construct() {
		echo "My example module!!!!!!!!!!<br>";
		
		$user = $_SESSION['authData'] ['autenticatedEntity'];
		
		echo "Hello " . $user->getLogin();
	}
	
	public static function isRestricted(): bool {
		return true;
	}
}
?>
