<?php
//Create a socket and connect it to the server.
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_connect($socket, 'localhost', 33333);

//Create a message, and send it to the server on $socket.
$message = "This is a message from the client.\n";
socket_send($socket, $message, strlen($message), MSG_EOF);
//Close the socket.
socket_close($socket);
?>