<?php
require_once __DIR__ . '/CEvento.php';

class CEncontro extends CEvento{
	
	protected $tema;
	
	public function getTema(){
	    return $this->tema;
	}

	public function setTema($tema){
	    $this->tema = $tema;
	}
}