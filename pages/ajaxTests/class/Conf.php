<?php

namespace userSignUp;

require_once __DIR__ . '/../../../class/configuration/Configuration.php';
final class Conf extends \Configuration {
	public static function getTemplate() {
		return __DIR__ . "/../template/template.html";
	}
	
	public static function getMailTemplate() {
		return __DIR__ . "/../template/mailTemplate.html";
	}
}
?>