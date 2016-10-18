<?php

namespace main;

class Module {
	public function __construct() {
		echo "Meu primeiro módulo!!!!!!!!!!<br>";
		
		$user = $_SESSION['authData'] ['autenticatedEntity'];
		
		echo "Olá " . $user->getLogin();
	}
}
new Module ();
?>
