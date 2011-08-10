<?php 
require_once '../Evento/CReuniao.php';

$r = new CReuniao();

//TODO Parei aqui!! Estou com problemas para salvar os dados após usar o método "carregar"
$r->setId("d8072b78ae8248c89e0f21f952001dda");
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