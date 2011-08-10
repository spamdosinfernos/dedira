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
	protected $id;

	/**
	 * Série que identifica a versão das informações na base de dados
	 * @var mixed
	 */
	protected $rev;

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

		foreach ($objInfo as $propriedade => $valor) {

			//Pula as propriedades "CLASSNAME" (não uso para as mesmas)
			if(is_null($valor) || $propriedade == "CLASSNAME") continue;

			//Traduz a id do banco para a id do sistema
			if($propriedade == "_id"){
				$this->id = $valor;
				continue;
			}

			//Traduz a série da revisão do banco para a revisão do sistema
			if($propriedade == "_rev"){
				$this->rev = $valor;
				continue;
			}

			//Monta a expressão que setará as propriedades das classes
			if(is_string($valor)) $expressao = "\$this->$propriedade = '$valor';";
			if(is_numeric($valor)) $expressao = "\$this->$propriedade = $valor;";

			if(is_object($valor) || is_array($valor)){
				$valor = serialize($valor);
				$expressao = "\$this->$propriedade = unserialize('$valor');";
			}

			//Se algum comando falhar retorna false
			if(eval($expressao) == false) return false;;
		}

		//Se chegou até aqui deu tudo certo
		return true;
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