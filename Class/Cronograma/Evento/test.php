<?php 
require_once '../Evento/CReuniao.php';

$r = new CReuniao();

$r->setId("01c7b558ba8ff48f9966cf1151003d9b");

class teste{
	protected $a;
	protected $b;
	protected $c;
	
	public function __construct(){
		$this->a = 10;
		$this->b = 1000;
		$this->c = new DateTime();
	}
}

$z = new teste();

$s = serialize($z);

$safe_object = str_replace("\0*\0","@@@",$s);

$safe_object = str_replace("@@@","\0*\0",$s);

$k = unserialize($safe_object);

$u = $r->carregar();

//print_r($r->getArrIntegrantes());

$r->setDataInicio(new DateTime());
$r->setPauta("Pauta");
$r->setObservacoes("teste de reunião ALTERADA");
$r->setTipoDeRecorrencia(CEvento::CONST_RECORRENCIA_MES);
$r->setQtdeDeRecorrencia(3);
$r->setArrIntegrantes(array("Pessoa1", "Pessoa2" => "teste de pessoa 2", "Pessoa3" => 1, "Pessoa4" => 10, "Pessoa20" => 21));
$r->salvar();

$r->setPauta("Pauta");
$r->setObservacoes("teste de reunião ataualizada ALTERADA --");
$r->salvar();
//$r->apagar();
?>