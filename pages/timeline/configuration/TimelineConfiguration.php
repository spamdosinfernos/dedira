<?php
require_once __DIR__ . '/../../general/configuration/Configuration.php';
class TimelineConfiguration extends Configuration {
	static public function getTemplatesDirectory() {
		return parent::getTemplatesDirectory () . "controllers" . DIRECTORY_SEPARATOR . "page" . DIRECTORY_SEPARATOR . "timeline" . DIRECTORY_SEPARATOR;
	}
}
?>