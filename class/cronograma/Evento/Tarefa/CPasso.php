<?php
require_once __DIR__ . '/../../../core/Core.php';

class Passo{
	
	protected $id;
	
	/**
	 * Indica se o passo foi realizado ou não 
	 * @var boolean
	 */
	protected $completo;

	/**
	 * Descreve o passo
	 * @var string
	 */
	protected $explicacaoDoPasso;
	
	/**
	 * Observações sobre o passo
	 * @var string
	 */	
	protected $observacoesDeQuemFezOPasso;
	
	public function __construct(){
		parent::__construct();
	}
	
	public function getId(){
	    return $this->id;
	}

	public function setId($id){
	    $this->id = $id;
	}

	public function isCompleto(){
		return $this->completo;
	}

	public function setCompleto($completo){
	    $this->completo = $completo;
	}

	public function getExplicacaoDoPasso(){
	    return $this->explicacaoDoPasso;
	}

	public function setExplicacaoDoPasso($explicacaoDoPasso){
	    $this->explicacaoDoPasso = $explicacaoDoPasso;
	}

	public function getObservacoesDeQuemFezOPasso(){
	    return $this->observacoesDeQuemFezOPasso;
	}

	public function setObservacoesDeQuemFezOPasso($observacoesDeQuemFezOPasso){
	    $this->observacoesDeQuemFezOPasso = $observacoesDeQuemFezOPasso;
	}
}
?>