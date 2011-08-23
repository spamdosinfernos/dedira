<?php 
require_once __DIR__ . '/CBaseDeDados.php';

$t = new CBaseDeDados();

$t->selecionarBaseDeDados("");
$t->criarBaseDeDados("milibase");
$t->gravarDocumento("andré", array("eu"=>array("apaixonado" => "André")));
$id1 = $t->getResposta();

$t->gravarDocumento("jacqueline", array("namorado"=>"andré","namorada"=>"jacqueline"));
$id2 = $t->getResposta();

$t->atualizarInformacao($id2->id,$id2->rev,array("apaixonada"=>"andré gostaosão","linda"=>"jacqueline minha amada"));
$id2 = $t->getResposta();

$t->atualizarInformacao($id1->id,$id1->rev,array("apaixonado"=>"jacqueline gostosona","linda"=>"delícia"));
$id1 = $t->getResposta();

$t->apagarDocumento("andré",$id1->rev);
$t->apagarDocumento("jacqueline",$id2->rev);

$t->apagaBaseDeDados("milibase");
$id1 = $t->getResposta();

?>