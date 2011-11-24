<?php
require_once 'Event.php';
require_once __DIR__ . '/../../Organizacao/OrganizacaoSocial.php';

class Manifestation extends Event{
	
	protected $demand;
	
	public function getDemand(){
	    return $this->demand;
	}

	public function setDemand($demand){
	    $this->demand = $demand;
	}
}