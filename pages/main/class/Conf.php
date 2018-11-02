<?php

namespace main;

require_once __DIR__ . '/../../../class/configuration/Configuration.php';
final class Conf extends \Configuration {
	public static function getTemplateFolder() {
		return __DIR__ . "/../template";
	}
	public static function getTemplateFile() {
		return "page.html";
	}
}
?>