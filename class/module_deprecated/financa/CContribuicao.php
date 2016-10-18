<?php
require_once __DIR__ . '/../../general/database/StorableObject.php';

/**
 * Representa uma contribuição financeira
 * 
 * @author André Furlan
 *        
 */
class Contribuicao extends AStorableObject {
	
	/**
	 * Valor
	 * 
	 * @var float
	 */
	protected $valor;
	
	/**
	 * Data em que a contribuição foi recebida ou agendada
	 * 
	 * @var DateTime
	 */
	protected $dataDePagamento;
	
	/**
	 * Informa se a contribuição foi paga
	 * 
	 * @var boolean
	 */
	protected $pago;
	public function getValor() {
		return $this->valor;
	}
	public function setValor($valor) {
		$this->valor = $valor;
	}
	public function getDataDePagamento() {
		return $this->dataDePagamento;
	}
	public function setDataDePagamento(DateTime $dataDePagamento) {
		$this->dataDePagamento = $dataDePagamento;
	}
	public function isPago() {
		return $this->pago;
	}
	public function setPago($pago) {
		$this->pago = $pago;
	}
}
?>