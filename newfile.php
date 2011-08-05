<?php
require_once './Class/Pessoa/CAdministrador.php';

$teste = new CAdministrador();
$teste->autenticar("andre", "teste");
?>