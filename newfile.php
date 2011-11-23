<?php
require_once __DIR__ . '/./Class/Person/Administrador.php';

$teste = new Administrador();
$teste->autenticar("andre", "teste");
?>