<?php 
require_once __DIR__ . '/Evento/Reuniao.php';
require_once __DIR__ . '/Evento/Manifestacao.php';

$r = new Reuniao();

$r->setId("4918c8130d75ec0beda8d60c39001c06");
$r = $r->carregar();

$r->setDataFim(new DateTime());
$r->setPauta("Pauta");
$r->setObservacoes("teste de reunião");
$r->setTipoDeRecorrencia(Evento::CONST_RECORRENCIA_MES);
$r->setQtdeDeRecorrencia(3);
$r->setArrIntegrantes(array("Pessoa1", "Pessoa2" => "teste de pessoa 2", "Pessoa3" => 1, "Pessoa4" => 10, "Pessoa20" => 21));
$r->salvar();

$r->setPauta("Pauta");
$r->setObservacoes("teste de reunião ataualizada ALTERADA");
$r->salvar();


$a = new Manifestacao();
$a->salvar();


?>