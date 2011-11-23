<?php 
require_once __DIR__ . '/Event/Reuniao.php';
require_once __DIR__ . '/Event/Manifestacao.php';

$r = new Reuniao();

$r->setId("4918c8130d75ec0beda8d60c39001c06");
$r = $r->carregar();

$r->setDataFim(new DateTime());
$r->setPauta("Pauta");
$r->setObservacoes("teste de reunião");
$r->setTipoDeRecorrencia(Event::CONST_RECORRENCY_MES);
$r->setQtdeDeRecorrencia(3);
$r->setArrIntegrantes(array("Person1", "Person2" => "teste de pessoa 2", "Person3" => 1, "Person4" => 10, "Person20" => 21));
$r->salvar();

$r->setPauta("Pauta");
$r->setObservacoes("teste de reunião ataualizada ALTERADA");
$r->salvar();


$a = new Manifestacao();
$a->salvar();


?>