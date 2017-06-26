<?php
/**
 * Used to represent a system notification in a page
 * @author ensismoebius
 *
 */
class Notification {
	
	/**
	 * Holds the notification message
	 *
	 * @var string
	 */
	protected $message;
	
	/**
	 * Holds an array of strings for further
	 * information about the notification
	 *
	 * @var array
	 */
	protected $arrMoreInfomation;
	
	/**
	 * Adds more information about the notification (optional)
	 *
	 * @param string $info        	
	 * @return Notification
	 */
	public function addInformation(string $info): Notification {
		$this->arrMoreInfomation [] = $info;
		return $this;
	}
	
	/**
	 * Set more informations
	 * @param array $arrMoreInfomation
	 * @return Notification
	 */
	public function setArrMoreInfomation(array $arrMoreInfomation) {
		$this->arrMoreInfomation = $arrMoreInfomation;
		return $this;
	}
	
	/**
	 * Returns more information about the notification
	 * 
	 * @return array
	 */
	public function getArrMoreInfomation(): array {
		return $this->arrMoreInfomation;
	}
	
	/**
	 * Return the message
	 * 
	 * @return string
	 */
	public function getMessage(): string {
		return $this->message;
	}
	
	/**
	 * Sets the message
	 * 
	 * @param string $message        	
	 * @return Notification
	 */
	public function setMessage(string $message): Notification {
		$this->message = $message;
		return $this;
	}
}