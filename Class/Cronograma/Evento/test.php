<?php 
require_once '../Evento/CReuniao.php';

$r = new CReuniao();

//TODO Parei aqui!! Estou com problemas para salvar os dados após usar o método "carregar"
$r->setId("20dc5a07bd6e5f24ccbbc68e940045df");
$r->carregar();


$r->setPauta("Pauta");
$r->setObservacoes("teste de reunião");
$r->setTipoDeRecorrencia(1);
$r->salvar();
$r->setPauta("Pauta");
$r->setObservacoes("teste de reunião ataualizada");
$r->salvar();
$r->apagar();
?>