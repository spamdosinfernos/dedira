<?php
require_once __DIR__ . '/../variable/JSONGenerator.php';
require_once __DIR__ . '/../configuration/Configuration.php';

/**
 * The base for a page (or module if you prefer) in system
 * 
 * @author ensismoebius
 *        
 */
abstract class APage {
	
	/**
	 * If the client is NOT requesting an HTML
	 * So just handle the request and give it
	 * the result in json format
	 */
	public function __construct() {
		if (! $this->isAHTMLRequest ()) {
			echo JSONGenerator::objectToJson ( $this->handleRequest () );
			return;
		}
		
		$this->generateHTML ( $this->handleRequest () );
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
	private function isAHTMLRequest(): bool {
		if (isset ( $_GET [Configuration::isHTMLGetParamName] )) {
			return true;
		}
		return false;
	}
	
	/**
	 * Gets an object from <b> handleRequest </b> and return the HTML
	 * @see handleRequest
	 * @param object $object        	
	 * @return string
	 */
	protected abstract function generateHTML($object): string;
	
	/**
	 * Handles the client request and returns an result object
	 * @tutorial NOTE: This method is ALWAYS called before generateHTML
	 * @see generateHTML
	 * @return object
	 */
	protected abstract function handleRequest();
	
	/**
	 * Informs if the page is restricted or public
	 *
	 * @return bool
	 */
	public abstract static function isRestricted(): bool;
}