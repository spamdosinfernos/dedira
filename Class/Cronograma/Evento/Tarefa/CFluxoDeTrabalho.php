<?php
require_once 'CPasso';

class CFluxoDeTrabalho extends CCore{
	
	private $id;
	
	private $descricao;
	
	/**
	 * Passos do fluxo para resolver a tarefa 
	 * @var Array : CPasso
	 */
	private $arrPassos;
	
	public function addPasso(CPasso $passo){
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