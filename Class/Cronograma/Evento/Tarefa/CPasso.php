<?php
class CPasso extends CCore{
	
	protected $id;
	
	/**
	 * Indica se o passo foi realizado ou não 
	 * @var boolean
	 */
	protected $completo;

	protected $explicacaoDoPasso;
	
	protected $observacoesDeQuemFezOPasso;
	
	public function __construct(){
		parent::__construct();
	}
	
	public function isCompleto(){
		return $this->completo;
	}
	
}
?>