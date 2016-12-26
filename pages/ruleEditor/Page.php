<?php

namespace ruleEditor;

require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/class/Lang_Configuration.php';
require_once __DIR__ . '/../../class/module/IPage.php';
require_once __DIR__ . '/../../class/template/TemplateLoader.php';
require_once __DIR__ . '/../../class/database/POPOs/user/User.php';
require_once __DIR__ . '/../../class/database/POPOs/rule/Rule.php';
require_once __DIR__ . '/../../class/security/PasswordPreparer.php';
require_once __DIR__ . '/../../class/protocols/http/HttpRequest.php';
require_once __DIR__ . '/../../class/security/authentication/Authenticator.php';
require_once __DIR__ . '/../../class/security/authentication/drivers/UserAuthenticatorDriver.php';
/**
 * Register a rule on system
 *
 * @author André Furlan
 */
class Page implements \IPage {
	
	/**
	 * Gerencia os templates
	 *
	 * @var XTemplate
	 */
	protected $xTemplate;
	public function __construct() {
		$this->xTemplate = new \TemplateLoader ( Conf::getTemplate () );
		
		$this->handleRequest ();
	}
	
	/**
	 * Handles authentication request If the authenticantion is successful keep executing the system
	 * otherwise show the authentication screen
	 *
	 * @return void|boolean
	 */
	public function handleRequest() : void {
		
		// get the next module user wants
		$httpRequest = new \HttpRequest ();
		$gotVars = $httpRequest->getGetRequest ();
		$nextModule = isset ( $gotVars ["module"] ) ? $gotVars ["module"] : \Configuration::MAIN_PAGE_NAME;
		
		if (! $this->checkMandatoryFields ()) {
			$this->showGui ( $nextModule );
			return;
		}
		
		$postVars = $httpRequest->getPostRequest ();
		
		if ($this->saveNewRule ( $postVars )) {
			$this->xTemplate->assign ( "message", Lang_Configuration::getDescriptions ( 2 ) );
		} else {
			$this->xTemplate->assign ( "message", Lang_Configuration::getDescriptions ( 3 ) );
		}
		
		$this->showGui ( $nextModule );
	}
	
	/**
	 * Create a new user
	 *
	 * @return bool
	 */
	private function saveNewRule($postData): bool {
		$rule = $this->createRuleObject ($postData);
		
		// Inserting object
		$query = new \DatabaseQuery ();
		$query->setObject ( $rule );
		$query->setOperationType ( \DatabaseQuery::OPERATION_PUT );
		
		return \Database::execute ( $query );
	}
	
	/**
	 * Creates a rule object 
	 *
	 * @param mixed $postedVars        	
	 * @return \Rule
	 */
	private function createRuleObject($postedVars): \Rule {
		$auth = new \Authenticator ();
		$user = $auth->getAutenticatedEntity ();
		
		$rule = new \Rule ();
		// The rule is not approved by default
		$rule->setApproved ( false );
		$rule->set_id ( dechex ( microtime ( true ) ) );
		$rule->setAuthorId ( $user->get_id () );
		$rule->setCreationDatetime ( new \DateTime () );
		$rule->setLawContents ( $postedVars ["lawcontents"] );
		
		return $rule;
	}
	private function checkMandatoryFields(): bool {
		$httpRequest = new \HttpRequest ();
		$postedVars = $httpRequest->getPostRequest ();
		
		// Check mandatory fields
		if (isset ( $postedVars ["lawcontents"] )) {
			return true;
		}
		
		return false;
	}
	private function showGui(string $nextModule) {
		$this->xTemplate->assign ( "lawcontents", Lang_Configuration::getDescriptions ( 0 ) );
		$this->xTemplate->assign ( "nextModule", $nextModule );
		$this->xTemplate->assign ( "sendText", Lang_Configuration::getDescriptions ( 1 ) );
		$this->xTemplate->parse ( "main" );
		$this->xTemplate->out ( "main" );
	}
	public static function isRestricted(): bool {
		return true;
	}
}
?>