<?php
require_once 'User.php';
require_once __DIR__ . '/../security/authentication/UserAuthRules.php';
require_once __DIR__ . '/../security/authentication/Authenticator.php';

$user = new User();
$user->setActive(true);
$user->setArrEmail(array("tatupheba@gmail.com.br","nonducornonduco@riseup.net"));
$user->setBirthDate(new DateTime("27-11-1980"));
$user->setArrTelefone(array("88889999"));
$user->setLogin("tatupheba");
$user->setPassword(md5("tatu7"));
$user->setSex("M");
//$user->save();

$user = new User();
$user->setActive(true);
$user->setArrEmail(array("jac.meire@hotmail.com","jacqueline@riseup.net"));
$user->setBirthDate(new DateTime("18-09-1989"));
$user->setArrTelefone(array("66276501"));
$user->setLogin("jacqueline");
$user->setPassword(md5("232523"));
$user->setSex("F");
//$user->save();

$uar = new UserAuthRules();
$uar->setUser($user);


$au = new Authenticator($uar);
$au->authenticate();
$au->unauthenticate();
?>