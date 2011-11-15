<?php
require_once __DIR__ . '/../Configuration.php';

class PageElementConf extends Configuration{
	
	public static function getTemplateFile(){
		return parent::getTemplatesDirectory() . "Class" . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "Template" . DIRECTORY_SEPARATOR . "PageElement.html";
	}
}
?>