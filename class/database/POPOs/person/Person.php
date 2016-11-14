<?php
require_once __DIR__ . '/../abstractions/AStorableObject.php';

/**
 * This is a person in system
 *
 * @author ensismoebius
 *        
 */
class Person extends AStorableObject {
	const SEX_BOTH = 'both';
	const SEX_FEMALE = 'female';
	const SEX_IRRELEVANT = 'irrelevant';
	const SEX_MALE = 'male';
	
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
	protected $lastName;
	
	/**
	 * Birthdate
	 *
	 * @var Datetime
	 */
	protected $birthDate;
	
	/**
	 * Sex
	 *
	 * @var string
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
	public function __construct() {
		$this->arrEmail = array ();
		$this->arrTelephone = array ();
	}
	public function getName() {
		return $this->name;
	}
	public function setName(string $name) {
		$this->name = $name;
		$this->AddChange ( "name", $name );
	}
	public function getLastName() {
		return $this->lastName;
	}
	public function setLastName(string $lastName) {
		$this->lastName = $lastName;
		$this->AddChange ( "lastName", $lastName );
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
	public function setSex(string $sex) {
		switch ($sex) {
			case self::SEX_BOTH :
			case self::SEX_FEMALE :
			case self::SEX_IRRELEVANT :
			case self::SEX_MALE :
				$this->sex = $sex;
				$this->AddChange ( "sex", $sex );
		}
	}
	public function getArrEmail() {
		return $this->arrEmail;
	}
	public function setArrEmail(array $arrEmail) {
		$this->arrEmail = $arrEmail;
		$this->AddChange ( "arrEmail", $arrEmail );
	}
	public function addEmail(string $email) {
		$this->arrEmail [] = $email;
		$this->AddChange ( "arrEmail", $email, self::COLLECTION_ADD );
	}
	public function removeEmail(string $email) {
		$key = array_search ( $email, $this->arrEmail );
		
		$this->AddChange ( "arrEmail", $email, self::COLLECTION_REMOVE );
		
		// If the key is boolean the item was not found
		if (is_bool ( $key )) return;
		
		unset ( $this->arrEmail [$key] );
	}
	public function getArrTelephone() {
		return $this->arrTelephone;
	}
	public function setArrTelephone(array $arrTelephone) {
		$this->arrTelephone = $arrTelephone;
		$this->AddChange ( "arrTelephone", $arrTelephone );
	}
	public function addTelephone(string $telephone) {
		$this->arrTelephone [] = $telephone;
		$this->AddChange ( "arrTelephone", $telephone, self::COLLECTION_ADD );
	}
	public function removeTelephone(string $telephone) {
		$key = array_search ( $telephone, $this->arrTelephone );
		
		$this->AddChange ( "arrTelephone", $telephone, self::COLLECTION_REMOVE );
		
		// If the key is boolean the item was not found
		if (is_bool ( $key )) return;
		
		unset ( $this->arrTelephone [$key] );
	}
}
?>