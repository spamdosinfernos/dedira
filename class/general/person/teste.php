<?php 
require_once __DIR__ . '/Administrador.php';

$p = new Administrador();
$p->setDataDeNascimento(new DateTime("1980-11-27"));
$p->setNome("André");
$p->setSobrenome("Furlan");
$p->setArrTelefone(array("89533194"));
$p->setSexo("M");
$p->setUsuario("tatupheba");
$p->setSenha("tatu7");
$p->setEmail("tatupheba@gmail.com");
$p->salvar();
?>