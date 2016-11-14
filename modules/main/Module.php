<?php

namespace main;

require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/class/Lang_Configuration.php';
require_once __DIR__ . '/../../class/module/IModule.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/template/TemplateLoader.php';
require_once __DIR__ . '/../../class/security/authentication/Authenticator.php';
class Module implements \IModule {
	
	/**
	 * Gerencia os templates
	 *
	 * @var XTemplate
	 */
	protected $xTemplate;
	public function __construct() {
		$auth = new \Authenticator ();
		$user = $auth->getAutenticatedEntity ();
		
		$this->xTemplate = new \TemplateLoader ( Conf::getMainTemplate () );
		$this->xTemplate->assign ( "wellcomeMessage", Lang_Configuration::getDescriptions ( 0 ) );
		
		$this->xTemplate->assign ( "userName", $user->getName () . " " . $user->getLastName () );
		
		$this->xTemplate->parse ( "main" );
		$this->xTemplate->out ( "main" );
	}
	public static function isRestricted(): bool {
		return true;
	}
}
?>
