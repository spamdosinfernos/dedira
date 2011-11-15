<?php

class Individuo{

	private $arrDNA;

	private $qtdeGenes;
	
	private $geracao;

	/**
	 *
	 * @var verificadorDeAptidao
	 */
	private $calculadorDeAptidao;

	public function __construct($arrGenes, verificadorDeAptidao $calculadorDeAptidao, $geracaoAnterior = 0){
		$this->calculadorDeAptidao = $calculadorDeAptidao;
		$this->setArrDNA($arrGenes);
		$this->geracao = $geracaoAnterior + 1;
	}

	public function getGeracao(){
		return $this->geracao;
	}
	
	public function getArrDNA(){
		return $this->arrCromossomo;
	}

	public function setArrDNA($arrDNA){
		$this->arrCromossomo = $arrDNA;
		$this->qtdeGenes = count($arrDNA);
		$this->mutarGenes();
	}

	private function mutarGenes(){

		//A mutação pode ou não ocorrer
		$mutar = mt_rand(0,1) == 1;

		if($mutar){
			$indice = mt_rand(0,$this->qtdeGenes-1);

			$this->arrCromossomo[$indice] = $this->arrCromossomo[$indice] == 1 ? 0 : 1;
		}
	}

	public function getRNA(){

		$arrRNA = array();

		$pegar = rand(0,1) == 1;

		foreach ($this->arrCromossomo as $gene){
			$pegar = !$pegar;

			if(!$pegar) continue;

			$arrRNA[] =  $gene;
		}

		return $arrRNA;
	}

	public function getAptidao(){
		return $this->calculadorDeAptidao->calcularAptidao($this);
	}

	public function getDescendente($arrRNADoador){

		$arrDNA = array();

		$arrRNAProprio = $this->getRNA();

		$qtdeGenes = count($arrRNAProprio);

		$this->crossingOver($arrRNAProprio, $arrRNADoador);

		//Combina os RNAs
		for($i = 0; $i <= $qtdeGenes - 1; $i++){
			$arrDNA[] = $arrRNADoador[$i];
			$arrDNA[] = $arrRNAProprio[$i];
		}

		return new Individuo($arrDNA, $this->calculadorDeAptidao, $this->geracao);
	}

	private function crossingOver(&$arrRNA1, &$arrRNA2){
		//A mutação pode ou não ocorrer
		$realizar = mt_rand(0,1) == 1;

		$tamRna = count($arrRNA1);

		if(!$realizar) return;

		$indice = mt_rand(0, $tamRna - 1);
		$indiceDeTroca = abs($indice - 1);

		$gene1 = $arrRNA1[$indice];
		$gene2 = $arrRNA2[$indiceDeTroca];

		$arrRNA1[$indice] = $gene2;
		$arrRNA2[$indiceDeTroca] = $gene1;
	}

}

class verificadorDeAptidao{

	public function calcularAptidao(Individuo $individuo){

		$alvo = 8;

		$soma = 0;
		
		$arrOperandos = $individuo->getArrDNA();

		foreach ($arrOperandos as $operando) {
			$soma += $operando == 1 ? 1 : -1;
		}
		
		if(abs($soma - $alvo) == 0){
			//print join(" ", $arrOperandos) . " " . $individuo->getGeracao() . "\n";
			
			file_put_contents("geracoes.txt",join("", $arrOperandos),FILE_APPEND);
			print join("", $arrOperandos) . "\n";
			flush();
		}

		return abs($soma - $alvo);
	}
}

$qtdeMaximaDeIndividuos = 64;

$arrIndividuos[0] = new Individuo(array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0), new verificadorDeAptidao());
$arrIndividuos[1] = new Individuo(array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0), new verificadorDeAptidao());
$arrIndividuos[2] = new Individuo(array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0), new verificadorDeAptidao());
$arrIndividuos[3] = new Individuo(array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0), new verificadorDeAptidao());
$arrIndividuos[4] = new Individuo(array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0), new verificadorDeAptidao());
$arrIndividuos[5] = new Individuo(array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0), new verificadorDeAptidao());
$arrIndividuos[6] = new Individuo(array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0), new verificadorDeAptidao());
$arrIndividuos[7] = new Individuo(array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0), new verificadorDeAptidao());



while(true){

	$apagar = 0;
	$arrListaDeAptidoes = array();
	$arrMae = $arrIndividuos;
	$arrPai = $arrIndividuos;

	foreach ($arrMae as $indiceMae => $mae) {
		foreach ($arrPai as $indicePai => $pai) {

			if($indiceMae == $indicePai) continue;

			$arrIndividuos[] = $mae->getDescendente($pai->getRNA());
		}
	}
	
	foreach ($arrIndividuos as $indice => $individuo) {
		$arrListaDeAptidoes[$indice] = $individuo->getAptidao();
	}

	arsort($arrListaDeAptidoes);
	
	$apagar = count($arrListaDeAptidoes) - $qtdeMaximaDeIndividuos;

	//Os menos adaptados morrem
	foreach ($arrListaDeAptidoes as $indice => $valor) {

		if($apagar <= 0) break;
		
		unset($arrIndividuos[$indice]);
		$apagar--;
	}
	
	sort($arrIndividuos);
}


?>