<?php
/**
 * Used to represent a system notification in a page, 
 * by default the notification type is NONE
 * 
 * @author ensismoebius
 *
 */
class SystemNotification {
	
	// Indicates the type of notification
	const FAIL = 0;
	const NONE = 1;
	const SUCCESS = 2;
	const WARNNING = 3;
	
	/**
	 * The type of notification
	 *
	 * @var SystemNotification::FAIL
	 * @var SystemNotification::SUCCESS
	 * @var SystemNotification::WARNNING
	 */
	protected $type;
	
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
	 * Used to represent a system notification in a page,
	 * by default the notification type is NONE
	 */
	public function __construct() {
		// By default the notification is of a NONE
		$this->type = self::NONE;
	}
	
	/**
	 * Adds more information about the notification (optional)
	 *
	 * @param string $info
	 * @param string $key
	 * @return SystemNotification
	 */
	public function addInformation(string $key, string $info): SystemNotification {
		$this->arrMoreInfomation [$key] = $info;
		return $this;
	}
	
	/**
	 * Set more informations
	 *
	 * @param array $arrMoreInfomation
	 * @return SystemNotification
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
		return $this->message != null ? $this->message : "";
	}
	
	/**
	 * Sets the message
	 *
	 * @param string $message
	 * @return SystemNotification
	 */
	public function setMessage(string $message): SystemNotification {
		$this->message = $message;
		return $this;
	}
	
	/**
	 * Sets the type of notification
	 *
	 * @return SystemNotification::FAIL
	 * @return SystemNotification::SUCCESS
	 * @return SystemNotification::WARNNING
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * Sets the type of notification
	 *
	 * @param
	 *        	FAIL | SUCCESS | WARNNING $type
	 * @return SystemNotification
	 */
	public function setType($type) {
		$this->type = $type;
		return $this;
	}
}