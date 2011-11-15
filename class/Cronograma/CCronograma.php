<?php
require_once __DIR__ . '/../Core/Configuration/Configuration.php';
require_once __DIR__ . '/Evento/CManifestacao.php';
require_once __DIR__ . '/Evento/CEncontro.php';
require_once __DIR__ . '/Evento/CReuniao.php';
require_once __DIR__ . '/Evento/CEvento.php';

/**
 * Representa o cronograma de um usuário dada um determinado intervalo de datas
 * @see setDataInicial()
 * @see setDataFinal()
 * @author andre
 */
class CCronograma extends CCore{

	/**
	 * Pessoa para a qual será exibido o cronograma 
	 * @var IPessoa
	 */
	protected $donoDoCronograma;

	/**
	 * Eventos da organização que deverão estar 
	 * ou ser ordenados e organizados
	 * @var Array : IEvento
	 */
	protected $arrEventos;

	/**
	 * Data inicial do cronograma
	 * @var Datetime
	 */
	protected $dataInicial;

	/**
	 * Data final do cronograma
	 * @var Datetime
	 */
	protected $dataFinal;

	/**
	 * Constrói o cronograma do usuário
	 * @param IPessoa $donoDoCronograma
	 * @param DateTime $dataFinal
	 * @param DateTime $dataInicial
	 */
	public function __construct(IPessoa $donoDoCronograma, $dataFinal = null, $dataInicial = null){

		if(is_null($dataInicial)){
			
			//Data inicial, por padrão é hoje
			$dataInicial = new DateTime();

			//Seta a data final
			$dataFinal = new DateTime();
			$dataFinal->modify(Configuration::getDefaultTimeInterval() . Configuration::getDefaultTimeIntervalType());
		}

		$this->setDonoDoCronograma($donoDoCronograma);
	}

	public function getDonoDoCronograma(){
		return $this->donoDoCronograma;
	}

	public function setDonoDoCronograma(IPessoa $donoDoCronograma){
		$this->donoDoCronograma = $donoDoCronograma;
	}

	public function getArrEventos(){
		return $this->arrEventos;
	}

	public function setArrEventos($arrEventos){
		$this->arrEventos = $arrEventos;
	}

	public function AddEvento(IEvento $evento){
		$this->arrEventos[] = $evento;
	}

	public function getDataInicial(){
		return $this->dataInicial;
	}

	public function setDataInicial(Datetime $dataInicial){
		$this->dataInicial = $dataInicial;
	}

	public function getDataFinal(){
		return $this->dataFinal;
	}

	public function setDataFinal(Datetime $dataFinal){
		$this->dataFinal = $dataFinal;
	}
}
?>