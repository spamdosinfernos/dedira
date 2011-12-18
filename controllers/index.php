<?php
require_once __DIR__ . '/../class/general/module/UserAuthenticaticator.php';
require_once __DIR__ . '/language/Lang_index.php';

class index{

	/**
	 * Constrói a página o cronograma
	 */
	public function __construct(){

		//Instância o módulo responsável pela autenticação no sistema
		$userAuth = new UserAuthenticaticator();
			
		if(!$userAuth->handleRequest()){
			$userAuth->showGui();
			return;
		}

		//Recupera identificação do módulo atual
		$httpRequest = new HttpRequest();
		$arrGet = $httpRequest->getGetRequest(Configuration::CONST_QUERY_STRING_MODULE_NAME_VAR_NAME);

		if(!is_null($arrGet)){
			//Só chega aqui se na url não houver o nome do módulo
			die(Lang_index::getDescriptions(1));
		}
	}

}
new index();
?>