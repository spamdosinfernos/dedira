<?php
require_once __DIR__ . '/../Configuration.php';

class CPageCreatorConf extends Configuration{
	
	public static function getTemplateFile(){
		return parent::getTemplatesDirectory() . "Class" . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "Template" . DIRECTORY_SEPARATOR . "CPageCreator.html";
	}
	
	public static function getStyleSheetFile(){
		return parent::getTemplatesDirectory() . "Class" . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "Template" . DIRECTORY_SEPARATOR . "CPageCreator.css";
	}
}
?>