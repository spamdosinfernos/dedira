<?php

namespace rulesEditor;

require_once __DIR__ . '/class/Conf.php';
require_once __DIR__ . '/class/Lang_Configuration.php';
require_once __DIR__ . '/../../class/page/IPage.php';
require_once __DIR__ . '/../../class/template/TemplateLoader.php';
require_once __DIR__ . '/../../class/database/POPOs/user/Rule.php';
require_once __DIR__ . '/../../class/protocols/http/HttpRequest.php';
/**
 * Register the Rule on system
 * @author André Furlan
 */
class Page implements \IPage {
	
	/**
	 * Manages the templates
	 * @var \TemplateLoader
	 */
	protected $xTemplate;

	/**
	 * Handles the requests
	 * @var \HttpRequest
	 */
	protected $httpRequest;
	
	public function __construct() {
		$this->xTemplate = new \TemplateLoader ( Conf::getTemplate () );
		$this->reflector = new ReflectionClass ( "Rule" );
		$this->httpRequest = new \HttpRequest ();
		$this->handleRequest ();
	}
	
	/**
	 * Handles request
	 * @return void | boolean
	 */
	public function handleRequest() {
		
		// get the next module
		$httpRequest = new \HttpRequest ();
		$gotVars = $httpRequest->getGetRequest ();
		$nextModule = isset ( $gotVars ["module"] ) ? $gotVars ["module"] : \Configuration::MAIN_PAGE_NAME;
		
		if (! $this->checkMandatoryFields ()) {
			$this->showGui ( $nextModule );
			return;
		}
		
		// Gets the obj id
		$authenticator = new \Authenticator ();
		$id = isset($gotVars["_id"]) ? $gotVars["_id"] : null;
		
		// If it does not exists create a new one
		if (is_null ( $id )) {
			if ($this->save()){
				$this->xTemplate->assign ( "message", Lang_Configuration::getDescriptions ( 0 ) );
			} else {
				$this->xTemplate->assign ( "message", Lang_Configuration::getDescriptions ( 1 ) );
			}
		} else {
			// Otherwise just updates
			if ($this->update ( $id )) {
				$this->xTemplate->assign ( "message", Lang_Configuration::getDescriptions ( 0 ) );
			} else {
				$this->xTemplate->assign ( "message", Lang_Configuration::getDescriptions ( 1 ) );
			}
		}
		
		$this->showGui ( $nextModule );
	}
	
	/**
	 * Updates Rule
	 *
	 * @param \Rule $user        	
	 * @return bool
	 */
	private function update( $int ): bool {
		$obj = $this->createEntityObject ( $int );
		
		// Updating object
		$c = new \DatabaseConditions ();
		$c->addCondition ( \DatabaseConditions::AND, "_id", $obj->get_id () );
		
		$query = new \DatabaseQuery ();
		$query->setConditions ( $c );
		$query->setObject ( $obj );
		$query->setOperationType ( \DatabaseQuery::OPERATION_UPDATE );
		
		return \Database::execute ( $query );
	}
	
	/**
	 * Create a new Rule
	 * @return bool
	 */
	private function save(): bool {
		$obj->createEntityObject ();
		
		// Inserting object
		$query = new \DatabaseQuery ();
		$query->setObject ( $obj );
		$query->setOperationType ( \DatabaseQuery::OPERATION_PUT );
		
		return \Database::execute ( $query );
	}
	
	/**
	 * Creates a Rule object using previous data or not
	 * @param $id        	
	 * @return \Rule
	 */
	private function createEntityObject($id = null): \Rule {

		$arrMethods = $this->reflector->getMethods ( ReflectionMethod::IS_PUBLIC );
		$postedVars = $this->httpRequest->getPostRequest();
		
		// Creates a new object
		$obj = new \Rule();
		if (!is_null($id)){
			$obj->set_id($id);
		}
		
		foreach ( $arrMethods as $method ) {
			if ($method->getNumberOfParameters () == 1){
					
				if (substr ( $method->getName (), 0, 3 ) == "set"){
					$method->invoke($obj, $postedVars[$method->getParameters()[0]]);
				}
			}
		}
		return obj;
	}

	private function checkMandatoryFields(): bool {
		$postedVars = $this->httpRequest->getPostRequest ();
		
		// Check mandatory fields
		foreach($postedVars as $var){
			if (trim($var) == ""){
				return false;
			}
		}

		return true;
	}
		
	private function showGui(string $nextModule) {
		
		$postedVars = $this->httpRequest->getPostRequest ();
	
		foreach($postedVars as $name => $value){
			$this->xTemplate->assign ( $name, $value );
		}
		
		$this->xTemplate->assign ( "nextModule", $nextModule );
		$this->xTemplate->parse ( "main" );
		$this->xTemplate->out ( "main" );
	}
	
	public static function isRestricted(): bool {
		return false;
	}
}
?>
