<?php
require_once __DIR__ . '/Passo.php';

class FluxoDeTrabalho{
	
	protected $descricao;
	
	/**
	 * Passos do fluxo para resolver a tarefa 
	 * @var Array : Passo
	 */
	protected $arrPassos;
	
	public function __construct(){
		parent::__construct();
	}
	
	public function addPasso(Passo $passo){
		$this->arrPassos[] = $passo;
	}
	
	public function setPassos($arrPassos){
		$this->arrPassos = $arrPassos;
	}
	
	/**
	 * Retorna um número entre 0 e 1 indicando o nível de completude de um dado fluxo de trabalho
	 * 
	 * 0 = Não completo (0%)
	 * 1 = Completo (100%)
	 * @return float
	 */
	private function getCompletude(){
		$qtdPassos = count($this->arrPassos);
		$qtdPassosConcluidos = 0;
		
		foreach ($this->arrPassos as $passo) {
			if($passo->isCompleto()){
				$qtdPassosConcluidos++;				
			}
		}

		return $qtdPassosConcluidos / $qtdPassos;
	}
}
?>