<?php
require_once __DIR__ . '/PageElement.php';
require_once __DIR__ . '/CXTemplate.php';
require_once __DIR__ . '/../filesystem/File.php';
require_once __DIR__ . '/../configuration/template/PageCreatorConf.php';

class Teste{

	/**
	 * Teste de propriedade privada
	 * @editarComo apenasMostrar
	 *
	 * @req true
	 * @get getPageElementPropPrivate
	 * @set setPageElementPropPrivate
	 * @var string
	 */
	private $campoHtmlPropPrivate;

	/**
	 * Teste de propriedade protegida
	 * @editarComo editarComoTexto
	 * @req false
	 * @get getPageElementPropProtected
	 * @set setPageElementPropProtected
	 * @var string
	 */
	protected $campoHtmlPropProtected;

	/**
	 * Teste de propriedade publica
	 * @editarComo editarComoSenha
	 * @req true
	 * @get getPageElementPropPublic
	 * @set setPageElementPropPublic
	 * @var string
	 */
	public $campoHtmlPropPublic;

	/**
	 * Teste de propriedade publica
	 * @editarComo editarComoListBox
	 * @req true
	 * @alimentador viewTeste
	 * @set setPageElementArrayPublic
	 * @get getPageElementArrayPublic
	 * @var array
	 */
	public $campoHtmlArrayPublic;

	/**
	 * Teste de propriedade publica check box array
	 * @editarComo editarComoCheckBox
	 * @req true
	 * @alimentador viewTeste
	 * @set setPageElementArrayPublic
	 * @get getPageElementArrayPublic
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

	public function getPageElementPropPrivate(){
		return $this->campoHtmlPropPrivate;
	}

	public function setPageElementPropPrivate($campoHtmlPropPrivate){
		$this->campoHtmlPropPrivate = $campoHtmlPropPrivate;
	}

	public function getPageElementPropProtected(){
		return $this->campoHtmlPropProtected;
	}

	public function setPageElementPropProtected($campoHtmlPropProtected){
		$this->campoHtmlPropProtected = $campoHtmlPropProtected;
	}

	public function getPageElementPropPublic(){
		return $this->campoHtmlPropPublic;
	}

	public function setPageElementPropPublic($campoHtmlPropPublic){
		$this->campoHtmlPropPublic = $campoHtmlPropPublic;
	}

	public function getPageElementArrayPublic(){
		return $this->campoHtmlArrayPublic;
	}

	public function setPageElementArrayPublic($campoHtmlArrayPublic){
		$this->campoHtmlArrayPublic = $campoHtmlArrayPublic;
	}
}

class PageCreator extends Core {

	/**
	 * Título da página
	 * @var string
	 */
	private $pageTitle;

	const CONST_DESTINO_PARA_OS_DADOS_DO_FORMULARIO = "index.php";

	public function __construct($instanciaDaClasse){

		parent::__construct();

		$styleSheet = new File(PageCreatorConf::getStyleSheetFile());

		$xTemplate = new CXTemplate(PageCreatorConf::getTemplateFile());
		$xTemplate->assign("title", $this->pageTitle);
		$xTemplate->assign("cssPath", $styleSheet->getRelativePath(__DIR__));
		$xTemplate->assign("conteudoDaPagina", $this->getPageBody($instanciaDaClasse));
		$xTemplate->assign("destinoDaPostagem", self::CONST_DESTINO_PARA_OS_DADOS_DO_FORMULARIO);
		$xTemplate->parse("main");

		$xTemplate->out("main");
	}

	private function getPageBody($instanciaDaClasse){

		$classProperties = new ReflectionClass($instanciaDaClasse);
		$arrProp = $classProperties->getProperties();

		$html = "";
		foreach ($arrProp as $prop) {
			$html .= $this->generateHtmlField($prop->getDocComment(), $prop->getName(), $instanciaDaClasse);
		}

		//Remove os espaços
		return preg_replace("/(\n\s|\s\s|\s\n|\n|\t)*/", "", $html);
	}

	private function generateHtmlField($docComment, $nomeDaVariavel, $instanciaDaClasse){

		$html = "";

		try{
			$campoHtml = new PageElement();

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

	public function getPageTitle(){
		return $this->pageTitle;
	}

	public function setPageTitle($pageTitle){
		$this->pageTitle = $pageTitle;
	}

	public function getStyleSheet(){
		return $this->styleSheet;
	}

	public function setStyleSheet($styleSheet){
		$this->styleSheet = new $styleSheet;
	}
}

new PageCreator(new Teste());
?>