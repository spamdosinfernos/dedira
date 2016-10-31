<?php

namespace userSignUp;

require_once __DIR__ . '/../../../class/general/configuration/Configuration.php';
final class UserSignUpConf extends \Configuration {
	public static function getAutenticationRequestTemplate() {
		return __DIR__ . "/../template/user.html";
	}
}
?>