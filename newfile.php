<?php
require_once __DIR__ . '/./Class/Pessoa/CAdministrador.php';

$teste = new CAdministrador();
$teste->autenticar("andre", "teste");
?>