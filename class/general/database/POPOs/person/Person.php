<?php
require_once __DIR__ . '/../abstractions/AStorableObject.php';

/**
 * This is a person in system
 *
 * @author ensismoebius
 *        
 */
class Person extends AStorableObject {
	
	/**
	 * Name
	 *
	 * @var string
	 */
	protected $name;
	
	/**
	 * Second name
	 *
	 * @var string
	 */
	protected $secondName;
	
	/**
	 * Birthdate
	 *
	 * @var Datetime
	 */
	protected $birthDate;
	
	/**
	 * Sex
	 *
	 * @var char
	 */
	protected $sex;
	
	/**
	 * Emails
	 *
	 * @var array : string
	 */
	protected $arrEmail;
	
	/**
	 * Telephones
	 *
	 * @var array : string
	 */
	protected $arrTelephone;
	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
		$this->AddChange ( "name", $name );
	}
	public function getSecondName() {
		return $this->secondName;
	}
	public function setSecondName($secondName) {
		$this->secondName = $secondName;
		$this->AddChange ( "secondName", $secondName );
	}
	public function getBirthDate() {
		return $this->birthDate;
	}
	public function setBirthDate(Datetime $birthDate) {
		$this->birthDate = $birthDate;
		$this->AddChange ( "birthDate", $birthDate );
	}
	public function getSex() {
		return $this->sex;
	}
	public function setSex($sex) {
		$this->sex = $sex;
		$this->AddChange ( "sex", $sex );
	}
	public function getArrEmail() {
		return $this->arrEmail;
	}
	public function setArrEmail($arrEmail) {
		$this->arrEmail = $arrEmail;
		$this->AddChange ( "arrEmail", $arrEmail );
	}
	public function getArrTelephone() {
		return $this->arrTelephone;
	}
	public function setArrTelephone($arrTelephone) {
		$this->arrTelephone = $arrTelephone;
		$this->AddChange ( "arrTelephone", $arrTelephone );
	}
}
?>