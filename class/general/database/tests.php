<?php 
//require_once __DIR__ . '/CDatabase.php';
//
//$t = new CDatabase();
//
//$t->databaseSelect("");
//$t->createDatabase("milibase");
//$t->saveDocument("andré", array("eu"=>array("apaixonado" => "André")));
//$id1 = $t->getResponse();
//
//$t->saveDocument("jacqueline", array("namorado"=>"andré","namorada"=>"jacqueline"));
//$id2 = $t->getResponse();
//
//$t->updateDocumentInformation($id2->id,$id2->rev,array("apaixonada"=>"andré gostaosão","linda"=>"jacqueline minha amada"));
//$id2 = $t->getResponse();
//
//$t->updateDocumentInformation($id1->id,$id1->rev,array("apaixonado"=>"jacqueline gostosona","linda"=>"delícia"));
//$id1 = $t->getResponse();
//
//$t->eraseDocument("andré",$id1->rev);
//$t->eraseDocument("jacqueline",$id2->rev);
//
//$t->apagaBaseDeDados("milibase");
//$id1 = $t->getResponse();


require_once __DIR__ . '/CObjectLoader.php';

$c = new CObjectLoader(Configuration::CONST_DB_PEOPLE_NAME);

$c->getUserIdThroughLoginAndPassword("tatupheba","tatu7");


$t->databaseSelect("");
$t->createDatabase("milibase");
$t->saveDocument("andré", array("eu"=>array("apaixonado" => "André")));
$id1 = $t->getResponse();

$t->saveDocument("jacqueline", array("namorado"=>"andré","namorada"=>"jacqueline"));
$id2 = $t->getResponse();

$t->updateDocumentInformation($id2->id,$id2->rev,array("apaixonada"=>"andré gostaosão","linda"=>"jacqueline minha amada"));
$id2 = $t->getResponse();

$t->updateDocumentInformation($id1->id,$id1->rev,array("apaixonado"=>"jacqueline gostosona","linda"=>"delícia"));
$id1 = $t->getResponse();

$t->eraseDocument("andré",$id1->rev);
$t->eraseDocument("jacqueline",$id2->rev);

$t->apagaBaseDeDados("milibase");
$id1 = $t->getResponse();

?>