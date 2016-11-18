<?php
require __DIR__ . "/../class/database/POPOs/user/User.php";
class EntityToHtmlForm {
	
	/**
	 *
	 * @var ReflectionClass
	 */
	private $reflector;
	public function __construct($class) {
		$this->reflector = new ReflectionClass ( $class );
		
		$arrMethods = $this->reflector->getMethods ( ReflectionMethod::IS_PUBLIC );
		
		foreach ( $arrMethods as $key => $method ) {
			
			if ($method->getNumberOfParameters () != 1)
				continue;
			
			echo $this->generateField ( $method->getName (), $method->getParameters () [0]->getType (), $class );
		}
	}
	private function generateField($setterName, $paramType, $class) {
		$setterName = strtolower ( str_ireplace ( "set", "", $setterName ) );
		
		$htmlType = "";
		$html = "";
		
		switch ($paramType) {
			case "Datetime" :
				$htmlType = "datetime";
				break;
			default :
				$htmlType = "text";
				break;
		}
		
		if (substr ( $setterName, 0, 3 ) == "arr") {
			
			return "<fieldset>
					<legend>" . substr ( $setterName, 3, strlen ( $setterName ) ) . "</legend>
					<labe>$setterName</label>
					<input type=\"$htmlType\" name=\"$setterName\"/>
					</fieldset>";
		}
		
		return "<labe>$setterName</label><input type=\"$htmlType\" name=\"$setterName\"/><br/>";
	}
}

new EntityToHtmlForm ( new User () );