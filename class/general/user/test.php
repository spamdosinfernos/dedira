<?php
require_once 'User.php';

$user = new User();

//$user->setId("d1e3b82a5a1e114190ba2c9877000a88");
//$user = $user->load();
//$user->erase();


$user->setName("André");
$user->setSecondName("Furlan");
$user->setArrEmail(array("tatupheba@gmail.com","nonducornonduco@riseup.net"));
$user->setArrTelefone(array("6627-6501","9102-1056"));
$user->setBirthDate(new DateTime("27-11-1980"));
$user->setSex("M");
$user->setLogin("tatupheba");
$user->setPassword(md5("tatu7"));
$user->save();
$s = serialize($user);


$s = str_replace("\0","*",$s);
?>