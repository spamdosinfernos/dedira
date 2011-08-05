<?php
require_once 'CFluxoDeTrabalho.php';

class CTarefa extends CEvento{
	
	/**
	 * Responsáveis pela tarefa 
	 * @var Array : IPessoa
	 */
	private $arrResponsaveis;
	
	/**
	 * Descrição da tarefa 
	 * @var string
	 */
	private $descricaoDaTarefa;
	
	/**
	 * Fluxo de trabalho da tarefa 
	 * @var CFluxoDeTrabalho
	 */
	private $fluxoDeTrabalho;
	
	/**
	 * Quantidade de segundos antes para avisar da data limite de conclusao da tarefa
	 * @var int
	 */
	private $qtdeDeSegundosAntesParaAvisar;
	
	private function SetQtdeDeSegundosAntesParaAvisar($segundos){
		$this->qtdeDeSegundosAntesParaAvisar = $segundos;
	}
}
?>