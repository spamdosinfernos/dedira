<?php

namespace fiscalize;

require_once __DIR__ . '/../../../class/configuration/Configuration.php';
final class Conf extends \Configuration {
	public static function getTemplate() {
		return __DIR__ . "/../template/page.html";
	}
}
?>