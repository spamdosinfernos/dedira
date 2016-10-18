<?php

namespace userAuthenticaticator;

require_once __DIR__ . '/class/UserAuthenticaticatorConf.php';
require_once __DIR__ . '/../../class/general/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/general/template/CustomXtemplate.php';
require_once __DIR__ . '/../../class/general/security/PasswordPreparer.php';
require_once __DIR__ . '/../../class/general/protocols/http/HttpRequest.php';
require_once __DIR__ . '/../../class/general/security/authentication/drivers/UserAuthenticatorDriver.php';
require_once __DIR__ . '/../../class/general/security/authentication/Authenticator.php';
/**
 * Responsável por carregar os módulos do sistema
 *
 * @author André Furlan
 *        
 */
class Module {
	
	/**
	 * Gerencia os templates
	 *
	 * @var XTemplate
	 */
	protected $xTemplate;
	public function __construct() {
		$this->xTemplate = new \CustomXtemplate ( \UserAuthenticaticatorConf::getAutenticationRequestTemplate () );
		
		$this->handleRequest ();
	}
	
	/**
	 * Handles authentication request
	 * If the authenticantion is successful keep executing
	 * the system otherwise show the authentication screen
	 * 
	 * @return void|boolean
	 */
	public function handleRequest() {
		
		// Se já estiver autenticado sai do método com true
		$authenticator = new \Authenticator ();
		if ($authenticator->isAuthenticated ()) return;
		
		// Recupera a requisição (login e senha)
		$httpRequest = new \HttpRequest ();
		$postedVars = $httpRequest->getPostRequest ();
		
		// Se os dados não foram postados corretamente sai do método com false
		if (! isset ( $postedVars ["login"] ) || ! isset ( $postedVars ["password"] )) {
			$this->showGui ();
			exit(0);
		}
		
		// Prepara o usuário para a verificação
		$user = new \User ();
		$user->setLogin ( $postedVars ["login"] );
		$user->setPassword ( \PasswordPreparer::messItUp ( $postedVars ["password"] ) );
		
		// Tenta autenticar o usuário no sistema
		$authenticator->setAuthenticationRules ( new \UserAuthenticatorDriver ( $user ) );
		if ($authenticator->authenticate ()) {
			return;
		}
		
		$this->showGui ();
		exit ( 0 );
	}
	private function showGui($arrGuiBlockNames = array()) {
		$this->xTemplate->assign ( "systemMessage", $this->getTitle () );
		$this->xTemplate->assign ( "nextModule", \Configuration::CONST_MAIN_MODULE_NAME );
		
		// Mostra os blocos de interface especificados
		foreach ( $arrGuiBlockNames as $guiBlock ) {
			$this->xTemplate->parse ( $guiBlock );
		}
		
		// Mostra o bloco principal
		$this->xTemplate->parse ( "main" );
		$this->xTemplate->out ( "main" );
	}
	public function getTitle() {
		return \UserAuthenticaticatorConf::getAuthMessage ();
	}
}
new Module ();
?>