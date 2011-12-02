<?php
require_once 'AEvent.php';
require_once __DIR__ . '/../../Organizacao/OrganizacaoSocial.php';

class Manifestation extends AEvent{
	
	protected $demand;
	
	public function getDemand(){
	    return $this->demand;
	}

	public function setDemand($demand){
	    $this->demand = $demand;
	}
}