<?php
require_once __DIR__ . '/FluxoDeTrabalho.php';
require_once __DIR__ . '/../Evento.php';
/**
 * Descrevem tarefas que devem ser realizadas pelo usuário
 *
 * Uma tarefa tem que ter no mínimo um fluxo de trabalho
 * @author andre
 *
 */
class Tarefa extends Evento{

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
	 * @var FluxoDeTrabalho
	 */
	protected $fluxoDeTrabalho;

	/**
	 * Indica a data limite para realização da tarefa
	 * @var DateTime
	 */
	protected $dataLimiteParaRealizacao;
	
	public function __construct(FluxoDeTrabalho $fluxoDeTrabalho){
		parent::__construct();
		$this->fluxoDeTrabalho = $fluxoDeTrabalho;
	}

	public function getArrResponsaveis(){
	    return $this->arrResponsaveis;
	}

	public function setArrResponsaveis($arrResponsaveis){
	    $this->arrResponsaveis = $arrResponsaveis;
	}

	public function getDescricaoDaTarefa(){
	    return $this->descricaoDaTarefa;
	}

	public function setDescricaoDaTarefa($descricaoDaTarefa){
	    $this->descricaoDaTarefa = $descricaoDaTarefa;
	}

	public function getFluxoDeTrabalho(){
	    return $this->fluxoDeTrabalho;
	}

	public function setFluxoDeTrabalho($fluxoDeTrabalho){
	    $this->fluxoDeTrabalho = $fluxoDeTrabalho;
	}

	public function getDataLimiteParaRealizacao(){
	    return $this->dataLimiteParaRealizacao;
	}

	public function setDataLimiteParaRealizacao($dataLimiteParaRealizacao){
	    $this->dataLimiteParaRealizacao = $dataLimiteParaRealizacao;
	}
}
?>