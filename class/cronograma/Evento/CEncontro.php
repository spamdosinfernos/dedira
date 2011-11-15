<?php
require_once __DIR__ . '/Evento.php';

class Encontro extends Evento{
	
	protected $tema;
	
	public function getTema(){
	    return $this->tema;
	}

	public function setTema($tema){
	    $this->tema = $tema;
	}
}