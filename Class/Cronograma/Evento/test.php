<?php 
require_once '../Evento/CReuniao.php';

$r = new CReuniao();

$r->setId("352aa213a17d2d41457cac53d7000410");
$r->carregar();

$r->setDataInicio(new DateTime());
$r->setPauta("Pauta");
$r->setObservacoes("teste de reunião");
$r->setTipoDeRecorrencia(CEvento::CONST_RECORRENCIA_MES);
$r->setQtdeDeRecorrencia(3);
$r->salvar();

$r->setPauta("Pauta");
$r->setObservacoes("teste de reunião ataualizada");
$r->salvar();
//$r->apagar();
?>