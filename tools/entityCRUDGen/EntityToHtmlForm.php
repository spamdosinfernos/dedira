<!-- BEGIN: main -->
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
var content = [];

function addAnotherField(fieldId){

	if(content[fieldId] == undefined){
		content[fieldId] = document.getElementById(fieldId).innerHTML;
	}

	document.getElementById(fieldId).innerHTML += content[fieldId];
}
</script>
<meta charset="UTF-8">
<title>{tittle}</title>
<link rel="stylesheet" type="text/css"	href="../modules/userSignUp/css/css.css">
</head>
<body>
<form action="index.php?module={nextModule}" method="post">
<p><em>{warning}</em></p>
<?php
require __DIR__ . "/../class/database/POPOs/rule/Rule.php";
class EntityToHtmlForm {
	
	/**
	 *
	 * @var ReflectionClass
	 */
	private $reflector;
	public function __construct($class) {
		$this->reflector = new ReflectionClass ( $class );
		
		$arrMethods = $this->reflector->getMethods ( ReflectionMethod::IS_PUBLIC );
		
		foreach ( $arrMethods as $method ) {
			
			if ($method->getNumberOfParameters () != 1) continue;
			
			if (substr ( $method->getName (), 0, 3 ) != "set") continue;
			
			echo $this->generateField ( $method->getName (), $method->getParameters () [0]->getType (), $class );
		}
	}
	private function generateField($setterName, $paramType, $class) {
		$setterName = strtolower ( str_ireplace ( "set", "", $setterName ) );
		
		$htmlType = "";
		
		switch ($paramType) {
			case "Datetime" :
				$htmlType = "datetime";
				break;
			default :
				$htmlType = "text";
				break;
		}
		
		if (substr ( $setterName, 0, 3 ) == "arr") {
			
			return "
		<fieldset>
		<legend>" . "{" . $setterName . "}" . "</legend>
		<div id=\"$setterName\">
		<label for=\"$setterName\">*" . "{" . $setterName . "}" . "</label>
		<input type=\"$htmlType\" id=\"$setterName\" name=\"$setterName" . "[]" . "\" placeholder=\"{" . $setterName . "}\"/>
		<button onclick=\"addAnotherField('$setterName')\">+</button><br/>
		</div>
		</fieldset>
		";
		}
		
		return "
		<label for=\"$setterName\">*" . "{" . $setterName . "}" . "</label>
		<input type=\"$htmlType\" name=\"$setterName\" id=\"$setterName\" placeholder=\"{" . $setterName . "}\"/><br/>
		";
	}
}
new EntityToHtmlForm ( new Rule () );
?>
<input type="submit" value={sendText}>
</form>
</body>
</html>
<!-- END: main -->