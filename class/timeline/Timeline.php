<?php
require_once __DIR__ . '/../core/configuration/Configuration.php';
require_once __DIR__ . '/Event/Manifestacao.php';
require_once __DIR__ . '/Event/Encontro.php';
require_once __DIR__ . '/Event/Reuniao.php';
require_once __DIR__ . '/Event/Event.php';

/**
 * Representa o cronograma de um usuário dado um determinado intervalo de datas
 * @see setInicialDate()
 * @see setFinalDate()
 * @author tatupheba
 */
class Timeline {

	/**
	 * Person para a qual será exibido o cronograma 
	 * @var IPerson
	 */
	protected $timelineOwner;

	/**
	 * Events da organização que deverão estar 
	 * ou ser ordenados e organizados
	 * @var Array : IEvent
	 */
	protected $arrEvents;

	/**
	 * Data inicial do cronograma
	 * @var Datetime
	 */
	protected $inicialDate;

	/**
	 * Data final do cronograma
	 * @var Datetime
	 */
	protected $finalDate;

	/**
	 * Constrói o cronograma do usuário
	 * @param IPerson $timelineOwner
	 * @param DateTime $finalDate
	 * @param DateTime $inicialDate
	 */
	public function __construct(IPerson $timelineOwner, $finalDate = null, $inicialDate = null){

		if(is_null($inicialDate)){
			
			//Data inicial, por padrão é hoje
			$inicialDate = new DateTime();

			//Seta a data final
			$finalDate = new DateTime();
			$finalDate->modify(Configuration::getDefaultTimeInterval() . Configuration::getDefaultTimeIntervalType());
		}

		$this->setTimelineOwner($timelineOwner);
	}

	public function getTimelineOwner(){
		return $this->timelineOwner;
	}

	public function setTimelineOwner(IPerson $timelineOwner){
		$this->timelineOwner = $timelineOwner;
	}

	public function getArrEvents(){
		return $this->arrEvents;
	}

	public function setArrEvents($arrEvents){
		$this->arrEvents = $arrEvents;
	}

	public function addEvent(IEvent $evento){
		$this->arrEvents[] = $evento;
	}

	public function getInicialDate(){
		return $this->inicialDate;
	}

	public function setInicialDate(Datetime $inicialDate){
		$this->inicialDate = $inicialDate;
	}

	public function getFinalDate(){
		return $this->finalDate;
	}

	public function setFinalDate(Datetime $finalDate){
		$this->finalDate = $finalDate;
	}
}
?>