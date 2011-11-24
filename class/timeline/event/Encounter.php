<?php
require_once 'Event.php';

class Encounter extends Event{
	
	protected $theme;
	
	public function getTheme(){
	    return $this->theme;
	}

	public function setTheme($theme){
	    $this->theme = $theme;
	}
}

$e = new Encounter();

?>