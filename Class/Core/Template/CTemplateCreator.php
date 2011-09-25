<?php
require_once __DIR__ . '/CCampoHtml.php';

class Teste{

	/**
	 * Teste de propriedade privada
	 * @editarComo apenasMostrar
	 * @val 1
	 * @req true
	 * @get getCampoHtmlPropPrivate
	 * @set setCampoHtmlPropPrivate
	 * @var string
	 */
	private $campoHtmlPropPrivate;

	/**
	 * Teste de propriedade protegida
	 * @editarComo editarComoTexto
	 * @req false
	 * @get getCampoHtmlPropProtected
	 * @set setCampoHtmlPropProtected
	 * @var string
	 */
	protected $campoHtmlPropProtected;

	/**
	 * Teste de propriedade publica
	 * @editarComo editarComoSenha
	 * @req true
	 * @get getCampoHtmlPropPubli
	 * @set setCampoHtmlPropPublic
	 * @var string
	 */
	public $campoHtmlPropPublic;

	/**
	 * Teste de propriedade publica
	 * @editarComo editarComoListBox
	 * @req true
	 * @alimentador viewTeste
	 * @set setCampoHtmlArrayPublic
	 * @get getCampoHtmlArrayPublic
	 * @var array
	 */
	public $campoHtmlArrayPublic;

	public function __construct(){
		$this->campoHtmlArrayPublic = array(1 => "teste", "teste2" => "2", 3);
	}

	public function getCampoHtmlPropPrivate(){
		return $this->campoHtmlPropPrivate;
	}

	public function setCampoHtmlPropPrivate($campoHtmlPropPrivate){
		$this->campoHtmlPropPrivate = $campoHtmlPropPrivate;
	}

	public function getCampoHtmlPropProtected(){
		return $this->campoHtmlPropProtected;
	}

	public function setCampoHtmlPropProtected($campoHtmlPropProtected){
		$this->campoHtmlPropProtected = $campoHtmlPropProtected;
	}

	public function getCampoHtmlPropPublic(){
		return $this->campoHtmlPropPublic;
	}

	public function setCampoHtmlPropPublic($campoHtmlPropPublic){
		$this->campoHtmlPropPublic = $campoHtmlPropPublic;
	}

	public function getCampoHtmlArrayPublic(){
		return $this->campoHtmlArrayPublic;
	}

	public function setCampoHtmlArrayPublic($campoHtmlArrayPublic){
		$this->campoHtmlArrayPublic = $campoHtmlArrayPublic;
	}
}

class CTemplateCreator{

	const CONST_DESTINO_PARA_OS_DADOS_DO_FORMULARIO = "index.php";

	public function __construct($instanciaDaClasse){

		$r = new ReflectionClass($instanciaDaClasse);
		$arrProp = $r->getProperties();

		foreach ($arrProp as $prop) {
			$html = $this->getHtml($prop->getDocComment(), $prop->getName(), $prop->getValue());
		}
	}

	function getHtml($docComment, $nomeDaVariavel, $valorInicial){

		$html = "";

		$campoHtml = new CampoHtml();

		//Separa os agrupamentos de comentários
		preg_match("/\/\*\*\n\s*\*\s*(.*)\n\s*((\*\s@.*\n\s*)*)/i", $docComment, $matches);

		//Seta a descrição
		$descricao = trim($matches[1]);
		$campoHtml->setDescricao($descricao);
		$campoHtml->setNome($nomeDaVariavel);
		$campoHtml->setValorInicial($valorInicial);

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
				case "editarComo": $campoHtml->setEditavel($valor);
				break;
				case "multilinha": $campoHtml->setMultilinha($valor);
				break;
				case "req": $campoHtml->setRequerido($valor);
				break;
				case "var": $campoHtml->setTipo($valor);
				break;
				case "alimentador": $campoHtml->setAlimentador($valor);
				break;
				case "get" :
					break;
				case "set" :
					break;
			}
		}

		return $campoHtml->getHtml();
	}
}

new CTemplateCreator(new Teste());
?>