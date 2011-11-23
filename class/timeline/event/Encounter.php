<?php
require_once 'Event.php';

class Encounter extends Event{
	
	protected $tema;
	
	public function getTema(){
	    return $this->tema;
	}

	public function setTema($tema){
	    $this->tema = $tema;
	}
}

$e = new Encounter();

?>