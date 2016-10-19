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
		
		// Already athenticated: continues
		$authenticator = new \Authenticator ();
		if ($authenticator->isAuthenticated ()) return;
		
		// get login and password if any
		$httpRequest = new \HttpRequest ();
		$postedVars = $httpRequest->getPostRequest ();
		
		// get the module user wants
		$gotVars = $httpRequest->getGetRequest ();
		$nextModule = isset ( $gotVars ["module"] ) ? $gotVars ["module"] : \Configuration::MAIN_MODULE_NAME;
		
		// Verifies the nullables
		if (! isset ( $postedVars ["login"] ) || ! isset ( $postedVars ["password"] )) {
			$this->showGui ( $nextModule );
			exit ( 0 );
		}
		
		// Creates the user to authenticate
		$user = new \User ();
		$user->setLogin ( $postedVars ["login"] );
		$user->setPassword ( \PasswordPreparer::messItUp ( $postedVars ["password"] ) );
		
		// Authenticate
		$authenticator->setAuthenticationRules ( new \UserAuthenticatorDriver ( $user ) );
		if ($authenticator->authenticate ()) {
			return;
		}
		
		$this->showGui ( $nextModule );
		exit ( 0 );
	}
	private function showGui(string $nextModule) {
		$this->xTemplate->assign ( "systemMessage", $this->getTitle () );
		$this->xTemplate->assign ( "nextModule", $nextModule );
		
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