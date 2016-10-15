<?php
require_once 'AEvent.php';
class Reunion extends AEvent {
	
	/**
	 * Pauta da reunião
	 * 
	 * @var string
	 */
	protected $guideLines;
	
	/**
	 * Pessoas da organização que devem participar da reunião
	 */
	protected $arrMembers;
	
	/**
	 *
	 * Ouvintes e outras pessoas
	 * 
	 * @var Array : Person
	 * @var Array : MilitanteDeApoio
	 */
	protected $arrInvited;
	public function getGuideLines() {
		return $this->guideLines;
	}
	public function setGuideLines($guideLines) {
		$this->guideLines = $guideLines;
	}
	public function getArrMembers() {
		return $this->arrMembers;
	}
	public function setArrMembers($arrMembers) {
		$this->arrMembers = $arrMembers;
	}
	public function getArrInvited() {
		return $this->arrInvited;
	}
	public function setArrInvited($arrInvited) {
		$this->arrInvited = $arrInvited;
	}
}
?>