<?php 
require_once 'Class/Cronograma/Evento/CReuniao.php';

$r = new CReuniao();
//$r->setId("941d23c627895b36360c691542000aca");
//$r->carregar();
$r->setPauta("Pauta");
$r->setObservacoes("teste de reunião");
$r->salvar();
$r->setPauta("Pauta");
$r->setObservacoes("teste de reunião ataualizada");
$r->salvar();
$r->apagar();
?>