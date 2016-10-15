<?php
require_once 'AEvent.php';
class Encounter extends AEvent {
	protected $theme;
	public function getTheme() {
		return $this->theme;
	}
	public function setTheme($theme) {
		$this->theme = $theme;
	}
}

$e = new Encounter ();

?>