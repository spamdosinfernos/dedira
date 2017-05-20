<?php
require_once __DIR__ . '/../variable/Caster.php';
/**
 * Retrives fields from form and return a desired object from it
 * 
 * @author ensismoebius
 *        
 */
final class Form {
	
	/**
	 * Holds the filters for the properties
	 *
	 * @var array
	 */
	private $arrFilters;
	
	/**
	 * Holds the target object form must generate
	 *
	 * @var object
	 */
	private $targetObject;
	
	/**
	 * Holds the generated object
	 *
	 * @var object
	 */
	private $generatedObject;
	
	/**
	 * the source of data you will retrieve from
	 *
	 * @var integer
	 */
	private $getOrPost;
	
	/**
	 * the source of data you will retrieve from
	 *
	 * @var integer
	 */
	const TYPE_GET = 0;
	
	/**
	 * the source of data you will retrieve from
	 *
	 * @var integer
	 */
	const TYPE_POST = 1;
	
	/**
	 * Informs the source of data you will retrieve from
	 *
	 * @param
	 *        	TYPE_GET
	 * @param
	 *        	TYPE_POST
	 */
	public function setType($getOrPost) {
		$this->getOrPost = $getOrPost;
	}
	
	/**
	 * Sets an instace of the target object the form must generate
	 *
	 * @param object $object        	
	 */
	public function setTargetObject($object) {
		$this->targetObject = $object;
	}
	
	/**
	 * Generates the object from form data
	 *
	 * @return boolean
	 */
	public function generateObject() {
		$dataSource = null;
		
		// You must set the source of data you will retrieve from
		switch ($this->getOrPost) {
			case self::TYPE_GET :
				$dataSource = &$_GET;
				break;
			
			case self::TYPE_POST :
				$dataSource = &$_POST;
				break;
			
			default :
				return false;
		}
		
		// Sanitize data and generate the object
		$generatedObject = $this->validateAndSanitize ( $this->arrFilters, $dataSource );
		
		// If obeject is a boolean something goes wrong
		if (is_bool ( $generatedObject )) {
			return false;
		}
		
		// Sets the generated object
		$this->generatedObject = $generatedObject;
		return true;
	}
	
	/**
	 * Register the form fields that must be filtered
	 *
	 * @param string $fieldName        	
	 * @param integer $filterType        	
	 */
	public function registerField($fieldName, $filterType) {
		$this->arrFilters [$fieldName] = $filterType;
	}
	
	/**
	 * Sanitizes fields and generate object
	 *
	 * @param array $arrFilters        	
	 * @param array $dataSource        	
	 * @return boolean|object
	 */
	private function validateAndSanitize(&$arrFilters, &$dataSource) {
		foreach ( $arrFilters as $fieldName => $filterType ) {
			
			if (is_array ( $dataSource [$fieldName] )) {
				
				foreach ( $dataSource [$fieldName] as &$value ) {
					$result = filter_var ( trim ( $value ), $filterType );
					
					// If result is boolean the filtering has failed
					// So stops everything and return false
					if (is_bool ( $result )) {
						return false;
					}
					
					// Filter is ok, go on
					$value = $result;
				}
				continue;
			}
			
			$result = filter_var ( trim ( $dataSource [$fieldName] ), $filterType );
			
			// If result is boolean the filtering has failed
			// So stops everything and return false
			if (is_bool ( $result )) {
				return false;
			}
			
			// Filter is ok, go on
			$dataSource [$fieldName] = $result;
		}
		
		return Caster::arrayToClassCast ( $dataSource, $this->targetObject );
	}
	
	/**
	 * Returns the generated object
	 *
	 * @return object
	 */
	public function getObject() {
		return $this->generatedObject;
	}
}

require_once __DIR__ . '/../database/POPOs/user/User.php';

$_GET ["_id"] = "12fb34";
$_GET ["login"] = "André";
$_GET ["password"] = "123445667";
$_GET ["arrEmail"] [] = "teste@teste.com.br";
$_GET ["arrEmail"] [] = "uga@teste.com.br";
$_GET ["arrEmail"] [] = "ytateste.com.br";

$f = new Form ();
$f->setType ( Form::TYPE_GET );
$f->setTargetObject ( new User () );
$f->registerField ( "login", FILTER_SANITIZE_STRING );
$f->registerField ( "password", FILTER_SANITIZE_STRING );
$f->registerField ( "arrEmail", FILTER_SANITIZE_EMAIL );
$f->registerField ( "_id", FILTER_SANITIZE_STRING );
$f->generateObject ();
print_r ( $f->getObject () );
?>

