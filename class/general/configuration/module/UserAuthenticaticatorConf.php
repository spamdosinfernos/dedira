<?php
require_once  __DIR__ . '/../Configuration.php';

final class UserAuthenticaticatorConf extends Configuration{

	public static function getTemplatesDirectory(){
		return parent::getTemplatesDirectory() . "class/general/module/";
	}

	public static function getAutenticationRequestTemplate(){
		return self::getTemplatesDirectory() . "UserAuthenticaticator.html";
	}

}
?>