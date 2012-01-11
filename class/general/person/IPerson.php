<?php
interface IPerson{

	public function getName();

	public function setName($name);

	public function getSecondName();

	public function setSecondName($secondName);

	public function getBirthDate();

	public function setBirthDate(Datetime $birthDate);

	public function getSex();

	public function setSex($sex);

	public function getArrEmail();

	public function setArrEmail($arrEmail);

	public function getArrTelefone();

	public function setArrTelefone($arrTelefone);
	
}
?>