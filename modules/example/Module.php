<?php

namespace example;

class Module {
	public function __construct() {
		echo "My example module!!!!!!!!!!<br>";
		
		$user = $_SESSION['authData'] ['autenticatedEntity'];
		
		echo "Hello " . $user->getLogin();
	}
}
new Module ();
?>
