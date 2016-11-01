<?php
namespace main;
require_once __DIR__ . '/../../../class/configuration/Configuration.php';
final class MainConf extends \Configuration {
	public static function getMainTemplate() {
		return __DIR__ . "/../template/Module.html";
	}
}
?>