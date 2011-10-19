<?php
require_once __DIR__ . '/CCampoHtml.php';

class Teste{

	/**
	 * Teste de propriedade privada
	 * @editarComo apenasMostrar
	 *
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
	 * @get getCampoHtmlPropPublic
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
	
	/**
	 * Teste de propriedade publica check box array
	 * @editarComo editarComoCheckBox
	 * @req true
	 * @alimentador viewTeste
	 * @set setCampoHtmlArrayPublic
	 * @get getCampoHtmlArrayPublic
	 * @var array
	 */
	public $campoHtmlArrayPublicCheckBox;
	
	/**
	 * Teste de propriedade publica check box string
	 * @editarComo editarComoCheckBox
	 * @req true
	 * @set setCampoBooleano
	 * @get getCampoBooleano
	 * @var boolean
	 */
	public $campoHtmlArrayPublicCheckBoxString;
	
	public function getCampoBooleano(){
		return true;
	}

	public function __construct(){
		$this->campoHtmlArrayPublic = array("valor00" => "teste", "valor02" => "2");
		$this->campoHtmlPropPrivate = "campoHtmlPropPrivateValue";
		$this->campoHtmlPropPublic = "testeDeSenha";
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

class CTemplateCreator extends CCore {

	const CONST_DESTINO_PARA_OS_DADOS_DO_FORMULARIO = "index.php";

	public function __construct($instanciaDaClasse){

		$r = new ReflectionClass($instanciaDaClasse);
		$arrProp = $r->getProperties();
		
		$html = "";
		foreach ($arrProp as $prop) {
			$html .= $this->getHtml($prop->getDocComment(), $prop->getName(), $instanciaDaClasse);
		}
	$html = '<html><form action="' . self::CONST_DESTINO_PARA_OS_DADOS_DO_FORMULARIO . ' method="post">' . $html . '</form></html>';
	
	//Remove os espaços
	$html = preg_replace("/(\n\s|\s\s|\s\n|\n|\t)*/", "", $html);
	echo $html;
	}

	private function getHtml($docComment, $nomeDaVariavel, $instanciaDaClasse){

		$html = "";

		try{
			$campoHtml = new CampoHtml();

			//Separa os agrupamentos de comentários
			preg_match("/\/\*\*\n\s*\*\s*(.*)\n\s*((\*\s(|@).*\n\s*)*)/i", $docComment, $matches);

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

				$propriedade = $arrDocTag[0];
				$valor = trim(str_replace($propriedade . " ", "", $docTag));

				//Seta as propriedades na classe construtora do html do campo
				switch ($propriedade){
					case "editarComo": $campoHtml->setEditarComo($valor);
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
						$comando = "return \$instanciaDaClasse->$valor();";
						$campoHtml->setValorInicial(eval($comando));
						break;
					case "set" :
						break;
				}
			}

			return $campoHtml->getHtml();
		}catch (Exception $e){
			throw new Exception("Falha ao gerar o código da página: " . $e->getMessage());
		}
	}
}

new CTemplateCreator(new Teste());
?>