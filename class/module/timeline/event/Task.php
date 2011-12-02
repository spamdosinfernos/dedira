<?php
require_once 'AEvent.php';
/**
 * Descreve tarefas que devem ser realizadas pelo usuário
 * @author tatupheba
 *
 */
class Task extends AEvent{

	/**
	 * Indica se a tarefa foi realizada ou não 
	 * @var boolean
	 */
	protected $complete;

	/**
	 * Responsáveis pela tarefa 
	 * @var array : IPerson
	 */
	protected $arrWorkers;

	/**
	 * Descrição da tarefa 
	 * @var string
	 */
	protected $taskDescription;

	/**
	 * Fluxo de trabalho da tarefa
	 * @var array : Task
	 */
	protected $arrSubTasks;

	/**
	 * Indica a data de início para realização da tarefa
	 * @var DateTime
	 */
	protected $startDate;

	/**
	 * Indica a data limite para realização da tarefa
	 * @var DateTime
	 */
	protected $limitDate;

	public function __construct(){
		parent::__construct();
	}

	public function isComplete(){
		return $this->getCompletionPercentage() == 1 ? true : false;
	}

	/**
	 * Retorna um valor entre 0 e 1 indicando o quanto da tarefa foi realizado.
	 * @return float
	 */
	public function getCompletionPercentage(){

		//Se já está completa não verificamos nada
		if($this->complete) return 1;

		//Se não temos que ver o quanto está completa
		$tasksCompleted = 0;

		//Conta a quantidade de tarefas realizadas
		foreach ($this->arrSubTasks as $task) {
			$tasksCompleted += $task->isComplete() ? 1 : 0;
		}

		return $tasksCompleted / count($this->arrSubTasks);
	}

	/**
	 * Indica que uma tarefa foi completada, se esta tiver alguma subtarefa esta também será maracada como feita
	 * @param boolean $complete
	 */
	public function setComplete($complete){

		if(!is_bool($complete)) throw new SystemException("o argumento deve ser booleano", __CLASS__ . __LINE__);

		//Ao marcar uma tarefa como completa ou imcompleta deve-se marcar também as subtarefas
		foreach ($this->arrSubTasks as $index => $task) {
			$this->arrSubTasks[$index]->setComplete($complete);
		}

		$this->complete = $complete;
	}

	public function getArrWorkers(){
		return $this->arrWorkers;
	}

	public function setArrWorkers($arrWorkers){
		$this->arrWorkers = $arrWorkers;
	}

	public function getTaskDescription(){
		return $this->taskDescription;
	}

	public function setTaskDescription($taskDescription){
		$this->taskDescription = $taskDescription;
	}

	public function getLimitDate(){
		return $this->limitDate;
	}

	public function setLimitDate(DateTime $limitDate){
		$this->limitDate = $limitDate;
	}

	public function getArrSubTasks(){
		return $this->arrSubTasks;
	}

	public function setArrSubTasks($arrSubTasks){
		$this->arrSubTasks = $arrSubTasks;
	}

	public function getStartDate(){
		return $this->startDate;
	}

	public function setStartDate(DateTime $startDate){
		$this->startDate = $startDate;
	}
}
?>