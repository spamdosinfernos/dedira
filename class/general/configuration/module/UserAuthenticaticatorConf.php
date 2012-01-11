<?php
require_once __DIR__ . '/../Configuration.php';

final class UserAuthenticaticatorConf extends Configuration{

	public static function getTemplatesDirectory(){
		return parent::getTemplatesDirectory() . "class" . DIRECTORY_SEPARATOR . "general" . DIRECTORY_SEPARATOR . "module" . DIRECTORY_SEPARATOR;
	}

	public static function getAutenticationRequestTemplate(){
		return self::getTemplatesDirectory() . "UserAuthenticaticator.html";
	}

}
?>