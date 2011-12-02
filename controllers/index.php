<?php
require_once __DIR__ . '/../class/general/module/UserAuthenticaticator.php';

class index{

	/**
	 * Constrói a página o cronograma
	 */
	public function __construct(){
		$userAuth = new UserAuthenticaticator();
		if($userAuth->handleRequest()) die($_SESSION['userData']['userId']);
		$userAuth->showGui();
	}

}
new index();
?>