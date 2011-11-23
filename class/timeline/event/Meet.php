<?php
require_once __DIR__ . '/Event.php';

class Meet extends Event{
	
	protected $tema;
	
	public function getTema(){
	    return $this->tema;
	}

	public function setTema($tema){
	    $this->tema = $tema;
	}
}