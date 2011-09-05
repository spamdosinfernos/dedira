<?php

class Teste{

	/**
	 * Teste de propriedade privada
	 * @edit não
	 * @val 1
	 * @nome Teste privado
	 * @rec true
	 * @var string
	 */
	private $testePropPrivate;

	/**
	 * Teste de propriedade protegida
	 * @edit sim
	 * @val 2
	 * @nome Teste protegido
	 * @rec false
	 * @var string
	 */
	protected $testePropProtected;

	/**
	 * Teste de propriedade publica
	 * @edit sim
	 * @val 3
	 * @nome Teste public
	 * @rec true
	 * @var string
	 */
	public $testePropPublic;

}

$r = new ReflectionClass(new Teste());
$r->getName();
$arrProp = $r->getProperties();

foreach ($arrProp as $prop) {
	$html = getHtml($prop->getDocComment());
}

/**
 * Teste de propriedade protegida
 * @edit sim
 * @val 2
 * @nome Teste protegido
 * @rec false
 * @var string
 */
function getHtml($docComment){
	
	//Separa os agrupamentos de comentários
	preg_match("/\/\*\*\n\s*\*\s*(.*)\n\s*((\*\s@.*\n\s*)*)/i", $docComment, $matches);
	
	//Seta a descrição
	$descricao = trim($matches[1]);

	//Separa as tags
	$arrDocTags = explode("@",$matches[2]);

	//Tira as impurezas dos dados das tags
	foreach ($arrDocTags as $index => $docTag) {
		$arrDocTags[$index] = trim(str_replace("\n", "", str_replace("*", "", $docTag)));
		if($arrDocTags[$index] == "") unset($arrDocTags[$index]);
	}
	
	

}

?>