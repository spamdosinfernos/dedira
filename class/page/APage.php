<?php
require_once __DIR__ . '/../variable/JSONGenerator.php';
require_once __DIR__ . '/../template/TemplateLoader.php';
require_once __DIR__ . '/../internationalization/i18n.php';
require_once __DIR__ . '/../protocols/http/HttpRequest.php';
require_once __DIR__ . '/../configuration/Configuration.php';

/**
 * The base for a page (or module if you prefer) in system
 *
 * @author ensismoebius
 *        
 */
abstract class APage {
	
	/**
	 * Manges the http requests
	 *
	 * @var HttpRequest
	 */
	protected $httpRequest;
	
	/**
	 * Manages the templates
	 *
	 * @var TemplateLoader
	 */
	protected $template;
	
	/**
	 * Contains the next seed
	 *
	 * @var string
	 */
	protected $nextSeed;
	
	/**
	 * If the client is NOT requesting an HTML
	 * So just handle the request and give it
	 * the result in json format
	 */
	public function __construct(string $templateFolder, string $currentDir) {
		\I18n::init ( Configuration::$defaultLanguage, $currentDir . "/" . Configuration::$localeDirName );
		
		// If something fails on setup, just stops and show a message
		if (! $this->setup ()) {
			Log::recordEntry ( __ ( "Sorry, Fail on process your request" ), true );
			return;
		}
		
		$this->httpRequest = new HttpRequest ();
		
		// The "seed" are used to ensure that no men(or woman)-in-the-middle
		// attack happens, this is made by generating a new seed every time
		// a request is made
		if (!isset($_SESSION ["seed"]) || $this->httpRequest->getGetRequest ( "seed" ) != $_SESSION ["seed"]) {
			$au = new Authenticator ();
			$au->unauthenticate ();
			return;
		}
		
		// Generates and stores the next seed for further verification
		$this->nextSeed = $this->getNextSeed ();
		$_SESSION ["seed"] = $this->nextSeed;
		
		// If we have to produce a Json statement, just do it and stop
		if ($this->isJsonRequest ()) {
			
			echo JSONGenerator::objectToJson ( array (
					"nextSeed" => $nextSeed,
					"data" => $this->handleRequest () 
			) );
			
			return;
		}
		
		// get the page user wants
		$gotVars = $this->httpRequest->getGetRequest ();
		$nextPage = isset ( $gotVars ["page"] ) ? $gotVars ["page"] : \Configuration::$mainPageName;
		
		// If we got here we have to show some html
		$this->template = new \TemplateLoader ( $templateFolder );
		
		// If theres no "next page" in the template we are ok, that why the @ at the begin
		@$this->template->assign ( "nextPage", $nextPage );
		
		echo $this->generateOutput ( $this->handleRequest () );
	}
	
	/**
	 * Generates a random seed with avoid man(or woman)-in-the-middle
	 * attacks
	 * @return number
	 */
	private function getNextSeed(){
		return rand();
	}
	
	/**
	 * Checks if the client is requesting an HTML page
	 *
	 * This is very useful if you are developing an ajax page
	 * which needs to load the html just once or an app that
	 * just want to have access to data
	 *
	 * @return bool
	 */
	private function isJsonRequest(): bool {
		if (isset ( $_GET [Configuration::$jsonRequestGetName] )) {
			return true;
		}
		return false;
	}
	
	/**
	 * Prepares the page to perform whatever task it should do
	 *
	 * @return bool
	 */
	protected abstract function setup(): bool;
	
	/**
	 * Gets an object from <b> handleRequest </b> and return the HTML
	 *
	 * @see APage:handleRequest
	 * @param object $dataObject
	 * @return string
	 */
	protected abstract function generateOutput(object $dataObject): string;
	
	/**
	 * Handles the client request and returns an result object
	 *
	 * @tutorial NOTE: This method is ALWAYS called before generateOutput
	 * @see APage::generateOutput
	 * @return object
	 */
	protected abstract function handleRequest(): object;
	
	/**
	 * Informs if the page is restricted or public
	 *
	 * @return bool
	 */
	public abstract static function isRestricted(): bool;
}