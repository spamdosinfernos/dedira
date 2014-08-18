<?php
require_once '../class/general/user/User.php';
require_once '../class/general/security/PasswordPreparer.php';
require_once '../class/general/database/Database.php';


//The first thing to do is to create the database 
$database = new Database();
$database->createDatabase("milisystem");

//and one user with admnistrative powers
$user = new User();
//$user->setAccessGroup();
$user->setActive(true);
$user->setArrEmail(array("user@localhost"));
$user->setBirthDate(new DateTime("27-11-1980"));
$user->setLogin("user");
$user->setPassword(PasswordPreparer::messItUp("tatu7"));
$user->setSecondName("simple");
$user->save();
?>