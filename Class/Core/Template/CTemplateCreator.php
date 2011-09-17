<?php
require_once __DIR__ . '/CCampoHtml.php';

class Teste{

	/**
	 * Teste de propriedade privada
	 * @edit editNao
	 * @val 1
	 * @req true
	 * @var string
	 */
	private $campoHtmlPropPrivate;

	/**
	 * Teste de propriedade protegida
	 * @edit editSim
	 * @val 2
	 * @req false
	 * @var string
	 */
	protected $campoHtmlPropProtected;

	/**
	 * Teste de propriedade publica
	 * @edit editSim
	 * @val 3
	 * @req true
	 * @var password
	 */
	public $campoHtmlPropPublic;

}

class CTemplateCreator{

	const CONST_DESTINO_PARA_OS_DADOS_DO_FORMULARIO = "index.php";

	public function __construct($instanciaDaClasse){

		$r = new ReflectionClass($instanciaDaClasse);
		$arrProp = $r->getProperties();

		foreach ($arrProp as $prop) {
			$html = $this->getHtml($prop->getDocComment(), $prop->getName());
		}
	}

	function getHtml($docComment, $nomeDaVariavel){

		$html = "";

		$campoHtml = new CampoHtml();

		//Separa os agrupamentos de comentários
		preg_match("/\/\*\*\n\s*\*\s*(.*)\n\s*((\*\s@.*\n\s*)*)/i", $docComment, $matches);

		//Seta a descrição
		$descricao = trim($matches[1]);
		$campoHtml->setDescricao($descricao);
		$campoHtml->setNome($nomeDaVariavel);

		//Separa as tags
		$arrDocTags = explode("@",$matches[2]);

		foreach ($arrDocTags as $index => $docTag) {

			//Tira as impurezas dos dados das tags
			$docTag = trim(str_replace("\n", "", str_replace("*", "", $docTag)));

			if($docTag == ""){
				unset($arrDocTags[$index]);
				continue;
			}

			//Recupera os nomes e valores das tags
			$arrDocTag = explode(" ", $docTag);
			
			$nome = $arrDocTag[0];
			$valor = trim(str_replace($nome . " ", "", $docTag));

			//Seta as propriedades na classe construtora do html do campo
			switch ($nome){
				case "edit": $campoHtml->setEditavel($valor);
				break;
				case "multilinha": $campoHtml->setMultilinha($valor);
				break;
				case "req": $campoHtml->setRequerido($valor);
				break;
				case "var": $campoHtml->setTipo($valor);
				break;
				case "val": $campoHtml->setValorPadrao($valor);
				break;
			}
		}
		
		return $campoHtml->getHtml();
	}
}

new CTemplateCreator(new Teste());
?>