<?php

namespace userSignUp;

require_once __DIR__ . '/../../../class/configuration/Configuration.php';
final class Conf extends \Configuration {
	public static function getSignUpTemplate() {
		return __DIR__ . "/../template/user.html";
	}
}
?>