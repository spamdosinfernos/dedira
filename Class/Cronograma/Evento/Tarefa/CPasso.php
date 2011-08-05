<?php
class CPasso extends CCore{
	
	private $id;
	
	/**
	 * Indica se o passo foi realizado ou não 
	 * @var boolean
	 */
	private $completo;

	private $explicacaoDoPasso;
	
	private $observacoesDeQuemFezOPasso;
	
	public function isCompleto(){
		return $this->completo;
	}
	
}
?>