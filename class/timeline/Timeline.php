<?php
require_once __DIR__ . '/../general/configuration/Configuration.php';
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
	 * Erro 1: O dono deve ser informado
	 * @var int
	 */
	const CONST_ERROR_1 = 1;
	
	/**
	 * Erro 2: A data inicial deve ser informada
	 * @var int
	 */
	const CONST_ERROR_2 = 2;
	
	/**
	 * Erro 3: A data final deve ser informada
	 * @var int
	 */
	const CONST_ERROR_3 = 3;

	/**
	 * Constrói o cronograma do usuário
	 * @param IPerson $timelineOwner
	 * @param DateTime $finalDate
	 * @param DateTime $inicialDate
	 */
	public function __construct(IPerson $timelineOwner, Datetime $finalDate = null, Datetime $inicialDate = null){

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

	public function loadEvents(){
		if(is_null($this->timelineOwner)) throw new SystemException(Lang_Timeline::getDescriptions(self::CONST_ERROR_1), self::CONST_ERROR_1);
		if(is_null($this->inicialDate)) throw new SystemException(Lang_Timeline::getDescriptions(self::CONST_ERROR_2), self::CONST_ERROR_2);
		if(is_null($this->finalDate)) throw new SystemException(Lang_Timeline::getDescriptions(self::CONST_ERROR_3), self::CONST_ERROR_3);
		
		//TODO Implementar o carregamento de todos os eventos dados o dono e as datas inicial e final.
		//TODO Parei aqui! Percebi que talvez aquela classe para gerar interfaces na qual trabalhei tanto seja nada mais que inútil as exigências das interfaces parecem ser muito mais superiores ao que esta classe pode ou poderá fazer, sendo assim é melhor eu fazer as interfaces do modo mais tradicional mesmo
		
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