<?php
require_once __DIR__ . '/language/Lang_MainController.php';
require_once __DIR__ . '/../class/general/module/UserAuthenticaticator.php';

/**
 * Esta classe gerencia todas as requisições recebidas pelo site
 * Requisições enviadas a outros arquivos devem ser ignoradas
 * 
 * @author André Furlan
 */
class MainController {
	public function __construct() {
		
		Database::init(Configuration::getDatabaseDriver());
		
		if (! Database::connect ()) {
			// TODO create a log entry
			echo "fail to connect";
			return;
		}
		
		// Instância o módulo responsável pela autenticação no sistema
		$userAuth = new UserAuthenticaticator ();
		
		// Se o usuário não estiver autenticado mostra a tela de autenticação
		if (! $userAuth->handleRequest ()) {
			$userAuth->showGui ();
			return;
		}
		
		// Recupera identificação do módulo atual
		$moduleId = $this->getModuleId ();
		
		// Só pára aqui se na url não houver o nome do módulo
		if (is_null ( $moduleId )) die ( Lang_MainController::getDescriptions ( 1 ) );
		
		// Só pára aqui se o módulo especificado é inválido senão carrega o mesmo
		if (! $this->loadModule ( $moduleId )) die ( Lang_MainController::getDescriptions ( 2 ) );
		
		Database::disconnect();
	}
	
	/**
	 * Carrega e executa o módulo expecificado
	 * 
	 * @param string $moduleId        	
	 * @return boolean
	 */
	private function loadModule($moduleId) {
		
		// Se o arquivo index.php do módulo não existe pára aqui
		if (! file_exists ( Configuration::getUserModuleDiretory () . DIRECTORY_SEPARATOR . $moduleId . DIRECTORY_SEPARATOR . Configuration::getUserModuleStarterFileName () )) return false;
		
		// Carrega o módulo
		require_once Configuration::getUserModuleDiretory () . DIRECTORY_SEPARATOR . $moduleId . DIRECTORY_SEPARATOR . Configuration::getUserModuleStarterFileName ();
		
		// Executa o módulo
		$starterClass = Configuration::getUserModuleStarterClassName ();
		new $starterClass ();
		
		// Se chegou até aqui deu tudo certo
		return true;
	}
	
	/**
	 * Recupera identificação do módulo atual, se o mesmo não for informado
	 * pára o programa
	 * 
	 * @return string - nome do módulo
	 */
	private function getModuleId() {
		$httpRequest = new HttpRequest ();
		$arrModuleId = $httpRequest->getGetRequest ( Configuration::CONST_QUERY_STRING_MODULE_NAME_VAR_NAME );
		
		if (! is_null ( $arrModuleId )) {
			return null;
		}
		
		return $arrModuleId [0];
	}
}
new MainController ();
?>