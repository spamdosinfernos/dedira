<?php
require_once __DIR__ . '/../../../class/general/configuration/Configuration.php';
final class UserAuthenticaticatorConf extends Configuration {
	public static function getAutenticationRequestTemplate() {
		return __DIR__ . "/../template/UserAuthenticaticator.html";
	}
}
?>