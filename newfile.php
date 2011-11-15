<?php
require_once __DIR__ . '/./Class/Pessoa/Administrador.php';

$teste = new Administrador();
$teste->autenticar("andre", "teste");
?>