<?php
require_once 'CFluxoDeTrabalho.php';
require_once '../CEvento.php';
/**
 * Descrevem tarefas que devem ser realizadas pelo usuário
 *
 * Uma tarefa tem que ter no mínimo um fluxo de trabalho
 * @author andre
 *
 */
class CTarefa extends CEvento{

	/**
	 * Responsáveis pela tarefa 
	 * @var Array : IPessoa
	 */
	protected $arrResponsaveis;

	/**
	 * Descrição da tarefa 
	 * @var string
	 */
	protected $descricaoDaTarefa;

	/**
	 * Fluxo de trabalho da tarefa
	 * @var CFluxoDeTrabalho
	 */
	protected $fluxoDeTrabalho;

	/**
	 * Quantidade de segundos antes para avisar da data limite de conclusao da tarefa
	 * @var int
	 */
	protected $qtdeDeSegundosAntesParaAvisar;

	public function __construct(CFluxoDeTrabalho $fluxoDeTrabalho){
		parent::__construct();
		$this->fluxoDeTrabalho = $fluxoDeTrabalho;
	}

	public function SetQtdeDeSegundosAntesParaAvisar($segundos){
		$this->qtdeDeSegundosAntesParaAvisar = $segundos;
	}
}
?>