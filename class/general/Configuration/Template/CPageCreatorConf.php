<?php
require_once __DIR__ . '/../CConfiguration.php';

class CPageCreatorConf extends CConfiguration{
	
	public static function getTemplateFile(){
		return parent::getTemplatesDirectory() . "Class" . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "Template" . DIRECTORY_SEPARATOR . "CPageCreator.html";
	}
	
	public static function getStyleSheetFile(){
		return parent::getTemplatesDirectory() . "Class" . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "Template" . DIRECTORY_SEPARATOR . "CPageCreator.css";
	}
}
?>