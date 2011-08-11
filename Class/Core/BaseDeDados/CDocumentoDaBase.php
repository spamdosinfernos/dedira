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

	public function __construct(){
		parent::__construct();

		//Preparando para realizar as transações com o banco de dados
		$this->operadorDeBancoDeDados = new CBaseDeDados();
		$this->operadorDeBancoDeDados->selecionarBaseDeDados(CConfiguracao::CONST_BD_NOME_EVENTOS);
	}

	/**
	 * Carrega uma informação da base dada sua identificação
	 * @return boolean
	 * @see setId()
	 */
	public function carregar(){

		//A id te que estar setada
		if($this->id == "") return false;

		if(!$this->operadorDeBancoDeDados->carregarInformacao($this->id)) return false;

		$objInfo = $this->operadorDeBancoDeDados->getResposta();

		$expressao = $this->gerarExpressaoDeCargaDosDados($objInfo,"\$this");

		if(eval($expressao) === false) return false;

		//Se chegou até aqui deu tudo certo serialize(new CDocumentoDaBase())
		return true;
	}

	private function gerarExpressaoDeCargaDosDados($objInfo, $raiz){

		$strTemp = "";

		/*
		 * Se o informação tratada é um objeto, tenta encontrar os
		 * métodos que atualizam suas proriedades
		 */
		if(isset($objInfo->CLASSNAME)){

			$str = "O:" . strlen($objInfo->CLASSNAME) . ":\"" . $objInfo->CLASSNAME . "\":";

			foreach ($objInfo as $propriedade => $valor) {

				//Pula a propriedade "CLASSNAME"
				if($propriedade == "CLASSNAME") continue;

				if(is_object($valor)){
					$strTemp .= "s:" . strlen($propriedade) . ":\"" . $propriedade . "\":" . count(get_object_vars($valor)) . ":{";
					$strTemp .= $this->gerarExpressaoDeCargaDosDados($valor,$propriedade) . "}";
				}

				//Traduz a id do banco para a id do sistema
				if($propriedade == "_id"){
					$strTemp .= "s:2:\"id\";";
					$strTemp .= "s:" . strlen($valor) . ":\"" . $valor . "\";";
					continue;
				}

				//Traduz a série da revisão do banco para a revisão do sistema
				if($propriedade == "_rev"){
					$strTemp .= "s:3:\"rev\";";
					$strTemp .= "s:" . strlen($valor) . ":\"" . $valor . "\";";
					continue;
				}

				$strTemp .= "s:" . strlen($propriedade) . ":\"" . $propriedade . "\";";

				if(is_numeric($valor)){
					$strTemp .= "i:" . $valor . ";";
				}

				if(is_string($valor)){
					$strTemp .= "s:" . strlen($valor) . ":\"" . $valor . "\";";
				}

				$arrStr[] = $strTemp;
			}

			$str .= count($arrStr) . ":{" . join("",$arrStr) . "}";
		}

		return $str;

		foreach ($objInfo as $propriedade => $valor) {

			if(is_null($valor)){
				continue;
			}

			if($propriedade == "CLASSNAME"){
				continue;
			}

			if(is_object($valor)){
				$arrExpressao[] = $this->gerarExpressaoDeCargaDosDados($valor,$raiz . "->" .$propriedade);
			}

			//Traduz a id do banco para a id do sistema
			if($propriedade == "_id"){
				$arrExpressao[] = $raiz . "->id='$valor';";
				continue;
			}

			//Traduz a série da revisão do banco para a revisão do sistema
			if($propriedade == "_rev"){
				$arrExpressao[] = $raiz . "->rev='$valor';";
				continue;
			}

			//Monta a expressão que setará as propriedades das classes
			if(is_string($valor)){
				$arrExpressao[] = "$raiz->$propriedade = '$valor';";
				continue;
			}

			if(is_numeric($valor)){
				$arrExpressao[] = "$raiz->$propriedade = $valor;";
				continue;
			}
		}
		return join("",$arrExpressao);
	}

	/**
	 * Salva as informações da classe
	 * @return boolean
	 */
	public function salvar(){

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
}
?>