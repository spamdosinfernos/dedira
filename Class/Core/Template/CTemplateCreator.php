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

class CTemplateCreator{

	const CONST_DESTINO_PARA_OS_DADOS_DO_FORMULARIO = "index.php";

	public function __construct($instanciaDaClasse){

		$r = new ReflectionClass($instanciaDaClasse);
		$r->getName();
		$arrProp = $r->getProperties();

		foreach ($arrProp as $prop) {
			$html = $this->getHtml($prop->getDocComment());
		}
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
		
		$html = "";

		//Separa os agrupamentos de comentários
		preg_match("/\/\*\*\n\s*\*\s*(.*)\n\s*((\*\s@.*\n\s*)*)/i", $docComment, $matches);

		//Seta a descrição
		$descricao = trim($matches[1]);

		//Separa as tags
		$arrDocTags = explode("@",$matches[2]);

		foreach ($arrDocTags as $index => $docTag) {

			//Tira as impurezas dos dados das tags
			$docTag = trim(str_replace("\n", "", str_replace("*", "", $docTag)));
			
			if($docTag == ""){
				unset($arrDocTags[$index]);
				continue;
			}
			
			$arrDocTag = explode(" ", $docTag);
			$propriedade = $arrDocTag[0];
			$valor = trim(str_replace($propriedade, "", $docTag));
			
			
			switch ($propriedade){
				case "edit" :
					$html = $valor == "sim" ? '<input type="text" ' : '<p>';
				case "val" : 
					$html .= $valor == "" ? '<input type="text" ' : '<p>';
			}	
			
		}
	}
}

new CTemplateCreator(new Teste());
?>