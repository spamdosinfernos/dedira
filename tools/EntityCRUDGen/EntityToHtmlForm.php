<?php
require_once __DIR__ . '/../../class/template/TemplateLoader.php';
class EntityToHtmlForm {
	
	/**
	 *
	 * @var XTemplate
	 */
	private $templateHtml;
	
	/**
	 *
	 * @var XTemplate
	 */
	private $templatePhp;
	
	/**
	 *
	 * @var XTemplate
	 */
	private $templateConf;
	
	/**
	 *
	 * @var XTemplate
	 */
	private $templateLang;
	
	/**
	 *
	 * @var ReflectionClass
	 */
	private $reflector;
	public function __construct($class, $pathToClass, $author, $pageName) {
		require_once $pathToClass;
		
		$this->reflector = new ReflectionClass ( $class );
		$this->templateHtml = new XTemplate ( "./template/template.tmpl" );
		$this->templatePhp = new XTemplate ( "./template/Page.tmpl" );
		$this->templateConf = new XTemplate ( "./template/Conf.tmpl" );
		
		$arrMethods = $this->reflector->getMethods ( ReflectionMethod::IS_PUBLIC );
		
		foreach ( $arrMethods as $method ) {
			
			if ($method->getNumberOfParameters () != 1)
				continue;
			
			if (substr ( $method->getName (), 0, 3 ) != "set")
				continue;
			
			$this->generateField ( $method->getName (), $method->getParameters () [0]->getType () );
			$this->generateFieldPHP ( $method->getName (), $method->getParameters () [0]->getType () );
		}
		
		$this->templatePhp->assign ( "pageName", $pageName );
		$this->templatePhp->assign ( "author", $author );
		$this->templatePhp->assign ( "class", $class );
		$this->templatePhp->parse ( "main" );
		$this->templatePhp->out_file ( "main", __DIR__ . "/result/Page.php" );
		
		$this->templateHtml->assign ( "pageName", $pageName );
		$this->templateHtml->assign ( "author", $author );
		$this->templateHtml->assign ( "sendText", "Enviar" );
		$this->templateHtml->assign ( "class", $class );
		$this->templateHtml->parse ( "main" );
		$this->templateHtml->out_file ( "main", __DIR__ . "/result/template/template.html" );
		
		$this->generateConfClass ( $pageName );
	}
	private function generateField($setterName, $paramType) {
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
		
		$this->templateHtml->assign ( "htmlType", $htmlType );
		$this->templateHtml->assign ( "setterName", $setterName );
		
		if (substr ( $setterName, 0, 3 ) == "arr") {
			$this->templateHtml->parse ( "main.fieldgroup" );
			return;
		}
		$this->templateHtml->parse ( "main.field" );
	}
	private function generateFieldPHP($setterName, $paramType) {
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
		
		$this->templatePhp->assign ( "htmlType", $htmlType );
		$this->templatePhp->assign ( "setterName", $setterName );
		
		if (substr ( $setterName, 0, 3 ) == "arr") {
			$this->templatePhp->parse ( "main.fieldgroup" );
			return;
		}
		$this->templatePhp->parse ( "main.field" );
	}
	private function generateConfClass($pageName) {
		$this->templateConf->assign ( "pageName", $pageName );
		$this->templateConf->parse ( "main" );
		$this->templateConf->out_file ( "main", __DIR__ . "/result/class/Conf.php" );
	}
}
new EntityToHtmlForm ( "CostsCenter", __DIR__ . '/../../class/database/POPOs/costs/CostsCenter.php', "André Furlan", "CostsCenterEditor" );
?>