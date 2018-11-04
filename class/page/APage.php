<?php
require_once __DIR__ . '/../variable/JSONGenerator.php';
require_once __DIR__ . '/../template/TemplateLoader.php';
require_once __DIR__ . '/../internationalization/i18n.php';
require_once __DIR__ . '/../protocols/http/HttpRequest.php';
require_once __DIR__ . '/../configuration/Configuration.php';
require_once __DIR__ . '/notification/SystemNotification.php';

/**
 * The base for a page (or module if you prefer) in system
 * @author ensismoebius
 */
abstract class APage {

	/**
	 * Manges the http requests
	 * @var HttpRequest
	 */
	protected $httpRequest;

	/**
	 * Manages the templates
	 * @var TemplateLoader
	 */
	protected $template;

	/**
	 * Contains the next seed
	 * @var string
	 */
	protected $nextSeed;

	/**
	 * If the client is NOT requesting an HTML
	 * So just handle the request and give it
	 * the result in json format
	 */
	public function __construct() {
		\I18n::init ( Configuration::$defaultLanguage, $this->returnCurrentDir () . "/" . Configuration::$localeDirName );

		// If something fails on setup, just stops and show a message
		if (! $this->setup ()) {
			Log::recordEntry ( gettext( "Sorry, Fail on process your request" ), true );
			return;
		}

		// If we have to produce a Json statement, just do it and stop
		if ($this->isJsonRequest ()) {

			echo JSONGenerator::objectToJson ( array (
					"nextSeed" => $_SESSION ["seed"],
					"data" => $this->handleRequest ()
			) );

			return;
		}

		// get the page user wants
		$this->httpRequest = new HttpRequest ();
		$gotVars = $this->httpRequest->getGetRequest ();
		$nextPage = isset ( $gotVars ["page"] ) ? $gotVars ["page"] : \Configuration::$mainPageName;

		// If we got here we have to show some html
		$this->template = new \TemplateLoader ( $this->returnTemplateFolder () );

		// If theres no "next page" in the template $filenamewe are ok, that why the @ at the begin
		@$this->template->assign ( "nextPage", $nextPage );

		echo $this->generateOutput ( $this->handleRequest () );
	}

	/**
	 * Checks if the client is requesting an HTML page
	 * This is very useful if you are developing an ajax page
	 * which needs to load the html just once or an app that
	 * just want to have access to data
	 * @return bool
	 */
	private function isJsonRequest(): bool {
		if (isset ( $_GET [Configuration::$jsonRequestGetName] )) {return true;}
		return false;
	}

	/**
	 * Gets an object from <b> handleRequest </b> and return the HTML
	 * @see APage:handleRequest
	 * @param object $dataObject
	 * @return string
	 */
	protected function generateOutput(\SystemNotification $dataObject): string {
		$this->template->mergeAssignments ( $this->generateTemplateData ( $dataObject ) );
		return $this->template->render ( $this->returnTemplateFile ( $dataObject ) );
	}

	/**
	 * Prepares the page to perform whatever task it should do
	 * @return bool
	 */
	protected abstract function setup(): bool;

	/**
	 * Must return the path of current folder
	 * @return string
	 */
	protected abstract function returnCurrentDir(): string;

	/**
	 * Handles the client request and returns an result object
	 * @tutorial NOTE: This method is ALWAYS called before generateOutput
	 * @see APage::generateOutput
	 * @return object
	 */
	protected abstract function handleRequest(): \SystemNotification;

	/**
	 * Informs if the page is restricted or public
	 * @return bool
	 */
	protected abstract static function isRestricted(): bool;

	/**
	 * Must return an associative array witch contains
	 * the data that must be send back to user
	 * @return array
	 */
	protected abstract function generateTemplateData(\SystemNotification $data): array;

	/**
	 * Must return the path of template folder
	 * @return string
	 */
	protected abstract function returnTemplateFolder(): string;

	/**
	 * Must return the template file name
	 * @param object $data
	 * @return string
	 */
	protected abstract function returnTemplateFile(\SystemNotification $data): string;
}