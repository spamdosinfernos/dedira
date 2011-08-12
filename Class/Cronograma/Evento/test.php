<?php 
require_once '../Evento/CReuniao.php';
require_once '../Evento/CManifestacao.php';

$r = new CReuniao();

$r->setId("01c7b558ba8ff48f9966cf1151005c6a");

$r = $r->carregar();

$r->setDataFim(new DateTime());
$r->setPauta("Pauta");
$r->setObservacoes("teste de reunião");
$r->setTipoDeRecorrencia(CEvento::CONST_RECORRENCIA_MES);
$r->setQtdeDeRecorrencia(3);
$r->setArrIntegrantes(array("Pessoa1", "Pessoa2" => "teste de pessoa 2", "Pessoa3" => 1, "Pessoa4" => 10, "Pessoa20" => 21));
$r->salvar();

$r->setPauta("Pauta");
$r->setObservacoes("teste de reunião ataualizada ALTERADA");
$r->salvar();


$a = new CManifestacao();
$a->salvar();


?>