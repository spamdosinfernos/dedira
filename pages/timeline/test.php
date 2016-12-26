<?php
require_once __DIR__ . '/event/Reunion.php';

$r = new Reunion ();
$r->setArrInvited ( array (
		"Osmualdo da silva",
		"Amadeu de cana",
		"Bonicéia da cruz" 
) );
$r->setArrMembers ( array (
		"Jac linda",
		"André gostoso",
		"Michel fala magra",
		"Hugo brisa",
		"Hernani chaveco véio" 
) );
$r->setArrPlacesAddresses ( array (
		"Ay carmela" 
) );
$r->setBeginDate ( new DateTime ( "26-11-2011 17:00:00" ) );
$r->setFinalDate ( new DateTime ( "26-11-2011 18:00:00" ) );
$r->setGuideLines ( "pauta 1, pauta 2, auta 3" );
$r->setObservations ( "Reunião de teste 01" );
$r->setPrivate ( false );
$r->setRememberingDate ( new DateTime ( "26-11-2011 17:50:00" ) );
$r->save ();

$r = new Reunion ();
$r->setArrInvited ( array (
		"Osmualdo da silva" 
) );
$r->setArrMembers ( array (
		"Jac linda",
		"André gostoso",
		"Michel fala magra",
		"Hugo brisa",
		"Hernani chaveco véio" 
) );
$r->setArrPlacesAddresses ( array (
		"Sinsprev" 
) );
$r->setBeginDate ( new DateTime ( "26-11-2011 16:00:00" ) );
$r->setFinalDate ( new DateTime ( "26-11-2011 19:00:00" ) );
$r->setGuideLines ( "pauta 4, pauta 5, pauta 6" );
$r->setObservations ( "Reunião de teste 02" );
$r->setPrivate ( false );
$r->setRememberingDate ( new DateTime ( "26-11-2011 17:50:00" ) );
$r->save ();

?>