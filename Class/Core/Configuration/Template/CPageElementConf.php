<?php
require_once __DIR__ . '/../CConfiguration.php';

class CPageElementConf extends CConfiguration{
	
	public static function getTemplateFile(){
		return parent::getTemplatesDirectory() . "Class" . DIRECTORY_SEPARATOR . "Core" . DIRECTORY_SEPARATOR . "Template" . DIRECTORY_SEPARATOR . "CPageElement.html";
	}
}
?>