<?php
//Set up some variables
$maxBytes = 5000;
$port = 33333;

$socket = false;

while($socket === FALSE){
	@$socket = socket_create_listen($port);
}

do{

	//Wait for incoming connections.
	@$connection = socket_accept($socket);
	
	//@$bytes = socket_read($connection, $maxBytes);
	//echo "Message From Client: $bytes \n";
	
	$msg = "testetetetstet";
	
	socket_write($connection, $msg, strlen($msg));

	unset($bytes);

}while (true);

//Close the socket
@socket_close($socket);
?>