<?php
require_once '../../Core/CCore.php';
require_once '../../Core/BaseDeDados/CBaseDeDados.php';
require_once '../../Core/Configuracao/CConfiguracao.php';

/**
 * Todos as classes que tiverem propriedades que se queira salvar
 * deve extender esta classe, mas atenção: As propriedades salvas 
 * serão apenas aquelas cuja visibilidade for "protected" ou "public"
 *
 * @author andre
 *
 */
class CDocumentoDaBase extends CCore{

	/**
	 * Indentificação das informações na base de dados
	 * @var mixed
	 */
	private $id;

	/**
	 * Série que identifica a versão das informações na base de dados
	 * @var mixed
	 */
	private $rev;

	/**
	 * Responsável por realizar as transações com o banco de dados
	 * @var CBaseDeDados
	 */
	private $operadorDeBancoDeDados;
	
	const CONST_TIPO_INFORMACAO_OBJETO = 0;
	const CONST_TIPO_INFORMACAO_ARRAY = 1;
	const CONST_TIPO_INFORMACAO_ORDINARIA = 2;
	
	//const CONST_PRO = "\0*\0";
	//const CONST_PRO = "@";
	const CONST_PRO = "@@@";

	public function __construct(){
		parent::__construct();
	}

	/**
	 * Carrega uma informação da base dada sua identificação
	 *
	 * ATENÇÃO: Infelizmente não é possivel, de forma 
	 * eficiente, um objeto por outro gerado por ele
	 * mesmo de forma interna à classe
	 * @return Object - O objeto carregado e gerado
	 * @see setId()
	 */
	public function carregar(){

		//A id te que estar setada
		if($this->id == "") return null;

		$this->abrirConexaoComObancoDeDados();

		if(!$this->operadorDeBancoDeDados->carregarInformacao($this->id)) return null;

		$informacao = $this->operadorDeBancoDeDados->getResposta();

		$expressao = $this->gerarExpressaoSerializada(null, $informacao);
		
		//$safe_object = str_replace("\0", "~~NULL_BYTE~~", $expressao); 

		return unserialize($expressao);
	}

	/**
	 * Salva as informações da classe
	 * @return boolean
	 */
	public function salvar(){

		$this->abrirConexaoComObancoDeDados();

		if($this->id == ""){
			$ok = $this->operadorDeBancoDeDados->inserirInformacao("", $this->toArray());
		}else{
			$ok = $this->operadorDeBancoDeDados->atualizarInformacao($this->id, $this->rev, $this->toArray());
		}

		if($ok){
			$resposta = $this->operadorDeBancoDeDados->getResposta();
			$this->id = $resposta->id;
			$this->rev = $resposta->rev;
			return true;
		}
		return false;
	}

	/**
	 * Apaga as informações da classe
	 * @return boolean
	 */
	public function apagar(){
		if($this->id == "") throw new Exception("Texto - Falha ao apagar informação: O evento não tem uma identificação.");

		$this->abrirConexaoComObancoDeDados();

		return $this->operadorDeBancoDeDados->apagarInformacao($this->id, $this->rev);
	}

	public function setId($id){
		$this->id = $id;
	}

	public function setRev($rev){
		$this->rev = $rev;
	}

	public function getId(){
		return $this->id;
	}

	public function getRev(){
		return $this->rev;
	}

	private function abrirConexaoComObancoDeDados(){
		if(is_null($this->operadorDeBancoDeDados)){
			//Preparando para realizar as transações com o banco de dados
			$this->operadorDeBancoDeDados = new CBaseDeDados();
			$this->operadorDeBancoDeDados->selecionarBaseDeDados(CConfiguracao::CONST_BD_NOME_EVENTOS);
		}
	}

	private function getTipoDaInformacao($informacao){
		
		if(is_null($informacao)) return self::CONST_TIPO_INFORMACAO_ORDINARIA;
		
		if(is_string($informacao) || is_numeric($informacao)){
			return self::CONST_TIPO_INFORMACAO_ORDINARIA;
		}
		
		if(isset($informacao->CLASSNAME)){
			if($informacao->CLASSNAME != "stdClass") return self::CONST_TIPO_INFORMACAO_OBJETO;
		}
		
		return self::CONST_TIPO_INFORMACAO_ARRAY;
	}

	private function gerarExpressaoSerializada($propriedade, $valor){

		$strTemp = "";
		$tipo = $this->getTipoDaInformacao($valor);

		if($tipo == self::CONST_TIPO_INFORMACAO_OBJETO){
			
			
			if(is_null($propriedade)){
				$strTemp = "O:" . strlen($valor->CLASSNAME) . ":\"" . $valor->CLASSNAME . "\":" . (count(get_object_vars($valor)) - 1) . ":{";
			}else{
				$propriedade = self::CONST_PRO . $propriedade;
				$strTemp = "s:" . strlen($propriedade) . ":\"" . $propriedade . "\";O:" . strlen($valor->CLASSNAME) . ":\"" . $valor->CLASSNAME . "\":" . (count(get_object_vars($valor)) - 1) . ":{";
			}

			foreach ($valor as $subPropriedade => $subValor) {

				if($subPropriedade == "CLASSNAME") continue;
				
				$strTemp .= $this->gerarExpressaoSerializada($subPropriedade, $subValor);
			}

			$strTemp .= "}";

			return $strTemp;
		}

		if($tipo == self::CONST_TIPO_INFORMACAO_ARRAY){

			$valor = get_object_vars($valor);
			
			if(is_null($propriedade)){
				$strTemp = "a:" . count($valor) . ":{";
			}else{
				$propriedade = self::CONST_PRO . $propriedade;
				$strTemp = "s:" . strlen($propriedade) . ":\"" . $propriedade . "\";a:" . count($valor) . ":{";
			}

			foreach ($valor as $subPropriedade => $subValor){
				$strTemp .= $this->gerarExpressaoSerializada($subPropriedade, $subValor);
			}

			$strTemp .= "}";

			return $strTemp;
		}

		//Traduz a id do banco para a id do sistema
		if($propriedade === "_id"){
			$strTemp .= "s:2:\"id\";";
			$strTemp .= "s:" . strlen($valor) . ":\"" . $valor . "\";";
			return $strTemp;
		}

		//Traduz a série da revisão do banco para a revisão do sistema
		if($propriedade === "_rev"){
			$strTemp .= "s:3:\"rev\";";
			$strTemp .= "s:" . strlen($valor) . ":\"" . $valor . "\";";
			return $strTemp;
		}

		$propriedade = self::CONST_PRO . $propriedade;
		$strTemp .= "s:" . strlen($propriedade) . ":\"" . $propriedade . "\";";

		if(trim($valor) == ""){
			$strTemp .= "N;";
			return $strTemp;
		}

		if(is_numeric($valor)){
			$strTemp .= "i:" . $valor . ";";
			return $strTemp;
		}

		if(is_string($valor)){
			$strTemp .= "s:" . strlen($valor) . ":\"" . $valor . "\";";
			return $strTemp;
		}
	}
}
?>